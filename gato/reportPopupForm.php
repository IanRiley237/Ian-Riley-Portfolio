<?php
    require_once("dbconnect.php");
    require_once('functions.php');
    logBadUserOut();
    $isThreadPost = isset($_POST["Is_Thread_Post"]);

    // Differentiate between thread and post. 
    if ($isThreadPost)
    {
        $postInfoQuery = '  SELECT Thread_ID AS ID, Username, Account_ID, Thread_Subject
                            FROM Accounts JOIN Threads ON Account_ID = Account_Posted_ID
                            WHERE Thread_ID = ' . $_POST["Post_ID"];
    }
    else
    {
        $postInfoQuery = '  SELECT Post_ID AS ID, Username, Account_ID
                            FROM Accounts JOIN Posts ON Account_ID = Account_Posted_ID
                            WHERE Post_ID = ' . $_POST["Post_ID"];
    }
    $postInfoTable = $conn->query($postInfoQuery);
    $postInfo = $postInfoTable->fetch_assoc();
?>

<!-- Content of popup -->
<div class = "containermain">
    <p>
        Select your reason for reporting <?php echo $postInfo["Username"]?>'s <?php if ($isThreadPost) echo "thread:<br> <em>" . $postInfo["Thread_Subject"] . "</em>"; else echo "post";?>.
    </p>
    <p>
        <select name = "reportReason" id = "reason">
            <option>Hate Speech and/or Harrassment</option>
            <option>Inappropriate Sexual or Adult Content</option>
            <option>Irrelevant Content</option>
            <option>Spamming</option>
            <option>Other</option>
        </select>

        <script>    
            $("#reason").change(function ()
            {    
                var option = $(this).children("option:selected").val();
                if (option === "Other")
                {
                    $("#textReason").show();
                    $("#textReason").attr("required", true);
                }
                else
                {
                    $("#textReason").hide();
                    $("#textReason").removeAttr("required");
                }
            });  
        </script>

        <input type = "hidden" name = "isThreadPost" value = "<?php echo $isThreadPost?>">
        <input type = "hidden" name = "postID" value = <?php echo $postInfo["ID"]?>>
        <input type = "hidden" name = "reportedAccountID" value = <?php echo $postInfo["Account_ID"]?>>
    </p>
    <p id = "textReason" hidden>
        Please specify:<br>
        <input type = "text" name = "textReportReason" maxlength = 50>
    </p>
    <p>
        Please describe how this <?php if ($isThreadPost) echo "thread"; else echo "post";?> is against <span class = "gato">gato</span>'s guidlines:
    </p>
    <p>
        <textarea placeholder = "Please keep your descriptions civil, brief, but detailed!" rows = 12 wrap = "soft" name = "reportDescription" maxlength = 20000></textarea>
    </p>
    <p> 
        <button type = "submit">Send Report</button>
    </p>
</div>

<div class = "containerextra">
    Please only report posts and threads that you truly believe to be not in accordance with <span class = "gato">gato</span>'s guidlines.
</div>