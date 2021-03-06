$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();

    if($('#taskList thead th.w-name').width() < 150) $('#taskList thead th.w-name').width(150);
    
    $(document).on('click', "#taskList tbody tr, .table-footer .check-all, #taskList thead .check-all", function(){showCheckedSummary();});
    $(document).on('change', "#taskList :checkbox", function(){showCheckedSummary();});
    $(document).on('click', "#datatable-taskList table tr", function(){showCheckedSummary();});
});

function setQueryBar(queryID, title)
{
    var $tagTab = $('#featurebar #calendarTab').size() > 0 ? $('#featurebar #calendarTab') : $('#featurebar #kanbanTab');
    $tagTab.before("<li id='QUERY" + queryID + "Tab' class='active'><a href='" + createLink('project', 'task', "projectID=" + projectID + "&browseType=bysearch&param=" + queryID) + "'>" + title + "</a></li>");
}

function showCheckedSummary()
{
    var $summary = $('#main #mainContent form.main-table .table-header .table-statistic');

    var checkedTotal    = 0;
    var checkedWait     = 0;
    var checkedDoing    = 0;
    var checkedEstimate = 0;
    var checkedConsumed = 0;
    var checkedLeft     = 0;
    $('[name^="taskIDList"]').each(function()
    {
        if($(this).prop('checked'))
        {
            var taskID = $(this).val();
            $tr = $("#taskList tbody tr[data-id='" + taskID + "']");

            checkedTotal += 1;

            var taskStatus = $tr.data('status');
            if(taskStatus == 'wait')  checkedWait += 1;
            if(taskStatus == 'doing') checkedDoing += 1;
            if(!$tr.hasClass('table-children'))
            {
                if(taskStatus != 'cancel')
                {
                    checkedEstimate += Number($tr.data('estimate'));
                    checkedConsumed += Number($tr.data('consumed'));
                }
                if(taskStatus != 'cancel' && taskStatus != 'closed') checkedLeft += Number($tr.data('left'));
            }
        }
    });
    if(checkedTotal > 0)
    {
        summary = checkedSummary.replace('%total%', checkedTotal).replace('%wait%', checkedWait)
          .replace('%doing%', checkedDoing)
          .replace('%estimate%', checkedEstimate)
          .replace('%consumed%', checkedConsumed)
          .replace('%left%', checkedLeft);
        $summary.html(summary);
    }
}

$('#module' + moduleID).addClass('active');
$('#product' + productID).addClass('active');
