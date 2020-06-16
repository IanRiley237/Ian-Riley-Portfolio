<?php
    // Verify a connection to the database
    require_once("dbconnect.php");
    require("functions.php");
    session_start();
    logBadUserOut();
?>

<?php
    // This code is executed when the user clicks the "Apply" button next to the "Appoint/Demote" dropdown.
    if (isset($_POST["Rank"]))
    {
        switch ($_POST["Rank"])
        {
            case "owner":
                $conn->query('UPDATE Accounts SET Is_Owner = 1, Is_Admin = 0, Is_Moderator = 0 WHERE Account_ID = ' . $_POST["UserID"]);
                break;
            case "admin":
                $conn->query('UPDATE Accounts SET Is_Owner = 0, Is_Admin = 1, Is_Moderator = 0 WHERE Account_ID = ' . $_POST["UserID"]);
                break;
            case "mod":
                $conn->query('UPDATE Accounts SET Is_Owner = 0, Is_Admin = 0, Is_Moderator = 1 WHERE Account_ID = ' . $_POST["UserID"]);
                break;
            case "unranked":
                $conn->query('UPDATE Accounts SET Is_Owner = 0, Is_Admin = 0, Is_Moderator = 0 WHERE Account_ID = ' . $_POST["UserID"]);
                break;
        }
        ?>
            <script>
                $('span[id="<?php echo $_POST["UserID"]?>rank"]').html(<?php echo json_encode(getUserRankByID($_POST["UserID"]))?>);
                if ($('span[id="<?php echo $_POST["UserID"]?>rank"]').html() == "Unranked")
                {
                    $("span[id='<?php echo $_POST['UserID']?>displayRank']").hide();
                }
                else
                {
                    $("span[id='<?php echo $_POST['UserID']?>displayRank']").show();
                }
            </script>
        <?php
    }
?>

<!-- This is the popup form that appears when the user clicks on a username -->
<div class = "popupwindow">
    <form class = "popupform" method = "post" id = "userInfoPopupForm">
        <?php
            // Collect user information
            $userInfo = $conn->query('  SELECT *
                                        FROM Accounts
                                        WHERE Account_ID = ' . $_POST["UserID"])->fetch_assoc();
            $userBans = $conn->query('  SELECT Username AS Banner_Username, Banner_Account_ID, Ban_Reason, Start_Timestamp, End_Timestamp
                                        FROM Bans JOIN Accounts ON Banner_Account_ID = Account_ID
                                        WHERE Banned_Account_ID = ' . $_POST["UserID"] . ' ORDER BY Ban_ID DESC');
            $isCurrentlyBanned = ($conn->query(' SELECT *
                                                FROM Bans
                                                WHERE Banned_Account_ID = ' . $_POST["UserID"] .
                                               ' AND Current_Timestamp >= `Start_Timestamp` 
                                                AND Current_Timestamp < `End_Timestamp`'))->num_rows > 0;
            // Current user can assign role if (the current user is logged in) and (the other user is not theirself) and (the other user is not an owner) and (the current user is owner or admin)
            // Admins also cannot appoint/demote admins
            $currentUserCanAssignRole = isset($_SESSION["Account_ID"]) && $_SESSION["Account_ID"] != $_POST["UserID"] && !$userInfo["Is_Owner"] &&
            (
                ($_SESSION["Is_Owner"]) ||
                ($_SESSION["Is_Admin"] && !$userInfo["Is_Admin"])
            );
        ?>

        <div class = "containermain">
            <?php
                if ($userInfo["Is_Deactivated"])
                    echo "<p>DEACTIVATED ACCOUNT</p>";
            ?>
            <p>
                <span style = "font-size: 'large'"><?php echo $userInfo["Username"] . ' '?></span>
                <span id = "seeRank">
                    <span style = "font-size: 'large'"><?php echo '(' . getUserRankByID($_POST["UserID"]) . ')'?></span>
                    <?php
                        if ($currentUserCanAssignRole && !$userInfo["Is_Deactivated"])
                        { ?>
                            <button type = "button" onclick = "$('#seeRank').hide(); $('#changeRank').show();">Change Rank</button>
                        <?php }
                    ?>
                </span>

                <span id = "changeRank" hidden>
                    (<select id = "ranks">
                        <?php
                            if ($_SESSION["Is_Owner"])
                            { ?>
                                <option value = "admin" <?php if($userInfo["Is_Admin"]) echo 'selected'?>>Administrator</option>
                            <?php }
                        ?>
                        <option value = "mod" <?php if($userInfo["Is_Moderator"]) echo 'selected'?>>Moderator</option>
                        <option value = "unranked" <?php if(!$userInfo["Is_Owner"] && !$userInfo["Is_Admin"] && !$userInfo["Is_Moderator"]) echo 'selected'?>>Unranked</option>
                    </select>)
                    <button type = "button" onclick = "applyRank(<?php echo $_POST['UserID']?>, $('#ranks').val());">Apply</button>
                    <button type = "button" onclick = "$('#seeRank').show(); $('#changeRank').hide();">Cancel</button>
                </span>

                <br>
                Account created: <?php echo date("m/d/Y", strtotime($userInfo["Creation_Timestamp"])) ?>
            </p>

            <?php
                

                if ($currentUserCanAssignRole && !$userInfo["Is_Deactivated"])
                {
                    // This section displays the select box and "Apply" button
                    ?>
                        <p id = "punish">
                            <?php if (!$isCurrentlyBanned)
                            { ?>
                                <button type = "button" onclick = "$('#banPopupBackground').css('display', 'block');">Ban</button>
                            <?php } ?>
                            <button type = "button" onclick = "$('#punish').hide(); $('#deactivateform').show();"><span style = "color: rgba(235, 224, 141, 1.0);">Deactivate</span></button>
                        </p>
                        
                        <p id = "deactivateform" hidden>
                            Deactivate this account?
                            
                            <input type = "hidden" name = "Deactivated_Account_Username" value = <?php echo $userInfo["Username"] ?>>
                            <input type = "hidden" name = "Deactivated_Account_ID" value = <?php echo $_POST["UserID"] ?>>
                            <input type = "hidden" name = "Deactivated_Account_Email" value = <?php echo $userInfo["Email"] ?>>
                            <button type = "submit" name = "deactivateUser"><span style = "color: rgba(235, 224, 141, 1.0);">Yes</span></button>
                            <button type = "button" onclick = "$('#punish').show(); $('#deactivateform').hide();">No</button>
                        </p>
                    <?php
                }
                
                ?>
                    <p>
                    Bans Filed against User:
                    <?php
                        // Compose a string variable with any applicable ban information
                        $banText= "";
                        while ($ban = $userBans->fetch_assoc())
                        {
                            $start = date("m/d/Y @ h:i:s A", strtotime($ban["Start_Timestamp"]));
                            $end = date("m/d/Y @ h:i:s A", strtotime($ban["End_Timestamp"]));
                            $banner = $ban["Banner_Username"];
                            $bannerRank = getUserRankByIDPlainText($ban["Banner_Account_ID"]);
                            $banReason = $ban["Ban_Reason"];

                            $banText .= $start . " - " . $end . "\n    Filed by... " . $banner . "(" . $bannerRank . ")\n    " . $banReason . "\n\n"; // Append ban at the end of the list
                        }
                        // If the string has content in it, apply it here. Otherwise, leave it with the placeholder text.
                        echo "<textarea placeholder = \"As of yet, no bans have been filed against this user.\" rows = 5 wrap = \"soft\" maxlength = 40000 readonly>" . $banText . "</textarea>";
                    ?>
                    </p>
                <?php
                
            ?>
        </div>
        <div class = "containerextra">
            This is <i><?php echo $userInfo["Username"]?></i>'s account profile.
        </div>

    </form>
    <button type = "button" onclick = "$('#userInfoPopupBackground').css('display', 'none');" class = "cancelbutton">Cancel</button>
</div>

<div id = "banPopupBackground" class = "popupBackground">
    <div class = "popupwindow">
        <form class = "popupform" method = "post" id = "banPopupForm">
            <div class = "containermain">
                <p>
                    How long do you want to ban <?php echo $userInfo["Username"] ?>?
                    <input type = "hidden" name = "Banned_Account_ID" value = <?php echo $_POST["UserID"] ?>>
                    <input type = "hidden" name = "Banned_Account_Email" value = <?php echo $userInfo["Email"] ?>>
                    <input type = "hidden" name = "Banned_Account_Username" value = <?php echo $userInfo["Username"] ?>>
                </p>
                <p id = "banform">
                    Ban <?php echo $userInfo["Username"] ?> for 
                    <input type = "text" id = "banUnitCount" name = "banUnitCount">
                    <select name = "banUnit" id = "banUnit">
                        <option>hours</option>
                        <option>days</option>
                        <option>weeks</option>
                        <option>months</option>
                    </select>

                    <script>
                        $("#banUnitCount").on('change keydown paste input', function(){
                            $("#banUnitCount").val($("#banUnitCount").val().replace(/\D/g,''));
                        })
                    </script>
                </p>
                <p id = "banReason">
                    Ban Reason:<br>
                    <select name = "banReason" id = "reason">
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
                                $("#textBanReason").show();
                                $("#textBanReason").attr("required", true);
                            }
                            else
                            {
                                $("#textBanReason").hide();
                                $("#textBanReason").removeAttr("required");
                            }
                        });  
                    </script>
                </p>
                <p id = "textBanReason" hidden>
                    Please specify:<br>
                    <input type = "text" name = "textBanReason" maxlength = 50>
                </p>
                <p> 
                    <button type = "submit">Apply Ban</button>
                </p>
            </div>

            <div class = "containerextra">
                You are banning <i><?php echo $userInfo["Username"] ?></i>.
            </div>
        </form>
        <button type = "button" onclick = "$('#banPopupBackground').css('display', 'none');" class = "cancelbutton">Cancel</button>
    </div>
</div>