<!-- Shopping checkout section  -->
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['delete-cart-submit'])){
            $deletedrecord = $Cart->deleteCart($_POST['item_id']);
        }

    }
    
    @include 'config.php';
    if(isset($_POST['submit_checkout'])){
        // print_r($_POST);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);
        $user_id = '1';
        foreach ($product->getData('cart') as $item) :
            $cart = $product->getProduct($item['item_id']);

            $subTotal[] = array_map(function ($item){
                return $item['item_price'];
                }, $cart); 
                endforeach;
        $total_price = $Cart->getSum($subTotal);
        $payment_status = 'pending';
        if($payment_type == 'COD'){
            $payment_status = 'success';
        }
        $order_status = 'pending';
        $added_on = date('Y-m-d h:i:s');

        $insert = "INSERT INTO `order`(user_id,name,address,city,pincode, email, phone,payment_type,total_price,payment_status,order_status,added_on) 
        VALUES('$user_id','$name','$address','$city','$pincode','$email','$phone','$payment_type','$total_price','$payment_status','$order_status','$added_on')";
        mysqli_query($conn, $insert);

        $order_id = mysqli_insert_id($conn);
        foreach ($product->getData('cart') as $item) :
            $cart = $product->getProduct($item['item_id']);
            $product_id = $item['item_id'];
            $price = $item['item_price'];
            $insert_detail = "INSERT INTO `order_detail`(order_id,product_id,qty,price) VALUES('$order_id','$product_id','1','$price')";
            mysqli_query($conn, $insert_detail);
            $subTotal[] = array_map(function ($item){
                return $item['item_price'];
                }, $cart); 
                endforeach;

                $Cart->emptycart();


        ?>
        <script>
            window.location.href = 'thank_you.php';
        </script>
        <?php
    }
?>

<!-- cart-main-area start -->
<div class="checkout-wrap ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="checkout__inner">
                            <div class="accordion-list">
                                <div class="accordion">
                                    <div class="accordion__title">
                                        Checkout Method
                                    </div>
                                    
                                    <div class="accordion__title">
                                        Address Information
                                    </div>
                                    <form action="" method="post">
                                    <div class="accordion__body">
                                        <div class="bilinfo">
                                            
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="single-input" >
                                                            <input type="text" name="name" placeholder="Name" required/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="single-input">
                                                            <input type="text" name="address" placeholder="Street Address" required/>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="single-input">
                                                            <input type="text" name="city" placeholder="City/State" required/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="single-input">
                                                            <input type="text" name="pincode" placeholder="Post code/ zip" required/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="single-input">
                                                            <input type="email" name="email" placeholder="Email address" required/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="single-input">
                                                            <input type="text" name="phone" placeholder="Phone number" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                        </div>
                                    </div>
                                    <div class="accordion__title">
                                        payment information
                                    </div>
                                    <div class="accordion__body">
                                        <div class="paymentinfo">
                                            <div class="single-method">
                                                COD <input type="radio" name="payment_type" value="COD" required/>
                                                &nbsp;&nbsp;  PayU <input type="radio" name="payment_type" value="payu" required/>
                                            </div>
                                            <div class="single-method">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" name="submit_checkout" class="form-btn"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="order-details">
                            <h5 class="order-details__title">Your Order</h5>
                            <div class="order-details__item">
                                <?php
                                    foreach ($product->getData('cart') as $item) :
                                    $cart = $product->getProduct($item['item_id']);
                                    $subTotal[] = array_map(function ($item){
                                ?>
                                <div class="single-item">
                                    <div class="single-item__thumb">
                                        <img src="<?php echo $item['item_image'] ?? "./assets/products/1.png" ?>" style="height: 65px">
                                    </div>
                                    <div class="single-item__content">
                                        <h5 class="font-baloo font-size-10"><?php echo $item['item_name'] ?? "Unknown"; ?></h5>
                                        <div class="font-size-10 text-danger font-baloo" style="display: flex;">
                                            ₹<span class="product_price" data-id="<?php echo $item['item_id'] ?? '0'; ?>"><?php echo $item['item_price'] ?? 0; ?></span>
                                        </div>
                                    </div>
                                    <div class="single-item__remove">
                                    <form method="post">
                                        <input type="hidden" value="<?php echo $item['item_id'] ?? 0; ?>" name="item_id">
                                        <button type="submit" name="delete-cart-submit" class="btn font-baloo text-danger px-3 border-right">Delete</button>
                                    </form>
                                    </div>
                                </div>
                                <?php
                                    return $item['item_price'];
                                    }, $cart); // closing array_map function
                                    endforeach;
                                ?>
                                
                            </div>
                            <div class="order-details__count">
                                <div class="order-details__count__single">
                                    <h5>sub total</h5>
                                    <span class="text-danger">₹<span class="text-danger" id="deal-price"><?php echo isset($subTotal) ? $Cart->getSum($subTotal) : 0; ?></span> </span>
                                </div>
                                
                            </div>
                            <div class="ordre-details__total">
                                <h5>Order total</h5>
                                <span class="text-danger">₹<span class="text-danger" id="deal-price"><?php echo isset($subTotal) ? $Cart->getSum($subTotal) : 0; ?></span> </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- cart-main-area end -->