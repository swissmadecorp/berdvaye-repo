// Render the button component
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
  document.getElementById("paynow").addEventListener("click", (event) => {
      event.preventDefault();

      const el = document.querySelector('#cart-component div[wire\\:id]');
      const component = Livewire.find(el.getAttribute('wire:id'));

      // Call the Livewire method; returns JS object directly
      const orderData = component.call('validateFields');

      cardField.getState().then((data) => {
        // Submit only if the current
        // state of the form is valid
        if (data.isFormValid) {
          // document.getElementById('overlay').classList.add('flex');
          // document.getElementById('overlay').classList.remove('hidden');
          cardField.submit({

            // From your billing address fields
            billingAddress: {
              addressLine1: document.getElementById("address1").value,
              addressLine2: document.getElementById("address2").value,
              countryCode: document.getElementById("bcountry").value,
              postalCode: document.getElementById("zip").value,
            },

          })
          .then(() => {
            // submit successful

          });
        }
    });

  });

}


async function createOrderCallback() {
  resultMessage("");
  try {
  //   const form = document.querySelector('form');
  //   const formData = new FormData(form);

  //   const response = await fetch("/payment/order", {
  //     method: "POST",
  //     headers: {
  //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
  //     },
  //     body: formData,
  //     redirect: 'follow',
  // });

  //   const orderData = await response.json();
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
    // console.error(error);
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
      // actions.redirect()
      // console.log(
      //   "Capture result",
      //   orderData,
      //   JSON.stringify(orderData, null, 2)
      // );

      //window.location.replace('/payment/thankyou?status='+transaction.status+'&data='+JSON.stringify(orderData, null, 2));
      // const requestOptions = {
      //   method: 'POST',
      //   headers: {
      //     "Content-type": "application/json; charset=UTF-8",
      //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      //   },
      //   body: JSON.stringify(orderData), // Coloque o JSON no corpo da solicitação
      //   redirect: 'follow'
      // };

      // const url = `/payment/thankyou`;

      // const response = await fetch(url, requestOptions);

      const el = document.querySelector('#cart-component div[wire\\:id]');
      const component = Livewire.find(el.getAttribute('wire:id'));

      // Call the Livewire method; returns JS object directly
      const order = await component.call('thankyou',orderData);

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