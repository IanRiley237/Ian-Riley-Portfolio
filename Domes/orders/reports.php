<?php require_once('../header.php') ?>
    <body class = "reportbody">
        <span class = "reportname">Sizes of hats ordered</span>
        <div class = "orderreport">
            <?php
                $XScount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE hat_size = 'XS'")->fetch_array()[0];
                $Scount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE hat_size = 'S'")->fetch_array()[0];
                $Mcount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE hat_size = 'M'")->fetch_array()[0];
                $Lcount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE hat_size = 'L'")->fetch_array()[0];
                $XLcount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE hat_size = 'XL'")->fetch_array()[0];
            ?>
            <p>XS: <?php if($XScount) echo $XScount; else echo 0; ?></p>
            <p>S: <?php if($Scount) echo $Scount; else echo 0; ?></p>
            <p>M: <?php if($Mcount) echo $Mcount; else echo 0; ?></p>
            <p>L: <?php if($Lcount) echo $Lcount; else echo 0; ?></p>
            <p>XL: <?php if($XLcount) echo $XLcount; else echo 0; ?></p>
        </div>
        <span class = "reportname">Hats ordered per day of week</span>
        <div class = "orderreport">
            <?php
                $monCount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE WEEKDAY(time_ordered) = 0")->fetch_array()[0];
                $tueCount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE WEEKDAY(time_ordered) = 1")->fetch_array()[0];
                $wedCount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE WEEKDAY(time_ordered) = 2")->fetch_array()[0];
                $thuCount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE WEEKDAY(time_ordered) = 3")->fetch_array()[0];
                $friCount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE WEEKDAY(time_ordered) = 4")->fetch_array()[0];
                $satCount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE WEEKDAY(time_ordered) = 5")->fetch_array()[0];
                $sunCount = $conn->query("SELECT SUM(hat_count)
                                        FROM `IMR_Orders`
                                        WHERE WEEKDAY(time_ordered) = 6")->fetch_array()[0];
            ?>
            <p>Monday: <?php if($monCount) echo $monCount; else echo 0; ?></p>
            <p>Tuesday: <?php if($tueCount) echo $tueCount; else echo 0; ?></p>
            <p>Wednesday: <?php if($wedCount) echo $wedCount; else echo 0; ?></p>
            <p>Thursday: <?php if($thuCount) echo $thuCount; else echo 0; ?></p>
            <p>Friday: <?php if($friCount) echo $friCount; else echo 0; ?></p>
            <p>Saturday: <?php if($satCount) echo $satCount; else echo 0; ?></p>
            <p>Sunday: <?php if($sunCount) echo $sunCount; else echo 0; ?></p>
        </div>
        <span class = "reportname">Average price of order</span>
        <div class = "orderreport">
            <?php
                $avgPrice = $conn->query("SELECT AVG(hat_count * price)
                                        FROM `IMR_Orders` JOIN `IMR_Hats` ON `IMR_Orders`.hat_id = `IMR_Hats`.id")->fetch_array()[0];
            ?>
            <p>$<?php if($avgPrice) echo number_format($avgPrice, 2, '.', ''); else echo "0.00"; ?></p>
        </div>
    </body>
<?php require_once('../footer.php') ?>
