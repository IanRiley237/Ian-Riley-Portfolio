<?php
    session_start();
    require_once("dbconnect.php");
    require_once('functions.php');
?>
<span class = "sectionname"> Subscribed Threads: </span>
<?php
    // $_POST["OrderBy"] can be "name", "nameRev", "oldest", or "newest"

    // Get subscribed threads
    $selectSubscribedToThreads = '  SELECT Threads.Thread_ID AS Thread_ID, Thread_Subject, TIMESTAMP(GREATEST
                                                                                            (
                                                                                                IFNULL(MAX(Threads.Last_Changed), 0),
                                                                                                IFNULL(MAX(Posts.Last_Changed), 0)
                                                                                            )) AS Last_Changed
                                    FROM Threads NATURAL JOIN Subscribed_To Left JOIN Posts ON Threads.Thread_ID = Posts.Thread_ID
                                    WHERE Subscribed_To.Account_ID = ' . $_SESSION["Account_ID"] . '
                                    GROUP BY Threads.Thread_ID
                                    ORDER BY ';
    switch($_POST["OrderBy"])
    {
        case "name":
            $selectSubscribedToThreads .= 'Thread_Subject ASC';
        break;
        case "oldest":
            $selectSubscribedToThreads .= 'Last_Changed ASC';
        break;
        case "newest":
            $selectSubscribedToThreads .= 'Last_Changed DESC';
        break;
    }
    $subscribedToThreadsTable = $conn->query($selectSubscribedToThreads);

    if($subscribedToThreadsTable !== false)
    {
        if ($subscribedToThreadsTable->num_rows == 0)
        {
            ?>
                <p>You aren't subscribed to any threads! You can find threads to subscribe to on the <a href="browse.php">Browse</a> page.</p>
            <?php
        }
        else
        {
            ?>
                <div class = "radiotoolbar">
                <label class = "descriptor"> Order by... </label>
                    <div class = "orderoptions">
                        <input type = "radio" id = "radioName" name = "orderBy" value = "name" <?php if($_POST["OrderBy"] == "name") echo "checked" ?>>
                        <label for = "radioName" class = "button"> <b>Name</b></label><input type = "radio" id = "radioOldest" name = "orderBy" value = "oldest" <?php if($_POST["OrderBy"] == "oldest") echo "checked" ?>><label for = "radioOldest" class = "button"> <b>Oldest</b></label><input type = "radio" id = "radioNewest" name = "orderBy" value = "newest" <?php if($_POST["OrderBy"] == "newest") echo "checked" ?>><label for = "radioNewest" class = "button"> <b>Newest</b></label>
                    </div>
                </div>
            <?php
            while ($thread = $subscribedToThreadsTable->fetch_assoc())
            {
                $link = "browse.php?threadid=" . $thread['Thread_ID'];
                ?>
                    <div class = "subbedthreads">
                        <div class = "subbedthreadname">
                            <a href = "<?php echo $link?>"><?php echo $thread['Thread_Subject']?></a>
                        </div>
                        <div class = "subbedthreadlastupdate">
                            <?php
                                echo date("m/d/Y @ h:i:s A", strtotime($thread["Last_Changed"]));
                            ?>
                        </div>
                        <div class = "unsubbutton" onclick = "subscribeToggle(<?php echo $_SESSION['Account_ID'] . ', ' . $thread['Thread_ID']?>)">
                            <b>Unsubscribe</b>
                        </div>
                    </div>
                <?php
            }
        }
    }
    else
    {
        echo "There was an error displaying subscription data.";
    }
?>
<script>
    $(".orderoptions input").change(function()
    {
        if($("#radioName").prop("checked"))
            subscribedThreads("name");
        if($("#radioNameRev").prop("checked"))
            subscribedThreads("nameRev");
        if($("#radioOldest").prop("checked"))
            subscribedThreads("oldest");
        if($("#radioNewest").prop("checked"))
            subscribedThreads("newest");
    });
</script>