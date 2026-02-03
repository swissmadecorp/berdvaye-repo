<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductRetail;
use App\Models\DiscountRule;
use App\Models\Taxable;

/**
 * Service class for all core cart manipulation and calculation logic.
 * Logic extracted primarily from the original CartController.
 */
class CartService
{
    // --- Discount Logic Helpers ---

    /**
     * Calculates the cart's web price subtotal (before discount/tax).
     * @return float
     */
    private function calculateWebPrice(): float
    {
        $webprice = 0.0;
        foreach (Cart::products() as $products) {
            $webprice += $products['price'];
        }
        return $webprice;
    }

    /**
     * Handles the complex discount calculation and applies it to the session.
     * Replaces the original CartController::discount() logic.
     *
     * @param string $promocode The discount code.
     * @param float $original_amount The original discount value (fixed amount or percentage).
     * @param int $action The type of discount (0: Fixed amount off, 1: Percentage off).
     * @return float The calculated discount amount.
     */
    public function applyDiscount(string $promocode, float $original_amount, int $action): float
    {
        $webprice = $this->calculateWebPrice();
        $amount = 0.0;

        if ($action === 0) {
            // Fixed amount off
            $amount = $original_amount;
        } elseif ($action === 1) {
            // Percentage off
            $amount = $webprice * ($original_amount / 100);
        }
        // Note: Actions 2 and others remain undefined as in the original controller.

        $discount = [
            'original_amount' => $original_amount,
            'action' => $action,
            'amount' => $amount,
            'promocode' => $promocode,
            'newprice' => $webprice - $amount, // Calculate net price after discount
        ];

        session()->put('discount', $discount);

        return $amount;
    }

    /**
     * Retrieves the current calculated discount amount.
     * @return float
     */
    public function getDiscountAmount(): float
    {
        if (session()->has('discount')) {
            return session()->get('discount')['amount'];
        }
        return 0.0;
    }

    // --- Cart Manipulation Logic ---

    /**
     * Adds an item to the cart, including product existence and quantity checks.
     * Replaces the logic in CartController::addToCart().
     *
     * @param string $model The product model number.
     * @return array|bool Returns an array with error details or true on success.
     */
    public function addItemToCart(string $model): array|bool
    {
        // 1. Check product status and quantity
        $products = Product::whereHas('retail', function($query) use ($model) {
            $query->where('p_model', $model);
        })
            ->where('p_status', 0)
            ->where('p_qty', '>', 0)
            ->get();

        $totalAvailable = $products->count();

        if ($totalAvailable==0) {
            return [
                'error' => 'qty',
                'description' => ' is out of stock.',
                'product_name' => $model
            ];
        }

        $product = Product::whereHas('retail', function($query) use ($model) {
            $query->where('p_model', $model);
        })
            ->where('p_status', 0)
            ->where('p_qty', '>', 0)
            ->first();

        $cartproduct = Cart::Find($product->id);

        if ($cartproduct)
            if ($cartproduct['qty'] >= $totalAvailable) {
                $cartproduct['qty'] = $totalAvailable;

                return ['error' => 'no_more', 'description' => 'We’ve reached the maximum available quantity of ' . $cartproduct['model_name'] .  '. Please contact us if you’re interested in purchasing more.'];
            }


        // 2. Gather product details
        $productRetail = ProductRetail::where('p_model', $model)->first();

        if (!$productRetail) {
             return ['error' => 'not_found', 'description' => 'Product retail data missing.'];
        }

        $cartProducts = [
            'id' => $product->id,
            'retail' => $productRetail->p_retail,
            'serial' => $product->p_serial,
            'price' => $productRetail->p_retail,
            'condition' => 'New',
            'available' => $totalAvailable,
            'size' => $productRetail->size,
            'qty' => 1, // Using qty 1 as in original logic
            'percent' => 0,
            'p_model' => $product->p_model,
            'model_name' => $productRetail->model_name,
            'image' => "/images/gallery/thumbnail/".strtolower($productRetail->p_model) .'_thumb.jpg'
        ];

        // 3. Insert or Add to Cart
        if (Cart::products()) {
            Cart::insert($cartProducts);
        } else {
            Cart::add($cartProducts);
        }

        // 4. Reapply discount if one exists (to recalculate webprice-based discount)
        if (session()->has('discount')) {
            $promoCode = session()->get('discount');
            $this->applyDiscount($promoCode['promocode'], $promoCode['original_amount'], $promoCode['action']);
        }

        return true;
    }

    /**
     * Handles the promo code application logic.
     * Replaces the logic in CartController::promo().
     *
     * @param string $promocode The discount code.
     * @return array
     */
    public function applyPromoCode(string $promocode): array
    {
        $discountRule = DiscountRule::where('discount_code', $promocode)->first();

        if ($discountRule) {
            if ($discountRule->is_active) {
                $discountAmt = $this->applyDiscount($promocode, $discountRule->amount, $discountRule->action);
                return [
                    'error' => 0,
                    'amount' => $discountRule->amount,
                    'content' => "A discount has been applied.",
                    'discount_applied' => $discountAmt
                ];
            } else {
                return ['error' => 1, 'content' => "This promo code has expired."];
            }
        } else {
            return ['error' => 1, 'content' => "That wasn't a correct promo code."];
        }
    }

    /**
     * Removes an item from the cart and reapplies discounts.
     * Replaces the logic in CartController::remove().
     *
     * @param int $product_id
     * @return int The remaining number of items in the cart (or 0 if empty).
     */
    public function removeItemFromCart(int $product_id): int
    {
        $remainingItems = Cart::Remove($product_id); // Assuming this returns the item count

        if (session()->has('discount')) {
            $promoCode = session()->get('discount');
            $this->applyDiscount($promoCode['promocode'], $promoCode['original_amount'], $promoCode['action']);

            if ($remainingItems === 0) {
                session()->forget('discount');
            }
        }

        return $remainingItems;
    }
}