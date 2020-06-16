// When the user clicks anywhere outside of the form, close it
// Makes the whole screen covering popup div invisible
var loginpopup = document.getElementById('popupBackground');
window.onclick = function(event)
{
    if (event.target == loginpopup)
    {
        loginpopup.style.display = "none";
    }
}

// AJAX function
// When the user clicks on another user's name
function userInfoPopup(userID)
{
    $("#userInfoPopupBackground").load("userInfoPopupForm.php", 
    {
        UserID: userID
    });
    document.getElementById('userInfoPopupBackground').style.display='block';
};

// AJAX function
// When the user applies a rank to another user
function applyRank(userID, rank)
{
    $("#userInfoPopupBackground").load("userInfoPopupForm.php", 
    {
        Rank: rank,
        UserID: userID
    });
    document.getElementById('userInfoPopupBackground').style.display='block';
};

// AJAX function
// When a subscribe or unsubscribe button is clicked, load the subscribe toggle script in the loader
function subscribeToggle(accountID, threadID)
{
    $("#loader").load("subscribeToggle.php", 
    {
        Account_ID: accountID,
        Thread_ID: threadID
    });
};

// AJAX function
// 
function postHistory(page)
{
    $("#postHistory").load("postHistory.php", 
    {
        page: page
    });
};

// AJAX function
// When the report popup is opened, this loads the popup with the relevant information
function reportPopup(postID, isThreadPost)
{
    $("#reportPopupForm").load("reportPopupForm.php", 
    {
        Post_ID: postID,
        Is_Thread_Post: isThreadPost
    });
    document.getElementById('reportPopupBackground').style.display='block';
};

// AJAX function
// When a button is clicked to delete a post, make the verification popup form appear with the proper information.
function deletePostPopup(postID, isThreadPost)
{
    $("#deletePostPopupForm").load("deletePostPopupForm.php", 
    {
        Post_ID: postID,
        Is_Thread_Post: isThreadPost
    });
    document.getElementById('deletePostPopupBackground').style.display='block';
};

// AJAX function
// Allow the postPopup to aquire the information it should show to the user depending on the thread and whether they clicked 'Post', 'Edit', or 'Delete'
function postPopup(threadID, postID, isEdit, isThreadPost)
{
    postID = postID || -1;
    isEdit = isEdit || 0;

    $("#postPopupForm").load("postPopupForm.php", 
    {
        Thread_ID: threadID,
        Post_To_Reference_ID: postID,
        isEdit: isEdit,
        Is_Thread_Post: isThreadPost
    });
    document.getElementById('postPopupBackground').style.display='block';
};

// AJAX function
// The div that will display all of the current user's subscribed threads
function subscribedThreads(orderby)
{
    $("#subscribedthreads").load("subscriptions.php", 
    {
        OrderBy: orderby
    });
};

// AJAX function
// Call the dismissReport script to delete the report with the supplied ID. Also, the script calls displayReports which is handy! No need to reload the whole page.
function dismissReport(reportID)
{
    $("#loader").load("dismissReport.php", 
    {
        Report_ID: reportID
    });
};

// AJAX function
// The reportTable script displays all reports following the filters checked by the user.
function displayReports(page)
{
    $("#reports").load("reportTable.php", 
    {
        viewOwners: $("#checkOwners").prop("checked"),
        viewAdministrators: $("#checkAdmins").prop("checked"),
        viewModerators: $("#checkMods").prop("checked"),
        viewUnrankedUsers: $("#checkUnranked").prop("checked"),
        orderByNewest: $("#radioNewest").prop("checked"),

        reportID: $("#reportID").val(),
        reportUsername: $("#reportUsername").val(),

        page: page
    });
};

// AJAX function
// Just like the subscribe/unsubscribe button. The ___FlagToggle scripts and ___LockToggle script just toggle the respective value when the check is clicked by a user with an appropriate rank.
function categoryFlagToggle(categoryID, isFlagged)
{
    $("#imageholder" + categoryID).load("categoryFlagToggle.php", 
    {
        Category_ID: categoryID,
        Is_Flagged: isFlagged
    });
};

// AJAX function
function threadFlagToggle(threadID, isFlagged)
{
    $("#imageholder" + threadID).load("threadFlagToggle.php", 
    {
        Thread_ID: threadID,
        Is_Flagged: isFlagged
    });
};

// AJAX function
function threadLockToggle(threadID, isLocked)
{
    $("#imageholder" + threadID).load("threadLockToggle.php", 
    {
        Thread_ID: threadID,
        Is_Locked: isLocked
    });
};

// These CheckRank functions are a buffer for the flag and lock toggles.
// If the user clicks on one of the icons, but are not the proper rank,
// then nothing will happen. Only when the user is the proper rank will
// the AJAX toggle functions be called.

function categoryCheckRank(categoryID, isFlagged, isOwner)
{
    if(isOwner)
    {
        categoryFlagToggle(categoryID, isFlagged);
    }
}

function threadFlagCheckRank(threadID, isFlagged, isOwner, isAdmin)
{
    if(isOwner || isAdmin)
    {
        threadFlagToggle(threadID, isFlagged);
    }
}

function threadLockCheckRank(threadID, isLocked, isOwner, isAdmin, isMod)
{
    if(isOwner || isAdmin || isMod)
    {
        threadLockToggle(threadID, isLocked);
    }
}