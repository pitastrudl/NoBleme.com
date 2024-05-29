<?php
/***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) === str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*                  These tests should only be ran through `tests.php` at the root of the project.                   */
/*                                                                                                                   */
/*********************************************************************************************************************/
// Limit page access rights
user_restrict_to_administrators();

// Only allow this page to be ran in dev mode
if(!$GLOBALS['dev_mode'])
  exit(header("Location: ."));

// Include required files
include_once './inc/functions_mathematics.inc.php';
include_once './inc/functions_numbers.inc.php';
include_once './inc/functions_time.inc.php';




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                         inc/functions_mathematics.inc.php                                         */
/*                                                                                                                   */
/*                                                    MATHEMATICS                                                    */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Calculate a percentage

// Calculate the percentage of two numbers
$test_number  = rand(1, 10);
$test_total   = rand(11, 100);
$test_percent = (float)(($test_number / $test_total) * 100);

// Expect the percentage calculations to be working
$test_results['maths_percent'] = test_assert( value:        maths_percentage_of($test_number, $test_total)        ,
                                              type:         'float'                                               ,
                                              expectation:  $test_percent                                         ,
                                              success:      'Percentage calculated'                               ,
                                              failure:      "Percentage is wrong ($test_number% of $test_total)"  );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Calculate a percentage growth

// Calculate the percentage growth between two numbers
$test_before  = rand(1, 50);
$test_after   = rand(51, 100);
$test_growth  = (float)((($test_after / $test_before ) * 100) - 100);

// Calculate the same growth using the function
$test_growth_function = maths_percentage_growth($test_before, $test_after);

// Prepare a phrase in case of failure
$test_failure = "Percentage growth is wrong ($test_before to $test_after)";

// Expect the percentage calculations to be working
$test_results['maths_percent_growth'] = test_assert(  value:        $test_growth_function           ,
                                                      type:         'float'                         ,
                                                      expectation:  $test_growth                    ,
                                                      success:      'Percentage growth calculated'  ,
                                                      failure:      $test_failure                   );




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                           inc/functions_numbers.inc.php                                           */
/*                                                                                                                   */
/*                                                      NUMBERS                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Prepend a sign to a number

$test_results['number_prepend_pos'] = test_assert(  value:        number_prepend_sign(10)             ,
                                                    type:         'string'                            ,
                                                    expectation:  '+10'                               ,
                                                    success:      'Sign added before number'          ,
                                                    failure:      'Sign not added before number'      );
$test_results['number_prepend_zero'] = test_assert( value:        number_prepend_sign(0)              ,
                                                    type:         'string'                            ,
                                                    expectation:  '0'                                 ,
                                                    success:      'No sign added before zero'         ,
                                                    failure:      'Sign added before zero'            );
$test_results['number_prepend_neg'] = test_assert(  value:        number_prepend_sign(-10)            ,
                                                    type:         'string'                            ,
                                                    expectation:  '-10'                               ,
                                                    success:      'No sign added before number'       ,
                                                    failure:      'Sign added before negative number' );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Format a number for display

// Prepare some formatted numbers
$test_format_number   = number_display_format(  number:       -10                 ,
                                                format:       "number"            ,
                                                decimals:     0                   );
$test_format_price    = number_display_format(  number:       1.8                 ,
                                                format:       "price"             ,
                                                decimals:     2                   );
$test_format_cents    = number_display_format(  number:       1.8                 ,
                                                format:       "price_cents"       );
$test_format_percent  = number_display_format(  number:       50.11111            ,
                                                format:       "percentage"        );
$test_format_point    = number_display_format(  number:       50.11111            ,
                                                format:       "percentage_point"  ,
                                                decimals:     1                   ,
                                                prepend_sign: true                );

// Expect the correct number formatting outcomes
$test_results['number_format_int'] = test_assert(     value:        $test_format_number                 ,
                                                      type:         'string'                            ,
                                                      expectation:  '-10'                               ,
                                                      success:      'Number formatted'                  ,
                                                      failure:      'Number formatted wrong'            );
$test_results['number_format_price'] = test_assert(   value:        $test_format_price                  ,
                                                      type:         'string'                            ,
                                                      expectation:  '2 €'                               ,
                                                      success:      'Price formatted'                   ,
                                                      failure:      'Price formatted wrong'             );
$test_results['number_format_cents'] = test_assert(   value:        $test_format_cents                  ,
                                                      type:         'string'                            ,
                                                      expectation:  '1,80 €'                            ,
                                                      success:      'Full price formatted'              ,
                                                      failure:      'Full price formatted wrong'        );
$test_results['number_format_percent'] = test_assert( value:        $test_format_percent                ,
                                                      type:         'string'                            ,
                                                      expectation:  '50 %'                              ,
                                                      success:      'Percentage formatted'              ,
                                                      failure:      'Percentage formatted wrong'        );
$test_results['number_format_point'] = test_assert(   value:        $test_format_point                  ,
                                                      type:         'string'                            ,
                                                      expectation:  '+50,1 p%'                          ,
                                                      success:      'Percentage point formatted'        ,
                                                      failure:      'Percentage point formatted wrong'  );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Style a number

$test_results['number_styling'] = test_assert(  value:        number_styling(rand(1, 100))  ,
                                                type:         'string'                      ,
                                                expectation:  'positive'                    ,
                                                success:      'Number styled'               ,
                                                failure:      'Number styled wrong'         );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Make a number ordinal

$test_results['number_ordinal_fr'] = test_assert( value:        number_ordinal(1, lang: 'FR')     ,
                                                  type:         'string'                          ,
                                                  expectation:  'er'                              ,
                                                  success:      'French ordinal returned'         ,
                                                  failure:      'French ordinal returned wrong'   );
$test_results['number_ordinal_en'] = test_assert( value:        number_ordinal(2, lang: 'EN')     ,
                                                  type:         'string'                          ,
                                                  expectation:  'nd'                              ,
                                                  success:      'English ordinal returned'        ,
                                                  failure:      'English ordinal returned wrong'  );




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                            inc/functions_time.inc.php                                             */
/*                                                                                                                   */
/*                                                       TIME                                                        */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Write time differences in plaintext

$test_results['time_since'] = test_assert(  value:        time_since(time() + 100)              ,
                                            type:         'string'                              ,
                                            expectation:  __('time_diff_past_future')           ,
                                            success:      'Future timestamp interpreted'        ,
                                            failure:      'Future timestamp interpreted wrong'  );
$test_results['time_until'] = test_assert(  value:        time_until(time() - 100)              ,
                                            type:         'string'                              ,
                                            expectation:  __('time_diff_future_past')           ,
                                            success:      'Past timestamp interpreted'          ,
                                            failure:      'Past timestamp interpreted wrong'    );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Calculate the days between dates

// Calculate days
$test_days_mysql      = time_days_elapsed( '2020-01-01', '2021-02-02');
$test_days_timestamp  = time_days_elapsed( '1704063600', '1704841200', use_timestamps: true);

// Expect the days to have been calculated properly
$test_results['time_days_mysql'] = test_assert(     value:        $test_days_mysql                          ,
                                                    type:         'int'                                     ,
                                                    expectation:  398                                       ,
                                                    success:      'Days calculated from dates'              ,
                                                    failure:      'Days wrongly calculated from dates'      );
$test_results['time_days_timestamp'] = test_assert( value:        $test_days_timestamp                      ,
                                                    type:         'int'                                     ,
                                                    expectation:  9                                         ,
                                                    success:      'Days calculated from timestamps'         ,
                                                    failure:      'Days wrongly calculated from timestamps' );