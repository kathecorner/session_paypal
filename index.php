<html>

<head>

<script src="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/5.18.0/adyen.js"
     integrity="sha384-+Rw9rSHx2q26vErvjVDm4hqgWknn8gKtLsfkpPNClC2OrzVcWBHM3N2IvElU8LGB"
     crossorigin="anonymous"></script>

<link rel="stylesheet"
     href="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/5.18.0/adyen.css"
     integrity="sha384-QG8p2tW0dTruLa4Vjrq40etubKF7pMdXk1edAO5Z7aMXgahCo1NLHDpQQihqhnx3"
     crossorigin="anonymous">

<style>
  * {
    font-family: arial;
    margin: 0px;
  }

  .purchase {
     display:grid;
     grid-template-columns: 100px auto;
     grid-row-gap: 10px;
     background-color: #D5F6F5;
     border: 1px solid black;
     border-radius: 10px;
     padding: 10px;
     font-size: 20px;
     width: 400px;
     margin: 30px;
   }

   .dropin {
    margin: 20px;
    width: 600px;
   }

   h1 {
    padding: 0px;
    margin: 0px;
    margin-bottom: 10px;
   }

   input {
     font-size: 20px;
   }

   .submit {
     grid-column-start: 1;
     grid-column-end: 3;
     justify-self: center;
   }

   .submitButton {
     background-color: rgb(13,183,73);
     font-size: 18px;
     border: 0px;
     border-radius: 8px;
     padding: 10px 15px 10px 15px;
     margin-top: 10px;
     cursor: pointer;
     box-shadow: 1px 1px 2px black;
   }

    .submitButton:hover {
     background-color: rgb(232,203,103);
   }

   #dropin-container {
    margin:  10px;
   }

</style>


<script>
var PaymentMethod = "";
var dropinComponent = ""

function showResults(strResult){
  document.write(strResult);
}

async function getSession() {

  // BUILD SESSION
  var url = "getSession.php";
  var data = {
    "amount":document.getElementById("amount").value,
    "currency":document.getElementById("currency").value,
  };

  // UNMOUNT ALREADY CREATED DROP-IN
  if (dropinComponent) {
    dropinComponent.unmount();
  }

  var res = await callServer(url, data);
  console.log("get session response: => " , res);

  // PREPARE CONFIG
  const configuration = {
    environment: 'test',
    clientKey: 'test_RKKBP5GHOFFUFJJMJHOJAG7ZIIJKBMI6',
    setStatusAutomatically: true,
    showPayButton: true,
    session: {
      id: res.id,
      sessionData: res.sessionData
    },
    countryCode: "DE",


    onPaymentCompleted: (result, component) => {
      console.log("Payment Completed result = " , result);
      console.info("Payment Completed component = " , component);
      showResults(result["resultCode"]);
      

    },
    onError: (error, component) => {
      console.error(error.name, error.message, error.stack, component);
    },/*
    beforeSubmit: (data, component, actions) => {
      console.log("Before submit payment");
      console.log("Data == ", data);
      console.log("Component == ", component);
      console.log("Actions == ", actions);
      actions.resolve(data);
    },*/

    paymentMethodsConfiguration: {
      showInstallmentOptions: true,
      card: {
        hasHolderName: false,
        holderNameRequired: false,
        billingAddressRequired: false,
        telephoneNumberRequired: false,
        showBrandsUnderCardNumber: false,
        installmentOptions: {
           card: {
              values: [3,6,9,12],
              plans: [ 'regular', 'revolving'],
              showInstallmentAmounts: true,
          }
        }
      },/*
      klarna_paynow: {
        amount: {
          currency: "USD",
          value: 1000
        }
      },*/
    }
  };

  // Create an instance of AdyenCheckout using the configuration object.
  var checkout = await AdyenCheckout(configuration);

  //document.getElementById("checkoutDiv").style.display = 'none';
  dropinComponent = checkout.create('paypal').mount('#dropin-container');
  //dropinComponent = checkout.create('svs').mount('#dropin-container');
  //dropinComponent = checkout.create('dropin').mount('#dropin-container');
  // dropinComponent = checkout.create('card').mount('#dropin-container');
  document.getElementById("checkoutButton").disabled = false;
}

async function callServer(url, data) {

  const res = await fetch(url, {
  	method: "POST",
    body: JSON.stringify(data),
  	headers: {
  		"Content-Type": "application/json"
  	}
  })

  data = await res.json();
  return data;

}

</script>

</head>

<body>

<div class="purchase" id='checkoutDiv'>
  <h1 class="submit">mount web object</h1>
   <div>Amount</div><div><input id="amount" size=10></div>
   <div>Currency</div><div><input id="currency" size=4></div>
   <div class="submit"><button class="submitButton" id="checkoutButton" type="buton" onclick="getSession()">call Session API</button></div>
</div>

<div class="dropin">
   <!--<div id="klarna_paynow-container"></div>-->
  <div id="dropin-container"></div>
</div>

</html>
