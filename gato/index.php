<?php
    session_start();
    // If the user is not logged in, they are sent to browse.php
    if (!isset($_SESSION['Account_ID']))
        header("Location: browse.php");
?>
<!--
    The content on this page will be in two secions, Subscriptions and Bans.

    The current user's subscriptions are shown first. If they have no subscriptions, a message is shown telling them to go out and get subscribing!
    Then, the current user's bans are shown. This section is hidden completely if the current user has no bans.
-->


<!DOCTYPE html>
<html>
    <head>
        <title> gato - Home </title>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1">
        <link rel = "icon" type = "image/png" href = "assets/gato_icon.png">
        <link rel = "stylesheet" href = "all_pages.css">
        <link rel = "stylesheet" href = "home_page.css">
    </head>
    <body>
        <div class = "backwall"> 
            <?php
                require("header.php");
            ?>

            <div class = "foreground">
                <div class = "sectionorganizer">
                
                    <!-- The following is what will display as the Subscribed Threads section. -->
                    <div id = "subscribedthreads">
                    </div>

                    <!-- The following is what will display when a user has one or more bans tied to their account. -->
                    <?php
                        $selectBans = 'SELECT Username AS Banner_Username, Banner_Account_ID, Ban_Reason, Start_Timestamp, End_Timestamp
                                       FROM Bans JOIN Accounts ON Banner_Account_ID = Account_ID
                                       WHERE Banned_Account_ID = ' . $_SESSION['Account_ID'] . ' ORDER BY Ban_ID DESC';
                        $bansTable = $conn->query($selectBans);

                        if($bansTable !== false)
                        {
                            if ($bansTable->num_rows != 0)
                            {
                                ?>
                                <div class = "banssection">
                                    <span class = "sectionname"> Bans Against Account: </span>
                                    <?php
                                        while ($ban = $bansTable->fetch_assoc())
                                        {
                                            $start = date("m/d/Y @ h:i:s A", strtotime($ban["Start_Timestamp"]));
                                            $end = date("m/d/Y @ h:i:s A", strtotime($ban["End_Timestamp"]));
                                            ?>
                                                <div id = "bansviewer">
                                                    <span class = bandate> <?php echo $start ?> - <?php echo $end ?></span><br>
                                                    Filed by... <span class = banner> <?php echo '<a onclick = "userInfoPopup(' . $ban["Banner_Account_ID"] . ')">' . $ban["Banner_Username"] . '</a> '; ?> </span> (<?php // Display the banner's rank
                                                                                                                                        echo getUserRankByID($ban['Banner_Account_ID'])
                                                                                                                                    ?>) <br>
                                                    <?php echo $ban["Ban_Reason"] ?>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <?php
                            }
                        }
                        else
                        {
                            echo "There was an error displaying ban data.";
                        }
                    ?>

                    <div class = "postHistory" id = "postHistory">
                        <!-- HERE -->
                    </div>
                </div>

                <?php
                    require("sidebar.php");
                ?>
            </div>

            <?php
                require("footer.php");
            ?>
            <script>
                subscribedThreads("name");
                postHistory(1);
            </script>
        </div>
    </body>
</html>