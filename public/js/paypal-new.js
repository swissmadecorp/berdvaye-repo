document.addEventListener("DOMContentLoaded", () => {
    // 1. Render the standard PayPal button
    if (document.getElementById('paypal-button-container')) {
        paypal.Buttons({
            createOrder: createOrderCallback,
            onApprove: onApproveCallback,
            onError: function (error) {
                console.error("PayPal Button Error:", error);
            },
            style: {
                shape: "rect",
                layout: "vertical",
                color: "gold",
                label: "paypal",
            },
        }).render("#paypal-button-container");
    }

    const cardStyle = {
        'input': {
            'font-size': '16px',
            'font-family': 'courier, monospace',
            'font-weight': 'lighter',
            'padding': '.50rem 0.75rem'
        },
        '.paypal-button-message': {
            'display': 'none'
        },
        '.invalid': {
            'color': 'purple',
        },
    };

    // 2. Configuration for Credit Card Fields
    const cardField = paypal.CardFields({
        createOrder: createOrderCallback,
        onApprove: onApproveCallback,
        style: cardStyle,
        inputEvents: {
            onChange: (data) => {
                const submitButton = document.getElementById('paynow');
                const formContainer = document.getElementById('card-form');

                // Check if user has started typing in any field
                const isAnyFieldNotEmpty =
                    !data.fields.number.isEmpty ||
                    !data.fields.cvv.isEmpty ||
                    !data.fields.expiry.isEmpty;

                if (submitButton) {
                    if (isAnyFieldNotEmpty) {
                        // User is typing: Enable the button fully
                        submitButton.classList.remove('hidden'); // Ensure it's visible
                        submitButton.style.opacity = '1';
                        submitButton.disabled = false;
                    } else {
                        // Fields are empty: Dim the button and disable it
                        // We keep it visible (no 'hidden' class) so layout doesn't jump
                        submitButton.style.opacity = '0.5';
                        submitButton.disabled = true;
                    }
                }

                // Update form validity class for styling
                if (formContainer) {
                    formContainer.className = data.isFormValid ? 'valid' : 'invalid';
                }
            },
            // REMOVED onFocus: We no longer hide the PayPal button
        }
    });

    // 3. Render Card Fields if eligible
    if (cardField.isEligible()) {
        const numberContainer = document.getElementById("card-number-field-container");
        const cvvContainer = document.getElementById("card-cvv-field-container");
        const expiryContainer = document.getElementById("card-expiry-field-container");

        if (numberContainer) cardField.NumberField().render("#card-number-field-container");
        if (cvvContainer) cardField.CVVField().render("#card-cvv-field-container");
        if (expiryContainer) cardField.ExpiryField().render("#card-expiry-field-container");

        // 4. Attach Click Listener to "Pay Now" Button
        const payNowBtn = document.getElementById("paynow");
        if (payNowBtn) {
            payNowBtn.addEventListener("click", async (event) => {
                event.preventDefault();

                // Find the Livewire component safely
                const el = document.querySelector('#cart-component div[wire\\:id]');
                if (!el) {
                    console.error("Cart component root not found");
                    return;
                }
                const component = Livewire.find(el.getAttribute('wire:id'));

                try {
                    // A. Run Livewire Validation
                    const isLivewireValid = await component.call('validateFields');

                    if (isLivewireValid !== true) {
                        console.log("Livewire validation failed (Customer Info missing)");
                        return; // Stop if PHP validation fails
                    }

                    // B. Check Card Field State
                    const data = await cardField.getState();

                    if (data.isFormValid) {
                        // Show overlay
                        const overlay = document.getElementById('divoverlay');
                        if (overlay) overlay.classList.remove('hidden');

                        // C. Submit to PayPal
                        await cardField.submit({
                            // Ensure these input IDs exist in your HTML
                            cardholderName: (document.getElementById("firstname")?.value || "") + " " + (document.getElementById("lastname")?.value || ""),
                            billingAddress: {
                                addressLine1: document.getElementById("address1")?.value || "",
                                addressLine2: document.getElementById("address2")?.value || "",
                                countryCode: document.getElementById("bcountry")?.value || "US",
                                postalCode: document.getElementById("zip")?.value || "",
                            },
                        });
                    } else {
                        console.log("Card fields are invalid/incomplete");
                        // Optional: trigger visual feedback on fields
                    }

                } catch (error) {
                    console.error('Payment Error:', error);
                    const overlay = document.getElementById('divoverlay');
                    if (overlay) overlay.classList.add('hidden');
                }
            });
        }
    }
});

// --- GLOBAL CALLBACKS ---

async function createOrderCallback() {
    if (typeof resultMessage === 'function') resultMessage("");
    try {
        const el = document.querySelector('#cart-component div[wire\\:id]');
        if (!el) throw new Error("Livewire component not found");

        const component = Livewire.find(el.getAttribute('wire:id'));

        // Call Livewire to create order on server
        const orderData = await component.call('order');

        if (orderData && orderData.id) {
            return orderData.id;
        } else {
            const errorDetail = orderData?.details?.[0];
            const errorMessage = errorDetail
                ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
                : JSON.stringify(orderData);
            throw new Error(errorMessage);
        }
    } catch (error) {
        if (typeof resultMessage === 'function') {
            resultMessage(`Could not initiate PayPal Checkout...<br><br>${error}`);
        } else {
            console.error(error);
        }
    }
}

async function onApproveCallback(data, actions) {
    try {
        const el = document.querySelector('#cart-component div[wire\\:id]');
        const component = Livewire.find(el.getAttribute('wire:id'));

        // Capture payment on server
        const orderData = await component.call('capture', data.orderID);

        const transaction = orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||
                            orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];
        const errorDetail = orderData?.details?.[0];

        if (errorDetail?.issue === "INSTRUMENT_DECLINED" && !data.card && actions) {
            return actions.restart();
        } else if (errorDetail || !transaction || transaction.status === "DECLINED") {
            let errorMessage;
            if (transaction) {
                errorMessage = `Transaction ${transaction.status}: ${transaction.id}`;
            } else if (errorDetail) {
                errorMessage = `${errorDetail.description} (${orderData.debug_id})`;
            } else {
                errorMessage = JSON.stringify(orderData);
            }
            throw new Error(errorMessage);
        } else {
            if (typeof resultMessage === 'function') {
                resultMessage(`Transaction ${transaction.status}: ${transaction.id}`);
            }

            // Finalize on server (thank you logic)
            await component.call('thankyou', orderData);

            const overlay = document.getElementById('divoverlay');
            if (overlay) overlay.classList.add('hidden');
        }

    } catch (error) {
        console.error(error);
        if (typeof resultMessage === 'function') {
            resultMessage(`Sorry, your transaction could not be processed...<br><br>${error}`);
        }
    }
}

function resultMessage(message) {
    const container = document.querySelector("#result-message");
    if (container) container.innerHTML = message;
}