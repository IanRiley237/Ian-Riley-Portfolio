<link rel = "stylesheet" href = "bbcode.css">
<?php
    session_start();
    require_once("dbconnect.php");
    
    $selectPosts = 'SELECT Thread_ID AS ID, Threads.Post_Text, Threads.Was_Edited, Threads.Last_Changed, 1 AS Is_Thread, Categories.Category_ID, Category_Subject, Threads.Thread_ID, Thread_Subject
                    FROM Threads JOIN Categories ON Threads.Category_ID = Categories.Category_ID
                    WHERE Threads.Account_Posted_ID = ' . $_SESSION['Account_ID'] . '
                    UNION
                    (SELECT Post_ID AS ID, Posts.Post_Text, Posts.Was_Edited, Posts.Last_Changed, 0 AS Is_Thread, Categories.Category_ID, Category_Subject, Threads.Thread_ID, Thread_Subject
                    FROM Posts JOIN Threads ON Posts.Thread_ID = Threads.Thread_ID JOIN Categories ON Threads.Category_ID = Categories.Category_ID
                    WHERE Posts.Account_Posted_ID = ' . $_SESSION['Account_ID'] . ')
                    ORDER BY Last_Changed DESC';
    $postsTable = $conn->query($selectPosts);

    $page = 1;
    $postsPerPage = 5;
    if(isset($_POST["page"]) && !empty($_POST["page"]))
        $page = $_POST["page"];

    $lastPage = ceil($postsTable->num_rows / $postsPerPage);
    if ($page > $lastPage)
        $page = $lastPage;

    $offset = ($page - 1) * $postsPerPage;

    $selectPosts .= ' LIMIT ' . $postsPerPage . ' OFFSET ' . $offset;
    $postsTable = $conn->query($selectPosts);

    // Page navigation buttons
    
    if($postsTable)
    {
        echo '<span class = "sectionname"> Post History: </span>';
        echo '<div class = "pageutilities">';
        if($page != 1)  // If user is not on first page
        { ?>
            <div class = "pageBack">
            <div class = "pagebutton" onclick = "postHistory(1)">First</div>
            <div class = "pagebutton" onclick = 'postHistory(<?php echo $page - 1 ?>)'>Previous</div>
            </div>
        <?php }
        if($lastPage != 1) // If user is not on the only page of the thread
        { ?>
            <div class = "pageJump">
            <div style = "display: inline;"><input size = "3" class = "pageinput" type = "text" id = "pageNum" name = "pageNum" value = <?php echo $page ?>></div>
            <div class = "pagebutton" id = "gotoPageNum" onclick = "">Go</div>
            </div>
        <?php }
        if($page < $lastPage) // If user is not on last page
        { ?>
            <div class = "pageForward">
            <div class = "pagebutton" onclick = 'postHistory(<?php echo $page + 1 ?>)'>Next</div>
            <div class = "pagebutton" onclick = 'postHistory(<?php echo $lastPage ?>)'>Last</div>
            </div>
        <?php } ?>

            <script>
                // Force non-negative integers in textbox
                $("#pageNum").on('change keydown paste input', function(){
                    $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                    $("#gotoPageNum").attr("onclick", "postHistory($('#pageNum').val())");
                })
            </script>
        <?php
        echo '</div>';

        echo '<div>';
        if($postsTable)
        {
            if ($postsTable->num_rows == 0)
            {
                echo "You have made no posts.";
            }
            
            while ($post = $postsTable->fetch_assoc())
            {
            ?>
                <!-- Post starts here -->
                <div class = "postBar" id = "postBar">
                    Posted in...
                    <?php
                        echo '<a href = "/gato/browse.php?categoryid=' . $post["Category_ID"] . '">' . $post["Category_Subject"] . '</a> > ';
                        echo '<a href = "/gato/browse.php?threadid=' . $post["Thread_ID"] . '">' . $post["Thread_Subject"] . '</a>';
                    ?>                    
                </div>
                <div class = "postText" id = "postText<?php echo $post["ID"] . $post['Is_Thread'] ?>">
                    <div class = "posttimestamp"><?php if($post["Was_Edited"] == 1) echo "Edited"; else echo "Posted";?> on... <?php echo date("m/d/Y @ h:i:s A", strtotime($post["Last_Changed"])) ?></div>
                </div>
                    <script>$("#postText<?php echo $post["ID"] . $post['Is_Thread'] ?>").html($("#postText<?php echo $post["ID"] . $post['Is_Thread'] ?>").html() + XBBCODE.process({text: <?php echo json_encode($post["Post_Text"]) ?> }).html);</script>
                <div class = "postfunctions">
                    <div class = "postingUtilities">
                        <div class = "posteditbutton" onclick = "postPopup(<?php echo $post['Thread_ID'] ?>, <?php echo $post['ID'] ?>, 1, <?php echo $post['Is_Thread'] ?>)">Edit</div>
                        <div class = "postdeletebutton" onclick = "deletePostPopup(<?php echo $post['ID'] ?>, <?php echo $post['Is_Thread'] ?>)">Delete</div>
                    </div>
                </div>
                <div class = "postspace"></div>
                <!-- Post ends here -->
            <?php
            }
        }
        else
        {
            // Query error
            echo $conn -> error;
        }
        echo '</div>';

        // Page navigation buttons
        echo '<div class = "pageutilities">';
        if($page != 1)  // If user is not on first page
        { ?>
            <div class = "pageBack">
            <div class = "pagebutton" onclick = "postHistory(1)">First</div>
            <div class = "pagebutton" onclick = 'postHistory(<?php echo $page - 1 ?>)'>Previous</div>
            </div>
        <?php }
        if($lastPage != 1) // If user is not on the only page of the thread
        { ?>
            <div class = "pageJump">
            <div style = "display: inline;"><input size = "3" class = "pageinput" type = "text" id = "pageNum" name = "pageNum" value = <?php echo $page ?>></div>
            <div class = "pagebutton" id = "gotoPageNum" onclick = "">Go</div>
            </div>
        <?php }
        if($page < $lastPage) // If user is not on last page
        { ?>
            <div class = "pageForward">
            <div class = "pagebutton" onclick = 'postHistory(<?php echo $page + 1 ?>)'>Next</div>
            <div class = "pagebutton" onclick = 'postHistory(<?php echo $lastPage ?>)'>Last</div>
            </div>
        <?php } ?>

            <script>
                // Force non-negative integers in textbox
                $("#pageNum").on('change keydown paste input', function(){
                    $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                    $("#gotoPageNum").attr("onclick", "postHistory($('#pageNum').val())");
                })
            </script>
        <?php
        echo '</div>';
    }
?>