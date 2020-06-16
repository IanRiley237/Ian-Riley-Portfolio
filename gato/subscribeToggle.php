<?php
    // This script should only be loaded when the user is trying to subscribe or unsubscribe from a thread.
    require("dbconnect.php");
    require_once('functions.php');
    logBadUserOut();
    if (isset($_POST["Account_ID"]) && isset($_POST["Thread_ID"]))
    {
        $isSubscribedTable = $conn->query(' SELECT Subscription_ID
                                            FROM Subscribed_To
                                            WHERE Account_ID = ' . $_POST["Account_ID"] . ' AND Thread_ID = ' . $_POST["Thread_ID"]);

        if ($isSubscribedTable->num_rows == 0)
        {
            // Subscribe account to thread
            $addSubscriptionQuery = '   INSERT INTO Subscribed_To (Account_ID, Thread_ID) 
                                        VALUES (' . $_POST["Account_ID"] . ', ' . $_POST["Thread_ID"] . ')';

            $conn->query($addSubscriptionQuery);
        }
        else
        {
            // Unsubscribe account to thread
            $removeSubscriptionQuery = 'DELETE FROM Subscribed_To
                                        WHERE Account_ID = ' . $_POST["Account_ID"] . ' AND Thread_ID = ' . $_POST["Thread_ID"];

            $conn->query($removeSubscriptionQuery);
        }
    }
?>
<script>
location.reload(); 
</script>