<?php
    // If the user is not the appropriate rank to view this page, send them back to the home page
    session_start();
    if (!isset($_SESSION['Account_ID']))
    {
        if (!($_SESSION['Is_Moderator'] == 1 || $_SESSION['Is_Admin'] == 1 || $_SESSION['Is_Owner'] == 1))
        {
            header("Location: /gato");
        }
    }
        
?>

<!DOCTYPE html>

<html>
    <head>
        <title> gato - Reports </title>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1">
        <link rel = "icon" type = "image/png" href = "assets/gato_icon.png">
        <link rel = "stylesheet" href = "all_pages.css">
        <link rel = "stylesheet" href = "reports_page.css">
    </head>
    <body>
        <div class = "backwall"> 
            <?php
                require("header.php");
            ?>

            <div class = "foreground">
                <div class = "sectionorganizer">
                    <div class = "organizerbar">
                        <form>
                            <p>
                                <div class = "checktoolbar">
                                    <label class = "descriptor"> Only view... </label>
                                    <?php
                                        if ($_SESSION['Is_Owner'] == 1)
                                        {
                                            echo '<div class = "owneroptions">';
                                            echo '<input type = "checkbox" id = "checkOwners" name = "onlyView" value = "owners" checked>';
                                            echo '<label for = "checkOwners" class = "ownerbutton"> <b>Owners</b> </label>';

                                            echo '<input type = "checkbox" id = "checkAdmins" name = "onlyView" value = "admins" checked>';
                                            echo '<label for = "checkAdmins" class = "adminbutton"> <b>Administrators</b> </label>';
                                            echo ' </div>';
                                        }
                                    echo '<div class = "otheroptions">';
                                        if ($_SESSION['Is_Admin'] == 1 || $_SESSION['Is_Owner'] == 1)
                                        {
                                            echo '<input type = "checkbox" id = "checkMods" name = "onlyView" value = "mods" checked>';
                                            echo '<label for = "checkMods" class = "modbutton"> <b>Moderators</b> </label>';
                                        }
                                    
                                    echo '<input type = "checkbox" id = "checkUnranked" name = "onlyView" value = "unranked" checked>';
                                    echo '<label for = "checkUnranked" class = "unrankedbutton"> <b>Unranked Users</b> </label>';
                                    echo ' </div>';
                                    ?>
                                </div>
                            </p>
                            <p>
                                <div class = "radiotoolbar">
                                <label class = "descriptor"> Order by... </label>
                                    <div class = "orderoptions">
                                        <input type = "radio" id = "radioOldest" name = "orderBy" value = "oldest" checked>
                                        <label for = "radioOldest" class = "button"> <b>Oldest</b></label><input type = "radio" id = "radioNewest" name = "orderBy" value = "newest"><label for = "radioNewest" class = "button"> <b>Newest</b></label>
                                    </div>
                                </div>
                            </p>
                            <div class = "searchtools">
                                <p>
                                <label class = "descriptor"> Report ID: </label>
                                    <input type = "text" id = "reportID" name = "searchReportID">
                                    <span class = "clearbutton" onclick = "document. getElementById('reportID'). value = ''"> Clear Field </span><br>
                                </p>
                                <p>
                                <label class = "descriptor"> Associated Username: </label>
                                    <input type = "text" id = "reportUsername" name = "searchUsername">
                                    <span class = "clearbutton" onclick = "document. getElementById('reportUsername'). value = ''"> Clear Field </span>
                                </p>
                                <p>
                                    <span class = "searchbutton" onclick = "displayReports()"> Search </span><br>
                                </p>
                                <p style = "font-size: 12px;"> Please note that you can only view reports filed against accounts with the given selectable ranks. <br><b> You cannot view reports filed against yourself. </b></p>
                            </div>
                        </form>
                    </div>
                    <div class = "sectionname">
                        Reports:
                    </div>
                    <div id = "reports" class = "reportstable">
                        <!-- Content in reportTable.php -->
                    </div>
                </div>
                <?php
                    require("sidebar.php");
                ?>
            </div>

            <?php
                require("footer.php");
            ?>

            <!-- Script so that the output always matches the filter -->
            <script>
                $("#reportID").on('change keydown paste input', function(){
                    $("#reportID").val($("#reportID").val().replace(/\D/g,''));
                })
                displayReports();
                $(".organizerbar input").change(function()
                {
                    displayReports();
                });

            </script>
        </div>
    </body>
</html>