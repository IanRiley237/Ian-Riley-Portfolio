<?php
    // Function to call as the current user is navigating the site. $userShouldNotBeLoggedIn will be true if the user as become banned or deactivated.
    function logBadUserOut()
    {
        if (isset($_SESSION['Account_ID']))
        {
            require('dbconnect.php');
            $userShouldNotBeLoggedIn = $conn->query('SELECT (SELECT `Is_Deactivated` FROM Accounts WHERE `Account_ID`= ' . $_SESSION['Account_ID'] . ') = 1 OR (SELECT `Ban_ID`
                                                            FROM Bans
                                                            WHERE Banned_Account_ID = ' . $_SESSION['Account_ID'] . 
                                                        ' AND Current_Timestamp >= `Start_Timestamp` 
                                                            AND Current_Timestamp < `End_Timestamp`) IS NOT NULL')->fetch_array()[0];
            if ($userShouldNotBeLoggedIn)
            {                           
                ?><script> window.location='logout.php'; </script><?php  
                exit();                   
            }
        }
    }
    // Returns the logged in user's rank as an html string with appropriate formatting.
    function loggedInUserRank()
    {
        if ($_SESSION['Is_Owner'])
            return '<span style = "color: rgba(54, 222, 180, 1.0);">Owner</span>';
        else if ($_SESSION['Is_Admin'])
            return '<span style = "color: rgba(253, 144, 120, 1.0);">Administrator</span>';
        else if ($_SESSION['Is_Moderator'])
            return '<span style = "color: rgba(235, 224, 141, 1.0);">Moderator</span>';
        else
            return "Unranked";
    } 

    // Returns the specified user's rank as an html string with appropriate formatting.
    function getUserRankByID($userID)
    {
        require('dbconnect.php');
        $userInfoTable = $conn->query(' SELECT Is_Owner, Is_Admin, Is_Moderator
                                        FROM Accounts
                                        WHERE Account_ID = ' . $userID);
        $userInfo = $userInfoTable->fetch_assoc();

        if ($userInfo['Is_Owner'])
            return '<span style = "color: rgba(54, 222, 180, 1.0);">Owner</span>';
        else if ($userInfo['Is_Admin'])
            return '<span style = "color: rgba(253, 144, 120, 1.0);">Administrator</span>';
        else if ($userInfo['Is_Moderator'])
            return '<span style = "color: rgba(235, 224, 141, 1.0);">Moderator</span>';
        else
            return "Unranked";
    }

    // Returns the specified user's rank as an html string with appropriate formatting.
    function getUserRankByIDPlainText($userID)
    {
        require('dbconnect.php');
        $userInfoTable = $conn->query(' SELECT Is_Owner, Is_Admin, Is_Moderator
                                        FROM Accounts
                                        WHERE Account_ID = ' . $userID);
        $userInfo = $userInfoTable->fetch_assoc();

        if ($userInfo['Is_Owner'])
            return 'Owner';
        else if ($userInfo['Is_Admin'])
            return 'Administrator';
        else if ($userInfo['Is_Moderator'])
            return 'Moderator';
        else
            return "Unranked";
    }

    // The following lines are for getting the mail function to work
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require_once(__DIR__ . '/PHPMailer/src/Exception.php');
    require_once(__DIR__ . '/PHPMailer/src/PHPMailer.php');
    require_once(__DIR__ . '/PHPMailer/src/SMTP.php');
    /* Email setup */
    $gatoMail = "S20gato.forum@gmail.com"; 
    $gatoMailSignature = "<hr>
    <p style = 'text-align:center'>gato is an independently ran site created for as a college project for the University of Central Missouri.</p>
    <p style = 'text-align:center'>All personal information submitted to gato will NOT be shared with another source.</p>
    <p style = 'text-align:center'>NEVER share your password with any other account.</p>";
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Port = 587;
    $mail->Username = $gatoMail;
    $mail->Password = 'S20-gato';
    $mail->SMTPSecure = 'tls';

    $mail->setFrom($gatoMail, 'gato');
    $mail->isHTML(true);
    /* End email setup */