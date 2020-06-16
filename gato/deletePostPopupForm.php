<!-- This is the popup form that appears when the user clicks a delete button tied to a post or thread. -->
<?php
    // Verify a connection to the database
    require_once("dbconnect.php");
    require_once('functions.php');
    logBadUserOut();
    $isThread = isset($_POST["Is_Thread_Post"]) && $_POST["Is_Thread_Post"] == 1;

    // Differentiate between thread and post. 
    if ($isThread)
    {
        $postInfoQuery = '  SELECT Thread_ID AS ID, Thread_Subject
                            FROM Accounts JOIN Threads ON Account_ID = Account_Posted_ID
                            WHERE Thread_ID = ' . $_POST["Post_ID"];
    }
    else
    {
        $postInfoQuery = '  SELECT Post_ID AS ID
                            FROM Accounts JOIN Posts ON Account_ID = Account_Posted_ID
                            WHERE Post_ID = ' . $_POST["Post_ID"];
    }
    $postInfoTable = $conn->query($postInfoQuery);
    $postInfo = $postInfoTable->fetch_assoc();
?>

<div class = "containermain">
    <p>
        Are you sure you want to delete your <?php if ($isThread) echo "thread:<br> <em>" . $postInfo["Thread_Subject"] . "</em>"; else echo "post";?>?
    </p>
    <p>
        <input type = "hidden" name = "postToDelete" value = <?php echo $_POST["Post_ID"]?>>
        <input type = "hidden" name = "isThreadPost" value = <?php echo $isThread?>>
        <button type = "submit">Yes</button>
    </p>
</div>

<div class = "containerextra">
    Reports filed against this <?php if ($isThread) echo "thread"; else echo "post";?> will not be deleted.
</div>