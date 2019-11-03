<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                   PAGE SETTINGS                                                   */
/*                                                                                                                   */
// Inclusions /*******************************************************************************************************/
include_once './inc/includes.inc.php';  # Core
include_once './lang/nobleme.lang.php'; # Translations

// Menus
$header_menu      = 'NoBleme';

// User activity
$page_name  = "404";
$page_url   = "404";

// Page summary
$page_lang        = array('EN', 'FR');
$page_title       = __('nobleme_404_page_title');
$page_description = __('nobleme_404_description');

// Extra CSS & JS
$css = array('404');
$js  = array('404');




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     BACK END                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Make the page a hard 404
header("HTTP/1.0 404 Not Found");

// Inform the header that this is a 404
$this_page_is_a_404 = '';




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     FRONT END                                                     */
/*                                                                                                                   */
/**********************************************************************************/ include './inc/header.inc.php'; ?>

    <div class="indiv align_center hugepadding_top bigpadding_bot">
      <img src="<?=$path?>img/404/404_title_<?=string_change_case($lang, 'lowercase')?>.png" alt="<?=__('nobleme_404_description')?>">
    </div>

    <div class="margin_auto bigpadding_top hugepadding_bot" style="width:1000px">
      <table class="indiv">
        <tbody>
          <tr>
            <td class="img404 noborder">
              <img class="img404left" src="<?=$path?>img/404/404_left.png" alt=" ">
            </td>
            <td class="noborder">
              <textarea class="indiv text404" rows="10" id="text404" readonly></textarea>
            </td>
            <td class="img404 noborder">
              <img class="img404right" src="<?=$path?>img/404/404_right.gif" alt=" ">
            </td>
          </tr>
        </tbody>
      </table>
    </div>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                    END OF PAGE                                                    */
/*                                                                                                                   */
/*************************************************************************************/ include './inc/footer.inc.php';