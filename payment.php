<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Payment</title>

<style>
body {
  font-family: Arial, sans-serif;
  background: #f7f5f2;
  padding: 20px;
}

.container {
  max-width: 500px;
  margin: auto;
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 0 10px #ccc;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

label {
  display: block;
  margin-top: 15px;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

input.error {
  border: 2px solid #e74c3c;
}

.total {
  margin-top: 25px;
  font-size: 18px;
  font-weight: bold;
}

button {
  margin-top: 25px;
  width: 100%;
  padding: 12px;
  background: #a87b2d;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
}

button:disabled {
  background: #ccc;
  cursor: not-allowed;
}
</style>
</head>

<body>

<div class="container">
  <h2>Payment Details</h2>

  <div id="payment-summary"></div>

  <form id="paymentForm" onsubmit="return submitPayment()">

    <label>Cardholder Name</label>
    <input type="text" id="cardName" placeholder="Name on card">

    <label>Card Number</label>
    <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">

    <label>Expiry Date</label>
    <input type="text" id="expDate" placeholder="MM/YY" maxlength="5">

    <label>CVV</label>
    <input type="text" id="cvv" placeholder="123" maxlength="4">

    <div class="total" id="totalAmount">Total: 0 OMR</div>

    <button type="submit" id="payBtn" disabled>Confirm & Pay</button>
  </form>
</div>

<script>

const order = JSON.parse(localStorage.getItem("currentOrder"));
const paymentSummary = document.getElementById("payment-summary");
const totalAmount = document.getElementById("totalAmount");
const payBtn = document.getElementById("payBtn");

if (!order || !order.cart || Object.keys(order.cart).length === 0) {
  paymentSummary.innerHTML = "<p>No orders found.</p>";
} else {

  let total = 0;
  let html = `
    <h3>Order Summary</h3>
    <p>
      <strong>Name:</strong> ${order.name}<br>
      <strong>Phone:</strong> ${order.phone}<br>
      <strong>Location:</strong> ${order.location}
    </p>
    <hr>
  `;

  Object.entries(order.cart).forEach(([productName, item]) => {
    const itemTotal = item.qty * item.price;
    total += itemTotal;

    html += `
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <img src="${item.image}" style="width:40px;height:40px;border-radius:5px;">
        <div>
          <strong>${productName}</strong><br>
          Qty: ${item.qty} × ${item.price} OMR
        </div>
        <div style="margin-left:auto;font-weight:bold;">
          ${itemTotal.toFixed(2)} OMR
        </div>
      </div>
    `;
  });

  html += `<hr><p><strong>Total:</strong> ${total.toFixed(2)} OMR</p>`;
  paymentSummary.innerHTML = html;
  totalAmount.innerText = `Total: ${total.toFixed(2)} OMR`;
}


const inputs = ["cardName","cardNumber","expDate","cvv"].map(id => document.getElementById(id));

inputs.forEach(input => input.addEventListener("input", validateForm));

function validateForm() {
  let valid = true;
  inputs.forEach(i => i.classList.remove("error"));

  if(inputs[0].value.trim() === "") { inputs[0].classList.add("error"); valid = false; }
  if(inputs[1].value.replace(/\s/g,'').length < 16) { inputs[1].classList.add("error"); valid = false; }
  if(!/^\d{2}\/\d{2}$/.test(inputs[2].value)) { inputs[2].classList.add("error"); valid = false; }
  if(inputs[3].value.length < 3) { inputs[3].classList.add("error"); valid = false; }

  payBtn.disabled = !valid;
}


document.addEventListener("DOMContentLoaded", () => {
  validateForm();
});


function submitPayment() {
  if(payBtn.disabled) return false;

  localStorage.removeItem("cart");
  localStorage.removeItem("currentOrder");

  window.location.href = "thankyou.php";
  return false;
}
</script>

</body>
</html>
