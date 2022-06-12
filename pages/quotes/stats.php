<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                       SETUP                                                       */
/*                                                                                                                   */
// File inclusions /**************************************************************************************************/
include_once './../../inc/includes.inc.php';              # Core
include_once './../../inc/functions_numbers.inc.php';     # Number formatting
include_once './../../inc/functions_mathematics.inc.php'; # Mathematics
include_once './../../actions/quotes.act.php';            # Actions
include_once './../../lang/quotes.lang.php';              # Translations

// Page summary
$page_lang        = array('FR', 'EN');
$page_url         = "pages/quotes/stats";
$page_title_en    = "Quotes statistics";
$page_title_fr    = "Statistiques des citations";
$page_description = "Statistics generated from NoBleme's quote database";

// Extra JS
$js = array('common/toggle', 'common/selector');




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     BACK END                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Page section selector

// Define the dropdown menu entries
$quotes_selector_entries = array( 'overall'   ,
                                  'featured'  ,
                                  'years'     ,
                                  'submitted' );

// Define the default dropdown menu entry
$quotes_selector_default = 'overall';

// Initialize the page section selector data
$quotes_selector = page_section_selector(           $quotes_selector_entries  ,
                                          default:  $quotes_selector_default  );





///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Recalculate all stats

if(isset($_GET['recalculate']) && user_is_administrator())
{
  // Recalculate the stats
  quotes_recalculate_all_stats();

  // Reload the page in its default state
  exit(header("Location: ".$path."pages/quotes/stats"));
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Fetch stats

// Overall stats
$quotes_stats = quotes_stats();





/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     FRONT END                                                     */
/*                                                                                                                   */
if(!page_is_fetched_dynamically()) { /***************************************/ include './../../inc/header.inc.php'; ?>

<div class="padding_bot align_center section_selector_container">

  <fieldset>
    <h5>

      <?=__('quotes_stats_selector_title').__(':')?>

      <select class="inh" id="quotes_stats_selector" onchange="page_section_selector('quotes_stats', '<?=$quotes_selector_default?>');">
        <option value="overall"<?=$quotes_selector['menu']['overall']?>><?=__('quotes_stats_selector_overall')?></option>
        <option value="featured"<?=$quotes_selector['menu']['featured']?>><?=__('quotes_stats_selector_featured')?></option>
        <option value="years"<?=$quotes_selector['menu']['years']?>><?=__('quotes_stats_selector_years')?></option>
        <option value="submitted"<?=$quotes_selector['menu']['submitted']?>><?=__('quotes_stats_selector_submitted')?></option>
      </select>

      <?php if($is_admin) { ?>
      <?=__icon('refresh', alt: 'R', title: __('quotes_stats_recalculate_button'), title_case: 'initials', class: 'valign_middle pointer spaced_left', href: 'pages/quotes/stats?recalculate', confirm: __('quotes_stats_recalculate_alert'))?>
      <?php } ?>

    </h5>
  </fieldset>

</div>

<hr>




<?php /************************************************ OVERALL ***************************************************/ ?>

<div class="width_50 padding_top quotes_stats_section<?=$quotes_selector['hide']['overall']?>" id="quotes_stats_overall">

  <p class="align_center big padding_bot">
    <?=__('quotes_stats_overall_summary', preset_values: array($quotes_stats['total']))?>
  </p>

  <p class="align_center">
    <?=__('quotes_stats_overall_lang_en', preset_values: array($quotes_stats['total_en'], $quotes_stats['percent_en']))?><br>
    <?=__('quotes_stats_overall_lang_fr', preset_values: array($quotes_stats['total_fr'], $quotes_stats['percent_fr']))?>
  </p>

  <p class="align_center bigpadding_top">
    <?=__('quotes_stats_overall_nsfw', preset_values: array($quotes_stats['total_nsfw'], $quotes_stats['percent_nsfw']))?>
  </p>

  <p class="align_center bigpadding_top">
    <?=__('quotes_stats_overall_deleted', preset_values: array($quotes_stats['deleted'], $quotes_stats['percent_del']))?>
  </p>

  <p class="align_center">
    <?=__('quotes_stats_overall_unvalidated', amount: $quotes_stats['unvalidated'], preset_values: array($quotes_stats['unvalidated']))?>
  </p>

  <p class="align_center bigpadding_top">
    <?=__('quotes_stats_overall_more')?>
  </p>

</div>




<?php /************************************************ FEATURED **************************************************/ ?>

<div class="width_50 padding_top quotes_stats_section<?=$quotes_selector['hide']['featured']?>" id="quotes_stats_featured">

  &nbsp;

</div>




<?php /************************************************** YEARS ***************************************************/ ?>

<div class="width_50 padding_top quotes_stats_section<?=$quotes_selector['hide']['years']?>" id="quotes_stats_years">

  &nbsp;

</div>




<?php /************************************************ SUBMITTED *************************************************/ ?>

<div class="width_50 padding_top quotes_stats_section<?=$quotes_selector['hide']['submitted']?>" id="quotes_stats_submitted">

  &nbsp;

</div>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                    END OF PAGE                                                    */
/*                                                                                                                   */
/*****************************************************************************/ include './../../inc/footer.inc.php'; }