<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                              THIS PAGE WILL WORK ONLY WHEN IT IS CALLED DYNAMICALLY                               */
/*                                                                                                                   */
// File inclusions /**************************************************************************************************/
include_once './../../inc/includes.inc.php';              # Core
include_once './../../inc/functions_time.inc.php';        # Time management
include_once './../../inc/functions_mathematics.inc.php'; # Maths
include_once './../../inc/functions_numbers.inc.php';     # Number formatting
include_once './../../actions/admin/ban.act.php';         # Ban actions
include_once './../../lang/admin/ban.lang.php';     # Translations

// Throw a 404 if the page is being accessed directly
page_must_be_fetched_dynamically();

// Limit page access rights
user_restrict_to_moderators($lang);




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     BACK END                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Ban log details
$ban_log = admin_ban_logs_get(  form_fetch_element('log_id', 0) ,
                                form_fetch_element('ban_id', 0) ,
                                $lang                           );

// Exit in case of error
if(!$ban_log)
  exit(string_change_case(__('error'), 'uppercase').__(':', 0, 0, 1).__('admin_ban_logs_error_missing'));




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     FRONT END                                                     */
/*                                                                                                                   */
/******************************************************************************************************************/ ?>

<h2 class="align_center padding_bot">
  <?=__('admin_ban_logs_popin_title')?>
</h2>

<table>
  <tbody>

    <tr class="row_separator_dark">
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_user')?>
      </td>
      <td>
        <?=__link('todo_link?id='.$ban_log['user_id'], $ban_log['username'])?>
      </td>
    </tr>

    <tr>
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_start')?>
      </td>
      <td>
        <?=$ban_log['start']?>
      </td>
    </tr>

    <?php if(!$ban_log['is_banned']) { ?>
    <tr>
    <?php } else { ?>
    <tr class="row_separator_dark">
    <?php } ?>
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_end')?>
      </td>
      <td>
        <?=$ban_log['end']?>
      </td>
    </tr>

    <?php if(!$ban_log['is_banned']) { ?>

    <tr class="row_separator_dark">
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_unban')?>
      </td>
      <td>
        <?=$ban_log['unban']?>
      </td>
    </tr>

    <?php } ?>

    <tr>
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_days')?>
      </td>
      <td>
        <?=$ban_log['days'].__('day', $ban_log['days'], 1)?>
      </td>
    </tr>

    <tr>
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_served')?>
      </td>
      <td>
        <?=$ban_log['served'].__('day', $ban_log['served'], 1)?>
      </td>
    </tr>

    <tr class="row_separator_dark">
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_percent')?>
      </td>
      <td>
        <?=$ban_log['percent']?>
      </td>
    </tr>

    <tr>
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_banned_by')?>
      </td>
      <td>
        <?=__link('todo_link?id='.$ban_log['banned_by_id'], $ban_log['banned_by'])?>
      </td>
    </tr>

    <tr>
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_reason_en')?>
      </td>
      <td>
        <?=$ban_log['ban_reason_en']?>
      </td>
    </tr>

    <tr class="row_separator_dark">
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_reason_fr')?>
      </td>
      <td>
        <?=$ban_log['ban_reason_fr']?>
      </td>
    </tr>

    <?php if(!$ban_log['is_banned']) { ?>

    <tr>
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_unbanned_by')?>
      </td>
      <td>
        <?php if($ban_log['unbanned_by_id']) { ?>
        <?=__link('todo_link?id='.$ban_log['unbanned_by_id'], $ban_log['unbanned_by'])?>
        <?php } else { ?>
        -
        <?php } ?>
      </td>
    </tr>

    <?php if($ban_log['unbanned_by_id']) { ?>

    <tr>
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_unreason_en')?>
      </td>
      <td>
        <?=$ban_log['unban_reason_en']?>
      </td>
    </tr>

    <tr>
      <td class="align_right bold underlined nowrap">
        <?=__('admin_ban_logs_full_unreason_fr')?>
      </td>
      <td>
        <?=$ban_log['unban_reason_fr']?>
      </td>
    </tr>

    <?php } ?>

    <?php } ?>

  </tbody>
</table>