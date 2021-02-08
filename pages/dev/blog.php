

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                       SETUP                                                       */
/*                                                                                                                   */
// File inclusions /**************************************************************************************************/
include_once './../../inc/includes.inc.php';        # Core
include_once './../../inc/functions_time.inc.php';  # Time management
include_once './../../actions/dev.act.php';         # Actions
include_once './../../lang/dev.lang.php';           # Translations

// Page summary
$page_lang        = array('FR', 'EN');
$page_url         = "pages/dev/blog";
$page_title_en    = "Devblog: ";
$page_title_fr    = "Devblog : ";
$page_description = "Development blog:";




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     BACK END                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Fetch the devblog

// Check if the user is the maintainer
$is_maintainer = user_is_maintainer();

// Grab the devblog's id
$blog_id = form_fetch_element('id', request_type: 'GET');

// Redirect if no ID is provided
if(!$blog_id)
  exit(header("Location: ".$path."pages/dev/blog_list"));

// Fetch the devblog's contents
$devblog_data = dev_blogs_get($blog_id);

// Redirect if the devblog does not exist
if(!isset($devblog_data))
  exit(header("Location: ".$path."pages/dev/blog_list"));

// Redirect if the devblog is deleted and the user isn't the maintainer
if(!$is_maintainer && $devblog_data['deleted'])
  exit(header("Location: ".$path."pages/dev/blog_list"));

// Redirect if the devblog doesn't exist in the current language
if(!$is_maintainer && !$devblog_data['title'])
  exit(header("Location: ".$path."pages/dev/blog_list"));

// Hide from activity if the maintainer is looking at a deleted devblog
if($devblog_data['deleted'])
  $hidden_activity = 1;

// Update the page summary
$page_url         .= "?id=".$blog_id;
$page_title_en    .= ($devblog_data['title_en']) ? $devblog_data['title_en'] : $devblog_data['title_fr'];
$page_title_fr    .= ($devblog_data['title_fr']) ? $devblog_data['title_fr'] : $devblog_data['title_en'];
$page_description .= ($devblog_data['title_en']) ? $devblog_data['title_en'] : $devblog_data['title_fr'];



/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     FRONT END                                                     */
/*                                                                                                                   */
if(!page_is_fetched_dynamically()) { /***************************************/ include './../../inc/header.inc.php'; ?>

<div class="width_50">

  <h1>
    <?=__link('pages/dev/blog_list', __('dev_blog_title'), 'text_red noglow')?>
  </h1>

  <h5>
    <?=$devblog_data['title']?>
  </h5>

  <span class="monospace"><?=__('dev_blog_published', preset_values: array($devblog_data['date'], $devblog_data['date_since']))?></span>

  <div class="align_justify padding_top hugepadding_bot">
    <?=$devblog_data['body']?>
  </div>

  <?php if($devblog_data['prev_id'] || $devblog_data['next_id']) { ?>
  <div class="flexcontainer">
    <?php if($devblog_data['prev_id']) { ?>
    <div class="align_center" style="flex:5">
      <span class="bold"><?=__('dev_blog_previous')?></span><br>
      <?=__link('pages/dev/blog?id='.$devblog_data['prev_id'], $devblog_data['prev_title'])?>
    </div>
    <?php } if($devblog_data['prev_id'] && $devblog_data['next_id']) { ?>
    <div class="flex">
      &nbsp
    </div>
    <?php } if($devblog_data['next_id']) { ?>
    <div class="align_center" style="flex:5">
      <span class="bold"><?=__('dev_blog_next')?></span><br>
      <?=__link('pages/dev/blog?id='.$devblog_data['next_id'], $devblog_data['next_title'])?>
    </div>
    <?php } ?>
  </div>
  <?php } ?>

</div>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                    END OF PAGE                                                    */
/*                                                                                                                   */
/*****************************************************************************/ include './../../inc/footer.inc.php'; }
