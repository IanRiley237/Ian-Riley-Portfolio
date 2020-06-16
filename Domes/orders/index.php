<?php require_once('../header.php') ?>
    <body>
        <div class = "orders">
            <hr>
            <span class = "orderdivtitle">Not Manufactured</span>
            <div class = "ordercardcontainer" id = "notmanufactured">
            </div><hr>
            <span class = "orderdivtitle">Orders Sent</span>
            <div class = "ordercardcontainer" id = "sent">
            </div><hr>
            <span class = "orderdivtitle">Orders Delivered</span>
            <div class = "ordercardcontainer" id = "delivered">
            </div>
        </div>
            <?php

                if (isset($_POST["orderToSend"]))
                {
                    $updateOrder = 'UPDATE `IMR_Orders`
                                    SET time_manufactured = NOW()
                                    WHERE id = ' . $_POST["orderToSend"];
                    if(!$conn->query($updateOrder))
                    {
                        echo $conn->error;
                    }
                }

                else if (isset($_POST["orderToDeliver"]))
                {
                    $updateOrder = 'UPDATE `IMR_Orders`
                                    SET time_delivered = NOW()
                                    WHERE id = ' . $_POST["orderToDeliver"];
                    if(!$conn->query($updateOrder))
                    {
                        echo $conn->error;
                    }
                }

                else if (isset($_POST["orderToRemove"]))
                {
                    $removeOrder = 'DELETE FROM `IMR_Orders`
                                    WHERE id = ' . $_POST["orderToRemove"];
                    if(!$conn->query($removeOrder))
                    {
                        echo $conn->error;
                    }
                }

                $selectOrders = ' SELECT `IMR_Orders`.*, `IMR_Hats`.`name` AS hat_name, `IMR_Hats`.`image` AS hat_image, (`IMR_Orders`.hat_count * `IMR_Hats`.price) AS order_cost
                                FROM `IMR_Orders` JOIN `IMR_Hats` ON `IMR_Orders`.hat_id = `IMR_Hats`.id';
                $ordersTable = $conn->query($selectOrders);

                while ($order = $ordersTable->fetch_assoc())
                {
                    ?><script>
                        var orderCard = '<div class = "ordercard">' +
                            '<form method = "post" class = "orderremove"><button type = "submit" name = "orderToRemove" class = "orderremovebtn" value = "<?php echo $order['id'] ?>">X</button></form>' +
                            '<div class = "orderrequest">' +
                                '<p class = "orderid">Order ID: <?php echo $order['id'] ?></p>' +
                                '<p><img  class = "orderimg" src="../hats/<?php echo $order['hat_image'] ?>"></p>' +
                                '<p class = "orderitem"><?php echo $order['hat_name'] . ' (' . $order['hat_size'] . ')' ?> &#x2A2F <?php echo $order['hat_count'] ?></p>' +
                                '<p class = "orderprice">Cost: $<?php echo number_format($order['order_cost'], 2, '.', ''); ?></p>' +
                            '</div>' +
                            '<hr><p class = "recipientinfo">Recipient Information</p>' +
                            '<p class = "custname">Customer Name: <?php echo $order['customer_name'] ?></p>' +
                            '<p class = "custaddress">Address: <?php echo $order['street'] . ', ' . $order['city'] . ' ' . $order['state'] ?></p>' +
                            '<p class = "custzip">Zipcode: <?php echo $order['zipcode'] ?></p>' +
                            '<hr><p class = "timeordered">Time Ordered: <?php echo $order['time_ordered'] ?></p>';
                            <?php
                            if ($order['time_manufactured'] != null)
                            {
                                ?>
                                orderCard += '<p class = "timemanufactured">Time Manufactured: <?php echo $order['time_manufactured'] ?></p>';
                                <?php
                            }
                            if ($order['time_delivered'] == null)
                            {
                                
                            ?>

                                orderCard += '<form method = "post">' +
                                    '<button class = "orderbutton" type = "submit" name = <?php if ($order['time_manufactured'] == null) echo json_encode("orderToSend"); else echo json_encode("orderToDeliver") ?> value = "<?php echo $order['id'] ?>">';
                                    <?php
                                        if ($order['time_manufactured'] == null)
                                            echo 'orderCard += "Send out order";';
                                        else if ($order['time_delivered'] == null)
                                            echo 'orderCard += "Deliver order";';
                                    ?>
                                    orderCard += '</button>' +
                                '</form>';
                            <?php
                            }
                            else
                            {
                                ?>
                                orderCard += '<p class = "timedelivered">Time Delivered: <?php echo $order['time_delivered'] ?></p>';
                                <?php
                            }
                            ?>
                        orderCard += '</div>';
                        <?php
                        if ($order['time_manufactured'] == null)
                            echo '$("#notmanufactured").html($("#notmanufactured").html()+orderCard);';
                        else if ($order['time_delivered'] == null)
                            echo '$("#sent").html($("#sent").html()+orderCard);';
                        else
                            echo '$("#delivered").html($("#delivered").html()+orderCard);';
                        ?>
                    </script><?php 
                }
            ?>
    </body>
<?php require_once('../footer.php') ?>