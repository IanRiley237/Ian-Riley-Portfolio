<?php
    require_once("dbconnect.php");
    require_once('functions.php');
    logBadUserOut();
    $isThread = (isset($_POST["Is_Thread_Post"]) && $_POST["Is_Thread_Post"] == 1);

    $postText = "";
    if ($_POST["Post_To_Reference_ID"] != -1)
    {
        // Differentiate between thread and post. 
        if ($isThread)
        {
            $selectPostInfo = ' SELECT Username, Post_Text
                                FROM Threads JOIN Accounts ON Account_Posted_ID = Account_ID
                                WHERE Thread_ID = ' . $_POST["Post_To_Reference_ID"];
        }
        else
        {
            $selectPostInfo = ' SELECT Username, Post_Text
                                FROM Posts JOIN Accounts ON Account_Posted_ID = Account_ID
                                WHERE Post_ID = ' . $_POST["Post_To_Reference_ID"];
        }
        

        $postInfoTable = $conn->query($selectPostInfo);

        $postInfo = $postInfoTable->fetch_assoc();

        $postText = $postInfo['Post_Text'];
        if (!$_POST["isEdit"])
        {
            $postText = "[quote=" . $postInfo['Username'] . "]" . $postInfo['Post_Text'] . "[/quote]\n\n"; // Wrap post in quote tag
        }
        $postText = stripcslashes($postText); // Removes the slashes from escape sequences
    }
?>

<!-- Content of popup -->
<div class = "containermain">
    <p>
        Write your post:
    </p>
    <p>
        <input type = "hidden" name = "isEdit" value = <?php echo $_POST["isEdit"]?>>
        <input type = "hidden" name = "isThreadPost" value = <?php echo $isThread?>>
        <input type = "hidden" name = "referencePostID" value = <?php echo $_POST["Post_To_Reference_ID"]?>>

        <textarea placeholder = "Write your post here!" rows = 12 wrap = "soft" name = "postText" maxlength = 40000></textarea>
        <script>
            $("textarea").val(<?php echo json_encode($postText)?>).focus();
        </script>
    </p>
    <p> 
        <button type = "submit"><?php if ($_POST["isEdit"]) echo "Edit"; else echo "Post";?></button>
    </p>
</div>

<div class = "containerextra">
    Please keep all content within relevant rules and guidelines.
</div>