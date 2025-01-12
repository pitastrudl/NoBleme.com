<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                  THIS PAGE CAN ONLY BE RAN ON A DEV ENVIRONMENT                                   */
/*                                                                                                                   */
/*********************************************************************************************************************/
/*                                                                                                                   */
/*                   Running this script might add, edit, delete various entries in the database.                    */
/*        It might completely mess up your environment, you can reset it afterwards by running /fixtures.php         */
/*                                                                                                                   */
// File inclusions /**************************************************************************************************/
include_once './inc/includes.inc.php' ; # Core
include_once './inc/fixtures.inc.php' ; # Dummy data generation
include_once './inc/tests.inc.php'    ; # Functions for tests
include_once './lang/dev.lang.php'    ; # Translations

// Limit page access rights
user_restrict_to_administrators();

// Only allow this page to be ran in dev mode, it wouldn't be nice to accidentally wipe production data, would it?
if(!$GLOBALS['dev_mode'])
  exit(header("Location: ."));

// Page summary
$page_lang      = array('FR', 'EN');
$page_url       = "tests";
$page_title_en  = "Tests";
$page_title_fr  = "Tests";

// Extra JS
$js = array('tests/suite');




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     BACK END                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Run tests

// Define the list of elements which can be tested
$test_categories = array('core', 'common', 'users');

// Initialize an array for test results
$test_results = array();

// Run the requested tests
foreach($test_categories as $test_category)
{
  if(form_fetch_element('dev_tests_'.$test_category, element_exists: true))
    include_once './test/tests/'.$test_category.'.tests.php';
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Prepare data for displaying

// Initialize the test success and failure count
$test_successes = 0;
$test_fails     = 0;

// Run through the list of tests
foreach($test_results as $test_name => $test_result)
{
  // Increment the number of successful tests if necessary
  if($test_result['result'] === true)
    $test_successes++;

  // Increment the number of failed tests if necessary
  if($test_result['result'] === false)
    $test_fails++;

  // Prepare a style for the cell displaying the test result
  $test_style[$test_name] = ($test_result['result'] === true) ? 'green' : 'red';
}

// Remember selected form entries
foreach($test_categories as $test_form_entry)
  $test_form_checked[$test_form_entry]  = (form_fetch_element('dev_tests_'.$test_form_entry, element_exists: true))
                                        ? ' checked' : '';




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     FRONT END                                                     */
/*                                                                                                                   */
/**********************************************************************************/ include './inc/header.inc.php'; ?>

<div class="width_50">

  <h1>
    <?=__('dev_tests_title')?>
  </h1>

  <p>
    <?=__('dev_tests_select_body')?>
  </p>

  <form method="POST" id="dev_tests_selector">
    <fieldset>

      <div class="smallpadding_top tinypadding_bot">

        <button type="button" name="dev_tests_all" onclick="tests_suite_select_all();"><?=__('dev_tests_select_all')?></button>
        <button type="button" name="dev_tests_none" onclick="tests_suite_unselect_all();"><?=__('dev_tests_select_uncheck')?></button>

      </div>

      <div class="tinypadding_top tinypadding_bot">

        <input type="checkbox" id="dev_tests_core" name="dev_tests_core"<?=$test_form_checked['core']?>>
        <label class="label_inline" for="dev_tests_core"><?=__('dev_tests_select_core')?></label><br>

        <input type="checkbox" id="dev_tests_common" name="dev_tests_common"<?=$test_form_checked['common']?>>
        <label class="label_inline" for="dev_tests_common"><?=__('dev_tests_select_common')?></label><br>

        <input type="checkbox" id="dev_tests_users" name="dev_tests_users"<?=$test_form_checked['users']?>>
        <label class="label_inline" for="dev_tests_users"><?=__('dev_tests_select_users')?></label><br>

      </div>

      <input type="submit" name="dev_tests_submit" value="<?=__('dev_tests_select_submit')?>">

    </fieldset>
  </form>

</div>
<div class="width_60">

  <?php if(!count($test_results) && isset($_POST['dev_tests_submit'])) { ?>

  <div class="smallpadding_top">
    <span class="bold red text_white"><?=string_change_case(__('error'), 'uppercase').__(':', spaces_after: 1).__('dev_tests_results_none')?></span>
  </div>

  <?php } else if(count($test_results)) { ?>

  <div class="autoscroll bigpadding_top">
    <table>
      <thead>

        <tr>
          <th colspan="3" class="uppercase bigger vspaced">
            <?=__('dev_tests_results_title')?>
          </th>
        </tr>

        <tr>
          <th colspan="3" class="uppercase text_light dark bold align_center">
            <span class="green text_white spaced bold"><?=$test_successes?></span> <?=__('dev_tests_results_passed', amount: $test_successes)?> <span class="red text_white spaced bold"><?=$test_fails?></span> <?=__('dev_tests_results_failed', amount: $test_fails)?>
          </th>
        </tr>

      </thead>
      <tbody>

        <?php /************************************** TEST RESULTS ***************************************************/

        // Display the required test results
        foreach($test_categories as $test_category)
        {
          if(form_fetch_element('dev_tests_'.$test_category, element_exists: true))
            include_once './test/results/'.$test_category.'.results.php';
        } ?>

      </tbody>
    </table>
  </div>

  <?php } ?>

</div>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                    END OF PAGE                                                    */
/*                                                                                                                   */
/*************************************************************************************/ include './inc/footer.inc.php';