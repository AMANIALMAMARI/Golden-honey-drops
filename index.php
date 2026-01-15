<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$host = "localhost";
$user = "u629988973_Honey";
$pass = "Oman2020oman*";
$db   = "u629988973_Honey";

$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error){
    die("Database connection failed: ".$conn->connect_error);
}

$products = [];
$result = $conn->query("SELECT * FROM products ORDER BY category, name");
if($result){
    while($row = $result->fetch_assoc()){
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Golden Drops Honey</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background: #fff8ec;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #eec271;
    padding: 10px 20px;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1001;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.navbar-left img {
    height: 60px;
    width: 180px;
    object-fit: contain;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
    align-items: center;
}

.nav-links li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav-links li a:hover {
    color: #4e3205;
}

.logout-btn {
    background-color: #4e3205ab;
    padding: 5px 12px;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    transition: background 0.3s;
}

.cart-icon {
    position: fixed;
    top: 18px;
    right: 20px;
    background: #e8b04a;
    color: white;
    padding: 10px 15px;
    border-radius: 30px;
    cursor: pointer;
    z-index: 1000;
}

.cart-icon::after {
    content: attr(data-count);
    position: absolute;
    top: -10px;
    right: -10px;
    background: red;
    color: white;
    font-size: 12px;
    padding: 3px 6px;
    border-radius: 50%;
}

.cart {
    position: fixed;
    top: 70px;
    right: 20px;
    background: white;
    border: 1px solid #ccc;
    padding: 10px;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    display: none;
    flex-direction: column;
    gap: 10px;
    border-radius: 10px;
    z-index: 999;
}

.cart-footer {
    display: flex;
    gap: 10px;
}

.cart-close-bottom,
.checkout-full {
    flex: 1;
    padding: 10px;
    font-size: 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
}

.cart-close-bottom { background: crimson; color: white; }
.checkout-full { background: #28a745; color: white; transition: background 0.3s; }
.checkout-full:hover { background: #218838; }

.checkout-popup {
    position: fixed;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    padding: 20px;
    width: 90%;
    max-width: 400px;
    border: 2px solid #aaa;
    border-radius: 10px;
    z-index: 9999;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    display: none;
}

.checkout-popup h3 { margin-top: 0; }
.checkout-popup input,
.checkout-popup textarea,
.checkout-popup button { width: 100%; margin: 10px 0; padding: 8px; font-size: 14px; }

section { padding: 40px 20px; }

.home {
    background: url('images/honey-bg.png') center/cover no-repeat;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.home-overlay { width: 100%; height: 100%; display: flex; align-items: center; justify-content: flex-start; padding-left: 5%; }

.home-content h1 { font-size: 3rem; color: #ffdd6c; margin-bottom: 20px; }
.btn-shop { background-color: #a87b2d; color: #fff; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-size: 1rem; }
.btn-shop:hover { background-color: #8c5e1a; }

.about-honey {
    position: relative;
    background-image: url('images/honey-bg1.jpg');
    background-size: cover;
    background-position: center;
    padding: 80px 20px;
    color: white;
    text-align: center;
}

.about-honey .overlay {
    background-color: rgba(255, 243, 205, 0.92);
    padding: 50px 20px;
    border-radius: 16px;
    max-width: 900px;
    margin: auto;
    box-shadow: 0 0 30px rgba(0,0,0,0.15);
}

.about-honey h1 { font-size: 40px; margin-bottom: 30px; color: #7a4e19; font-family: 'Georgia', serif; }
.honey-info { font-size: 18px; line-height: 2; color: #3f2e16; }

.products h1 { text-align: center; color: #a87b2d; margin-bottom: 30px; font-size: 32px; }
.product-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
.product { background: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; }
.product img {
  width: 200px;
  height: 200px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 10px;
}

.product button { background-color: #e8b04a; color: white; border: none; padding: 10px; margin-top: 10px; border-radius: 5px; cursor: pointer; }

#contact { background-color: #e9c0735c; padding: 60px 20px; text-align: center; }
#contact h1 { color: #a87b2d; font-size: 32px; margin-bottom: 10px; }
#contact p { color: #4e3205; font-size: 18px; margin-bottom: 30px; }
.contact-form-container { max-width: 500px; margin: auto; }
.contact-form-container form input,
.contact-form-container form textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-family: inherit; }
.contact-form-container form button { background-color: #e8b04a; color: white; padding: 10px 20px; border: none; border-radius: 25px; cursor: pointer; font-size: 16px; transition: background 0.3s; }
.contact-form-container form button:hover { background-color: #c78f31; }
.contact-info { margin-top: 30px; color: #4e3205; font-size: 16px; }
.contact-info p { margin: 5px 0; }

</style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-left">
        <img src="images/logo.png" alt="Logo">
    </div>
    <ul class="nav-links">
        <li><a href="#home">Home</a></li>
        <li><a href="#about-honey">About</a></li>
        <li><a href="#products">Products</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="login.php" class="logout-btn">Log Out</a></li>
    </ul>
    <div class="navbar-cart">
        <div class="cart-icon" id="cartIcon" data-count="0">🛒</div>
    </div>
</nav>

<div class="cart" id="cart">
    <h3>Shopping Cart</h3>
    <div id="cart-items"></div>
    <p><strong>Total:</strong> <span id="cart-total">0</span> OMR</p>
    <div class="cart-footer">
        <button class="cart-close-bottom" onclick="toggleCart()">Close</button>
        <button id="checkout-btn" class="checkout-full">Checkout</button>
    </div>
</div>

<div class="checkout-popup" id="checkout-popup">
    <h3>Complete Your Order</h3>
    <form id="checkout-form">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="tel" name="phone" placeholder="Phone Number" required>
        <textarea name="location" placeholder="Delivery Location" required></textarea>
        <button type="submit">Confirm Order</button>
        <button type="button" onclick="closeCheckout()">Cancel</button>
    </form>
</div>

<section class="home" id="home">
    <div class="home-overlay">
        <div class="home-content">
            <h1>Golden Drops Honey</h1>
            <p>Nature's finest gift — Straight from the mountains and valleys of Oman.</p>
            <a href="#products" class="btn-shop">Shop Now</a>
        </div>
    </div>
</section>

<section class="about-honey" id="about-honey">
  <div class="overlay">
    <h1>About Golden Drops Honey</h1>
    <div class="honey-info">
      <p>
        <strong>Golden Drops Honey</strong> represents the finest natural honey, harvested with care from
        pristine Omani landscapes. Our honey is celebrated for its exceptional purity, golden color, and
        rich taste that captures the essence of nature.  
        We offer a variety of premium types including <em>Sidr (Jujube)</em>, <em>Sumr (Acacia)</em>,
        and <em>Wild Flower Honey</em> — each known for its unique flavor and natural health benefits.  
        At Golden Drops, we bring you nature’s sweetness in its purest form.
      </p>
      <div class="video-container">
        <video controls width="720">
          <source src="images/Oman'sHoneySeason.mp4" type="video/mp4">
        </video>
      </div>
    </div>
  </div>
</section>

<section class="products" id="products">
<h1>Our Products</h1>
<?php
$categoriesOrder = ["Raw & Flavored Honey","Honey-Based Products","Bee Supplements"];
$productsByCategory = [];
foreach($products as $p){ $productsByCategory[$p['category']][] = $p; }

foreach($categoriesOrder as $cat){
  if(!empty($productsByCategory[$cat])){
    echo "<h2>$cat</h2><div class='product-list'>";
    foreach($productsByCategory[$cat] as $prod){
      echo "<div class='product'>
        <img src='{$prod['image']}' alt='{$prod['name']}'>
        <h3>{$prod['name']}</h3>
        <p>{$prod['price']} OMR</p>";
      
      if($prod['stock'] > 0){
        echo "<button onclick=\"addToCart('{$prod['name']}', {$prod['price']}, '{$prod['image']}')\">Add to Cart</button>";
      } else {
        echo "<button disabled style='background:gray; cursor:not-allowed;'>Sold Out</button>";
      }

      echo "</div>";
    }
    echo "</div>";
  }
}
?>
</section>

<section id="contact">
<h1>Contact Us</h1>
<p>We’d love to hear from you!</p>
<div class="contact-form-container">
    <form action="save_message.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
    </form>
</div>
<div class="contact-info">
    <p><strong>Phone:</strong> +968 9123 4567</p>
    <p><strong>Email:</strong> info@goldendrops.com</p>
    <p><strong>Location:</strong> Muscat, Oman</p>
</div>
</section>

</section>

<script>

const cartIcon = document.getElementById("cartIcon");
const cart = document.getElementById("cart");
const cartItems = document.getElementById("cart-items");
const cartTotal = document.getElementById("cart-total");
const checkoutPopup = document.getElementById("checkout-popup");

let cartData = {};


function toggleCart(){
    cart.style.display = cart.style.display === "block" ? "none" : "block";
}
cartIcon.addEventListener("click", toggleCart);


function addToCart(name, price, image){
    if(cartData[name]){
        cartData[name].qty += 1;
    } else {
        cartData[name] = {
            price: price,
            qty: 1,
            image: image
        };
    }
    updateCart();
}


function increaseQty(name){
    cartData[name].qty += 1;
    updateCart();
}


function decreaseQty(name){
    if(cartData[name].qty > 1){
        cartData[name].qty -= 1;
    } else {
        delete cartData[name];
    }
    updateCart();
}


function updateCart(){
    cartItems.innerHTML = '';
    let total = 0;
    let count = 0;

    for(const item in cartData){
        const div = document.createElement("div");
        div.style.display = "flex";
        div.style.alignItems = "center";
        div.style.marginBottom = "8px";
        div.style.gap = "6px";

        div.innerHTML = `
            <img src="${cartData[item].image}"
                 style="width:40px;height:40px;object-fit:cover;border-radius:5px;">

            <span style="flex:1;font-size:14px;">${item}</span>

            <button onclick="decreaseQty('${item}')"
                    style="padding:2px 8px;">−</button>

            <span>${cartData[item].qty}</span>

            <button onclick="increaseQty('${item}')"
                    style="padding:2px 8px;">+</button>

            
        `;

        cartItems.appendChild(div);

        total += cartData[item].price * cartData[item].qty;
        count += cartData[item].qty;
    }

    cartTotal.textContent = total.toFixed(2);
    cartIcon.setAttribute("data-count", count);
}








document.getElementById("checkout-btn").addEventListener("click", ()=>{
    if(Object.keys(cartData).length === 0){
        alert("Your cart is empty!");
        return;
    }
    cart.style.display = "none";
    checkoutPopup.style.display = "block";
});


function closeCheckout(){
    checkoutPopup.style.display = "none";
}


document.getElementById("checkout-form").addEventListener("submit", function(e){
    e.preventDefault();

    const payload = {
        name: this.name.value,
        phone: this.phone.value,
        location: this.location.value,
        cart: cartData
    };

    fetch("process_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
         
            localStorage.setItem("currentOrder", JSON.stringify(payload));

       
            cartData = {};
            updateCart();
            closeCheckout();

        
            window.location.href = "payment.php";
        } else {
            alert("❌ " + data.message);
        }
    })
    .catch(err => alert("❌ Error: " + err));
});



</script>

</body>
</html>
