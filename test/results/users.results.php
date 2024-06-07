<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) === str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                 inc/users.inc.php                                                 */
/*                                                                                                                   */
/*                                            COMMON USER FUNCTIONALITIES                                            */
/*                                                                                                                   */
/******************************************************************************************************************/ ?>

<tr>
  <td class="nowrap cellnoaltc" rowspan="22">
    users.inc.php
  </td>
  <td class="nowrap">
    encrypt_data()
  </td>
  <td class="<?=$test_style['encrypt_data']?> text_white bold spaced">
    <?=$test_results['encrypt_data']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_is_logged_in()
  </td>
  <td class="<?=$test_style['user_logged_in']?> text_white bold spaced">
    <?=$test_results['user_logged_in']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_get_id()
  </td>
  <td class="<?=$test_style['user_get_id']?> text_white bold spaced">
    <?=$test_results['user_get_id']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_get_mode()
  </td>
  <td class="<?=$test_style['user_get_mode']?> text_white bold spaced">
    <?=$test_results['user_get_mode']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_unban()
  </td>
  <td class="<?=$test_style['user_unban']?> text_white bold spaced">
    <?=$test_results['user_unban']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_fetch_id()
  </td>
  <td class="<?=$test_style['user_fetch_id']?> text_white bold spaced">
    <?=$test_results['user_fetch_id']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_get_username()
  </td>
  <td class="<?=$test_style['user_get_nick']?> text_white bold spaced">
    <?=$test_results['user_get_nick']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_get_language()
  </td>
  <td class="<?=$test_style['user_get_lang']?> text_white bold spaced">
    <?=$test_results['user_get_lang']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    user_is_administrator()
  </td>
  <td class="<?=$test_style['user_is_admin']?> text_white bold spaced">
    <?=$test_results['user_is_admin']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['user_get_admin']?> text_white bold spaced">
    <?=$test_results['user_get_admin']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    user_is_moderator()
  </td>
  <td class="<?=$test_style['user_is_mod']?> text_white bold spaced">
    <?=$test_results['user_is_mod']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['user_get_mod']?> text_white bold spaced">
    <?=$test_results['user_get_mod']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    user_is_banned()
  </td>
  <td class="<?=$test_style['user_is_banned']?> text_white bold spaced">
    <?=$test_results['user_is_banned']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['user_get_ban']?> text_white bold spaced">
    <?=$test_results['user_get_ban']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_is_ip_banned()
  </td>
  <td class="<?=$test_style['user_is_iped']?> text_white bold spaced">
    <?=$test_results['user_is_iped']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap" rowspan="2">
    user_is_deleted()
  </td>
  <td class="<?=$test_style['user_is_del']?> text_white bold spaced">
    <?=$test_results['user_is_del']['explanation']?>
  </td>
</tr>

<tr>
  <td class="<?=$test_style['user_get_del']?> text_white bold spaced">
    <?=$test_results['user_get_del']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_settings_nsfw()
  </td>
  <td class="<?=$test_style['user_get_nsfw']?> text_white bold spaced">
    <?=$test_results['user_get_nsfw']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_settings_privacy()
  </td>
  <td class="<?=$test_style['user_get_priv']?> text_white bold spaced">
    <?=$test_results['user_get_priv']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_get_oldest()
  </td>
  <td class="<?=$test_style['user_get_oldest']?> text_white bold spaced">
    <?=$test_results['user_get_oldest']['explanation']?>
  </td>
</tr>

<tr>
  <td class="nowrap">
    user_get_birth_years()
  </td>
  <td class="<?=$test_style['user_get_byears']?> text_white bold spaced">
    <?=$test_results['user_get_byears']['explanation']?>
  </td>
</tr>

<tr class="row_separator_dark">
  <td class="nowrap">
    user_generate_random_username()
  </td>
  <td class="<?=$test_style['user_guest_nick']?> text_white bold spaced">
    <?=$test_results['user_guest_nick']['explanation']?>
  </td>
</tr>