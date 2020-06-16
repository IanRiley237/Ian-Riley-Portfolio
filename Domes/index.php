<?php require_once('header.php') ?>
    <body>
        <div class = "checkout" onclick = "$('#cartPopupBackground').css('display', 'block'); $('#cartList').html(getCartList()); $('#cartString').val(cartToString())">
            <img class = "cartimg" src = "cart.png">
            <div class = "cartcount" id = "cartcountcontainer" hidden><span id = "cartcount">0</span></div>
        </div>
        <div class = "hatcardcontainer">
            <?php
                $selectHats = ' SELECT *
                                FROM `IMR_Hats`';
                $hatsTable = $conn->query($selectHats);

                while ($hat = $hatsTable->fetch_assoc())
                {
                    ?>
                        <div class = "hatcard">
                            <div class = "hatinfoleft">
                                <img class = "hatimg" src="hats/<?php echo $hat['image'] ?>">
                            </div>
                            <div class = "hatinforight">
                                <p class = "hatname"><?php echo $hat['name'] ?></p>
                                <p class = "hatdesc"><?php echo $hat['description'] ?></p>
                                <div style="height: 35px;"></div> <!-- Hi, I am a div that gives a bit of space between hatdesc and orderinfo so they do no overlap -->
                                <div id = "<?php echo $hat['id'] ?>error" hidden style = "color: red">You have an invalid input!</div>
                                <p class = "orderinfo">
                                    <span class = "hatprice">$<?php echo number_format($hat['price'], 2, '.', '') ?></span>
                                    <select id = "<?php echo $hat['id'] ?>size">
                                        <option value = "" disabled hidden selected>Choose size</option>
                                        <option value = "XS">XS</option>
                                        <option value = "S">S</option>
                                        <option value = "M">M</option>
                                        <option value = "L">L</option>
                                        <option value = "XL">XL</option>
                                    </select>
                                    Count: <input class = "count" id = "<?php echo $hat['id'] ?>count" type = "number" min = "1" max = "99" columns = "2" size="2">
                                    <script>
                                        // Force non-negative integers in textbox
                                        $("#<?php echo $hat['id'] ?>count").on('change keydown paste input', function(){
                                            $("#<?php echo $hat['id'] ?>count").val($("#<?php echo $hat['id'] ?>count").val().replace(/\D/g,''));
                                            if (parseInt($("#<?php echo $hat['id'] ?>count").val()) > 99)
                                            {
                                                $("#<?php echo $hat['id'] ?>count").val("99");
                                            }
                                        })
                                        function addToCart<?php echo $hat['id'] ?>()
                                        {
                                            console.log($("#<?php echo $hat['id'] ?>size").val());
                                            if($("#<?php echo $hat['id'] ?>size").val() != null && parseInt($("#<?php echo $hat['id'] ?>count").val()) > 0)
                                            {
                                                order = new Order(<?php echo $hat["id"] ?>, <?php echo json_encode($hat["name"]) ?>, <?php echo $hat["price"] ?>, $("#<?php echo $hat["id"] ?>size").val(), $("#<?php echo $hat["id"] ?>count").val());
                                                $("#cartcount").html(cart.length);
                                                $("#cartcountcontainer").removeAttr("hidden");
                                                $("#<?php echo $hat['id'] ?>error").attr("hidden", "hidden");
                                            }
                                            else
                                            {
                                                $("#<?php echo $hat['id'] ?>error").removeAttr("hidden");
                                            }
                                        }
                                    </script>
                                    <img class = "addtocartimg" src = "add_to_cart.png" onclick = "addToCart<?php echo $hat['id'] ?>()">
                                </p>
                            </div>
                        </div>
                    <?php
                }
            ?>
        </div>
    </body>
<?php require_once('footer.php') ?>