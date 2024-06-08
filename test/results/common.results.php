<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) === str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                               inc/activity.inc.php                                                */
/*                                                                                                                   */
/*                                                   ACTIVITY LOGS                                                   */
/*                                                                                                                   */
/******************************************************************************************************************/ ?>

<tr class="row_separator_dark">
  <td class="nowrap cellnoaltc">
    activity.inc.php
  </td>
  <td class="nowrap">
    logs_activity_parse
  </td>
  <td class="<?=$test_style['activity_parse']?> text_white bold spaced">
    <?=$test_results['activity_parse']['explanation']?>
  </td>
</tr>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                inc/bbcodes.inc.php                                                */
/*                                                                                                                   */
/*                                                      BBCODES                                                      */
/*                                                                                                                   */
/******************************************************************************************************************/ ?>

<tr>
  <td class="nowrap cellnoaltc" rowspan="4">
    bbcodes.inc.php
  </td>
  <td class="nowrap">
    bbcodes
  </td>
  <td class="<?=$test_style['bbcodes']?> text_white bold spaced">
    <?=$test_results['bbcodes']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    nbcodes
  </td>
  <td class="<?=$test_style['nbcodes']?> text_white bold spaced">
    <?=$test_results['nbcodes']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    bbcodes_remove
  </td>
  <td class="<?=$test_style['unbbcodes']?> text_white bold spaced">
    <?=$test_results['unbbcodes']['explanation']?>
  </td>
</tr>

<tr class="row_separator_dark">
  <td class="nowrap">
    nbcodes_remove
  </td>
  <td class="<?=$test_style['unnbcodes']?> text_white bold spaced">
    <?=$test_results['unnbcodes']['explanation']?>
  </td>
</tr>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                           inc/functions_common.inc.php                                            */
/*                                                                                                                   */
/*                                                   COMMON TOOLS                                                    */
/*                                                                                                                   */
/******************************************************************************************************************/ ?>

<tr>
  <td class="nowrap cellnoaltc" rowspan="48">
    functions_common.inc.php
  </td>
  <td class="nowrap">
    root_path
  </td>
  <td class="<?=$test_style['root_path']?> text_white bold spaced">
    <?=$test_results['root_path']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    database_row_exists
  </td>
  <td class="<?=$test_style['db_exists_row']?> text_white bold spaced">
    <?=$test_results['db_exists_row']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['db_exists_row_not']?> text_white bold spaced">
    <?=$test_results['db_exists_row_not']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    database_entry_exists
  </td>
  <td class="<?=$test_style['db_exists_entry']?> text_white bold spaced">
    <?=$test_results['db_exists_entry']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['db_exists_entry_not']?> text_white bold spaced">
    <?=$test_results['db_exists_entry_not']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    system_variable_fetch<br>
    system_variable_update
  </td>
  <td class="<?=$test_style['sysvar_correct']?> text_white bold spaced">
    <?=$test_results['sysvar_correct']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['sysvar_wrong']?> text_white bold spaced">
    <?=$test_results['sysvar_wrong']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    system_assemble_version_number
  </td>
  <td class="<?=$test_style['version_assemble']?> text_white bold spaced">
    <?=$test_results['version_assemble']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    system_get_current_version_number
  </td>
  <td class="<?=$test_style['version_current']?> text_white bold spaced">
    <?=$test_results['version_current']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['version_next']?> text_white bold spaced">
    <?=$test_results['version_next']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    page_is_fetched_dynamically
  </td>
  <td class="<?=$test_style['page_is_xhr']?> text_white bold spaced">
    <?=$test_results['page_is_xhr']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    has_file_been_included
  </td>
  <td class="<?=$test_style['include_file']?> text_white bold spaced">
    <?=$test_results['include_file']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['include_fail']?> text_white bold spaced">
    <?=$test_results['include_fail']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="3">
    form_fetch_element
  </td>
  <td class="<?=$test_style['form_fetch_exists']?> text_white bold spaced">
    <?=$test_results['form_fetch_exists']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['form_fetch_value']?> text_white bold spaced">
    <?=$test_results['form_fetch_value']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['form_fetch_get']?> text_white bold spaced">
    <?=$test_results['form_fetch_get']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    string_truncate
  </td>
  <td class="<?=$test_style['string_truncate']?> text_white bold spaced">
    <?=$test_results['string_truncate']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="3">
    string_change_case
  </td>
  <td class="<?=$test_style['string_lowercase']?> text_white bold spaced">
    <?=$test_results['string_lowercase']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['string_uppercase']?> text_white bold spaced">
    <?=$test_results['string_uppercase']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['string_initials']?> text_white bold spaced">
    <?=$test_results['string_initials']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    string_remove_accents
  </td>
  <td class="<?=$test_style['string_no_accents']?> text_white bold spaced">
    <?=$test_results['string_no_accents']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    string_increment
  </td>
  <td class="<?=$test_style['string_increment']?> text_white bold spaced">
    <?=$test_results['string_increment']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="3">
    date_to_text
  </td>
  <td class="<?=$test_style['date_to_text']?> text_white bold spaced">
    <?=$test_results['date_to_text']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['date_to_text_2']?> text_white bold spaced">
    <?=$test_results['date_to_text_2']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['date_to_text_3']?> text_white bold spaced">
    <?=$test_results['date_to_text_3']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    date_to_ddmmyy
  </td>
  <td class="<?=$test_style['date_to_ddmmyy']?> text_white bold spaced">
    <?=$test_results['date_to_ddmmyy']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    date_to_mysql
  </td>
  <td class="<?=$test_style['date_to_mysql']?> text_white bold spaced">
    <?=$test_results['date_to_mysql']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['date_to_mysql_err']?> text_white bold spaced">
    <?=$test_results['date_to_mysql_err']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    date_to_aware_datetime
  </td>
  <td class="<?=$test_style['date_aware_time']?> text_white bold spaced">
    <?=$test_results['date_aware_time']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['date_aware_zone']?> text_white bold spaced">
    <?=$test_results['date_aware_zone']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    diff_strings
  </td>
  <td class="<?=$test_style['string_diff']?> text_white bold spaced">
    <?=$test_results['string_diff']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    search_string_context
  </td>
  <td class="<?=$test_style['string_context']?> text_white bold spaced">
    <?=$test_results['string_context']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    string_wrap_in_html_tags
  </td>
  <td class="<?=$test_style['string_wrap_tags']?> text_white bold spaced">
    <?=$test_results['string_wrap_tags']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    page_section_selector
  </td>
  <td class="<?=$test_style['page_section_selector']?> text_white bold spaced">
    <?=$test_results['page_section_selector']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="3">
    private_message_send
  </td>
  <td class="<?=$test_style['private_message']?> text_white bold spaced">
    <?=$test_results['private_message']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['private_message_system']?> text_white bold spaced">
    <?=$test_results['private_message_system']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['private_message_admin']?> text_white bold spaced">
    <?=$test_results['private_message_admin']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    flood_check
  </td>
  <td class="<?=$test_style['flood_check']?> text_white bold spaced">
    <?=$test_results['flood_check']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    log_activity
  </td>
  <td class="<?=$test_style['recent_activity_log']?> text_white bold spaced">
    <?=$test_results['recent_activity_log']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    log_activity_details
  </td>
  <td class="<?=$test_style['recent_activity_diff']?> text_white bold spaced">
    <?=$test_results['recent_activity_diff']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    log_activity_purge_orphan_diffs
  </td>
  <td class="<?=$test_style['recent_activity_diff_purge']?> text_white bold spaced">
    <?=$test_results['recent_activity_diff_purge']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    log_activity_delete
  </td>
  <td class="<?=$test_style['recent_activity_delete']?> text_white bold spaced">
    <?=$test_results['recent_activity_delete']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['recent_activity_restore']?> text_white bold spaced">
    <?=$test_results['recent_activity_restore']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    schedule_task
  </td>
  <td class="<?=$test_style['schedule_task']?> text_white bold spaced">
    <?=$test_results['schedule_task']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    schedule_task_update
  </td>
  <td class="<?=$test_style['schedule_task_update']?> text_white bold spaced">
    <?=$test_results['schedule_task_update']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    schedule_task_delete
  </td>
  <td class="<?=$test_style['schedule_task_delete']?> text_white bold spaced">
    <?=$test_results['schedule_task_delete']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    irc_send_message
  </td>
  <td class="<?=$test_style['irc_send_message']?> text_white bold spaced">
    <?=$test_results['irc_send_message']['explanation']?>
  </td>
</tr>

<tr class="row_separator_dark">
  <td class="nowrap">
    discord_send_message
  </td>
  <td class="<?=$test_style['discord_send_message']?> text_white bold spaced">
    <?=$test_results['discord_send_message']['explanation']?>
  </td>
</tr>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                         inc/functions_mathematics.inc.php                                         */
/*                                                                                                                   */
/*                                                    MATHEMATICS                                                    */
/*                                                                                                                   */
/******************************************************************************************************************/ ?>

<tr>
  <td class="nowrap cellnoaltc" rowspan="2">
    functions_mathematics.inc.php
  </td>
  <td class="nowrap">
    maths_percentage_of
  </td>
  <td class="<?=$test_style['maths_percent']?> text_white bold spaced">
    <?=$test_results['maths_percent']['explanation']?>
  </td>
</tr>

<tr class="row_separator_dark">
  <td class="nowrap">
    maths_percentage_growth
  </td>
  <td class="<?=$test_style['maths_percent_growth']?> text_white bold spaced">
    <?=$test_results['maths_percent_growth']['explanation']?>
  </td>
</tr>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                           inc/functions_numbers.inc.php                                           */
/*                                                                                                                   */
/*                                                      NUMBERS                                                      */
/*                                                                                                                   */
/******************************************************************************************************************/ ?>

<tr>
  <td class="nowrap cellnoaltc" rowspan="11">
    functions_numbers.inc.php
  </td>
  <td class="nowrap" rowspan="3">
    number_prepend_sign
  </td>
  <td class="<?=$test_style['number_prepend_pos']?> text_white bold spaced">
    <?=$test_results['number_prepend_pos']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['number_prepend_zero']?> text_white bold spaced">
    <?=$test_results['number_prepend_zero']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['number_prepend_neg']?> text_white bold spaced">
    <?=$test_results['number_prepend_neg']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="5">
    number_display_format
  </td>
  <td class="<?=$test_style['number_format_int']?> text_white bold spaced">
    <?=$test_results['number_format_int']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['number_format_price']?> text_white bold spaced">
    <?=$test_results['number_format_price']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['number_format_cents']?> text_white bold spaced">
    <?=$test_results['number_format_cents']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['number_format_percent']?> text_white bold spaced">
    <?=$test_results['number_format_percent']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['number_format_point']?> text_white bold spaced">
    <?=$test_results['number_format_point']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    number_styling
  </td>
  <td class="<?=$test_style['number_styling']?> text_white bold spaced">
    <?=$test_results['number_styling']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    number_ordinal
  </td>
  <td class="<?=$test_style['number_ordinal_fr']?> text_white bold spaced">
    <?=$test_results['number_ordinal_fr']['explanation']?>
  </td>
</tr>

<tr class="row_separator_dark">
  <td class="<?=$test_style['number_ordinal_en']?> text_white bold spaced">
    <?=$test_results['number_ordinal_en']['explanation']?>
  </td>
</tr>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                            inc/functions_time.inc.php                                             */
/*                                                                                                                   */
/*                                                       TIME                                                        */
/*                                                                                                                   */
/******************************************************************************************************************/ ?>

<tr>
  <td class="nowrap cellnoaltc" rowspan="4">
    functions_time.inc.php
  </td>
  <td class="nowrap">
    time_since
  </td>
  <td class="<?=$test_style['time_since']?> text_white bold spaced">
    <?=$test_results['time_since']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    time_until
  </td>
  <td class="<?=$test_style['time_until']?> text_white bold spaced">
    <?=$test_results['time_until']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    time_days_elapsed
  </td>
  <td class="<?=$test_style['time_days_mysql']?> text_white bold spaced">
    <?=$test_results['time_days_mysql']['explanation']?>
  </td>
</tr>

<tr class="row_separator_dark">
  <td class="<?=$test_style['time_days_timestamp']?> text_white bold spaced">
    <?=$test_results['time_days_timestamp']['explanation']?>
  </td>
</tr>