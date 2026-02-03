<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class AjaxProductsController extends Controller
{
    public function ajaxGetProduct(Request $request) {
        if ($request->ajax()) {
            $id = $request['id'];

            $product = Product::find($id);

            if (!$product)
                return response()->json('false');

            return response()->json(array('content'=>$product->p_description,'price'=>number_format($product->p_retail,2)));
        }
    }

    public function ajaxLoadProduct(Request $request) {
        if ($request->ajax()) {
            $id = $request['id'];

            $product = Product::find($id);

            if (!$product)
                return response()->json('false');

            $productCounterpart = Product::where([
                ['p_linked','=',$product->p_linked],
                ['id','<>',$product->id]
            ])->orderBy('id', 'desc')->first();

            $title = $product->p_title;
            
            ob_start(); 
            ?>
            <article>
                <div class="close-popup close-popup-styled"><i class="fa fa-times" aria-hidden="true"></i></div>
                <div style="margin: 0 26px;">
                    <h3 class="font_6" style="font-style: italic;">Modern Art with Vintage Watch  Parts</h3>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div style="padding: 25px 0 0;">
                                <ul id="lightslider" class="gallery list-unstyled">
                                <?php foreach ($product->images as $image) { ?>
                                <li>
                                <img src="/public/uploads/<?= $image->location ?>" style="max-width:100%" />
                                </li>
                                <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="m_bottom_25"></div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-5">
                            <div>
                                <span style="text-transform: uppercase;letter-spacing: 0.25em;"><?= $title ?></span><br>
                                <?php if ($product->p_model != 'FRL') {?>
                                <ul class="product-size">
                                    <li><label><input type="radio" name="size" value="<?= $productCounterpart->id ?>"> <?= $productCounterpart->p_size ?> Size</label></li>
                                    <li><label><input type="radio" name="size" checked value="<?= $product->id ?>"> <?= $product->p_size ?> Size</label></li>
                                </ul>
                                <?php }  ?>
                                <!-- <p class="font_4">Pricing: <span class="pricing">$<? //= number_format($product->p_retail,2) ?><span></p> -->
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-7 description">
                            <?= $product->p_description ?>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <button class="btn btn-secondary close-popup"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</button>
                        </div>
                    </div>
                    <div class="m_bottom_25"></div>
                </div>

            </article>
            
            <?php
            $content = ob_get_clean();
            return response()->json($content);
        }
    }
}
