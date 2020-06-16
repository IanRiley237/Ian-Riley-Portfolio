<?php
    require_once("dbconnect.php");
    require_once('functions.php');
    logBadUserOut();
    $reportInfo = $conn->query('SELECT * FROM Reports WHERE Report_ID = ' . $_POST['Report_ID'])->fetch_assoc();
    $reporterInfo = $conn->query('SELECT * FROM Accounts WHERE Account_ID = ' . $reportInfo['Reporter_Account_ID'])->fetch_assoc();
    
    // Send email
    $mail->addAddress($reporterInfo["Email"], $reporterInfo["Username"]);

    $mail->Subject = 'gato - Your Report Has Been Dismissed';
    $mail->Body    = 'Hello, ' . $reporterInfo['Username'] . '. Your report with the ID <b>#' . $_POST['Report_ID'] . '</b> has been dismissed. Action has been taken as deemed appropriate by the ranked official handling your report.' . $gatoMailSignature;
    $mail->AltBody = 'Hello, ' . $reporterInfo['Username'] . '. Your report with the ID #' . $_POST['Report_ID'] . ' has been dismissed. Action has been taken as deemed appropriate by the ranked official handling your report.';

    if(!$mail->send())
        echo "{$mail->ErrorInfo}";
    

    $conn->query('DELETE FROM Reports WHERE Report_ID = ' . $_POST['Report_ID']);

?>
<script>displayReports();</script>