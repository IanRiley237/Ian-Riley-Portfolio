<?php
    if(isset($_SESSION["Username"]))
    {
?>
<!-- The Account Settings Popup Form -->
<div id = "accountSettingsPopupBackground" class = "popupBackground">
    <div class = "popupwindow">
        <form class = "popupform" method = "post" id = "accountSettingsPopupForm">
            <div class = "containermain">
                <!-- Username -->
                <p>
                    <div id = "displayUsername">
                        <b>Username:</b>
                        <?php echo $_SESSION["Username"] . " (" . loggedInUserRank() . ")" ?>
                        <button type = "button" onclick = "toggleUsernameEdit()">Change Username</button>
                    </div>
                    <div id = "editUsername" hidden>
                        <input type = "text" name = "newUsername" id = "newUsername" title = "Must be a different username" value = "<?php echo $_SESSION["Username"] ?>" required>
                        <button type = "button" onclick = "toggleUsernameEdit()">Cancel Username Change</button>
                    </div>

                    <script>
                        function toggleUsernameEdit()
                        {
                            let username = <?php echo json_encode($_SESSION["Username"]) ?>;
                            if($("#editUsername")[0].hasAttribute("hidden"))
                            {
                                $("#editUsername").removeAttr("hidden");
                                $("#newUsername").attr("pattern", "^(" + username + ".+|(?!" + username + ").*)$");
                                $("#displayUsername").attr("hidden", "true");
                            }
                            else
                            {
                                $("#displayUsername").removeAttr("hidden");
                                $("#newUsername").removeAttr("pattern");
                                $("#newUsername").val(username);
                                $("#editUsername").attr("hidden", "true");
                            }
                        }
                    </script>
                <!-- End Username -->
                <!-- Email -->
                    <div id = "displayEmail">
                        <b>Email:</b>
                        <?php echo $_SESSION["Email"] ?>
                        <button type = "button" onclick = "toggleEmailEdit()">Change Email</button>
                    </div>

                    <div id = "editEmail" hidden>
                        <input type = "email" name = "newEmail" id = "newEmail" title = "Must be a different email" value = "<?php echo $_SESSION["Email"] ?>" required>
                        <button type = "button" onclick = "toggleEmailEdit()">Cancel Email Change</button>
                    </div>

                    <script>
                        function toggleEmailEdit()
                        {
                            let email = <?php echo json_encode($_SESSION["Email"]) ?>;
                            if($("#editEmail")[0].hasAttribute("hidden"))
                            {
                                $("#editEmail").removeAttr("hidden");
                                $("#newEmail").attr("pattern", "^(" + email + ".+|(?!" + email + ").*)$");
                                $("#displayEmail").attr("hidden", "true");
                            }
                            else
                            {
                                $("#displayEmail").removeAttr("hidden");
                                $("#newEmail").removeAttr("pattern");
                                $("#newEmail").val(email);
                                $("#editEmail").attr("hidden", "true");
                            }
                        }
                    </script>
                </p>
                <!-- End Email -->
                <!-- Password -->
                <p>
                    <div id = "editPasswordPrompt">
                        <button type = "button" onclick = "togglePasswordEdit()">Change Password</button>
                    </div>
                    <div id = "editPassword" hidden>
                        <p> 
                            New Password:
                            <input type = "password" name = "newPassword" id = "newPassword" maxlength = 300 title = "1 number, 1 capital letter, 1 lowercase letter, 1 special character, and length of at least 8." pattern = "^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@# %^&*()<>?_+=-])(?=.*\d)[a-zA-Z\d!@#%^&*()<>?_+=-]{8,}$" onkeyup = "confirmNewPassword()">
                            <br>
                            Confirm New Password:
                            <input type = "password" id = "confirmedNewPassword" name = "confirmedNewPassword" maxlength = 300 pattern = "" title = "Repeat your password exactly" onkeyup = "confirmNewPassword()">
                        </p>
                        <script>
                            // Forces the user to enter a matching password in the "Confirm New Password" field.
                            function confirmNewPassword()
                            {
                                $("#confirmedNewPassword").attr("pattern", $("#newPassword").val());
                            }
                        </script>
                        <button type = "button" onclick = "togglePasswordEdit()">Cancel Password Change</button>
                    </div>

                    <script>
                        function togglePasswordEdit()
                        {
                            if($("#editPassword")[0].hasAttribute("hidden"))
                            {
                                $("#editPassword").removeAttr("hidden");
                                $("#editPasswordPrompt").attr("hidden", "true");

                                $("#newPassword").attr("required", "true");
                                $("#confirmedNewPassword").attr("required", "true");
                            }
                            else
                            {
                                $("#editPasswordPrompt").removeAttr("hidden");
                                $("#editPassword").attr("hidden", "true");
                                
                                $("#newPassword").removeAttr("required");
                                $("#confirmedNewPassword").removeAttr("required");
                                $("#newPassword").val("");
                                $("#confirmedNewPassword").val("");
                            }
                        }
                    </script>
                </p>
                <!-- End Password -->
                <hr>
                <p> 
                    Current Password:
                    <input type = "password" name = "currentPassword" required>
                    
                </p>
                <p id = "verificationError">
                        <!-- Display verification error here -->
                </p>
                <p> 
                    <button type = "submit" name = "manageAccount">Submit Changes</button><br>
                    <button type = "button" onclick = "$('#deactiviateAccountPopupBackground').css('display', 'block');"><span style = "color: rgba(235, 224, 141, 1.0);">Deactivate Account</span></button>
                </p>
            </div>
            
            <div class = "containerextra">
                Your password must be confirmed before you can make any changes to your account.
            </div>
        </form>
        <button type = "button" onclick = "$('#accountSettingsPopupBackground').css('display', 'none'); $('#verificationError').html('')" class = "cancelbutton">Cancel</button>
    </div>
</div>
<!-- End of Account Settings Popup Form -->
<?php
    }
?>

<!-- If the user submitted a form to change some details -->
<?php
    if (isset($_POST['manageAccount']))
    {
        ?><script>$('#accountSettingsPopupBackground').css('display', 'block');</script><?php
        $userinfo = $conn->query('SELECT `Password` FROM Accounts WHERE Account_ID = \'' . $_SESSION["Account_ID"] . '\'');
        $userrow = $userinfo->fetch_assoc();
        if(password_verify($_POST["currentPassword"], $userrow["Password"]))
        {
            $error = 0;
            $changeUsername = isset($_POST['newUsername']) && $_POST['newUsername'] != $_SESSION["Username"];
            $changeEmail = isset($_POST['newEmail']) && $_POST['newEmail'] != $_SESSION["Email"];
            if ($changeUsername) // User changed their Username
            {
                // First check if there already exists a username that matches the new username
                $duplicateUsernameTable = $conn->query( '   SELECT Username
                                                            From Accounts
                                                            Where Username = \'' . $conn->real_escape_string($_POST["newUsername"]) . '\'');
                // Error if that username is already taken, but don't error if that username that is taken is the current users. This is so the User can change the case of their Username
                if($duplicateUsernameTable->num_rows != 0 && strcasecmp($_POST['newUsername'], $_SESSION["Username"]) != 0)
                {
                    // That username is already in use
                    $error = 1;
                    ?><script>
                        toggleUsernameEdit();
                        $("#newUsername").val(<?php echo json_encode($_POST["newUsername"]) ?>);
                        $('#verificationError').append("<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> That username is already in use </span><b>!!!</b><br>");
                    </script><?php
                }
            }
            if ($changeEmail) // User changed their Email
            {
                // First check if there already exists a username that matches the new username
                $duplicateEmailTable = $conn->query( '  SELECT Email
                                                        From Accounts
                                                        Where Email = \'' . $conn->real_escape_string($_POST["newEmail"]) . '\'');
                if($duplicateEmailTable->num_rows != 0)
                {
                    // That email is already in use
                    $error = 1;
                    ?><script>
                        toggleEmailEdit();
                        $("#newEmail").val(<?php echo json_encode($_POST["newEmail"]) ?>);
                        $('#verificationError').append("<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> That email is already in use </span><b>!!!</b><br>");
                    </script><?php
                }
            }
            if (isset($_POST['newPassword']) && $_POST['newPassword'] != '' && $_POST['confirmedNewPassword'] == $_POST['newPassword']) // User changed their Password
            {
                $conn->query('  UPDATE Accounts
                                SET `Password` = \'' . $conn->real_escape_string(password_hash($_POST['newPassword'], PASSWORD_DEFAULT)) .'\'
                                WHERE Account_ID = \'' . $_SESSION["Account_ID"] . '\'');

                
            }

            if ($error == 1)
            {
            }
            else
            {
                // No errors, reload page
                if ($changeUsername)
                {
                    // Change username
                    $conn->query('  UPDATE Accounts
                                    SET `Username` = \'' . $conn->real_escape_string($_POST['newUsername']) .'\'
                                    WHERE Account_ID = \'' . $_SESSION["Account_ID"] . '\'');
                    $_SESSION["Username"] = $_POST['newUsername'];
                }
                
                if ($changeEmail)
                {
                    // Change email
                    $conn->query('  UPDATE Accounts
                                    SET `Email` = \'' . $conn->real_escape_string($_POST['newEmail']) .'\'
                                    WHERE Account_ID = \'' . $_SESSION["Account_ID"] . '\'');

                    // Send email
                    $mail->addAddress($_POST['newEmail'], $_SESSION["Username"]);

                    $mail->Subject = 'gato - Email Successfully Reset!';
                    $mail->Body = 'Hello, ' . $_SESSION["Username"] . '. Your gato account just had its email changed from ' . $_SESSION["Email"] . ' to ' . $_POST['newEmail'] . '.' . $gatoMailSignature;
                    $mail->AltBody = 'Hello, ' . $_SESSION["Username"] . '. Your gato account just had its email changed from ' . $_SESSION["Email"] . ' to ' . $_POST['newEmail'] . '.';

                    if(!$mail->send())
                        echo "{$mail->ErrorInfo}";

                    $_SESSION["Email"] = $_POST['newEmail'];
                }
                ?><script>window.location = window.location.href</script><?php
            }
        }
        else
        {
            // Incorrect password
            ?>
                <script>
                    $('#verificationError').html("<b>!!!</b><span style = \"color: rgba(253, 144, 120, 1.0);\"> Incorrect password </span><b>!!!</b><br>");
                </script>
            <?php
        }
    }
?>

<hr class = "sectiondivider">

<!-- The Account Deactivation Verification Form -->
<div id = "deactiviateAccountPopupBackground" class = "popupBackground">
    <div class = "popupwindow">
        <form class = "popupform" method = "post" id = "deactiviateAccountPopupForm">
            <div class = "containermain">
                <p>
                    Are you absolutely sure you want to deactivate your account?
                </p>
                <p> 
                    <button type = "submit" name = "deactivate">Yes, I am sure.</button>
                </p>
            </div>

            <div class = "containerextra">
                Deactivating your account means that you will never be able to access it again.<br>
                You would have to contact support to regain access to your account.
            </div>
        </form>
        <button type = "button" onclick = "$('#deactiviateAccountPopupBackground').css('display', 'none');" class = "cancelbutton">Cancel</button>
    </div>
</div>
<!-- End of Account Deactivation Verification Form -->

<?php // Deactivate account
    if (isset($_POST["deactivate"]))
    {
        $conn->query('  UPDATE Accounts
                        SET `Is_Deactivated` = 1
                        WHERE Account_ID = \'' . $_SESSION["Account_ID"] . '\'');

        // Send email
        $mail->addAddress($_SESSION['Email'], $_SESSION["Username"]);

        $mail->Subject = 'gato - Your account has been deactivated.';
        $mail->Body = 'Hello, ' . $_SESSION["Username"] . '. Your gato account has just been deactivated.' . $gatoMailSignature;
        $mail->AltBody = 'Hello, ' . $_SESSION["Username"] . '. Your gato account has just been deactivated.';

        if(!$mail->send())
            echo "{$mail->ErrorInfo}";

        ?><script> window.location='logout.php'; </script><?php
    }
?>

<!-- Sidebar content -->
<div class = "accountinfo">
    <?php
            if(isset($_SESSION["Account_ID"]))
            {
                // Display information about the currently logged in user
                ?>
                    <span class = "info"> You are currently logged in as: </span><br>
                    <span class = "label"> Username: </span><br> 
                    <span id = "accountUsername"><?php echo $_SESSION['Username'] ?></span><br>
                    <span class = "label"> Ranking: </span><br> 
                    <span id = "accountRanking"> <?php // Display the logged in user's rank
                                                    echo loggedInUserRank();
                                                ?></span>
                    <div class = "buttonholder">
                        <div id = "settingsbutton" class = "sidebutton" onclick = "$('#accountSettingsPopupBackground').css('display', 'block');">
                            Account Settings
                        </div>
                        <div id = "loggerbutton" class = "sidebutton" onclick="window.location='logout.php';">
                            <span class = "logouttext"> Logout </span>
                        </div>
                    </div>
                <?php
            }
            else
            {
                // The user is not logged in. Display login options
                ?>
                    <span class = "info"> You are not currently logged in! </span><br>
                    <span class = "label"> To be able to use all of the functions of <span class = "info"><b>gato</b></span>, you can either make a new account, or log in with an existing one. </span><br> 
                    <div class = "buttonholder">
                        <div id = "settingsbutton" class = "registerbutton" onclick = "$('#registerPopupBackground').css('display', 'block')">
                            Register
                        </div>
                        <div id = "loggerbutton" class = "sidebutton" onclick = "$('#loginPopupBackground').css('display', 'block')">
                            Login
                        </div>
                    </div>
                <?php
            }
        ?>
</div>