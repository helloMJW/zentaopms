<?php
/**
 * The edit view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: edit.html.php 4645 2013-04-11 08:32:09Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div class='main-content' id='mainContent'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title, '', 'class="story-title"');?>
        <small><?php echo $lang->arrow . ' ' . $lang->story->edit;?></small>
      </h2>
      <div class="pull-right btn-toolbar">
        <?php echo html::submitButton($lang->save)?>
      </div>
    </div>
    <div class='main-row'>
      <div class='main-col col-8'>
        <div class='cell'>
          <div class='form-group'>
            <div class='input-group'>
              <input type='hidden' id='color' name='color' data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='<?php echo $lang->story->colorTag ?>' value='<?php echo $story->color ?>' data-update-text='#title, .story-title'>
              <?php echo html::input('title', $story->title, 'class="form-control disabled" disabled="disabled" autocomplete="off"');?>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendSpec;?></div>
            <div class='detail-content article-content'><?php echo $story->spec;?></div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->verify;?></div>
            <div class='detail-content article-content'><?php echo $story->verify;?></div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->comment;?></div>
            <div class='form-group'>
              <?php echo html::textarea('comment', '', "rows='5' class='form-control'");?>
            </div>
          </div>
          <div id='linkStoriesBOX'><?php echo html::hidden('linkStories', $story->linkStories);?></div>
          <div id='childStoriesBOX'><?php echo html::hidden('childStories', $story->childStories);?></div>
          <div class='actions actions-form text-center'>
            <?php 
            echo html::hidden('lastEditedDate', $story->lastEditedDate);
            echo html::submitButton($lang->save, '', 'btn btn-wide btn-primary');
            echo html::linkButton($lang->goback, $app->session->storyList ? $app->session->storyList : inlink('view', "storyID=$story->id"), 'self', '', 'btn btn-wide');
            ?>
          </div>
          <hr class='small' />
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendBasicInfo;?></div>
            <table class='table table-form'>
              <tr>
                <th class='w-80px'><?php echo $lang->story->product;?></th>
                <td>
                  <div class='input-group'>
                    <?php echo html::select('product', $products, $story->product, "onchange='loadProduct(this.value);' class='form-control chosen control-product'");?>
                    <span class='input-group-addon fix-border fix-padding'></span>
                    <?php if($product->type != 'normal') echo html::select('branch', $branches, $story->branch, "onchange='loadBranch();' class='form-control chosen control-branch'");?>
                  </div>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->story->module;?></th>
                <td>
                  <div class='input-group' id='moduleIdBox'>
                  <?php
                  echo html::select('module', $moduleOptionMenu, $story->module, "class='form-control chosen'");
                  if(count($moduleOptionMenu) == 1)
                  {
                      echo "<span class='input-group-addon'>";
                      echo html::a($this->createLink('tree', 'browse', "rootID=$story->product&view=story&currentModuleID=0&branch=$story->branch"), $lang->tree->manage, '_blank');
                      echo '&nbsp; ';
                      echo html::a("javascript:loadProductModules($story->product)", $lang->refresh);
                      echo '</span>';
                  }
                  ?>
                  </div>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->story->plan;?></th>
                <td>
                  <div class='input-group' id='planIdBox'>
                  <?php $multiple = ($this->session->currentProductType != 'normal' and empty($story->branch)) ? true : false;?>
                  <?php echo html::select($multiple ? 'plan[]' : 'plan', $plans, $story->plan, "class='form-control chosen'" . ($multiple ? ' multiple' : ''));
                  if(count($plans) == 1) 
                  {
                      echo "<span class='input-group-addon'>";
                      echo html::a($this->createLink('productplan', 'create', "productID=$story->product&branch=$story->branch"), $lang->productplan->create, '_blank');
                      echo html::a("javascript:loadProductPlans($story->product)", $lang->refresh);
                      echo '</span>';
                  }
                  ?>
                  </div>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->story->source;?></th>
                <td><?php echo html::select('source', $lang->story->sourceList, $story->source, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->sourceNote;?></th>
                <td><?php echo html::input('sourceNote', $story->sourceNote, "class='form-control' autocomplete='off'");?>
              </td>
              </tr>
              <tr>
                <th><?php echo $lang->story->status;?></th>
                <td><span class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></span></td>
              </tr>
              <?php if($story->status != 'draft'):?>
              <tr>
                <th><?php echo $lang->story->stage;?></th>
                <td><?php echo html::select('stage', $lang->story->stageList, $story->stage, "class='form-control chosen'");?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->story->pri;?></th>
                <td><?php echo html::select('pri', $lang->story->priList, $story->pri, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->estimate;?></th>
                <td><?php echo html::input('estimate', $story->estimate, "class='form-control' autocomplete='off'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->keywords;?></th>
                <td><?php echo html::input('keywords', $story->keywords, "class='form-control' autocomplete='off'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->mailto;?></th>
                <td>
                  <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $story->mailto), "class='form-control' multiple");?>
                  <?php echo $this->fetch('my', 'buildContactLists')?>
                </td>
              </tr>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendLifeTime;?></div>
            <table class='table table-form'>
              <tr>
                <th class='w-70px'><?php echo $lang->story->openedBy;?></th>
                <td><?php echo $users[$story->openedBy];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->assignedTo;?></th>
                <td><?php echo html::select('assignedTo', $users, $story->assignedTo, 'class="form-control chosen"');?></td>
              </tr>
              <?php if($story->reviewedBy):?>
              <tr>
                <th><?php echo $lang->story->reviewedBy;?></th>
                <td><?php echo html::select('reviewedBy[]', $users, str_replace(' ', '', $story->reviewedBy), 'class="form-control chosen" multiple');?></td>
              </tr>
              <?php endif;?>
              <?php if($story->status == 'closed'):?>
              <tr>
                <th><?php echo $lang->story->closedBy;?></th>
                <td><?php echo html::select('closedBy', $users, $story->closedBy, 'class="form-control chosen"');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->closedReason;?></th>
                <td><?php echo html::select('closedReason', $lang->story->reasonList, $story->closedReason, "class='form-control'  onchange='setStory(this.value)'");?></td>
              </tr>
              <?php endif;?>
            </table>
          </div>
    
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendMisc;?></div>
            <table class='table table-form'>
              <?php if($story->status == 'closed'):?>
              <tr id='duplicateStoryBox'>
                <th class='w-70px'><?php echo $lang->story->duplicateStory;?></th>
                <td><?php echo html::input('duplicateStory', $story->duplicateStory, "class='form-control' autocomplete='off'");?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th class='w-70px'><?php echo $lang->story->linkStories;?></th>
                <td><?php echo html::a($this->createLink('story', 'linkStory', "storyID=$story->id&type=linkStories", '', true), $lang->story->linkStory, '', "data-toggle='modal' data-type='iframe' data-width='95%'");?></td>
              </tr>
              <tr>
                <th></th>
                <td>
                  <?php if($story->linkStories):?>
                  <ul class='list-unstyled' id='linkStoriesBox'>
                  <?php
                  $linkStories = explode(',', $story->linkStories);
                  foreach($linkStories as $linkStoryID)
                  {
                      if(isset($story->extraStories[$linkStoryID]))
                      {
                          echo '<li>';
                          echo html::a(inlink('view', "storyID=$linkStoryID"), "#$linkStoryID " . $story->extraStories[$linkStoryID], '_blank');
                          echo html::a("javascript:unlinkStory($story->id, \"linkStories\", $linkStoryID)", '<i class="icon-remove"></i>', '', "title='{$lang->unlink}' style='float:right'");
                          echo '</li>';
                      }
                  }
                  ?>
                  </ul>
                  <?php endif;?>
                </td>
              </tr>
              <?php if($story->status == 'closed'):?>
              <tr class='text-top'>
                <th><?php echo $lang->story->childStories;?></th>
                <td>
                  <?php echo html::a($this->createLink('story', 'linkStory', "storyID=$story->id&type=childStories", '', true), $lang->story->linkStory, '', "data-toggle='modal' data-type='iframe' data-width='95%'");?>
                  <ul class='list-unstyled' id='childStoriesBox'>
                  <?php
                  $childStories = explode(',', $story->childStories);
                  foreach($childStories as $childStoryID)
                  {
                      if(isset($story->extraStories[$childStoryID]))
                      {
                          echo '<li>';
                          echo html::a(inlink('view', "storyID=$childStoryID"), "#$childStoryID" . $story->extraStories[$childStoryID], '_blank');
                          echo html::a("javascript:unlinkStory($story->id, \"childStories\", $childStoryID)", '<i class="icon-remove"></i>', '', "title='{$lang->unlink}' style='float:right'");
                          echo '</li>';
                      }
                  }
                  ?>
                  </ul>
                </td>
              </tr>
              <?php endif;?>
           </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
