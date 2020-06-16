<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php
    require_once('dbconnect.php');

    require_once('functions.php');

    logBadUserOut();
?>
<link rel = "stylesheet" href = "popup.css">

<!-- The Login Popup Form -->
    <div id = "loginPopupBackground" class = "popupBackground">
        <div class = "popupwindow">
            <form class = "popupform" method = "post" id = "loginPopupForm">
                <div class = "containermain">
                    <p>
                        <label for = "uname">Username:</label><br>
                        <input type=  "text" placeholder = "Enter Username" name = "uname" maxlength = 50 required>
                    </p>
                    <p>
                        <label for = "pwd">Password:</label><br>
                        <input type = "password" placeholder = "Enter Password" name = "pwd" maxlength = 300 required>
                    </p>
                    <p id = "loginError">
                        <!-- Display login error here -->
                    </p>
                    <p> 
                        <button type = "submit">Login</button>
                    </p>
                </div>

                <div class = "containerextra">
                    <a onclick = "$('#passwordResetPopupBackground').css('display', 'block'); $('#loginPopupBackground').css('display', 'none'); $('#loginError').text('')">Forgot password?</a><br>
                    Don't have an account? <a onclick = "$('#registerPopupBackground').css('display', 'block'); $('#loginPopupBackground').css('display', 'none'); $('#loginError').text('')">Register!</a>
                </div>
            </form>
            <button type = "button" onclick = "$('#loginPopupBackground').css('display', 'none'); $('#loginError').text('')" class = "cancelbutton">Cancel</button>
        </div>
    </div>
<!-- End of Login Popup Form -->

<!-- The Register Popup Form -->
    <div id = "registerPopupBackground" class = "popupBackground">
        <div class = "popupwindow">
            <form class = "popupform" method = "post" id = "registerPopupForm">
                <div class = "containermain">
                    <p>
                        <label for = "unameRegister">Username:</label><br>
                        <input type = "text" placeholder = "Enter Username" name = "unameRegister" maxlength = 50 pattern = "[A-Za-z0-9_-]{3,}" title = "No special character. Name must not already be used" value = "<?php if (isset($_POST['uname'])) echo $_POST['uname']; ?>" required>
                    </p>
                    <p>
                        <label for = "emailRegister">Email:</label><br>
                        <input type = "email" placeholder = "Enter Email" name = "emailRegister" maxlength = 300 title = "Must be valid email. Email must not already be used" value = "<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required>
                    </p>
                    <p>
                        <label for = "pwdRegister">Password:</label><br>
                        <input type = "password" placeholder = "Enter Password" name = "pwdRegister" id = "pwdRegister" maxlength = 300 title = "1 number, 1 capital letter, 1 lowercase letter, 1 special character, and length of at least 8." pattern = "^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@# %^&*()<>?_+=-])(?=.*\d)[a-zA-Z\d!@#%^&*()<>?_+=-]{8,}$" onkeyup = "confirmPassword()" required>
                    </p>
                    <p>
                        <label for = "cpwdRegister">Confirm Password:</label><br>
                        <input type = "password" placeholder = "Repeat Password" name = "cpwdRegister" id = "cpwdRegister" maxlength = 300 pattern = "" title = "Repeat your password exactly" onkeyup = "confirmPassword()" required>
                    </p>
                    <p style = "font-size: 10px;">
                        All passwords must have a minimum length of 8, contain at least one<br>uppercase letter, lowercase letter, number, and special character each.
                    </p>
                    <p id = "registerError">
                        <!-- Display register error here -->
                    </p>
                    <p> 
                        <button type = "submit" onclick = "confirmPassword()" onload = "confirmPassword()">Register</button>
                    </p>
                </div>

                <div class = "containerextra">
                    If you already have an account, click <a onclick = "$('#loginPopupBackground').css('display', 'block'); $('#registerPopupBackground').css('display', 'none'); $('#registerError').text('')">here</a> to login!
                </div>
            </form>
            <button type = "button" onclick = "$('#registerPopupBackground').css('display', 'none'); $('#registerError').text('')" class = "cancelbutton">Cancel</button>
        </div>
    </div>
    <script>
        // Forces the user to enter a matching password in the "Confirm Password" field.
        function confirmPassword()
        {
            $("#cpwdRegister").attr("pattern", $("#pwdRegister").val());
        }
    </script>
<!-- End of Register Popup Form -->

<!-- The Password Reset Popup Form -->
    <div id = "passwordResetPopupBackground" class = "popupBackground">
        <div class = "popupwindow">
            <form class = "popupform" method = "post" id = "passwordResetPopupForm">
                <div class = "containermain">
                    <p>
                        <label for = "unameReset">Username:</label><br>
                        <input type = "text" placeholder = "Enter Username" name = "unameReset" maxlength = 50 pattern = "[A-Za-z0-9_-]{1,}" title = "No special character. Name must not already be used" value = "<?php if (isset($_POST['unameReset'])) echo $_POST['unameReset']; ?>" required>
                    </p>
                    <p>
                        <label for = "emailReset">Email:</label><br>
                        <input type = "email" placeholder = "Enter Email" name = "emailReset" maxlength = 300 title = "Must be valid email. Email must not already be used" value = "<?php if (isset($_POST['emailReset'])) echo $_POST['emailReset']; ?>" required>
                    </p>
                    <p id = "resetError">
                        <!-- Display reset error here -->
                    </p>
                    <p> 
                        <button type = "submit">Send Temporary Password</button>
                    </p>
                </div>

                <div class = "containerextra">
                    We will email you a temporary password to use to login and change your password.
                </div>
            </form>
            <button type = "button" onclick = "$('#passwordResetPopupBackground').css('display', 'none'); $('#resetError').text('')" class = "cancelbutton">Cancel</button>
        </div>
    </div>
<!-- End of Password Reset Popup Form -->

<!-- The Create Category Popup Form -->
<div id = "createCategoryPopupBackground" class = "popupBackground">
        <div class = "popupwindow">
            <form class = "popupform" method = "post" id = "createCategoryPopupForm">
                <div class = "containermain">
                    <p>
                        <label for = "categoryName">Category Name:</label><br>
                        <input type = "text" placeholder = "Enter category name" name = "categoryName" maxlength = 300 required>
                    </p>
                    <p> 
                        <button type = "submit">Create Category</button>
                    </p>
                </div>

                <div class = "containerextra">
                    The birth of a community.
                </div>
            </form>
            <button type = "button" onclick = "$('#createCategoryPopupBackground').css('display', 'none');" class = "cancelbutton">Cancel</button>
        </div>
    </div>
<!-- End of Create Category Popup Form -->

<!-- The Create Thread Popup Form -->
    <div id = "createThreadPopupBackground" class = "popupBackground">
        <div class = "popupwindow">
            <form class = "popupform" method = "post" id = "createThreadPopupForm">
                <div class = "containermain">
                    <p>
                        <label for = "threadName">Thread Name:</label><br>
                        <input type = "text" placeholder = "Enter thread name" name = "threadName" maxlength = 300 required>
                    </p>
                    <p>
                        Write your thread post:<br>
                        <textarea placeholder = "Write your post here!" rows = 12 wrap = "soft" name = "threadPost" maxlength = 40000></textarea>
                    </p>
                    <p> 
                        <button type = "submit">Create Thread</button>
                    </p>
                </div>

                <div class = "containerextra">
                    Keep thread content relevant to the containing category <b>AND</b> within <span class = "gato">gato</span>'s rules and guidelines.
                </div>
            </form>
            <button type = "button" onclick = "$('#createThreadPopupBackground').css('display', 'none');" class = "cancelbutton">Cancel</button>
        </div>
    </div>
<!-- End of Create Thread Popup Form -->

<!-- The Post Popup Form -->
    <div id = "postPopupBackground" class = "popupBackground">
        <div class = "popupwindow">
            <form class = "popupform" method = "post" id = "postPopupForm">
                <!-- Content of form is in postPopupForm.php -->
            </form>
            <button type = "button" onclick = "$('#postPopupBackground').css('display', 'none');" class = "cancelbutton">Cancel</button>
        </div>
    </div>
<!-- End of Post Popup Form -->

<!-- The Report Popup Form -->
    <div id = "reportPopupBackground" class = "popupBackground">
        <div class = "popupwindow">
            <form class = "popupform" method = "post" id = "reportPopupForm">
                <!-- Content of form is in reportPopupForm.php -->
            </form>
            <button type = "button" onclick = "$('#reportPopupBackground').css('display', 'none');" class = "cancelbutton">Cancel</button>
        </div>
    </div>
<!-- End of Report Popup Form -->

<!-- The Delete Post Popup Form -->
    <div id = "deletePostPopupBackground" class = "popupBackground">
        <div class = "popupwindow">
            <form class = "popupform" method = "post" id = "deletePostPopupForm">
                <!-- Content of form is in deletePostPopupForm.php -->
            </form>
            <button type = "button" onclick = "$('#deletePostPopupBackground').css('display', 'none');" class = "cancelbutton">Cancel</button>
        </div>
    </div>
<!-- End of Delete Post Popup Form -->

<!-- The User Info Popup Form -->
    <div id = "userInfoPopupBackground" class = "popupBackground">
        <!-- Content of form is in userInfoPopupForm.php -->
        <!-- I had to put the popup window div inside the file too because it made managing the secondary Appoint form easier -->
    </div>
<!-- End of User Info Popup Form -->

<?php
##########################

/*  Log user in         */
    if(isset($_POST["uname"]))
    {
        $userinfo = $conn->query("SELECT * FROM Accounts WHERE Username = '" . $_POST["uname"] . "'");
        if($userinfo !== false)
        {
            if($userinfo->num_rows > 0)
            {
                $userrow = $userinfo->fetch_assoc();
                // Check pw
                if(password_verify($_POST["pwd"], $userrow["Password"]))
                {
                    if ($userrow["Is_Deactivated"] == 0)
                    {
                        $currentbans = $conn->query('   SELECT *
                                                        FROM Bans
                                                        WHERE Banned_Account_ID = ' . $userrow["Account_ID"] .
                                                      ' AND Current_Timestamp >= `Start_Timestamp` 
                                                        AND Current_Timestamp < `End_Timestamp`');
                        $isCurrentlyBanned = $currentbans->num_rows > 0;
                        if (!$isCurrentlyBanned)
                        {
                            // Set session variables
                            $_SESSION["Account_ID"] =           $userrow["Account_ID"];
                            $_SESSION["Is_Owner"] =             $userrow["Is_Owner"];
                            $_SESSION["Is_Admin"] =             $userrow["Is_Admin"];
                            $_SESSION["Is_Moderator"] =         $userrow["Is_Moderator"];
                            $_SESSION["Username"] =             $userrow["Username"];
                            $_SESSION["Email"] =                $userrow["Email"];
                            $_SESSION["Creation_Timestamp"] =   $userrow["Creation_Timestamp"];
                            
                            // Send user to home page
                            ?>
                                <script>window.location.href = '/gato';</script>
                            <?php
                        }
                        else
                        {
                            // Account is banned

                            ?>
                                <script>
                                    $("#loginPopupBackground").css("display", "block");
                                    $("#loginError").html("<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> That account is banned until <?php echo $currentbans->fetch_assoc()["End_Timestamp"] ?> </span><b>!!!</b>");
                                </script>
                            <?php
                        }
                    }
                    else
                    {
                        // Account is deactivated
                        ?>
                            <script>
                                $("#loginPopupBackground").css("display", "block");
                                $("#loginError").html("<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> That account is deactivated </span><b>!!!</b>");
                            </script>
                        <?php
                    }
                    
                }
                else
                {
                    // That's the wrong password
                    ?>
                        <script>
                            $("#loginPopupBackground").css("display", "block");
                            $("#loginError").html("<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> Incorrect password </span><b>!!!</b><br> Please try again.");
                        </script>
                    <?php
                }
            }
            else
            {
                // That username does not exist
                ?>
                    <script>
                        $("#loginPopupBackground").css("display", "block");
                        $("#loginError").html("<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> That account does not exist or is deactivated </span><b>!!!</b><br> Please try again.");
                    </script>
                <?php
            }
        }
        else
        {
            // Something is wrong with the query
        }
    }
/*  End of logging in user  */

##############################    

/*  Register user           */
    if(isset($_POST["unameRegister"]))
    {
        ?><script>$("#registerPopupBackground").css("display", "block");</script><?php

        $duplicateAccountQuery = '  SELECT Username, Email
                                    From Accounts
                                    Where Username = \'' . $conn->real_escape_string($_POST["unameRegister"]) . '\' OR Email = \'' . $conn->real_escape_string($_POST["emailRegister"]) . '\'';
        $duplicateAccountsTable = $conn->query($duplicateAccountQuery);

        if($duplicateAccountsTable->num_rows != 0)
        {
            // Regristration failed
            $duplicateUsername = false;
            $duplicateEmail = false;
            while ($accounts = $duplicateAccountsTable->fetch_assoc())
            {
                $duplicateUsername = (strcasecmp($accounts['Username'], $_POST['unameRegister']) == 0) || $duplicateUsername;
                $duplicateEmail = (strcasecmp($accounts['Email'], $_POST['emailRegister']) == 0) || $duplicateEmail;
            }
            ?>
                <script>
                    var duplicateUsername = <?php echo json_encode($duplicateUsername) ?>;
                    var duplicateEmail = <?php echo json_encode($duplicateEmail) ?>;
                    var error = "";
                    $("#registerError").html("");
                    if (duplicateUsername)
                        error += "<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> That username is already in use </span><b>!!!</b><br>";
                    if (duplicateEmail)
                        error += "<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> That email is already in use </span><b>!!!</b><br>";
                    $("#registerError").html(error + "Please try again.");
                </script>
            <?php
        }
        else
        {
            // Regristration was successful
            $conn->query('  INSERT INTO Accounts (Username, `Password`, Email) 
                            VALUES (\'' . $conn->real_escape_string($_POST["unameRegister"]) . '\', \'' . $conn->real_escape_string(password_hash($_POST["pwdRegister"], PASSWORD_DEFAULT)) . '\', \'' . $conn->real_escape_string($_POST["emailRegister"]) . '\')');

            $newAccountID = $conn->query('  SELECT Account_ID
                                            FROM Accounts
                                            WHERE Username = \'' . $_POST["unameRegister"] .'\'')
                                            ->fetch_assoc()['Account_ID'];

            $conn->query('  INSERT INTO Subscribed_To (Account_ID, Thread_ID) 
                            VALUES (' . $newAccountID . ', 1)');
            $conn->query('  INSERT INTO Subscribed_To (Account_ID, Thread_ID) 
                            VALUES (' . $newAccountID . ', 3)');

            ?><script>
                $("#registerError").html("Congratulations! You are registered. You may now <a onclick = \"$('#loginPopupBackground').css('display', 'block');
                $('#registerPopupBackground').css('display', 'none');
                $('#registerError').text('')\">login</a>.");
            </script><?php
        }
    }
/*  End of registering user */

##############################

/*  Email new password              */
    if(isset($_POST["unameReset"]))
    {
        $userAccount = $conn->query('   SELECT *
                                        FROM Accounts
                                        WHERE Username = \'' . $_POST["unameReset"] . '\' AND Email = \'' . $_POST["emailReset"] . '\'');

        if($userAccount->num_rows != 0)
        {
            /* Generate random password */
            $passwordChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#%^&*()<>?_+=-";
            $newPass = "";
            $newPass .= substr($passwordChars, rand(0, 25), 1);
            $newPass .= substr($passwordChars, rand(26, 51), 1);
            $newPass .= substr($passwordChars, rand(52, 61), 1);
            $newPass .= substr($passwordChars, rand(62, strlen($passwordChars) - 1), 1);
            for($i = 0; $i < 8; $i++)
            {
                $newPass .= substr($passwordChars, rand(0, strlen($passwordChars) - 1), 1);
            }
            $newPass = str_shuffle($newPass);
            /* End of generating random password */

            $conn->query('  UPDATE Accounts
                            SET `Password` = \'' . $conn->real_escape_string(password_hash($newPass, PASSWORD_DEFAULT)) .'\'
                            WHERE Username = \'' . $_POST["unameReset"] . '\'');

            ini_set('sendmail_from', $gatoMail);

            
            // Send email
            $mail->addAddress($_POST["emailReset"], $_POST["unameReset"]);

            $mail->Subject = 'gato - Password Successfully Reset!';
            $mail->Body    = 'Hello, ' . $_POST["unameReset"] . '. Your new gato password is... "<b>' . $newPass . '</b>".<br> It is highly recommended that you change your password after logging in to something more appropriate.' . $gatoMailSignature;
            $mail->AltBody = 'Hello, ' . $_POST["unameReset"] . '. Your new gato password is... "' . $newPass . '" It is highly recommended that you change your password after logging in to something more appropriate.';

            if(!$mail->send())
                echo "{$mail->ErrorInfo}";
        }
        else
        {
            // Username and Email do not match
            ?><script>
                $('#passwordResetPopupBackground').css('display', 'block');
                $("#resetError").html("<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> That email doesn't match that username </span><b>!!!</b><br> Please try again.");
            </script><?php
        }
    }
/*  End of emailing new password    */

##############################

/*  Create Category             */
    if(isset($_POST["categoryName"]) )
    {
        $conn->query('  INSERT INTO Categories (Account_Posted_ID, Category_Subject) 
                        VALUES (' . $_SESSION["Account_ID"] . ', \'' . $conn->real_escape_string($_POST["categoryName"]) . '\')');
    }
/*  End of creating category    */

##############################

/*  Create Thread             */
    if(isset($_POST["threadName"]) )
    {
        $conn->query('  INSERT INTO Threads (Account_Posted_ID, Category_ID, Thread_Subject, Post_Text) 
                        VALUES (' . $_SESSION["Account_ID"] . ', ' . $_GET["categoryid"] . ', \'' . $conn->real_escape_string($_POST["threadName"]) . '\', \'' . $conn->real_escape_string($_POST["threadPost"]) . '\')');
    }
/*  End of creating thread    */

##############################

/*  Send post           */
    if(isset($_POST["postText"]) )
    {
        if ($_POST["isEdit"])
        {
            if ($_POST["isThreadPost"])
            {
                $conn->query('  UPDATE Threads
                                SET Post_Text = \'' . $conn->real_escape_string($_POST["postText"]) . '\', Was_Edited = 1
                                WHERE Thread_ID = ' . $_POST["referencePostID"]);
            }
            else
            {
                $conn->query('  UPDATE Posts
                                SET Post_Text = \'' . $conn->real_escape_string($_POST["postText"]) . '\', Was_Edited = 1
                                WHERE Post_ID = ' . $_POST["referencePostID"]);
            }
        }
        else
        {
            $conn->query('  INSERT INTO Posts (Thread_ID, Account_Posted_ID, Post_Text) 
                            VALUES (' . $_GET["threadid"] . ', ' . $_SESSION["Account_ID"] . ', \'' . $conn->real_escape_string($_POST["postText"]) . '\')');
        }
    }
/*  End of sending post */

##############################

/*  Report post             */
    if(isset($_POST["reportReason"]))
    {
        $isThreadPost = (isset($_POST["isThreadPost"]) && $_POST["isThreadPost"]);

        $reportReason = $_POST["reportReason"];
        if ($reportReason === "Other")
            $reportReason = $_POST["textReportReason"];

        if ($isThreadPost)
        {
            $postText = $conn->query('  SELECT Post_Text
                                        FROM Threads
                                        WHERE Thread_ID = '  . $_POST["postID"]);
                                        
            $conn->query('  INSERT INTO Reports (Reported_Account_ID, Reported_Post_ID, Is_Thread_Post, Reporter_Account_ID, Report_Reason, Report_Description, Post_Text) 
                            VALUES (' . $_POST["reportedAccountID"] . ', ' . $_POST["postID"] . ', ' . $isThreadPost . ', ' . $_SESSION["Account_ID"] .', \'' .
                                $conn->real_escape_string($reportReason) . '\', \'' . $conn->real_escape_string($_POST["reportDescription"]) . '\', \'' . $conn->real_escape_string($postText->fetch_assoc()["Post_Text"]) . '\')');
        }
        else
        {
            $postText = $conn->query('  SELECT Post_Text
                                        FROM Posts
                                        WHERE Post_ID = '  . $_POST["postID"]);
                                        
            $conn->query('  INSERT INTO Reports (Reported_Account_ID, Reported_Post_ID, Reporter_Account_ID, Report_Reason, Report_Description, Post_Text) 
                            VALUES (' . $_POST["reportedAccountID"] . ', ' . $_POST["postID"] . ', ' . $_SESSION["Account_ID"] .', \'' .
                                $conn->real_escape_string($reportReason) . '\', \'' . $conn->real_escape_string($_POST["reportDescription"]) . '\', \'' . $conn->real_escape_string($postText->fetch_assoc()["Post_Text"]) . '\')');
        }

        // Get the report ID so we can display it in the email

        // Send email
        $mail->addAddress($_SESSION["Email"], $_SESSION["Username"]);

        $mail->Subject = 'gato - Report Successfuly Filed'; // Add report ID between the two '.' below...
        $mail->Body    = 'Hello, ' . $_SESSION["Username"] . '. You have successfuly filed a report with the ID <b>#' . $conn->insert_id . '</b>. It will be looks over by our ranked users and appropriate action will be taken as soon as possible.' . $gatoMailSignature;
        $mail->AltBody = 'Hello, ' . $_SESSION["Username"] . '. You have successfuly filed a report with the ID #' . $conn->insert_id . '. It will be looks over by our ranked users and appropriate action will be taken as soon as possible.';

        if(!$mail->send())
            echo "{$mail->ErrorInfo}";

    }
/*  End of reporting post   */

##############################

/*  Delete post             */
    if(isset($_POST["postToDelete"]))
    {
        if (!is_null($_POST["isThreadPost"]) && $_POST["isThreadPost"] == 1)
        {
            $categoryID = $conn->query('SELECT Category_ID
                                        FROM Threads
                                        WHERE Thread_ID = ' . $_POST["postToDelete"]);

            $conn->query('  DELETE FROM Threads
                            WHERE Thread_ID = ' . $_POST["postToDelete"]);
            $conn->query('  DELETE FROM Posts
                            WHERE Thread_ID = ' . $_POST["postToDelete"]);
            $conn->query('  DELETE FROM Subscribed_To
                            WHERE Thread_ID = ' . $_POST["postToDelete"]);
                            
            ?>
            <script>
                window.location.href = '/gato/browse.php?categoryid=<?php echo $categoryID->fetch_assoc()['Category_ID'] ?>';
            </script>
            <?php
        }
        else
        {
            $conn->query('  DELETE FROM Posts
                            WHERE Post_ID = ' . $_POST["postToDelete"]);
        }
    }
/*  End of Deleting post   */

##############################

/*  Ban User        */
    if(isset($_POST["banReason"]))
    {
        $banReason = $_POST["banReason"];
        if ($banReason === "Other")
            $banReason = $_POST["textBanReason"];
        $unit = "";
        switch ($_POST["banUnit"])
        {
            case "hours":
                $unit = "HOUR";
                break;
            case "days":
                $unit = "DAY";
                break;
            case "weeks":
                $unit = "WEEK";
                break;
            case "months":
                $unit = "MONTH";
                break;
        }
                                        
        $conn->query('  INSERT INTO Bans (Banned_Account_ID, Banner_Account_ID, Ban_Reason, End_Timestamp) 
                        VALUES (' . $_POST["Banned_Account_ID"] . ', ' . $_SESSION["Account_ID"] .', \'' .
                            $conn->real_escape_string($banReason) . '\', DATE_ADD(CURRENT_TIMESTAMP, INTERVAL ' . $_POST["banUnitCount"] . ' ' . $conn->real_escape_string($unit) . '))');
        
        // Send email
        $mail->addAddress($_POST["Banned_Account_Email"], $_POST["Banned_Account_Username"]);

        $mail->Subject = 'gato - Your Account Has Been Banned';
        $mail->Body    = 'Hello, ' . $_POST["Banned_Account_Username"] . '. You have been banned from gato by ' . $_SESSION["Username"] . '(' . loggedInUserRank() . ') for ' . $_POST["banUnitCount"] . ' ' . $_POST["banUnit"] . ' for ' . $banReason . '.' . $gatoMailSignature;
        $mail->AltBody = 'Hello, ' . $_POST["Banned_Account_Username"] . '.You have been banned from gato by ' . $_SESSION["Username"] . '(' . getUserRankByIDPlainText($_SESSION["Account_ID"]) . ') for ' . $_POST["banUnitCount"] . ' ' . $_POST["banUnit"] . ' for ' . $banReason . '.';
        if(!$mail->send())
            echo "{$mail->ErrorInfo}";
    }
/*  End of Ban User */

##############################

/*  Deactivate User        */
    if(isset($_POST["deactivateUser"]))
    {    
        $conn->query('  UPDATE Accounts
                        SET `Is_Deactivated` = 1
                        WHERE Account_ID = \'' . $_POST["Deactivated_Account_ID"] . '\'');
        
        // Send email
        $mail->addAddress($_POST["Deactivated_Account_Email"], $_POST["Deactivated_Account_Username"]);

        $mail->Subject = 'gato - Your Account Has Been Forcibly Deactivated';
        $mail->Body    = 'Hello, ' . $_POST["Deactivated_Account_Username"] . '. Your account has been deactivated by ' . $_SESSION["Username"] . '(' . loggedInUserRank() . ').' . $gatoMailSignature;
        $mail->AltBody = 'Hello, ' . $_POST["Deactivated_Account_Username"] . '. Your account has been deactivated by ' . $_SESSION["Username"] . '(' . getUserRankByIDPlainText($_SESSION["Account_ID"]) . ').';
        if(!$mail->send())
            echo "{$mail->ErrorInfo}";
    }
/*  End of Deactivate User */

##############################
?>

<script type="text/javascript" src="xbbcode.js">
    function BBCodeToHtml(bbcode)
    {
        var result = XBBCODE.process({
            text: bbcode
        });
        // Normally, result.html will be put into the database
        //document.getElementById("posts").innerHTML = "<div class = \"post\">" + result.html + "</div>";
        return result.html;
    }
</script>

<div class = "header">
    <img class = "logo" src = "assets/gato_logo.png">

    <div class = "buttonsection">
        <a href = "/gato"><div class = "menubutton">
            Home
        </div></a>
        <a href = "browse.php"><div class = "menubutton">
            Browse
        </div></a>
        <?php
            if (isset($_SESSION["Is_Owner"]) && isset($_SESSION["Is_Admin"]) && isset($_SESSION["Is_Moderator"]))
            {
                // Check if the user can go to the reports page or not
                if ($_SESSION["Is_Owner"] == 1 || $_SESSION["Is_Admin"] == 1 || $_SESSION["Is_Moderator"] == 1)
                {
                    // Reveal the button
                    ?>
                        <a href = "reports.php"><div class = "menubutton">
                            Reports
                        </div></a>
                    <?php
                }
            }
        ?>
    </div>
</div>

<!-- This div is used for ajax to run php that does not need to be seen by the user. -->
<div id = "loader" hidden>
</div>

<hr>