<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) === str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                 THIS PAGE CAN ONLY BE USED IN SPECIFIC SITUATIONS                                 */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Debug mode

// In ENV debug mode, print all environment variables in header
if($GLOBALS['dev_mode'] && $GLOBALS['env_debug_mode'])
{
  var_dump(array('GET', $_GET));
  var_dump(array('POST', $_POST));
  var_dump(array('FILES', $_FILES));
  var_dump(array('ENV', $_ENV));
  var_dump(array('REQUEST', $_REQUEST));
  var_dump(array('SESSION', $_SESSION));
  var_dump(array('COOKIE', $_COOKIE));
  var_dump(array('SERVER', $_SERVER));
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Restrictions and prerequisites

// If the user permission variables don't exist, stop here
if(!isset($is_admin) || !isset($is_moderator))
  exit(__('error_forbidden'));

// If the user doesn't have a set language, stop here
if(!isset($lang))
  exit(__('error_forbidden'));

// If the user doesn't have a mode, stop here
if(!isset($mode))
  exit(__('error_forbidden'));




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Default variable values (those are required by the header but it's fine if they're not set)

// Check whether the page exist in the user's current language - if not, throw an error message
$lang_error = (isset($page_lang) && !in_array($lang, $page_lang));

// If page names and URLs for user activity are not set, give them a default value
$activity_url     = (isset($page_url) && !isset($hidden_activity)) ? $page_url : '';
$activity_page_en = (isset($page_title_en) && !isset($hidden_activity)) ? $page_title_en : 'Unlisted page';
$activity_page_fr = (isset($page_title_fr) && !isset($hidden_activity)) ? $page_title_fr : 'Page non listée';




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                 WEBSITE SHUTDOWN                                                  */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Check whether a website update is in progress
$website_closed = $system_variables['website_is_closed'];

// If yes, close the website to anyone who's not an admin
if($website_closed  && !$is_admin)
{
  $logo_path = ($path) ? $path : '.';
  exit(__('error_website_update', preset_values: array($logo_path)));
}




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                      ACCOUNT                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Account actions

// Determine the current url
$current_url = $_SERVER['REQUEST_URI'];

// Determine the color theme to suggest
$color_theme = ($mode === "dark") ? "light" : "dark";



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Logout

if(isset($_POST['account_logout_go']))
  user_log_out();




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                      METRICS                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// View count of the current page

// We only go through this if the page name and url are set before the header
if(isset($page_url) && !isset($error_mode))
{
  // Sanitize the page's url
  $page_url_sanitized = sanitize($page_url, 'string');

  // Fetch current page's view count
  $qpageviews = query(" SELECT  stats_pages.view_count AS 'p_views'
                        FROM    stats_pages
                        WHERE   stats_pages.page_url = '$page_url_sanitized' " ,
                        description: "Fetch the current pageview count");

  // Define the current view count (used in the footer for metrics)
  $dpageviews       = query_row($qpageviews);
  $pageviews        = (isset($dpageviews["p_views"])) ? ($dpageviews["p_views"] + 1) : 1;
  $pageviews        = ($is_admin && !$GLOBALS['dev_mode']) ? ($pageviews - 1) : $pageviews;
  $pageviews        = ($pageviews < 1) ? 1 : $pageviews;
  $pageviews_exist  = isset($dpageviews["p_views"]);
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// User and guest activity

// Fetch and sanitize all the data required to track recent activity
$activity_timestamp         = sanitize(time(), 'int', 0);
$activity_language          = sanitize(user_get_language(), 'string');
$activity_mode              = sanitize(user_get_mode(), 'dark');
$activity_ip                = sanitize($_SERVER["REMOTE_ADDR"], 'string');
$activity_page_en_sanitized = sanitize($activity_page_en, 'string');
$activity_page_fr_sanitized = sanitize($activity_page_fr, 'string');
$activity_url_sanitized     = sanitize($activity_url, 'string');
$activity_user              = (user_is_logged_in()) ? sanitize(user_get_id(), 'int', 0) : 0;

// Logged in user activity
if($activity_user)
  query(" UPDATE  users
          SET     users.current_language      = '$activity_language'            ,
                  users.current_theme         = '$activity_mode'                ,
                  users.last_visited_at       = '$activity_timestamp'           ,
                  users.last_visited_page_en  = '$activity_page_en_sanitized'   ,
                  users.last_visited_page_fr  = '$activity_page_fr_sanitized'   ,
                  users.last_visited_url      = '$activity_url_sanitized'       ,
                  users.visited_page_count    = (users.visited_page_count + 1)  ,
                  users.current_ip_address    = '$activity_ip'
          WHERE   users.id                    = '$activity_user'                " ,
          description: "Update user activity statistics");

// Guest activity
else
{
  // Clean up older guest activity
  $guest_limit = sanitize(time() - 2500000, 'int', 0);
  query(" DELETE FROM users_guests
          WHERE       users_guests.last_visited_at < '$guest_limit' " ,
          description: "Clean old guest activity");

  // Check whether the guest already exists in the database
  $qguest   = query(" SELECT  users_guests.ip_address AS 'g_ip'
                      FROM    users_guests
                      WHERE   users_guests.ip_address LIKE '$activity_ip' " ,
                      description: "Update guest activity");

  // Create the guest if it does not exist
  if(!query_row_count($qguest))
  {
    // Generate a random username
    $guest_name_en = sanitize(user_generate_random_username('EN'), 'string');
    $guest_name_fr = sanitize(user_generate_random_username('FR'), 'string');

    // Create the guest
    query(" INSERT INTO users_guests
            SET         users_guests.ip_address                 = '$activity_ip'    ,
                        users_guests.randomly_assigned_name_en  = '$guest_name_en'  ,
                        users_guests.randomly_assigned_name_fr  = '$guest_name_fr'  " ,
            description: "Create a new activity entry for the guest");
  }

  // Update guest activity data
  query(" UPDATE  users_guests
          SET     users_guests.current_language     = '$activity_language'                  ,
                  users_guests.current_theme        = '$activity_mode'                      ,
                  users_guests.last_visited_at      = '$activity_timestamp'                 ,
                  users_guests.last_visited_page_en = '$activity_page_en_sanitized'         ,
                  users_guests.last_visited_page_fr = '$activity_page_fr_sanitized'         ,
                  users_guests.last_visited_url     = '$activity_url_sanitized'             ,
                  users_guests.visited_page_count   = (users_guests.visited_page_count + 1)
          WHERE   users_guests.ip_address           = '$activity_ip'                        " ,
          description: "Update guest activity data");
}




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                 PRIVATE MESSAGES                                                  */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Private messages

// Check only if the user is logged in
if($activity_user)
{
  $private_message_count      = isset($unread_pms) ? $unread_pms : 0;
  $private_message_count_css  = ($private_message_count && basename($_SERVER['PHP_SELF']) !== 'inbox.php') ? ' header_submenu_blink' : '';
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Administrative mail

// Check only if the user is moderator or above
if($is_moderator)
{
  // Fetch the result for display
  $admin_mail_count       = $system_variables['unread_mod_mail_count'];
  $admin_mail_count      += ($is_admin) ? $system_variables['unread_admin_mail_count'] : 0;
  $admin_mail_count_css = ($admin_mail_count && basename($_SERVER['PHP_SELF']) !== 'inbox.php') ? ' header_submenu_blink' : '';
}




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                  HEADER CONTENTS                                                  */
/*                                                                                                                   */
/*********************************************************************************************************************/


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Page title

// Set the current page title based on the user's language
$page_title = ($lang === 'EN' && isset($page_title_en)) ? $page_title_en : '';
$page_title = ($lang === 'FR' && isset($page_title_fr)) ? $page_title_fr : $page_title;

// If the current page is unnamed, simply call it NoBleme, else append NoBleme to it - or Devmode when in dev mode
$title_suffix = ($GLOBALS['dev_mode']) ? ' | Devmode' : ' | NoBleme';
$page_title   = ($page_title) ? sanitize_meta_tags($page_title.$title_suffix) : 'NoBleme.com';




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Page description

// If there is no description, use a default generic one
$page_description = (isset($page_description)) ? $page_description : $page_title_en." - See more by visiting this page on NoBleme.com";

// Shorten the description if it is too long
if(strlen($page_description) >= 155)
  $page_description = string_truncate($page_description, 150, '...');

// Make the page's description W3C meta tag compliant
$page_description = sanitize_meta_tags($page_description);

// Set the page description to default if it is too short
if(strlen($page_description) <= 25)
  $page_description = $page_title_en." - See more by visiting this page on NoBleme.com";




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Other settings

// Theme color
$theme_color  = ($mode === 'dark') ? '#B00B1E' : '#EEEEEE';




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                  FILE INCLUSIONS                                                  */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CSS stylesheets

// Include the default stylesheets (weird line breaks are for indentation)
$stylesheets = '<link rel="stylesheet" href="'.$path.'css/nobleme.css" type="text/css">';

// If extra stylesheets are set, add them to the list
if(isset($css))
{
  // Loop through all extra sheets and include them
  for($i = 0; $i < count($css); $i++)
    $stylesheets .= '
    <link rel="stylesheet" href="'.$path.'css/'.$css[$i].'.css" type="text/css">';
}

// Add light mode if required
if($mode === "light")
$stylesheets .= '
    <link rel="stylesheet" href="'.$path.'css/light_mode.css" type="text/css">';

// If extra stylesheets are added after light mode, add them to the list
if(isset($css_after_light))
{
  // Loop through all extra sheets and include them
  for($i = 0; $i < count($css_after_light); $i++)
    $stylesheets .= '
    <link rel="stylesheet" href="'.$path.'css/'.$css_after_light[$i].'.css" type="text/css">';
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// JavaScript files

// Include the default javascript files (weird line breaks are for indentation)
$javascripts = '
    <script src="'.$path.'js/common/nobleme.js"> </script>
    <script src="'.$path.'js/common/header.js"> </script>';

// If extra JS files are set, add them to the list
if (isset($js))
{
  // Loop through all files and include them
  for($i = 0; $i < count($js); $i++)
    $javascripts .= '
    <script src="'.$path.'js/'.$js[$i].'.js"> </script>';
}

// Add a line break at the end to preserve indentation
$javascripts .= '
';




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Favicon

// Determine which favicon to use
$favicon = ($GLOBALS['dev_mode']) ? 'favicon_dev.ico' : 'favicon.ico';




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                           DISPLAY THE HEADER AND MENUS                                            */
/*                                                                                                                   */
/******************************************************************************************************************/ ?>

<!DOCTYPE html>
<html lang="<?=string_change_case($lang,'lowercase')?>">
  <head>
    <title><?=$page_title?></title>
    <link rel="shortcut icon" href="<?=$path?>favicon.ico">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="index, follow">
    <meta name="description" content="<?=$page_description?>">
    <meta property="og:title" content="<?=$page_title?>">
    <meta property="og:description" content="<?=$page_description?>">
    <meta property="og:url" content="<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>">
    <meta property="og:site_name" content="NoBleme">
    <meta property="og:image" content="<?=$GLOBALS['website_url']?>img/common/logo_filled.png">
    <meta property="og:locale" content="en_US">
    <meta property="og:locale:alternate" content="fr_FR">
    <meta name="theme-color" content="<?=$theme_color?>">
    <link rel="icon" href="<?=$path.$favicon?>">
    <?=$stylesheets?>
    <?=$javascripts?>
  </head>

  <?php if(isset($this_page_is_a_404)) { ?>
  <body id="body" onload="this_page_is_a_404();">
  <?php } else if(isset($onload)) { ?>
  <body id="body" onload="<?=$onload?>">
  <?php } else { ?>
  <body id="body">
  <?php } ?>

  <?php if(!isset($hide_header)) { ?>

  <input id="root_path" type="hidden" class="hidden" value="<?=$path?>">

    <div id="popin_lost_access" class="popin_background">
      <div class="popin_body">
        <a class="popin_close" onclick="popin_close('popin_lost_access');">×</a>
        <h4>
          <?=__('users_lost_access_title')?>
        </h4>
        <p>
          <?=__('users_lost_access_body', 0, 0, 0, array($path))?>
        </p>
        <p>
          <?=__('users_lost_access_solution', 0, 0, 0, array($path))?>
        </p>
      </div>
    </div>


<?php ############################################# WEBSITE UPDATE ################################################# ?>

    <?php if($website_closed) { ?>

    <div class="header_infobar" id="header_topmenu_closed">
      <?=__link('pages/dev/settings', __('website_closed'), 'header_infobar_link', 1, $path);?>
    </div>

    <?php } ?>


<?php /* ############################################## TOP MENU ################### */ if(!isset($_GET["popup"])) { ?>

    <div class="header_topbar">

      <div class="header_topmenu width_50">

        <div id="header_titles" class="header_topmenu_zone">

          <div class="header_topmenu_title" id="header_menu_title_nobleme" onclick="toggle_header_menu('nobleme', 1);">
            <?=__('menu_top_nobleme')?>
          </div>

          <div class="header_topmenu_title" id="header_menu_title_pages" onclick="toggle_header_menu('pages', 1);">
            <?=__('menu_top_pages')?>
          </div>

          <div class="header_topmenu_title" id="header_menu_title_social" onclick="toggle_header_menu('social', 1);">
            <?=__('menu_top_social')?>
          </div>

        </div>

        <div class="header_topmenu_zone">

          <?php if(user_is_logged_in() && $private_message_count && basename($_SERVER['PHP_SELF']) !== 'inbox.php') { ?>
          <img id="header_topmenu_account_icon" class="header_topmenu_icon header_topmenu_mail" src="<?=$path?>img/icons/login_mail.svg" alt="A" title="<?=string_change_case(__('account'), 'initials');?>" onclick="toggle_header_menu('account');">
          <?php } else { ?>
          <img id="header_topmenu_account_icon" class="header_topmenu_icon header_topmenu_account" src="<?=$path?>img/icons/login.svg" alt="A" title="<?=string_change_case(__('account'), 'initials');?>" onclick="toggle_header_menu('account');">
          <?php } ?>

          <?php if($is_moderator && $admin_mail_count && basename($_SERVER['PHP_SELF']) !== 'inbox.php') { ?>
          <img id="header_topmenu_admin_icon" class="header_topmenu_icon header_topmenu_mail" src="<?=$path?>img/icons/login_mail.svg" alt="M" title="<?=string_change_case(__('administration'), 'initials');?>" onclick="toggle_header_menu('admin');">
          <?php } else if($is_moderator) { ?>
          <img id="header_topmenu_admin_icon" class="header_topmenu_icon header_topmenu_panel" src="<?=$path?>img/icons/admin_panel.svg" alt="M" title="<?=string_change_case(__('administration'), 'initials');?>" onclick="toggle_header_menu('admin');">
          <?php } ?>

          <form id="account_mode" method="post">
            <input type="hidden" name="account_change_mode" value="change_mode">
            <?php if($mode === "dark") { ?>
            <img class="header_topmenu_icon header_topmenu_mode" src="<?=$path?>img/icons/light_mode.svg" alt="L" title="<?=string_change_case(__('mode_light'), 'initials');?>" onclick="user_change_mode();">
            <?php } else { ?>
            <img class="header_topmenu_icon header_topmenu_mode" src="<?=$path?>img/icons/dark_mode.svg" alt="D" title="<?=string_change_case(__('mode_dark'), 'initials');?>" onclick="user_change_mode();">
            <?php } ?>
          </form>

          <form id="account_language" method="post">
            <input type="hidden" name="account_change_language" value="change_language">
            <?php if($lang === 'FR') { ?>
            <img class="header_topmenu_icon header_topmenu_flag" src="<?=$path?>img/icons/lang_en.png" alt="EN" title="<?=string_change_case(__('english'), 'initials')?>" onclick="user_change_language();">
            <?php } else { ?>
            <img class="header_topmenu_icon header_topmenu_flag" src="<?=$path?>img/icons/lang_fr.png" alt="FR" title="<?=string_change_case(__('french'), 'initials')?>" onclick="user_change_language();">
            <?php } ?>
          </form>

        </div>
      </div>


<?php ############################################ SUBMENU: NOBLEME ################################################ ?>

      <div class="header_submenu header_submenu_5" id="header_submenu_nobleme">

        <div class="header_submenu_column desktop_wide">
          &nbsp;
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('nobleme.com')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('index', __('submenu_nobleme_homepage'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/nobleme/activity', __('submenu_nobleme_activity'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/nobleme/follow', __('submenu_nobleme_follow'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/doc/nobleme', __('submenu_nobleme_what_is'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_nobleme_support')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/doc/coc', __('submenu_nobleme_coc'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/doc/privacy', __('submenu_nobleme_privacy'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/doc/data', __('submenu_nobleme_personal_data'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/doc/legal', __('submenu_nobleme_legal'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/users/admins', __('submenu_nobleme_staff'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/messages/admins', __('submenu_nobleme_contact_admin'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_nobleme_dev')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/doc/dev', __('submenu_nobleme_behind_scenes'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/blog_list', __('submenu_nobleme_devblog'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('api/doc/intro', __('submenu_nobleme_api'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/tasks/list', __('submenu_nobleme_todolist'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/tasks/roadmap', __('submenu_nobleme_roadmap'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/tasks/proposal', __('submenu_nobleme_report_bug'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

      </div>


<?php ############################################# SUBMENU: PAGES ################################################# ?>

      <div class="header_submenu header_submenu_6" id="header_submenu_pages">

        <div class="header_submenu_column desktop_wide">
          &nbsp;
        </div>
        <div class="header_submenu_column desktop_wide">
          &nbsp;
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_pages_compendium')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/compendium/index', __('submenu_pages_compendium_index'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/compendium/mission_statement', __('submenu_pages_compendium_faq'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/compendium/page_list', __('submenu_pages_compendium_pages'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/compendium/page_type_list', __('submenu_pages_compendium_types'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/compendium/category_list', string_change_case(__('category+'), 'initials'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/compendium/cultural_era_list', __('submenu_pages_compendium_eras'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/compendium/random_page', __('submenu_pages_compendium_random'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/compendium/random_image', __('submenu_pages_compendium_image'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_pages_politics')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/politics/contramanifesto', __('submenu_pages_politics_manifesto'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/politics/contrapolitics', __('submenu_pages_politics_faq'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

      </div>


<?php ############################################## SUBMENU: SOCIAL ############################################## ?>

      <div class="header_submenu header_submenu_5" id="header_submenu_social">

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('user+')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/users/online', __('submenu_nobleme_online'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/users/list', __('submenu_nobleme_userlist'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/users/admins', __('submenu_nobleme_staff'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_social_quotes')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/quotes/list', __('submenu_social_quotes_list'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/quotes/random', __('submenu_social_quotes_random'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/quotes/submit', __('submenu_social_quotes_submit'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_social_platforms')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/social/irc', __('submenu_social_platforms_irc'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/social/irc?browser', __('submenu_social_platforms_irc_web'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/social/irc?channels', __('submenu_social_platforms_irc_chans'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/social/discord', __('submenu_social_platforms_discord'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/social/others', __('submenu_social_platforms_others'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_social_meetups')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/meetups/list', __('submenu_social_meetups_list'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/meetups/organize', __('submenu_social_meetups_host'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_social_games')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/social/games', __('submenu_social_games_nobleme'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/social/games_history', __('submenu_social_games_memories'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

      </div>


<?php ############################################ SUBMENU: ACCOUNT ################################################ ?>

      <div class="header_submenu header_submenu_5" id="header_submenu_account">
      <?php if($is_logged_in) { ?>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_user_settings')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/account/settings_privacy', __('submenu_user_settings_privacy'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/account/settings_nsfw', __('submenu_user_settings_nsfw'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_user_edit')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/account/settings_email', __('submenu_user_edit_email'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/account/settings_password', __('submenu_user_edit_password'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/messages/admins?username', __('submenu_user_edit_username'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/messages/admins?delete', __('submenu_user_edit_delete'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_user_pms')?>
          </div>
          <div class="header_submenu_item<?=$private_message_count_css?>">
            <?=__link('pages/messages/inbox', __('submenu_user_pms_inbox'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/messages/outbox', __('submenu_user_pms_outbox'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/messages/write', __('submenu_user_pms_write'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/messages/admins', __('submenu_nobleme_contact_admin'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_user_profile')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/users/profile', __('submenu_user_profile_self'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/users/profile_edit', __('submenu_user_profile_edit'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=sanitize_output(user_get_username())?>
          </div>
          <div class="header_submenu_item">
            <form id="account_logout" method="post">
              <input type="hidden" name="account_logout_go" value="logout">
              <a class="header_submenu_link" href="#" onclick="user_logout();"><?=__('submenu_user_logout_logout')?></a>
            </form>
          </div>
        </div>

        <?php } else if(!$is_ip_banned) { ?>

        <div class="header_submenu_fullwidth">

          <div class="width_30 bigpadding_top hugepadding_bot">

          <h1 class="align_center padding_bot header_login_form_title">
            <?=string_change_case(__('login'), 'initials')?>
          </h1>

          <h5 id="login_form_error" class="align_center bigpadding_bot padding_top underlined dowrap hidden">
            &nbsp;
          </h5>

          <form method="POST" name="login_form" onsubmit="user_login_attempt('<?=$path?>pages/account/login_attempt', <?=$GLOBALS['dev_mode']?>); return false;">
            <fieldset>

              <div class="smallpadding_bot">
                <label class="login_form_text" id="label_login_form_username" for="login_form_username"><?=string_change_case(__('username'), 'initials')?></label>
                <input id="login_form_username" name="login_form_username" class="indiv" type="text" value="">
              </div>

              <div class="padding_bot">
                <label class="login_form_text" id="label_login_form_password" for="login_form_password"><?=string_change_case(__('password'), 'initials')?> </label>
                <input id="login_form_password" name="login_form_password" class="indiv" type="password" value="">
              </div>

              <div class="padding_bot">
                <input id="login_form_remember" name="login_form_remember" type="checkbox" checked>
                <label class="label_inline login_form_text" for="login_form_remember"><?=__('login_form_form_remember')?></label>
              </div>

              <input type="submit" class="button_chain" value="<?=string_change_case(__('login'), 'initials')?>">

              <button type="button" onclick="window.location = '<?=$path?>pages/account/register';"><?=__('login_form_form_register')?></button>

            </fieldset>
          </form>

        </div>

        </div>

        <?php } else { ?>

        <div class="header_submenu_fullwidth dowrap text_light">

          <div class="width_30 bigpadding_top hugepadding_bot">

            <h1 class="align_center padding_bot">
              <?=__('users_ip_banned_title')?>
            </h1>

            <?=__('users_ip_banned_body')?>

          </div>

        </div>

        <?php } ?>

      </div>



<?php ############################################# SUBMENU: ADMIN ################################################# ?>

      <?php if($is_moderator) { ?>
      <?php if($is_admin) { ?>
      <div class="header_submenu header_submenu_5" id="header_submenu_admin">
      <?php } else { ?>
      <div class="header_submenu header_submenu_6" id="header_submenu_admin">
        <div class="header_submenu_column desktop_wide">
          &nbsp;
        </div>
        <div class="header_submenu_column desktop_wide">
          &nbsp;
        </div>
        <?php } ?>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_admin_activity')?>
          </div>
          <div class="header_submenu_item<?=$admin_mail_count_css?>">
            <?=__link('pages/admin/inbox', __('submenu_admin_inbox'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/nobleme/activity?mod', __('submenu_admin_modlogs'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/users/admins', __('submenu_nobleme_staff'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_admin_users')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/admin/ban', __('submenu_admin_ban'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/admin/user_rename', __('submenu_admin_username'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/admin/user_password', __('submenu_admin_password'), 'header_submenu_link', 1, $path);?>
          </div>
          <?php if($is_admin) { ?>
          <div class="header_submenu_item">
            <?=__link('pages/admin/user_rights', __('submenu_admin_rights'), 'header_submenu_link', 1, $path);?>
          </div>
          <?php } ?>
          <div class="header_submenu_item">
            <?=__link('pages/admin/user_deactivate', __('submenu_admin_deactivate'), 'header_submenu_link', 1, $path);?>
          </div>
          <?php } if($is_moderator && !$is_admin) { ?>
          <div class="header_submenu_item">
            <?=__link('pages/admin/stats_users', __('submenu_admin_account_settings'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/admin/stats_guests', __('submenu_admin_guest_settings'), 'header_submenu_link', 1, $path);?>
          </div>
          <?php } if($is_moderator) { ?>
          <div class="header_submenu_item">
            <?=__link('pages/admin/stats_doppelganger', __('submenu_admin_doppelganger'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <?php if($is_admin) { ?>
        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_admin_website')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/settings', __('submenu_admin_settings'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/irc_bot', __('submenu_admin_ircbot'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/discord', __('submenu_admin_discord'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/scheduler', __('submenu_admin_scheduler'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/versions', __('submenu_admin_versions'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/queries', __('submenu_admin_sql'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('stats')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/admin/stats_metrics', __('submenu_admin_metrics'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/admin/stats_views', __('submenu_admin_pageviews'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/admin/stats_users', string_change_case(__('user_acc+'), 'initials'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/admin/stats_guests', __('submenu_admin_stats_guests'), 'header_submenu_link', 1, $path);?>
          </div>
        </div>

        <div class="header_submenu_column">
          <div class="header_submenu_title">
            <?=__('submenu_admin_doc')?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/doc_snippets', __('submenu_admin_doc_snippets'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/doc_functions', __('submenu_admin_doc_functions'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/doc_css_palette', __('submenu_admin_doc_css'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/doc_js_toolbox', __('submenu_admin_doc_js'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/doc_workflow', __('submenu_admin_doc_workflow'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('pages/dev/translations', __('submenu_admin_doc_duplicates'), 'header_submenu_link', 1, $path);?>
          </div>
          <?php if($GLOBALS['dev_mode']) { ?>
          <div class="header_submenu_item">
            <?=__link('tests', __('submenu_admin_local_tests'), 'header_submenu_link', 1, $path);?>
          </div>
          <div class="header_submenu_item">
            <?=__link('fixtures', __('submenu_admin_local_fixtures'), 'header_submenu_link', 1, $path);?>
          </div>
          <?php } ?>
        </div>
        <?php } ?>

      </div>
      <?php } ?>

    </div>
    <?php } ?>


<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                   //
//                                             END HEADER AND BEGIN PAGE                                             //
//                                                                                                                   //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>

    <div class="header_main_page">

      <?php } if($lang_error) { ?>

      <div class="align_center monospace bigpadding_bot">
        <?=__('header_language_error');?>
      </div>

      <?php } ?>