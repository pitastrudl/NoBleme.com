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
include_once './inc/users.inc.php';




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                 inc/users.inc.php                                                 */
/*                                                                                                                   */
/*                                            COMMON USER FUNCTIONALITIES                                            */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Encrypt some data

// Encrypt a random string
$test_random_string = fixtures_generate_data('string', 10, 20);
$test_random_crypt  = sha1($test_random_string.$GLOBALS['salt_key']);

// Do the same using the built in function
$test_encryption = encrypt_data($test_random_string);

// Expect the built-in function to match the other one
$test_results['encrypt_data'] = test_assert(  value:        $test_encryption              ,
                                              type:         'string'                      ,
                                              expectation:  $test_random_crypt            ,
                                              success:      'Data encrypted correctly'    ,
                                              failure:      'Data encrypted incorrectly'  );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Check user status

// Expect the user to be logged in
$test_results['user_logged_in'] = test_assert(  value:        user_is_logged_in()         ,
                                                type:         'bool'                      ,
                                                expectation:  isset($_SESSION['user_id']) ,
                                                success:      'User login detected'       ,
                                                failure:      'User login not detected'   );

// Expect the user's id to be correct
$test_results['user_get_id'] = test_assert(     value:        user_get_id()             ,
                                                type:         'int'                     ,
                                                expectation:  (int)$_SESSION['user_id'] ,
                                                success:      'User id fetched'         ,
                                                failure:      'User id not fetched'     );

// Expect the user's display mode to be correct
$test_results['user_get_mode'] = test_assert(   value:        user_get_mode()               ,
                                                type:         'string'                      ,
                                                expectation:  $_SESSION['mode']             ,
                                                success:      'Display mode fetched'        ,
                                                failure:      'Display mode not fetched'    );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Unban a user

// Create a banned user
$test_ban_start = sanitize(time() - 1, 'int', 0);
$test_ban_end   = sanitize(time() + 1000, 'int', 0);
query(" INSERT INTO users
        SET         users.username              = 'Testban'       ,
                    users.password              = ''              ,
                    users.current_language      = 'EN'            ,
                    users.is_banned_since       = $test_ban_start ,
                    users.is_banned_until       = $test_ban_end   ,
                    users.is_banned_because_en  = 'Testing'       ,
                    users.is_banned_because_fr  = 'Testing'       ");

// Fetch the test user's ID
$test_banned_id = sanitize(query_id(), 'int', 0);

// Check whether the test user is banned
$test_is_banned = user_is_banned($test_banned_id);

// Unban the test user
user_unban($test_banned_id);

// Check again without using the built-in function
$test_banned_user = query(" SELECT  users.is_banned_until AS 'u_ban'
                            FROM    users
                            WHERE   users.id = '$test_banned_id' ",
                            fetch_row: true );

// Expect the user to be unbanned
$test_results['user_unban'] = test_assert(  value:        (int)$test_banned_user['u_ban'] ,
                                            type:         'int'                           ,
                                            expectation:  0                               ,
                                            success:      'User was unbanned'             ,
                                            failure:      'User was not unbanned'         );

// Cleanup: temporarily delete the test user
query(" UPDATE  users
        SET     users.is_deleted  = 1
        WHERE   users.id          = '$test_banned_id' ");

// Cleanup: confirm user deletion
$test_is_deleted = user_is_deleted($test_banned_id);

// Cleanup: delete the test user
query(" DELETE FROM users
        WHERE       users.id = '$test_banned_id' ");




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Get data on a user

// Get data on the current user
$test_user_id   = sanitize(user_get_id(), 'int', 0);
$test_user_data = query(" SELECT    users.username                    AS 'u_nick'   ,
                                    users.current_language            AS 'u_lang'   ,
                                    users.is_administrator            AS 'u_admin'  ,
                                    users_settings.show_nsfw_content  AS 'u_nsfw'   ,
                                    users_settings.hide_youtube       AS 'u_tube'
                          FROM      users
                          LEFT JOIN users_settings ON users_settings.fk_users = users.id
                          WHERE     users.id = '$test_user_id' ",
                          fetch_row: true );

// Get the current user's private settings
$test_user_privacy = user_settings_privacy();

// Expect the ID to be correctly fetched
$test_results['user_fetch_id'] = test_assert(   value:        user_fetch_id($test_user_data['u_nick'])  ,
                                                type:         'int'                                     ,
                                                expectation:  $test_user_id                             ,
                                                success:      'User ID was fetched correctly'           ,
                                                failure:      'User ID was fetched incorrectly'         );

// Expect the username to be correctly fetched
$test_results['user_get_nick'] = test_assert(   value:        user_get_username($test_user_id)          ,
                                                type:         'string'                                  ,
                                                expectation:  $test_user_data['u_nick']                 ,
                                                success:      'Username was fetched correctly'          ,
                                                failure:      'Username was fetched incorrectly'        );

// Expect language settings to be correctly fetched
$test_results['user_get_lang'] = test_assert(   value:        user_get_language($test_user_id)          ,
                                                type:         'string'                                  ,
                                                expectation:  $test_user_data['u_lang']                 ,
                                                success:      'Username was fetched correctly'          ,
                                                failure:      'Username was fetched incorrectly'        );

// Expect the user's admin rights to be correct
$test_results['user_is_admin'] =  test_assert(  value:        user_is_administrator()                   ,
                                                type:         'bool'                                    ,
                                                expectation:  true                                      ,
                                                success:      'Admin rights granted'                    ,
                                                failure:      'Admin rights not granted'                );

// Expect admin rights to be correctly fetched
$test_results['user_get_admin'] = test_assert(   value:        user_is_administrator($test_user_id)     ,
                                                type:         'bool'                                    ,
                                                expectation:  true                                      ,
                                                success:      'Admin rights fetched correctly'          ,
                                                failure:      'Admin rights fetched incorrectly'        );

// Expect the user's moderation rights to be correct
$test_results['user_is_mod'] =  test_assert(    value:        user_is_moderator()                       ,
                                                type:         'bool'                                    ,
                                                expectation:  true                                      ,
                                                success:      'Mod rights granted'                      ,
                                                failure:      'Mod rights not granted'                  );

// Expect moderation rights to be correctly fetched
$test_results['user_get_mod'] = test_assert(    value:        user_is_moderator($test_user_id)          ,
                                                type:         'bool'                                    ,
                                                expectation:  true                                      ,
                                                success:      'Mod rights fetched correctly'            ,
                                                failure:      'Mod rights fetched incorrectly'          );

// Expect the user to not be banned
$test_results['user_is_banned'] =  test_assert( value:        user_is_banned()                          ,
                                                type:         'bool'                                    ,
                                                expectation:  false                                     ,
                                                success:      'Banned status detected'                  ,
                                                failure:      'Banned status not detected'              );

// Expect the test banned user to be banned
$test_results['user_get_ban'] = test_assert(    value:        $test_is_banned                           ,
                                                type:         'bool'                                    ,
                                                expectation:  true                                      ,
                                                success:      'Banned user status detected'             ,
                                                failure:      'Banned user status not detected'         );

// Expect the user to not be IP banned
$test_results['user_is_iped'] =  test_assert(   value:        user_is_ip_banned()                       ,
                                                type:         'int'                                     ,
                                                expectation:  0                                         ,
                                                success:      'IP ban status detected'                  ,
                                                failure:      'IP ban status not detected'              );

// Expect the user to not be deleted
$test_results['user_is_del'] =  test_assert(    value:        user_is_deleted()                         ,
                                                type:         'bool'                                    ,
                                                expectation:  false                                     ,
                                                success:      'Deleted status detected'                 ,
                                                failure:      'Deleted status not detected'             );

// Expect the test deleted user to be deleted
$test_results['user_get_del'] = test_assert(    value:        $test_is_deleted                          ,
                                                type:         'bool'                                    ,
                                                expectation:  true                                      ,
                                                success:      'Deleted user status detected'            ,
                                                failure:      'Deleted user status not detected'        );

// Expect NSFW filter settings to be correctly fetched
$test_results['user_get_nsfw'] = test_assert(   value:        user_settings_nsfw($test_user_id)         ,
                                                type:         'int'                                     ,
                                                expectation:  (int)$test_user_data['u_nsfw']            ,
                                                success:      'NSFW settings fetched correctly'         ,
                                                failure:      'NSFW settings fetched incorrectly'       );

// Expect privacy settings to be correctly fetched
$test_results['user_get_priv'] = test_assert(   value:        $test_user_privacy['youtube']             ,
                                                type:         'int'                                     ,
                                                expectation:  (int)$test_user_data['u_tube']            ,
                                                success:      'Privacy settings fetched correctly'      ,
                                                failure:      'Privacy settings fetched incorrectly'    );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Get the oldest user on the website

// Fetch the oldest user's account creation year
$test_oldest_user = query(" SELECT  MIN(users_profile.created_at) AS 'u_min'
                            FROM    users_profile ",
                            fetch_row: true);
$test_oldest_year = (int)date('Y', $test_oldest_user['u_min']);

// Expect the oldest user's creation year to be correct
$test_results['user_get_oldest'] = test_assert( value:        user_get_oldest()                 ,
                                                type:         'int'                             ,
                                                expectation:  $test_oldest_year                 ,
                                                success:      'Oldest user fetched correctly'   ,
                                                failure:      'Oldest user fetched incorrectly' );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Get user birthdays

// Create a user born in a random year
$test_user_birthday = fixtures_generate_data('int', 1000, 1500);
$test_user_birth    = $test_user_birthday.'-01-01';
query(" INSERT INTO users
        SET         users.username              = 'Testbday'  ,
                    users.password              = ''          ");

// Fetch the test user's ID
$test_user_id = sanitize(query_id(), 'int', 0);

// Add a birthday to the user
query(" INSERT INTO users_profile
        SET         users_profile.fk_users  = '$test_user_id'     ,
                    users_profile.birthday  = '$test_user_birth'  ");

// Fetch all birth years
$test_user_birthdays = user_get_birth_years();

// Look for the birth year in the birthday list
$test_year_found = false;
foreach($test_user_birthdays as $test_user_birthyear)
{
  if(isset($test_user_birthyear['year']) && $test_user_birthday === $test_user_birthyear['year'])
    $test_year_found = true;
}

// Expect the test user's birthday to be in the list
$test_results['user_get_byears'] = test_assert( value:        $test_year_found                  ,
                                                type:         'bool'                            ,
                                                expectation:  true                              ,
                                                success:      'Birth years listed correctly'    ,
                                                failure:      'Birth years listed incorrectly'  );

// Cleanup: delete the test user
query(" DELETE FROM users
        WHERE       users.id = '$test_user_id' ");
query(" DELETE FROM users_profile
        WHERE       users_profile.fk_users = '$test_user_id' ");




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Generate a random username for a guest

// Expect random guest usernames to be a non-empty string
$test_results['user_guest_nick'] = test_assert( value:        (strlen(user_generate_random_username()) > 1) ,
                                                type:         'bool'                                        ,
                                                expectation:  true                                          ,
                                                success:      'Guest username generated correctly'          ,
                                                failure:      'Guest username generated incorrectly'        );