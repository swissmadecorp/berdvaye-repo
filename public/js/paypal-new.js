// Render the button component and Card Fields component
paypal
  .Buttons({
    // Sets up the transaction when a payment button is clicked
    createOrder: createOrderCallback,
    onApprove: onApproveCallback,
    onError: function (error) {
      // Do something with the error from the SDK
    },
    style: {
      shape: "rect",
      layout: "vertical",
      color: "gold",
      label: "paypal",
    },
    // message: {
    //   amount: 100, // 4 payments
    // } ,
  })
  .render("#paypal-button-container");

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
// Render each field after checking for eligibility
const cardField = paypal.CardFields({
  createOrder: createOrderCallback,
  onApprove: onApproveCallback,
  style: cardStyle,
  inputEvents: {
    onChange: (data) => {
        formContainer.className = data.isFormValid ? 'valid' : 'invalid'
    },
    onInputSubmitRequest: (data) => {
      console.debug(data)
    }
  }
});

if (cardField.isEligible()) {
  // const nameField = cardField.NameField();
  // nameField.render("#card-name-field-container");

  const numberField = cardField.NumberField();
  numberField.render("#card-number-field-container");

  const cvvField = cardField.CVVField();
  cvvField.render("#card-cvv-field-container");

  const expiryField = cardField.ExpiryField();
  expiryField.render("#card-expiry-field-container");

  function handleChange(event) {
    const input = event.target;
    const errorInput = document.getElementById(input.id+'-error');

    errorInput.classList.add('hidden');
    errorInput.classList.remove('blockitem');

    input.classList.add('border');
    input.classList.remove('border-danger');
  }

  // Add click listener to submit button and call the submit function on the CardField component
 document.getElementById("paynow").addEventListener("click", async (event) => {
    event.preventDefault();

    const el = document.querySelector('#cart-component div[wire\\:id]');
    const component = Livewire.find(el.getAttribute('wire:id'));

    try {
        // 1. Run Livewire Validation and CAPTURE the result
        // If validation fails in PHP, this likely returns null/undefined, NOT true.
        const isLivewireValid = await component.call('validateFields');

        // 🛑 STOP HERE if Livewire validation failed
        if (isLivewireValid !== true) {
            console.log("Livewire validation failed (Customer Info missing)");
            return; // Exit function. Do NOT show overlay.
        }

        // 2. Check the Card Field State
        const data = await cardField.getState();

        // 3. ONLY Show Overlay if BOTH are valid
        if (data.isFormValid) {

            // ✅ NOW it is safe to show the overlay
            document.getElementById('divoverlay').classList.remove('hidden');

            await cardField.submit({
                cardholderName: document.getElementById("firstname").value + " " + document.getElementById("lastname").value,
                billingAddress: {
                    addressLine1: document.getElementById("address1").value,
                    addressLine2: document.getElementById("address2").value,
                    countryCode: document.getElementById("bcountry").value,
                    postalCode: document.getElementById("zip").value,
                },
            });

        } else {
            // Card details are invalid/missing
            console.log("Card validation failed");
        }

    } catch (error) {
        // This catches network errors or PayPal/Stripe SDK errors
        console.error('System error:', error);

        // Safety: Ensure overlay is hidden if we crash here
        document.getElementById('divoverlay').classList.add('hidden');
    }
});


}

//4207 6702 5078 0056 11/30 096
async function createOrderCallback() {
  resultMessage("");
  try {


    const el = document.querySelector('#cart-component div[wire\\:id]');
    const component = Livewire.find(el.getAttribute('wire:id'));

    // Call the Livewire method; returns JS object directly
    const orderData = await component.call('order');

    if (orderData.id) {
      return orderData.id;
    } else {
      const errorDetail = orderData?.details?.[0];
      const errorMessage = errorDetail
        ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
        : JSON.stringify(orderData);

      throw new Error(errorMessage);
    }
  } catch (error) {
    resultMessage(`Could not initiate PayPal Checkout...<br><br>${error}`);
  }
}

async function onApproveCallback(data, actions) {
  try {
    // const response = await fetch(`/payment/${data.orderID}/capture`, {
    //   method: "POST",
    //   headers: {
    //     "Content-Type": "application/json",
    //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //   },
    // });

    // const orderData = await response.json();
    const el = document.querySelector('#cart-component div[wire\\:id]');
    const component = Livewire.find(el.getAttribute('wire:id'));

    const orderData = await component.call('capture',data.orderID);

    // Three cases to handle:
    //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
    //   (2) Other non-recoverable errors -> Show a failure message
    //   (3) Successful transaction -> Show confirmation or thank you message

    const transaction =
      orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||
      orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];
    const errorDetail = orderData?.details?.[0];

    // this actions.restart() behavior only applies to the Buttons component
    if (errorDetail?.issue === "INSTRUMENT_DECLINED" && !data.card && actions) {
      // (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
      // recoverable state, per https://developer.paypal.com/docs/checkout/standard/customize/handle-funding-failures/
      return actions.restart();
    } else if (
      errorDetail ||
      !transaction ||
      transaction.status === "DECLINED"
    ) {
      // (2) Other non-recoverable errors -> Show a failure message
      let errorMessage;
      if (transaction) {
        errorMessage = `Transaction ${transaction.status}: ${transaction.id}`;
        //document.getElementById('overlay').classList.remove('flex');
        //document.getElementById('overlay').classList.add('hidden');
      } else if (errorDetail) {
        errorMessage = `${errorDetail.description} (${orderData.debug_id})`;
        //document.getElementById('overlay').classList.remove('flex');
        //document.getElementById('overlay').classList.add('hidden');
      } else {
        errorMessage = JSON.stringify(orderData);
        //document.getElementById('overlay').classList.remove('flex');
        //document.getElementById('overlay').classList.add('hidden');
      }

      throw new Error(errorMessage);
    } else {
      // (3) Successful transaction -> Show confirmation or thank you message
      // Or go to another URL:  actions.redirect('thank_you.html');
      resultMessage(
        `Transaction ${transaction.status}: ${transaction.id}<br><br>See console for all available details`
      );

      const el = document.querySelector('#cart-component div[wire\\:id]');
      const component = Livewire.find(el.getAttribute('wire:id'));

      // Call the Livewire method; returns JS object directly
      const order = await component.call('thankyou',orderData);

      document.getElementById('divoverlay').classList.add('hidden');
      // document.getElementById('overlay').classList.remove('flex');
      // document.getElementById('overlay').classList.add('hidden');
      // document.location.href="/payment/alldone";
    }

  } catch (error) {
    console.error(error);
    resultMessage(
      `Sorry, your transaction could not be processed...<br><br>${error}`
    );
  }

}

// Function to search for the iframe by its title
function findIframeByTitle(title) {
    const iframes = document.getElementsByTagName('iframe');
    for (let iframe of iframes) {
        if (iframe.title === title) {
            return iframe;
        }
    }
    return null;
}

// Select the parent node where the element might be added
const observerTarget = document.body; // You can adjust this to be more specific

// Create a callback function to execute when mutations are observed
const observerCallback = function(mutationsList, observer) {
    for (let mutation of mutationsList) {
        if (mutation.type === 'childList') {
            // Check if the element has been added
            const newElement = document.querySelector('[id^="zoid-paypal-card-cvv-field-uid_"]');
            if (newElement) {
                console.log('Element created:', newElement);
                // document.getElementById('overlay').classList.remove('flex');
                // document.getElementById('overlay').classList.add('hidden');

                // Perform any actions you need here
                observer.disconnect(); // Stop observing once the element is found
                break;
            }
        }
    }
};

// Create a MutationObserver instance
const observer = new MutationObserver(observerCallback);

// Start observing for child node additions or removals
observer.observe(observerTarget, {
    childList: true,
    subtree: true
});

// Example function to show a result to the user. Your site's UI library can be used instead.
function resultMessage(message) {
  const container = document.querySelector("#result-message");
  container.innerHTML = message;
}