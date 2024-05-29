<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) === str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }


/*********************************************************************************************************************/
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