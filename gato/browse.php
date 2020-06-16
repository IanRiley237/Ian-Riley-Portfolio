<?php session_start();?>
<!DOCTYPE html>

<html>
    <head>
        <title> gato - Browse </title>
        <meta charset = "UTF-8">
		<meta name = "viewport" content = "width = device-width, initial-scale = 1">
        <link rel = "icon" type = "image/png" href = "assets/gato_icon.png">
        <link rel = "stylesheet" href = "all_pages.css">
        <link rel = "stylesheet" href = "browse_page.css">
        <link rel = "stylesheet" href = "bbcode.css">
    </head>
    <body>
        <div class = "backwall"> 
            <?php
                require("header.php");
            ?>

            <div class = "foreground">
                <div class = "sectionorganizer">
                
                    <?php
                        // Number of items that appear on one page. The page value is universal accross all views (categories/threads/posts)
                            $postsPerPage = 20;
                            $threadsPerPage = 20;
                            $categoriesPerPage = 50;
                            $page = 1;
                            if(isset($_GET["page"]) && !empty($_POST["page"]))
                                $page = $_GET["page"];
//------------------------------------- Display categories to the user
                        if(!isset($_GET["categoryid"]) && !isset($_GET["threadid"]))
                        {
                            // Ohh boy finding the correct last changed value was a doosey. Apparently, GREATEST always returns NULL if even just one of it's parameters are NULL.
                            $selectCategories = '   SELECT Categories.Category_ID, Categories.Is_Flagged, Categories.Category_Subject,
                                                                    TIMESTAMP(GREATEST
                                                                    (
                                                                        IFNULL(MAX(Threads.Last_Changed), 0),
                                                                        IFNULL(MAX(Posts.Last_Changed), 0)
                                                                    )) AS Last_Changed
                                                                FROM Categories
                                                                LEFT JOIN Threads ON Categories.Category_ID = Threads.Category_ID
                                                                LEFT JOIN Posts ON Threads.Thread_ID = Posts.Thread_ID
                                                                GROUP BY Category_ID
                                                                ORDER BY Is_Flagged DESC, Category_Subject ASC';
                            $categoriesTable = $conn->query($selectCategories);

                            $lastPage = ceil($categoriesTable->num_rows / $categoriesPerPage);
                            if ($page > $lastPage)
                                $page = $lastPage;

                            $offset = ($page - 1) * $categoriesPerPage;

                            $selectCategoriesForPage = $selectCategories . ' LIMIT ' . $categoriesPerPage . ' OFFSET ' . $offset;
                            $categoriesTable = $conn->query($selectCategoriesForPage);

                        ?>
                            <span class = "sectionname"> <a href=''>Categories</a> > ... </span>
                            <?php
                                if(isset($_SESSION["Account_ID"]))
                                {
                                    if ($_SESSION["Is_Owner"] == 1)
                                    {
                                        // Display the create category button
                                        ?>
                                            <div class = "createbutton" onclick = "document.getElementById('createCategoryPopupBackground').style.display='block';">
                                                <b>Create Category</b>
                                            </div>
                                        <?php
                                    }
                                }
                            ?>
                            
                            <div id = "categories" class = "categories">
                                <?php
                                    if($categoriesTable !== false)
                                    {
                                        // Page navigation buttons
                                        echo '<div class = "pageutilities">';
                                        if($page != 1)  // If user is not on first page
                                        { ?>
                                            <div class = "pageBack">
                                            <div class = "pagebutton" onclick = "window.location.href = 'browse.php'">First</div>
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?page=<?php echo ($page - 1) ?>"'>Previous</div>
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
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?page=<?php echo ($page + 1) ?>"'>Next</div>
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?page=<?php echo $lastPage ?>"'>Last</div>
                                            </div>
                                        <?php } ?>

                                            <script>
                                                // Force non-negative integers in textbox
                                                $("#pageNum").on('change keydown paste input', function(){
                                                    $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                                                    $("#gotoPageNum").attr("onclick", "window.location.href = 'browse.php?page=" + $("#pageNum").val() + "'");
                                                })
                                            </script>
                                        <?php
                                        echo '</div>';

                                        echo '<div>';
                                        while ($category = $categoriesTable->fetch_assoc())
                                        {
                                            ?>
                                                <div class = "category">
                                                    <div class = "imageholder" id = "imageholder<?php echo $category['Category_ID'] ?>">
                                                        <img id = "flagimg" src = "<?php if($category["Is_Flagged"] == 1) echo "assets/flag.png"; 
                                                                        else echo "assets/no_flag.png";?>" class = "flag" onclick = "categoryCheckRank(<?php echo $category['Category_ID'] . ', ' . $category['Is_Flagged'] . ', 0' . $_SESSION['Is_Owner'] ?>)">
                                                    </div>
                                                    
                                                    <div class = "categoryInfo">
                                                        <a href = "browse.php?categoryid=<?php echo $category["Category_ID"] ?>" ><?php echo $category["Category_Subject"] ?></a>
                                                    </div>
                                                    <div class = "categoryLastUpdate">
                                                        <!--01/23/4567 @ 12:34:56 AM-->
                                                        <?php
                                                            if (isset($category["Last_Changed"]))
                                                            {
                                                                echo date("m/d/Y @ h:i:s A", strtotime($category["Last_Changed"]));
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                        echo '</div>';

                                        // Page navigation buttons
                                        echo '<div class = "pageutilities">';
                                        if($page != 1)  // If user is not on first page
                                        { ?>
                                            <div class = "pageBack">
                                            <div class = "pagebutton" onclick = "window.location.href = 'browse.php'">First</div>
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?page=<?php echo ($page - 1) ?>"'>Previous</div>
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
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?page=<?php echo ($page + 1) ?>"'>Next</div>
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?page=<?php echo $lastPage ?>"'>Last</div>
                                            </div>
                                        <?php } ?>

                                            <script>
                                                // Force non-negative integers in textbox
                                                $("#pageNum").on('change keydown paste input', function(){
                                                    $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                                                    $("#gotoPageNum").attr("onclick", "window.location.href = 'browse.php?page=" + $("#pageNum").val() + "'");
                                                })
                                            </script>
                                        <?php
                                        echo '</div>';
                                    }
                                    else
                                    {
                                        echo "There was an error displaying this data.";
                                    }
                                ?>
                            </div>

                        <?php
                        }
//------------------------------------- Display threads to the user
                        else if(isset($_GET["categoryid"]) && !isset($_GET["threadid"]))
                        {
                            // Get category subject to display at top of page
                            $selectCategoryName = 'SELECT Category_Subject
                                                 FROM Categories
                                                 WHERE Category_ID = ' . $_GET["categoryid"];
                            $categoryNameTable = $conn->query($selectCategoryName);

                            // Get thread information
                            $selectThreads = '  SELECT Threads.Thread_ID AS Thread_ID, Is_Flagged, Is_Locked, TIMESTAMP(GREATEST
                                                                    (
                                                                        IFNULL(MAX(Threads.Last_Changed), 0),
                                                                        IFNULL(MAX(Posts.Last_Changed), 0)
                                                                    )) AS Last_Changed, Thread_Subject
                                                FROM Threads LEFT JOIN Posts ON Threads.Thread_ID = Posts.Thread_ID
                                                WHERE Category_ID = ' . $_GET["categoryid"] . '
                                                GROUP BY Threads.Thread_ID
                                                ORDER BY Is_Flagged DESC, Last_Changed DESC';
                            $threadsTable = $conn->query($selectThreads);

                            $lastPage = ceil($threadsTable->num_rows / $threadsPerPage);
                            if ($page > $lastPage)
                                $page = $lastPage;

                            $offset = ($page - 1) * $threadsPerPage;
                                

                            $selectThreadsForPage = $selectThreads . ' LIMIT ' . $threadsPerPage . ' OFFSET ' . $offset;
                            $threadsTable = $conn->query($selectThreadsForPage);
                            
                            // Let user click back to Categories page
                            echo "<span class = 'sectionname'><span class = 'hierarchy'><a href='browse.php'>Categories</a> > <a href=''>" . $categoryNameTable->fetch_assoc()["Category_Subject"] . "</a> > ...</span></span>";
                            ?>
                            <?php
                                if(isset($_SESSION["Account_ID"]))
                                {
                                    // Display the create thread button
                                    ?>
                                        <div class = "createbutton" onclick = "document.getElementById('createThreadPopupBackground').style.display='block';">
                                            <b>Create Thread</b>
                                        </div>
                                    <?php
                                }
                            ?>
                            <div id = "threads" class = "threads">
                                <?php
                                    // Display each of the threads as rows.
                                    if($threadsTable !== false)
                                    {
                                        // Page navigation buttons
                                        echo '<div class = "pageutilities">';
                                        if($page != 1)  // If user is not on first page
                                        { ?>
                                            <div class = "pageBack">
                                            <div class = "pagebutton" onclick = "window.location.href = 'browse.php?categoryid=<?php echo $_GET['categoryid'] ?>'">First</div>
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?categoryid=" + <?php echo json_encode($_GET["categoryid"] . "&page=" . ($page - 1)) ?>'>Previous</div>
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
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?categoryid=" + <?php echo json_encode($_GET["categoryid"] . "&page=" . ($page + 1)) ?>'>Next</div>
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?categoryid=" + <?php echo json_encode($_GET["categoryid"] . "&page=" . $lastPage) ?>'>Last</div>
                                            </div>
                                        <?php } ?>

                                            <script>
                                                // Force non-negative integers in textbox
                                                $("#pageNum").on('change keydown paste input', function(){
                                                    $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                                                    $("#gotoPageNum").attr("onclick", "window.location.href = 'browse.php?categoryid=" + <?php echo json_encode($_GET["categoryid"] . '&page=') ?> + $("#pageNum").val() + "'");
                                                })
                                            </script>
                                        <?php
                                        echo '</div>';

                                        echo '<div>';
                                        if ($threadsTable->num_rows == 0)
                                        {
                                            echo "There is nothing here! :(";
                                        }
                                        while ($thread = $threadsTable->fetch_assoc())
                                        {
                                            ?>
                                                <div class = "category">
                                                    <div class = "imageholder" id = "imageholder<?php echo $thread['Thread_ID'] ?>">
                                                        <img id = "lockimg" src = "<?php if($thread["Is_Locked"] == 1) echo "assets/lock.png"; 
                                                                        else echo "assets/no_lock.png";?>" class = "lock" onclick = "threadLockCheckRank(<?php echo $thread['Thread_ID'] . ', ' . $thread['Is_Locked'] . ', 0' . $_SESSION['Is_Owner'] . ', 0' . $_SESSION['Is_Admin'] . ', 0' . $_SESSION['Is_Moderator'] ?>)">
                                                                        
                                                        <img id = "flagimg" src = "<?php if($thread["Is_Flagged"] == 1) echo "assets/flag.png"; 
                                                                        else echo "assets/no_flag.png";?>" class = "flag" onclick = "threadFlagCheckRank(<?php echo $thread['Thread_ID'] . ', ' . $thread['Is_Flagged'] . ', 0' . $_SESSION['Is_Owner'] . ', 0' . $_SESSION['Is_Admin'] ?>)">
                                                    </div>
                                                    <div <?php if($thread["Is_Locked"] == 0) { echo 'class = "categoryInfo"'; } else { echo 'class = "lockedCategoryInfo"'; } ?> >
                                                        <a href = "browse.php?threadid=<?php echo $thread["Thread_ID"] ?>" ><?php echo $thread["Thread_Subject"] ?></a>
                                                    </div>
                                                    <div <?php if($thread["Is_Locked"] == 0) { echo 'class = "categoryLastUpdate"'; } else { echo 'class = "lockedCategoryLastUpdate"'; } ?>>
                                                        <!--01/23/4567 @ 12:34:56 AM-->
                                                        <?php
                                                            if (isset($thread["Last_Changed"]))
                                                            {
                                                                echo date("m/d/Y @ h:i:s A", strtotime($thread["Last_Changed"]));
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                        echo '</div>';
                                        // Page navigation buttons
                                        echo '<div class = "pageutilities">';
                                        if($page != 1)  // If user is not on first page
                                        { ?>
                                            <div class = "pageBack">
                                            <div class = "pagebutton" onclick = "window.location.href = 'browse.php?categoryid=<?php echo $_GET['categoryid'] ?>'">First</div>
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?categoryid=" + <?php echo json_encode($_GET["categoryid"] . "&page=" . ($page - 1)) ?>'>Previous</div>
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
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?categoryid=" + <?php echo json_encode($_GET["categoryid"] . "&page=" . ($page + 1)) ?>'>Next</div>
                                            <div class = "pagebutton" onclick = 'window.location.href = "browse.php?categoryid=" + <?php echo json_encode($_GET["categoryid"] . "&page=" . $lastPage) ?>'>Last</div>
                                            </div>
                                        <?php } ?>

                                            <script>
                                                // Force non-negative integers in textbox
                                                $("#pageNum").on('change keydown paste input', function(){
                                                    $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                                                    $("#gotoPageNum").attr("onclick", "window.location.href = 'browse.php?categoryid=" + <?php echo json_encode($_GET["categoryid"] . '&page=') ?> + $("#pageNum").val() + "'");
                                                })
                                            </script>
                                        <?php
                                        echo '</div>';
                                    }
                                    else
                                    {
                                        echo "There was an error displaying this data.";
                                    }
                                ?>
                            </div>


                        <?php
                        }
//------------------------------------- Display posts to the user
                        else if(isset($_GET["threadid"]))
                        {
                            // Get thread subject to display at top of page and the category subject and id for the hierarchy nav
                            $selectThreadName = 'SELECT Thread_Subject, Threads.Category_ID, Category_Subject
                                                 FROM Threads LEFT JOIN Categories ON Threads.Category_ID = Categories.Category_ID
                                                 WHERE Thread_ID = ' . $_GET["threadid"];
                            $threadNameTable = $conn->query($selectThreadName);
                            $threadInfo = $threadNameTable->fetch_assoc();

                            if ($threadInfo) // If finding the thread was successful
                            {
                                echo "<span class = 'sectionname'><span class = 'hierarchy'><a href='browse.php'>Categories</a> > <a href='browse.php?categoryid=" . $threadInfo["Category_ID"] . "'>" . $threadInfo["Category_Subject"] . "</a> > <a href=''>" . $threadInfo["Thread_Subject"] . "</a></span></span>";

                                // Get and display posts
                                $selectPosts = 'SELECT Thread_ID AS ID, Accounts.Account_ID, Accounts.Username, Threads.Post_Text, Threads.Was_Edited, Threads.Last_Changed, 1 AS Is_Thread
                                                FROM Accounts JOIN Threads
                                                WHERE Accounts.Account_ID = (SELECT Threads.Account_Posted_ID
                                                                            FROM Threads
                                                                            WHERE Thread_ID = '. $_GET['threadid'] .')
                                                AND Thread_ID = '. $_GET['threadid'] .'
                                                UNION
                                                (SELECT Post_ID AS ID, Accounts.Account_ID, Accounts.Username, Posts.Post_Text, Posts.Was_Edited, Posts.Last_Changed, 0 AS Is_Thread
                                                FROM Accounts JOIN Posts ON Accounts.Account_ID = Posts.Account_Posted_ID
                                                WHERE Posts.Thread_ID = '. $_GET['threadid'] .')
                                                ORDER BY Is_Thread DESC, ID ASC';
                                $postTable = $conn->query($selectPosts);
                                // Figure out out many pages there are
                                $lastPage = ceil($postTable->num_rows / $postsPerPage);
                                if ($page > $lastPage)
                                    $page = $lastPage;

                                $offset = ($page - 1) * $postsPerPage;
                                    

                                $selectPostsForPage = $selectPosts . ' LIMIT ' . $postsPerPage . ' OFFSET ' . $offset;
                                $postTable = $conn->query($selectPostsForPage);

                                if($postTable) // If finding the post was successful
                                {
                                    if(isset($_SESSION["Account_ID"]))
                                    {
                                        $selectSubs = 'SELECT Thread_ID
                                                    FROM Subscribed_To
                                                    WHERE Account_ID = ' . $_SESSION['Account_ID'] . ' AND Thread_ID = ' . $_GET['threadid'];
                                        $subTable = $conn->query($selectSubs);

                                        if ($subTable->num_rows != 0)
                                        {
                                            // Display the unsubscribe button
                                            echo '<div class = "subbutton" id = "sub" onclick = "subscribeToggle(' . $_SESSION['Account_ID'] . ', ' . $_GET['threadid'] . ')"> <b>Unsubscribe</b> </div>';
                                        }
                                        else
                                        {
                                            // Display the subscribe button
                                            echo '<div class = "subbutton" id = "unsub" onclick = "subscribeToggle(' . $_SESSION['Account_ID'] . ', ' . $_GET['threadid'] . ')"> <b>Subscribe</b> </div>';
                                        }
                                    }

                                    // Page navigation buttons
                                    echo '<div class = "pageutilities">';
                                    if($page != 1)  // If user is not on first page
                                    { ?>
                                        <div class = "pageBack">
                                        <div class = "pagebutton" onclick = "window.location.href = 'browse.php?threadid=<?php echo $_GET['threadid'] ?>'">First</div>
                                        <div class = "pagebutton" onclick = 'window.location.href = "browse.php?threadid=" + <?php echo json_encode($_GET["threadid"] . "&page=" . ($page - 1)) ?>'>Previous</div>
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
                                        <div class = "pagebutton" onclick = 'window.location.href = "browse.php?threadid=" + <?php echo json_encode($_GET["threadid"] . "&page=" . ($page + 1)) ?>'>Next</div>
                                        <div class = "pagebutton" onclick = 'window.location.href = "browse.php?threadid=" + <?php echo json_encode($_GET["threadid"] . "&page=" . $lastPage) ?>'>Last</div>
                                        </div>
                                    <?php } ?>

                                        <script>
                                            // Force non-negative integers in textbox
                                            $("#pageNum").on('change keydown paste input', function(){
                                                $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                                                $("#gotoPageNum").attr("onclick", "window.location.href = 'browse.php?threadid=" + <?php echo json_encode($_GET["threadid"] . '&page=') ?> + $("#pageNum").val() + "'");
                                            })
                                        </script>
                                    <?php
                                    echo '</div>';

                                    $isThreadPost = true;
                                    while ($post = $postTable->fetch_assoc())
                                    {
                                        ?>
                                            <div class = "postBar" id = "postBar">
                                                <?php
                                                    echo '<a onclick = "userInfoPopup(' . $post["Account_ID"] . ')">' . $post["Username"] . '</a> ';

                                                    // Output differently depending on rank
                                                    echo '<span id = "' . $post["Account_ID"] . 'displayRank" hidden>';
                                                        $posterRank = getUserRankByID($post["Account_ID"]);
                                                        echo "(<span id = '" . $post['Account_ID'] . "rank'>" . $posterRank . "</span>) ";
                                                    echo '</span>';
                                                    if ($posterRank !== "Unranked")
                                                    {
                                                        ?>
                                                            <script>$("span[id='<?php echo $post['Account_ID']?>displayRank']").show();</script>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                            <script>$("span[id='<?php echo $post['Account_ID']?>displayRank']").hide();</script>
                                                        <?php
                                                    }
                                                    echo 'posted:';
                                                ?>
                                            </div>
                                            <div class = "postText">
                                                <?php
                                                    // Create a small timestamp on the post's upper-right corner and indicate if it was edited, if that applies
                                                    if ($post["Was_Edited"] == 1)
                                                    {
                                                        // The post was edited
                                                        echo '<div class = "posttimestamp">Edited on... ' . date("m/d/Y @ h:i:s A", strtotime($post["Last_Changed"])) . '</div>';
                                                    }
                                                    else
                                                    {
                                                        // The post wasn't edited
                                                        echo '<div class = "posttimestamp">Posted on... ' . date("m/d/Y @ h:i:s A", strtotime($post["Last_Changed"])) . '</div>';
                                                    }
                                                    // Process the BBCode into html
                                                    echo '<script>document.write(XBBCODE.process({text:' . json_encode($post["Post_Text"]) . '}).html);</script>';
                                                ?>
                                            </div>
                                            
                                            <?php if(isset($_SESSION["Account_ID"]))
                                            {
                                                // The bar where all the functional buttons are located
                                                $checkLock = 'SELECT Is_Locked
                                                                FROM Threads
                                                                WHERE Thread_ID = ' . $_GET['threadid'];
                                                $lockTable = $conn->query($checkLock);
                                                $isLocked = $lockTable->fetch_assoc();

                                                ?><div class = "postfunctions"><?php
                                                    if(!$isLocked["Is_Locked"]) // If the thread isn't locked
                                                    {
                                                        ?>
                                                        <div class = "postreportbutton" onclick="reportPopup(<?php echo $post['ID'] . ', ' . $isThreadPost?>)">Report</div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class = "postingUtilities"><?php
                                                        if(!$isLocked["Is_Locked"]) // If the thread isn't locked
                                                        {
                                                            ?>
                                                            <div class = "postquotebutton" onclick = "postPopup(<?php echo $_GET['threadid'] . ', ' . $post['ID']?>, 0, <?php echo $isThreadPost ?>)">Quote</div>
                                                            <div class = "postbutton" onclick = "postPopup(<?php echo $_GET['threadid']?>)">Post</div>
                                                            <?php
                                                        }
                                                        if ($_SESSION["Username"] === $post["Username"]) // If the current user made the post
                                                        {
                                                            if(!$isLocked["Is_Locked"])
                                                            {
                                                                echo '<div class = "posteditbutton" onclick = "postPopup(' . $_GET["threadid"] . ', ' . $post['ID'] .  ', 1, ' . $isThreadPost . ')">Edit</div>';
                                                            }
                                                            echo '<div class = "postdeletebutton" onclick = "deletePostPopup(' . $post['ID'] . ', ' . $isThreadPost . ')">Delete</div>';
                                                        }?>
                                                    </div>
                                                </div>
                                            <?php 
                                            }
                                            ?>
                                            <div class = "postspace"></div>
                                        <?php
                                        $isThreadPost = false;
                                    }

                                    // Page navigation buttons
                                    echo '<div class = "pageutilities">';
                                    if($page != 1)  // If user is not on first page
                                    { ?>
                                        <div class = "pageBack">
                                        <div class = "pagebutton" onclick = "window.location.href = 'browse.php?threadid=<?php echo $_GET['threadid'] ?>'">First</div>
                                        <div class = "pagebutton" onclick = 'window.location.href = "browse.php?threadid=" + <?php echo json_encode($_GET["threadid"] . "&page=" . ($page - 1)) ?>'>Previous</div>
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
                                        <div class = "pagebutton" onclick = 'window.location.href = "browse.php?threadid=" + <?php echo json_encode($_GET["threadid"] . "&page=" . ($page + 1)) ?>'>Next</div>
                                        <div class = "pagebutton" onclick = 'window.location.href = "browse.php?threadid=" + <?php echo json_encode($_GET["threadid"] . "&page=" . $lastPage) ?>'>Last</div>
                                        </div>
                                    <?php } ?>

                                        <script>
                                            // Force non-negative integers in textbox
                                            $("#pageNum").on('change keydown paste input', function(){
                                                $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                                                $("#gotoPageNum").attr("onclick", "window.location.href = 'browse.php?threadid=" + <?php echo json_encode($_GET["threadid"] . '&page=') ?> + $("#pageNum").val() + "'");
                                            })
                                        </script>
                                    <?php
                                    echo '</div>';
                                }
                                else
                                {
                                    echo "There was an error displaying this data.";
                                }
                            }
                            else
                            {
                                echo "This thread either does not exist or there was an error displaying the data.";
                            }
                        }
                    ?>

                </div>

                <?php
                    require("sidebar.php");
                ?>
            </div>

            <?php
                require("footer.php");
            ?>
        </div>
    </body>
</html>