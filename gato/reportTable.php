<?php
    /*
    POSTS
        viewOwners
        viewAdministrators
        viewModerators
        viewUnrankedUsers
        orderByNewest

        reportID
        reportUsername

        page
    */
    require_once("dbconnect.php");
    session_start();
    require_once('functions.php');
    logBadUserOut();

    $reportsPerPage = 10;

    // The user cannot see reports filed against them
    $onlyView = " WHERE Reported_Account_ID != " . $_SESSION['Account_ID'] . " AND (";

    // Apply filters based on the checks clicked. If the user is a rank that does not allow them to see reports on another rank, that variable wouldn't be set. It would be as if they chose not to see those reports.
    if(isset($_POST['viewOwners']) && $_POST['viewOwners'] === 'true')
        $onlyView .= "(SELECT Is_Owner FROM Accounts WHERE Accounts.Account_ID = Reported_Account_ID)=1";
    else
        $onlyView .= "1=0"; // This false statement is needed so the query doesn't start with an OR with no term on the left.

    if(isset($_POST['viewAdministrators']) && $_POST['viewAdministrators'] === 'true')
        $onlyView .= " OR (SELECT Is_Admin FROM Accounts WHERE Accounts.Account_ID = Reported_Account_ID)=1";
        
    if(isset($_POST['viewModerators']) && $_POST['viewModerators'] === 'true')
        $onlyView .= " OR (SELECT Is_Moderator FROM Accounts WHERE Accounts.Account_ID = Reported_Account_ID)=1";
        
    if(isset($_POST['viewUnrankedUsers']) && $_POST['viewUnrankedUsers'] === 'true')
        $onlyView .= " OR ((SELECT Is_Owner FROM Accounts WHERE Accounts.Account_ID = Reported_Account_ID)=0 AND (SELECT Is_Admin FROM Accounts WHERE Accounts.Account_ID = Reported_Account_ID)=0 AND (SELECT Is_Moderator FROM Accounts WHERE Accounts.Account_ID = Reported_Account_ID)=0)";

    $onlyView .= ")";

    // If there are more specific search filters, apply them
    if(isset($_POST['reportID']) && $_POST['reportID'] != '')
    {
        $onlyView .= " AND Report_ID = " . $_POST['reportID'];
    }
    if(isset($_POST['reportUsername']) && $_POST['reportUsername'] !== '')
    {
        $onlyView .= " AND ((SELECT Username FROM Accounts WHERE Accounts.Account_ID = Reported_Account_ID) = '" . $conn->real_escape_string($_POST['reportUsername']) . "'
                         OR (SELECT Username FROM Accounts WHERE Accounts.Account_ID = Reporter_Account_ID) = '" . $conn->real_escape_string($_POST['reportUsername']) . "')";
    }

    // Order by ascending or descending.
    $orderBy = ' ORDER BY Creation_Timestamp ';
    if($_POST['orderByNewest'] === 'true')
        $orderBy .= 'DESC';
    else
        $orderBy .= 'ASC';

    // I didn't know a good way to use a JOIN statement here since there are two accounts.
    $reportsQuery = '  SELECT *,
                            (SELECT Username FROM Accounts WHERE Accounts.Account_ID = Reported_Account_ID) AS Reported_Account,
                            (SELECT Username FROM Accounts WHERE Accounts.Account_ID = Reporter_Account_ID) AS Reporter_Account
                        FROM Reports' . $onlyView . $orderBy;
    $reportsTable = $conn->query($reportsQuery);

    // Fetch all the reports. Don't try displaying data if there was an error with the query 
    if($reportsTable)
    {
        if ($reportsTable->num_rows == 0)
        {
            echo "There are no reports matching your filters.";
        }
        else
        {
            
            $page = 1;
            if(isset($_POST["page"]) && !empty($_POST["page"]))
                $page = $_POST["page"];

            $lastPage = ceil($reportsTable->num_rows / $reportsPerPage);
            if ($page > $lastPage)
                $page = $lastPage;

            $offset = ($page - 1) * $reportsPerPage;

            $reportsQuery .= ' LIMIT ' . $reportsPerPage . ' OFFSET ' . $offset;
            $reportsTable = $conn->query($reportsQuery);

            // Page navigation buttons
            echo '<div class = "pageutilities">';
            if($page != 1)  // If user is not on first page
            { ?>
                <div class = "pageBack">
                <div class = "pagebutton" onclick = "displayReports(1)">First</div>
                <div class = "pagebutton" onclick = 'displayReports(<?php echo $page - 1 ?>)'>Previous</div>
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
                <div class = "pagebutton" onclick = 'displayReports(<?php echo $page + 1 ?>)'>Next</div>
                <div class = "pagebutton" onclick = 'displayReports(<?php echo $lastPage ?>)'>Last</div>
                </div>
            <?php } ?>

                <script>
                    // Force non-negative integers in textbox
                    $("#pageNum").on('change keydown paste input', function(){
                        $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                        $("#gotoPageNum").attr("onclick", "displayReports($('#pageNum').val())");
                    })
                </script>
            <?php
            echo '</div>';

            echo '<div>';
            while ($report = $reportsTable->fetch_assoc())
            {
                // Get rank of reporter
                $reporterRank = getUserRankByID($report['Reporter_Account_ID']);

                // Get rank of reported
                $reportedRank = getUserRankByID($report['Reported_Account_ID']);

                // Print the report
                ?>
                <div class = "reportfile">
                    <?php echo "<div class = \"reportheader\"> Report #<span style = \"color: rgba(144, 176, 152, 1.0);\">" . $report['Report_ID'] . "</span>:</div>"; ?>
                    
                    <?php echo "<div class = \"accountsrelated\"> <b>Report filed by:</b> <a onclick = 'userInfoPopup(" . $report['Reporter_Account_ID'] . ")'>" . $report['Reporter_Account'] . "</a> (<span id = '" . $report['Reporter_Account_ID'] . "rank'>" . $reporterRank . "</span>)<br>"; ?>
                    <?php echo "<b>Against:</b> <a onclick = 'userInfoPopup(" . $report['Reported_Account_ID'] . ")'>" . $report['Reported_Account'] . "</a> (<span id = '" . $report['Reported_Account_ID'] . "rank'>" . $reportedRank . "</span>)</div>"; ?>
                    <?php echo "<div class = \"relatedinfo\"> <div class = \"reason\">" . $report['Report_Reason'] . "</div>"; ?>
                    <?php echo "<div class = \"infoheader\">Report Description:</div> <div class = \"infobody\"><textarea class = \"reportarea\" placeholder = \"No description given!\" rows = 5 wrap = \"soft\" maxlength = 40000 readonly>" . $report['Report_Description'] . "</textarea></div>"; ?>
                    <?php echo "<div class = \"infoheader\">Related Post:</div> <div class = \"infobody\"><textarea class = \"reportarea\" rows = 5 wrap = \"soft\" maxlength = 40000 readonly>" . $report['Post_Text'] . "</textarea></div>"; ?>
                    <?php echo "<div class = \"reporttimestamp\"> <b>Report filed... </b>" . date("m/d/Y @ h:i:s A", strtotime($report['Creation_Timestamp'])) . "</div></div>"; ?>
                    <div class = "reportfooter"> <div class = "dismissbutton" onclick = "dismissReport(<?php echo $report['Report_ID']?>)">Dismiss Report</div> </div>
                </div>
                <?php
            }
            echo '</div>';

            // Page navigation buttons
            echo '<div class = "pageutilities">';
            if($page != 1)  // If user is not on first page
            { ?>
                <div class = "pageBack">
                <div class = "pagebutton" onclick = "displayReports(1)">First</div>
                <div class = "pagebutton" onclick = 'displayReports(<?php echo $page - 1 ?>)'>Previous</div>
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
                <div class = "pagebutton" onclick = 'displayReports(<?php echo $page + 1 ?>)'>Next</div>
                <div class = "pagebutton" onclick = 'displayReports(<?php echo $lastPage ?>)'>Last</div>
                </div>
            <?php } ?>

                <script>
                    // Force non-negative integers in textbox
                    $("#pageNum").on('change keydown paste input', function(){
                        $("#pageNum").val($("#pageNum").val().replace(/\D/g,''));
                        $("#gotoPageNum").attr("onclick", "displayReports($('#pageNum').val())");
                    })
                </script>
            <?php
            echo '</div>';

        }
    }
    else
    {
        echo "There was a problem displaying the reports. Please contact support.";
    }
?>