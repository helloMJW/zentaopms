<?php
/**
 * The view file of view method of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: view.html.php 4955 2013-07-02 01:47:21Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php if(!$todo->private or ($todo->private and $todo->account == $app->user->account)):?>
<div class='modal-content'>
  <div class="modal-header">
    <h4 class='modal-title pull-left'><?php echo html::a($this->createLink('todo', 'view', 'todo=' . $todo->id), "TODO #{$todo->id} {$todo->name}");?></h4>
  </div>
  <div class='main-row'>
    <div class='main-col col-8'>
      <div class='cell'>
        <div class='detail'>
          <div class='detail-title'>
            <?php
            echo $lang->todo->desc;
            if($todo->type == 'bug')   echo html::a($this->createLink('bug',   'view', "id={$todo->idvalue}"), '  BUG#'   . $todo->idvalue);
            if($todo->type == 'task')  echo html::a($this->createLink('task',  'view', "id={$todo->idvalue}"), '  TASK#'  . $todo->idvalue);
            if($todo->type == 'story') echo html::a($this->createLink('story', 'view', "id={$todo->idvalue}"), '  STORY#' . $todo->idvalue);
            ?>
          </div>
          <div class='detail-content'><?php echo $todo->desc;?></div>
        </div>
        <div class='detail'><?php include '../../common/view/action.html.php';?></div>
      </div>
    </div>
    <div class='side-col col-4'>
      <div class='cell'>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->todo->legendBasic;?></div>
          <div class='detail-content'>
            <table class='table table-data'>
              <tr>
                <th><?php echo $lang->todo->pri;?></th>
                <td><?php echo $lang->todo->priList[$todo->pri];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->todo->status;?></th>
                <td class='todo-<?php echo $todo->status?>'><?php echo $lang->todo->statusList[$todo->status];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->todo->type;?></th>
                <td><?php echo $lang->todo->typeList[$todo->type];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->todo->account;?></th>
                <td><?php echo zget($users, $todo->account);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->todo->date;?></th>
                <td><?php echo $todo->date == '20300101' ? $lang->todo->periods['future'] : formatTime($todo->date);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->todo->beginAndEnd;?></th>
                <td><?php if(isset($times[$todo->begin])) echo $times[$todo->begin]; if(isset($times[$todo->end])) echo ' ~ ' . $times[$todo->end];?></td>
              </tr>
              <?php if(isset($todo->assignedTo)):?>
              <tr>
                <th><?php echo $lang->todo->assignTo;?></th>
                <td><?php echo $todo->assignedTo;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->todo->assignTo . $lang->todo->date;?></th>
                <td><?php echo formatTime($todo->assignedDate, DT_DATE1);?></td>
              </tr>
              <?php endif;?>
            </table>
          </div>
        </div>
        <?php if($todo->cycle):?>
        <?php $todo->config = json_decode($todo->config);?>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->todo->cycle;?></div>
          <div class='detail-content'>
            <table class='table table-data'>
              <tr>
                <th class='w-80px'><?php echo $lang->todo->beginAndEnd?></th>
                <td><?php echo $todo->config->begin . " ~ " . $todo->config->end;?></td>
              </tr>
              <tr>
                <th class='w-80px text-top'><?php echo $lang->todo->cycleConfig?></th>
                <td>
                  <?php
                  if($todo->config->type == 'day')
                  {
                      echo $lang->todo->every . $todo->config->day . $lang->day;
                  }
                  elseif($todo->config->type == 'week')
                  {
                      foreach(explode(',', $todo->config->week) as $week) echo $lang->todo->dayNames[$week] . ' ';
                  }
                  elseif($todo->config->type == 'month')
                  {
                      foreach(explode(',', $todo->config->month) as $month) echo $month . ' ';
                  }
                  echo '<br />';
                  if($todo->config->beforeDays) printf($lang->todo->lblBeforeDays, $todo->config->beforeDays);
                  ?>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class='modal-footer'>
    <?php
    if($todo->account == $app->user->account)
    {
        if($todo->status != 'closed') echo html::a($this->createLink('todo', 'edit', "todoID=$todo->id"), "<i class='icon icon-edit'></i>", '', "title='{$lang->todo->edit}' class='btn showinonlybody'");
        if($todo->status == 'done' || $todo->status == 'closed') echo html::a($this->createLink('todo', 'activate', "todoID=$todo->id"), "<i class='icon icon-magic'></i>", 'hiddenwin', "title='{$lang->todo->activate}' class='btn showinonlybody'");
        if($todo->status == 'done') echo html::a($this->createLink('todo', 'close', "todoID=$todo->id"), "<i class='icon icon-off'></i>", 'hiddenwin', "title='{$lang->todo->close}' class='btn showinonlybody'");
        echo html::a($this->createLink('todo', 'delete', "todoID=$todo->id"), "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->todo->delete}' class='btn showinonlybody'");

        echo html::a('#commentBox', '<i class="icon-chat-line"></i>', '', "title='{$lang->comment}' onclick='setComment()' class='btn'");
    }

    if($this->session->todoList)
    {
        $browseLink = $this->session->todoList;
    }
    elseif($todo->account == $app->user->account)
    {
        $browseLink = $this->createLink('my', 'todo');
    }
    else
    {
        $browseLink = $this->createLink('user', 'todo', "account=$todo->account");
    }

    if($todo->status != 'done' && $todo->status != 'closed')
    {
        echo "<div class='btn-group dropup'>";
        echo html::a($this->createLink('todo', 'finish', "id=$todo->id", 'html', true), "<i class='icon icon-checked'></i>", 'hiddenwin', "title='{$lang->todo->finish}' class='btn showinonlybody btn-success'");
        $createStoryPriv = common::hasPriv('story', 'create');
        $createTaskPriv  = common::hasPriv('task', 'create');
        $createBugPriv   = common::hasPriv('bug', 'create');
        if($createStoryPriv or $createTaskPriv or $createBugPriv)
        {
            $isonlybody = isonlybody();
            unset($_GET['onlybody']);
            echo "<button type='button' class='btn btn-success dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
            echo "<ul class='dropdown-menu pull-right' role='menu'>";
            if($createStoryPriv) echo '<li>' . html::a('###', $lang->todo->reasonList['story'], '', "data-toggle='modal' data-target='#productModal' data-moveable='true' data-position='193px' id='toStoryLink'") . '</li>';
            if($createTaskPriv)  echo '<li>' . html::a('###', $lang->todo->reasonList['task'], '', "data-toggle='modal' data-target='#projectModal' data-moveable='true' data-position='193px' id='toTaskLink'") . '</li>';
            if($createBugPriv)   echo '<li>' . html::a('###', $lang->todo->reasonList['bug'], '', "data-toggle='modal' data-target='#productModal' data-moveable='true' data-position='193px' id='toBugLink'") . '</li>';
            echo "</ul>";
            if($isonlybody) $_GET['onlybody'] = 'yes';
        }
        echo "</div>";
    }

    common::printRPN($browseLink);
    ?>
    <div id='commentBox' class='hide'>
      <h4 class='text-left'><?php echo $lang->comment;?></h2>
      <form method='post' action='<?php echo $this->createLink('action', 'comment', "objectType=todo&objectID=$todo->id")?>' target='hiddenwin'>
        <div class="form-group"><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></div>
        <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="projectModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><i class="icon-file-text"></i> <?php echo $lang->project->selectProject;?></h4>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('project', $projects, '', "class='form-control chosen'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->todo->reasonList['task'], "id='toTaskButton'", 'btn btn-primary');?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="productModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><i class="icon-file-text"></i> <?php echo $lang->product->select;?></h4>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('product', $products, '', "class='form-control chosen'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->todo->reasonList['story'], "id='toStoryButton'", 'btn btn-primary');?></span>
          <span class='input-group-btn'><?php echo html::commonButton($lang->todo->reasonList['bug'], "id='toBugButton'", 'btn btn-primary');?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('todoID', $todo->id);?>
<?php else:?>
<?php echo $lang->todo->thisIsPrivate;?>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
