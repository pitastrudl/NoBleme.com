<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*  admin_account_deactivate    Deactivates an account.                                                              */
/*  admin_account_reactivate    Reactivates a deleted account.                                                       */
/*                                                                                                                   */
/*********************************************************************************************************************/


/**
 * Deactivates an account.
 *
 * @param   string        $username   Username of the account to deactivate.
 *
 * @return  string|null               Returns a string containing an error, or null if all went well.
 */

function admin_account_deactivate( $username )
{
  // Require moderator rights to run this action
  user_restrict_to_moderators();

  // Check if the required files have been included
  require_included_file('user_management.lang.php');

  // Sanitize and prepare the data
  $username_raw = $username;
  $username     = sanitize($username, 'string');
  $timestamp    = sanitize(time(), 'int', 0);
  $mod_nick_raw = user_get_nickname();

  // Error: No username provided
  if(!$username)
    return __('admin_deactivate_error_username');

  // Look for the user id
  $delete_id = sanitize(database_entry_exists('users', 'nickname', $username), 'int', 0);

  // Error: User not found
  if(!$delete_id)
    return __('admin_deactivate_error_id');

  // Error: Account already deleted
  if(user_is_deleted($delete_id))
    return __('admin_deactivate_error_deleted');

  // Error: Can't deactivate the administrative team
  if(user_is_moderator($delete_id) || user_is_administrator($delete_id))
    return __('admin_deactivate_error_admin');

  // Assemble the new username
  $new_username = 'user '.$delete_id;

  // Delete the user
  query(" UPDATE  users
          SET     users.is_deleted        = 1             ,
                  users.deleted_at        = '$timestamp'  ,
                  users.deleted_nickname  = '$username'   ,
                  users.nickname          = '$new_username'
          WHERE   users.id                = '$delete_id'  ");

  // Activity log
  log_activity('users_delete', 1, 'ENFR', 0, NULL, NULL, 0, $delete_id, $username_raw, $mod_nick_raw);

  // IRC bot message
  irc_bot_send_message("$mod_nick_raw has deleted the account of $username_raw - ".$GLOBALS['website_url']."pages/nobleme/activity?mod", 'mod');
  irc_bot_send_message("$mod_nick_raw has deleted the account of $username_raw - ".$GLOBALS['website_url']."pages/admin/user_deactivate", 'admin');

  // Return that all went well
  return NULL;
}




/**
 * Deactivates a deleted account.
 *
 * @param   string        $user_id    ID of the account to reactivate.
 *
 * @return  string|null               Returns a string containing an error, or null if all went well.
 */

function admin_account_reactivate( $user_id )
{
  // Require administrator rights to run this action
  user_restrict_to_administrators();

  // Check if the required files have been included
  require_included_file('user_management.lang.php');

  // Sanitize and prepare the data
  $user_id        = sanitize($user_id, 'int', 0);
  $admin_nick_raw = user_get_nickname();

  // Error: No ID provided
  if(!$user_id)
    return __('admin_reactivate_no_id');

  // Error: Account does not exist
  if(!database_row_exists('users', $user_id))
    return __('admin_reactivate_no_user');

  // Fetch the user's old nickname
  $duser = mysqli_fetch_array(query(" SELECT  users.deleted_nickname AS 'u_nick'
                                      FROM    users
                                      WHERE   users.id = '$user_id' "));
  $username_raw = $duser['u_nick'];
  $username     = sanitize($duser['u_nick'], 'string');

  // Reactivate the user
  query(" UPDATE  users
          SET     users.is_deleted        = 0           ,
                  users.deleted_at        = 0           ,
                  users.deleted_nickname  = ''          ,
                  users.nickname          = '$username'
          WHERE   users.id                = '$user_id'  ");

  // Activity log
  log_activity('users_undelete', 1, 'ENFR', 0, NULL, NULL, 0, $user_id, $username_raw, $admin_nick_raw);

  // IRC bot message
  irc_bot_send_message("$admin_nick_raw has reactivated the deleted account of $username_raw - ".$GLOBALS['website_url']."pages/nobleme/activity?mod", 'mod');
  irc_bot_send_message("$admin_nick_raw has reactivated the deleted account of $username_raw - ".$GLOBALS['website_url']."pages/admin/user_deactivate", 'admin');

  // Return that all went well
  return NULL;
}
