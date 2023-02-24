<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                       SETUP                                                       */
/*                                                                                                                   */
// File inclusions /**************************************************************************************************/
include_once './../../../inc/includes.inc.php';        # Core
include_once './../../../inc/functions_time.inc.php';  # Time management
include_once './../../../inc/bbcodes.inc.php';         # BBCOdes
include_once './../../../actions/users.act.php';       # Actions
include_once './../../../lang/users.lang.php';         # Translations




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                    API OUTPUT                                                     */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Fetch the user

// Sanitize the requested ID
$user_name = form_fetch_element('username', request_type: 'GET', default_value: '');

// Fetch the user
$user_get = users_get(  username: $user_name  ,
                        format:   'api'       );




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Output the user data as JSON

// Throw a 404 if necessary
if(!$user_get)
  exit(header("HTTP/1.0 404 Not Found"));

// Send headers announcing a json output
header("Content-Type: application/json; charset=UTF-8");

// Output the user data
echo sanitize_api_output($user_get);