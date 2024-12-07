<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="Home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>MotoMagX</title>
</head>

<body>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once 'database.php';

    $conn = Database::getInstance();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{
        echo("Success");
    }
    ?>

    <?php include 'header.php'; ?>
    <div class="slider">
        <div class="sliderWrapper">
            <div class="sliderItem">
                <img src="./img/boomx.png" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">BOM X</br> NEW</br></h1>
                <h2 class="sliderPrice">₱2,500</h2>
                <a href="#product">
                    <button class="buyButton">BUY NOW!</button>
                </a>
            </div>
            <div class="sliderItem">
                <img src="./img/asio.png" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">ASIO</br> NEW</br></h1>
                <h2 class="sliderPrice">₱2,500</h2>
                <a href="#product">
                    <button class="buyButton">BUY NOW!</button>
                </a>
            </div>
            <div class="sliderItem">
                <img src="./img/" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">TRC</br> NEW</br></h1>
                <h2 class="sliderPrice">₱2,500</h2>
                <a href="#product">
                    <button class="buyButton">BUY NOW!</button>
                </a>
            </div>
            <div class="sliderItem">
                <img src="./img/" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">RCB</br> NEW</br></h1>
                <h2 class="sliderPrice">₱2,500</h2>
                <a href="#product">
                    <button class="buyButton">BUY NOW!</button>
                </a>
            </div>
            <div class="sliderItem">
                <img src="./img/" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">MUTTARU</br> NEW</br></h1>
                <h2 class="sliderPrice">₱2,500</h2>
                <a href="#product">
                    <button class="buyButton">BUY NOW!</button>
                </a>
            </div>
        </div>
    </div>

    <div class="features">
        <div class="feature">
            <img src="./img/shipping.png" alt="" class="featureIcon">
            <span class="featureTitle">FREE SHIPPING</span>
            <span class="featureDesc">Free worldwide shipping on all orders.</span>
        </div>
        <div class="feature">
            <img class="featureIcon" src="./img/return.png" alt="">
            <span class="featureTitle">30 DAYS RETURN</span>
            <span class="featureDesc">No question return and easy refund in 14 days.</span>
        </div>
        <div class="feature">
            <img class="featureIcon" src="./img/gift.png" alt="">
            <span class="featureTitle">GIFT CARDS</span>
            <span class="featureDesc">Buy gift cards and use coupon codes easily.</span>
        </div>
        <div class="feature">
            <img class="featureIcon" src="./img/contact.png" alt="">
            <span class="featureTitle">CONTACT US!</span>
            <span class="featureDesc">Keep in touch via email and support system.</span>
        </div>
    </div>

    <div class="product" id="product">
        <img src="./img/boomx.png" alt="" class="productImg">
        <div class="productDetails">
            <h1 class="productTitle">BOM X</h1>
            <h2 class="productPrice">₱2500</h2>
            <p class="productDesc">Lorem ipsum dolor sit amet consectetur impal adipisicing elit. Alias assumenda
                dolorum
                doloremque sapiente aliquid aperiam.</p>
            <div class="colors">
                <div class="color"></div>
                <div class="color"></div>
                <div class="color"></div>
            </div>
            <div class="brands">
                <select id="motorcycleBrands" name="motorcycleBrands">
                    <option value="" selected disabled>Select a Model</option>
                    <option value="hondaClick">Honda Click</option>
                    <option value="sniper">Sniper</option>
                    <option value="nmax">Nmax</option>
                    <option value="suzuki">Aerox</option>
                </select>
            </div>
            <button class="productButton">BUY NOW!</button>
        </div>

        <div class="payment">
            <h1 class="payTitle">Personal Information</h1>
            <label>Name and Surname</label>
            <input type="text" placeholder="John Doe" class="payInput">
            <label>Phone Number</label>
            <input type="text" placeholder="+1 234 5678" class="payInput">
            <label>Address</label>
            <input type="text" placeholder="Elton St 21 22-145" class="payInput">
            <h1 class="payTitle">Card Information</h1>
            <div class="cardIcons">
                <img src="./img/visa.png" width="40" alt="" class="cardIcon">
                <img src="./img/master.png" alt="" width="40" class="cardIcon">
            </div>
            <input type="password" class="payInput" placeholder="Card Number">
            <div class="cardInfo">
                <input type="text" placeholder="mm" class="payInput sm">
                <input type="text" placeholder="yyyy" class="payInput sm">
                <input type="text" placeholder="cvv" class="payInput sm">
            </div>
            <button class="payButton">Checkout!</button>
            <span class="close">X</span>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="./Home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>