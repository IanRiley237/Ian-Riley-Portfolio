<?php
    // Verify a connection to the database
    require_once("dbconnect.php");
    session_start();
    require_once('functions.php');
    logBadUserOut();
    
    // Change the value of Is_Flagged depending on its current value.
    if ($_POST['Is_Flagged'])
    {
        $conn->query('  UPDATE Threads
                        SET Is_Flagged = 0, Flagger_Account_ID = NULL
                        Where Thread_ID = ' . $_POST["Thread_ID"]);
    }
    else
    {
        $conn->query('  UPDATE Threads
                        SET Is_Flagged = 1, Flagger_Account_ID = ' . $_SESSION["Account_ID"] . '
                        Where Thread_ID = ' . $_POST["Thread_ID"]);
    }
    
    // After changing the value, update the lock and flag icons
    $isFlaggedTable = $conn->query('SELECT Thread_ID, Is_Flagged, Is_Locked
                                    From Threads
                                    Where Thread_ID = ' . $_POST["Thread_ID"]);
    $thread = $isFlaggedTable->fetch_assoc();
?>

<!-- A '0' is added to the begining of each of the Is_Rank variables to assure that even if thos variables happen to be null, will will still pass the integer '0' through.
    For example, if $_SESSION['Is_Owner'] has the integer value pf 0, 01 would be passed through the function. 01 is equivilant to 1, so there should be no potential error. -->
<img id = "lockimg" src = "<?php if($thread["Is_Locked"] == 1) echo "assets/lock.png"; 
                else echo "assets/no_lock.png";?>" class = "lock" onclick = "threadLockCheckRank(<?php echo $thread['Thread_ID'] . ', ' . $thread['Is_Locked'] . ', 0' . $_SESSION['Is_Owner'] . ', 0' . $_SESSION['Is_Admin'] . ', 0' . $_SESSION['Is_Moderator'] ?>)">
                
<img id = "flagimg" src = "<?php if($thread["Is_Flagged"] == 1) echo "assets/flag.png"; 
                else echo "assets/no_flag.png";?>" class = "flag" onclick = "threadFlagCheckRank(<?php echo $thread['Thread_ID'] . ', ' . $thread['Is_Flagged'] . ', 0' . $_SESSION['Is_Owner'] . ', 0' . $_SESSION['Is_Admin'] ?>)">