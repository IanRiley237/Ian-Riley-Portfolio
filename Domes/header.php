<!DOCTYPE html>
<html class = "document">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="allpages.js"></script>
        <?php
            require_once('setup.php');
            if (isset($_POST["cart"]))
            {
                $query = $conn->query(" SELECT COUNT(*) + 1
                                        FROM `IMR_Orders`
                                        WHERE time_manufactured IS NULL")->fetch_array();
                $queue = $query[0];
                $cart = explode(";", $_POST["cart"]);
                foreach ($cart as &$hatInfoString)
                {
                    $hatInfo = explode(",", $hatInfoString);

                    if(!isset($hatInfo[2]))
                        continue;
                        
                    if(!$conn->query("  INSERT INTO `IMR_Orders` (hat_id, hat_size, hat_count, customer_name, `state`, city, street, zipcode, time_ordered)
                                        VALUES (" . intval($hatInfo[0]) . ",'" . $conn->real_escape_string($hatInfo[1]) . "'," . intval($hatInfo[2]) . ",'" . $conn->real_escape_string($_POST['firstname']) . "','" . $conn->real_escape_string($_POST['state']) . "','" . $conn->real_escape_string($_POST['city']) . "','" . $conn->real_escape_string($_POST['street']) . "'," . intval($_POST['zip']) . ", NOW())"))
                    {
                        echo "Error message = ".$conn->error."<br>";
                    }
                }
                ?>
                    <div class = "confirmationPopupBackground" id = "confirmationPopupBackground">
                        <div class = "cartPopup">
                            <p>Thank you for your purchase!</p>
                            <p>Your order is currently #<?php echo $queue ?> in queue</p>
                        </div>
                        <div class = "cancelbutton" onclick = "$('#confirmationPopupBackground').css('display', 'none')">Cancel</div>
                    </div>
                <?php
            }
        ?>
        <link rel = "stylesheet" href = "allpages.css">
        
        <div class = "banner">
            Welcome to
            <p class = "title" class = "title" onclick = "window.location.assign('/Domes')">Domes</p>
        </div>

        <div class = "cartPopupBackground" id = "cartPopupBackground">
            <form class = "cartPopup" method="post">
                <div class = "cartPopupContent">
                    <div id = "cartList" class = "cartList"><div></div></div>
                    <div id = "userInfo" class = "userInfo">
                        <p>
                            <label for="firstname">First Name:</label>
                            <input name = "firstname" id = "firstname" type = "text" required>
                        </p>
                        <p>
                            <label for="street">Street:</label>
                            <input name = "street" id = "street" type = "text" required>
                        </p>
                        <p>
                            <label for="city">City:</label>
                            <input name = "city" id = "city" type = "text" required>
                        </p>
                        <p>
                            <label for="zip">Zip:</label>
                            <input name = "zip" id = "zip" type = "text" required>
                        </p>
                        <script>
                            // Force non-negative integers in textbox
                            $("#zip").on('change keydown paste input', function(){
                                $("#zip").val($("#zip").val().replace(/\D/g,''));
                                if (parseInt($("#zip").val()) > 99999)
                                {
                                    $("#zip").val("99999");
                                }
                            })
                        </script>
                        <p>
                            <label for="state">State:</label>
                            <input name = "state" id = "state" type = "text" required>
                        </p>
                    
                        <input name = "cart" id = "cartString" type = "hidden" required>
                        <input type = "submit">
                    </div>
                </div>
            </form>
            <div class = "cancelbutton" onclick = "$('#cartPopupBackground').css('display', 'none')">Cancel</div>
        </div>

    </head>
