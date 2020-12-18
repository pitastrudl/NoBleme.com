<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*  private_message_list            Lists a user's private messages and system notifications.                        */
/*  private_message_get             Fetches information about a private message.                                     */
/*  private_message_reply           Reply to an existing private message.                                            */
/*  private_message_delete          Deletes a private message.                                                       */
/*                                                                                                                   */
/*  private_message_years_list      Fetches all years during which the current user got or sent private messages.    */
/*                                                                                                                   */
/*********************************************************************************************************************/


/**
 * Lists a user's private messages and system notifications.
 *
 * @param   string  $sort_by        (OPTIONAL)  The order in which the returned data will be sorted.
 * @param   array   $search         (OPTIONAL)  Search for specific field values.
 * @param   bool    $mark_as_read   (OPTIONAL)  Marks all unread messages in the selection as read.
 * @param   bool    $sent_messages  (OPTIONAL)  Lists sent messages instead of recieved messages.
 *
 * @return  array                               The private messages, ready for displaying.
 */

function private_message_list(  string  $sort_by        = ''      ,
                                array   $search         = array() ,
                                ?bool   $mark_as_read   = false   ,
                                bool    $sent_messages  = false   ) : array
{
  // Require users to be logged in to run this action
  user_restrict_to_users();

  // Check if the required files have been included
  require_included_file('functions_time.inc.php');
  require_included_file('bbcodes.inc.php');

  // Fetch the user's id
  $user_id = sanitize(user_get_id(), 'int', 1);

  // Sanitize the search parameters
  $search_title     = isset($search['title'])     ? sanitize($search['title'], 'string')            : NULL;
  $search_sender    = isset($search['sender'])    ? sanitize($search['sender'], 'string')           : NULL;
  $search_recipient = isset($search['recipient']) ? sanitize($search['recipient'], 'string')        : NULL;
  $search_date      = isset($search['date'])      ? sanitize($search['date'], 'int', 0, date('Y'))  : NULL;
  $search_read      = isset($search['read'])      ? sanitize($search['read'], 'int', -1, 1)         : NULL;
  $timestamp        = sanitize(time(), 'int', 0);

  // Fetch the private messages
  $qmessages = "    SELECT      users_private_messages.id                 AS 'pm_id'    ,
                                users_private_messages.fk_users_sender    AS 'us_id'    ,
                                users_sender.username                     AS 'us_nick'  ,
                                users_private_messages.fk_users_recipient AS 'ur_id'    ,
                                users_recipient.username                  AS 'ur_nick'  ,
                                users_private_messages.sent_at            AS 'pm_sent'  ,
                                users_private_messages.read_at            AS 'pm_read'  ,
                                users_private_messages.title              AS 'pm_title' ,
                                users_private_messages.body               AS 'pm_body'
                    FROM        users_private_messages
                    LEFT JOIN   users AS users_sender
                    ON          users_private_messages.fk_users_sender = users_sender.id
                    LEFT JOIN   users AS users_recipient
                    ON          users_private_messages.fk_users_recipient = users_recipient.id ";

  // Select whether they are sent or recieved messages
  if(!$sent_messages)
    $qmessages .= " WHERE       users_private_messages.fk_users_recipient   = '$user_id'
                    AND         users_private_messages.deleted_by_recipient = 0 ";
  else
      $qmessages .= " WHERE       users_private_messages.fk_users_sender    = '$user_id'
                      AND         users_private_messages.deleted_by_sender  = 0 ";


  // Search for data if requested
  if($search_title)
    $qmessages .= " AND         users_private_messages.title                        LIKE '%$search_title%'        ";
  if($search_sender && str_contains(string_change_case(__('nobleme'), 'lowercase'), string_change_case($search_sender, 'lowercase')))
    $qmessages .= " AND       ( users_private_messages.fk_users_sender                 = 0
                    OR          users_sender.username                               LIKE '%$search_sender%' )     ";
  else if($search_sender)
    $qmessages .= " AND         users_sender.username                               LIKE '%$search_sender%'       ";
  if($search_recipient && str_contains(string_change_case(__('nobleme'), 'lowercase'), string_change_case($search_recipient, 'lowercase')))
    $qmessages .= " AND       ( users_private_messages.fk_users_recipient              = 0
                    OR          users_recipient.username                            LIKE '%$search_recipient%' )  ";
  else if($search_recipient)
    $qmessages .= " AND         users_recipient.username                            LIKE '%$search_recipient%'    ";
  if($search_date)
    $qmessages .= " AND         YEAR(FROM_UNIXTIME(users_private_messages.sent_at))    = '$search_date'           ";
  if($search_read == -1)
    $qmessages .= " AND         users_private_messages.read_at                         = 0                        ";
  else if($search_read)
    $qmessages .= " AND         users_private_messages.read_at                         > 0                        ";

  // Sort the data as requested
  if($sort_by == 'title')
    $qmessages .= " ORDER BY  users_private_messages.title              ASC   ,
                              users_private_messages.sent_at            DESC  ";
  else if($sort_by == 'sender')
    $qmessages .= " ORDER BY  users_private_messages.fk_users_sender    = 0   ,
                              users_sender.username                     ASC   ,
                              users_private_messages.sent_at            DESC  ";
  else if($sort_by == 'recipient')
    $qmessages .= " ORDER BY  users_private_messages.fk_users_recipient = 0   ,
                              users_recipient.username                  ASC   ,
                              users_private_messages.sent_at            DESC  ";
  else if($sort_by == 'rsent')
    $qmessages .= " ORDER BY  users_private_messages.sent_at            ASC   ";
  else if($sort_by == 'read')
    $qmessages .= " ORDER BY  users_private_messages.read_at            != 0  ,
                              users_private_messages.read_at            DESC  ";
  else
    $qmessages .= " ORDER BY  users_private_messages.sent_at            DESC  ";

  // Execute the query
  $qmessages = query($qmessages);

  // Initialize the unread messages counter
  $count_unread = 0;

  // Loop through the messages
  for($i = 0; $row = mysqli_fetch_array($qmessages); $i++)
  {
    // Prepare the data
    $data[$i]['id']           = sanitize_output($row['pm_id']);
    $data[$i]['title']        = sanitize_output($row['pm_title']);
    $data[$i]['body']         = bbcodes(sanitize_output(string_truncate($row['pm_body'], 400, ' [...]'), 1));
    $data[$i]['system']       = (!$row['us_id'] || ($sent_messages && !$row['ur_id']));
    $data[$i]['sender_id']    = sanitize_output($row['us_id']);
    $data[$i]['sender']       = sanitize_output($row['us_nick']);
    $data[$i]['recipient_id'] = sanitize_output($row['ur_id']);
    $data[$i]['recipient']    = sanitize_output($row['ur_nick']);
    $data[$i]['sent']         = sanitize_output(time_since($row['pm_sent']));
    $data[$i]['fsent']        = sanitize_output(date_to_text($row['pm_sent'], 0, 2));
    $data[$i]['read']         = ($row['pm_read']) ? sanitize_output(time_since($row['pm_read'])) : '-';
    $data[$i]['fread']        = ($row['pm_read']) ? sanitize_output(date_to_text($row['pm_sent'], 0, 2)) : NULL;
    $data[$i]['css']          = ($row['pm_read']) ? '' : ' bold glow text_red';

    // Update the unread message count
    $count_unread            += ($row['pm_read']) ? 0 : 1;

    // Mark the messages as read if requested
    if(!$row['pm_read'] && $mark_as_read)
    {
      $data[$i]['read']       = sanitize_output(time_since($timestamp));
      $data[$i]['fread']      = sanitize_output(date_to_text($timestamp, 0, 2));
      $data[$i]['css']        = '';
      $message_id             = sanitize($row['pm_id'], 0);
      query(" UPDATE  users_private_messages
              SET     users_private_messages.read_at  = '$timestamp'
              WHERE   users_private_messages.id       = '$message_id' ");
    }
  }

  // Add the number of rows and the unread message count to the data
  $data['rows']   = $i;
  $data['unread'] = ($mark_as_read) ? 0 : $count_unread;

  // In ACT debug mode, print debug data
  if($GLOBALS['dev_mode'] && $GLOBALS['act_debug_mode'])
    var_dump(array('file' => 'users/messages.act.php', 'function' => 'private_messages_list', 'data' => $data));

  // Return the prepared data
  return $data;
}




/**
 * Fetches information about a private message.
 *
 * @param   int     $message_id   The private message's ID.
 *
 * @return  array                 An array of data regarding the message.
 */

function private_message_get( int $message_id ) : array
{
  // Require the user the be logged in
  user_restrict_to_users();

  // Check if the required files have been included
  require_included_file('functions_time.inc.php');
  require_included_file('bbcodes.inc.php');
  require_included_file('messages.lang.php');

  // Sanitize the user and message ID
  $user_id    = sanitize(user_get_id(), 'int', 0);
  $message_id = sanitize($message_id, 'int', 0);

  // Error: Message ID not found
  if(!database_row_exists('users_private_messages', $message_id))
  {
    $data['error'] = __('users_message_not_found');
    return $data;
  }

  // Prepare the query to fetch the message's data
  $qmessage = " SELECT    users_private_messages.deleted_by_recipient AS 'pm_deleted'   ,
                          users_private_messages.fk_users_recipient   AS 'pm_recipient' ,
                          users_private_messages.fk_users_sender      AS 'pm_sender_id' ,
                          users_private_messages.fk_parent_message    AS 'pm_parent'    ,
                          users_sender.username                       AS 'pm_sender'    ,
                          users_private_messages.sent_at              AS 'pm_sent'      ,
                          users_private_messages.read_at              AS 'pm_read'      ,
                          users_private_messages.title                AS 'pm_title'     ,
                          users_private_messages.body                 AS 'pm_body'
                FROM      users_private_messages
                LEFT JOIN users AS users_sender ON users_private_messages.fk_users_sender = users_sender.id
                WHERE     users_private_messages.id = '$message_id' ";

  // Fetch data regarding the message
  $dmessage = mysqli_fetch_array(query($qmessage));

  // Error: Deleted messages
  if($dmessage['pm_deleted'])
  {
    $data['error'] = __('users_message_deleted');
    return $data;
  }

  // Error: Message does not belong to user
  if($dmessage['pm_recipient'] != $user_id)
  {
    $data['error'] = __('users_message_neighbor');
    return $data;
  }

  // Prepare the data
  $data['id']         = sanitize_output($message_id);
  $data['self']       = ($user_id == $dmessage['pm_sender_id']);
  $data['title']      = sanitize_output($dmessage['pm_title']);
  $data['sender_id']  = sanitize_output($dmessage['pm_sender_id']);
  $data['sender']     = ($dmessage['pm_sender_id']) ? sanitize_output($dmessage['pm_sender']) : NULL;
  $data['sent_at']    = sanitize_output(date_to_text($dmessage['pm_sent'], 0, 2));
  $data['read_at']    = ($dmessage['pm_read']) ? sanitize_output(date_to_text($dmessage['pm_read'], 0, 2)) : NULL;
  $data['body']       = bbcodes(sanitize_output($dmessage['pm_body'], true));

  // Mark the message as read if it was previously unread
  if(!$dmessage['pm_read'])
  {
    $timestamp = sanitize(time(), 'int', 0);
    query(" UPDATE  users_private_messages
            SET     users_private_messages.read_at  = '$timestamp'
            WHERE   users_private_messages.id       = '$message_id' ");
  }

  // Get parent message chain
  if($dmessage['pm_parent'] && $dmessage['pm_parent'] != $message_id)
  {
    // Initialize the parent counter
    $i = 0;

    // Loop through parent messages
    do
    {
      // Fetch the parent ID
      $message_id = sanitize($dmessage['pm_parent']);

      // Prepare the query to fetch the message's data
      $qmessage = " SELECT    users_private_messages.deleted_by_recipient AS 'pm_deleted_r' ,
                              users_private_messages.deleted_by_sender    AS 'pm_deleted_s' ,
                              users_private_messages.fk_users_recipient   AS 'pm_recipient' ,
                              users_private_messages.fk_users_sender      AS 'pm_sender_id' ,
                              users_private_messages.fk_parent_message    AS 'pm_parent'    ,
                              users_sender.username                       AS 'pm_sender'    ,
                              users_private_messages.sent_at              AS 'pm_sent'      ,
                              users_private_messages.title                AS 'pm_title'     ,
                              users_private_messages.body                 AS 'pm_body'
                    FROM      users_private_messages
                    LEFT JOIN users AS users_sender ON users_private_messages.fk_users_sender = users_sender.id
                    WHERE     users_private_messages.id = '$message_id' ";

      // Fetch data regarding the message
      $dmessage = mysqli_fetch_array(query($qmessage));

      // Error: Message does not exist
      if(!isset($dmessage['pm_title']))
        $message_error = 1;

      // If the message does exist, look for more potential errors
      if(!isset($message_error))
      {
        // Identify whether the user is the sender or the recipient
        $user_is_sender = ($user_id == $dmessage['pm_sender_id']);

        // Error: User is neither sender nor recipient
        if(!$user_is_sender && $user_id != $dmessage['pm_recipient'])
          $message_error = 1;

        // Error: Too many parents (maybe an infinite loop?)
        if($i >= 100)
          $message_error = 1;
      }

      // Prepare the message's contents and increment the loop counter
      if(!isset($message_error))
      {
        $temp                   = ($user_is_sender && $dmessage['pm_deleted_s']) ? 1 : 0;
        $data[$i]['deleted']    = (!$user_is_sender && $dmessage['pm_deleted_r']) ? 1 : $temp;
        $data[$i]['title']      = sanitize_output($dmessage['pm_title']);
        $temp                   = ($dmessage['pm_sender_id']) ? $dmessage['pm_sender'] : __('nobleme');
        $data[$i]['sender']     = sanitize_output($temp);
        $data[$i]['sent_at']    = sanitize_output(date_to_text($dmessage['pm_sent'], 0, 2));
        $data[$i]['sent_time']  = sanitize_output(time_since($dmessage['pm_sent']));
        $data[$i]['body']       = bbcodes(sanitize_output($dmessage['pm_body'], true));
        $i++;
      }
    }

    // Keep looping as long as there is a parent and no error has arisen
    while(!isset($message_error) && $dmessage['pm_parent'] && $dmessage['pm_parent'] != $message_id);

    // Add the number of parents to the data
    $data['parents'] = $i;
  }

  // In ACT debug mode, print debug data
  if($GLOBALS['dev_mode'] && $GLOBALS['act_debug_mode'])
    var_dump(array('file' => 'users/messages.act.php', 'function' => 'private_message_get', 'data' => $data));

  // Return the data
  return $data;
}




/**
 * Reply to an existing private message.
 *
 * @param   int         $message_id   The id of the message being replied to.
 * @param   string      $body         The private message's body.
 *
 * @return  string|null               An error string, or NULL if all went well.
 */

function private_message_reply( int     $message_id ,
                                string  $body       ) : mixed
{
  // Require users to be logged in to run this action
  user_restrict_to_users();

  // Check if the required files have been included
  require_included_file('messages.lang.php');

  // Sanitize the data
  $user_id    = sanitize(user_get_id(), 'int', 1);
  $message_id = sanitize($message_id, 'int', 0);
  $body       = sanitize($body, 'string');

  // Error: Message ID not found
  if(!database_row_exists('users_private_messages', $message_id))
    return __('users_message_not_found');

  // Error: Empty body
  if(!$body)
    return __('users_message_reply_no_body');

  // Fetch some data regarding the message being replied to
  $dmessage = mysqli_fetch_array(query("  SELECT  users_private_messages.deleted_by_recipient AS 'pm_deleted'   ,
                                                  users_private_messages.fk_users_recipient   AS 'pm_recipient' ,
                                                  users_private_messages.fk_users_sender      AS 'pm_sender'    ,
                                                  users_private_messages.title                AS 'pm_title'
                                          FROM    users_private_messages
                                          WHERE   users_private_messages.id = '$message_id' "));

  // Error: Message has been deleted
  if($dmessage['pm_deleted'])
    return __('users_message_deleted');

  // Error: Can not reply to self
  if($user_id == $dmessage['pm_sender'])
    return __('users_message_reply_self');

  // Error: Can not reply to other people's messages
  if($user_id != $dmessage['pm_recipient'])
    return __('users_message_reply_others');

  // Check if the user is flooding the website
  if(!flood_check(error_page: false))
    return __('users_message_reply_flood');

  // Prepare the message's data
  $recipient  = sanitize($dmessage['pm_sender'], 'int', 0);
  $title_raw  = 'RE '.string_truncate($dmessage['pm_title'], 25, '…');
  $title      = sanitize($title_raw, 'string');

  // Send the message
  private_message_send( $title                      ,
                        $body                       ,
                        recipient: $recipient       ,
                        sender: $user_id            ,
                        parent_message: $message_id ,
                        do_not_sanitize: true       );

  // Notify IRC in case of admin message
  if(!$recipient)
  {
    $sender = user_get_username($user_id);
    irc_bot_send_message("Private message sent to the administrative team by $sender: $title ".$GLOBALS['website_url']."todo_link", 'mod');
  }

  // Everything went well
  return NULL;
}




/**
 * Deletes a private message.
 *
 * @param   int         $message_id   The private message's id.
 *
 * @return  string|null               An error string, or NULL if all went well.
 */

function private_message_delete( int $message_id ) : mixed
{
  // Require users to be logged in to run this action
  user_restrict_to_users();

  // Sanitize the data
  $user_id    = sanitize(user_get_id(), 'int', 1);
  $message_id = sanitize($message_id, 'int', 0);
  $timestamp  = time();

  // Error: Message ID not found
  if(!database_row_exists('users_private_messages', $message_id))
    return __('users_message_not_found');

  // Fetch some data regarding the message
  $dmessage = mysqli_fetch_array(query("  SELECT  users_private_messages.deleted_by_recipient AS 'pm_deleted_r' ,
                                                  users_private_messages.deleted_by_sender    AS 'pm_deleted_s' ,
                                                  users_private_messages.fk_users_recipient   AS 'pm_recipient'
                                          FROM    users_private_messages
                                          WHERE   users_private_messages.id = '$message_id' "));

  // Error: Message is already deleted
  if($dmessage['pm_deleted_r'])
    return __('users_message_predeleted');

  // Error: Message does not belong to user
  if($dmessage['pm_recipient'] != $user_id)
    return __('users_message_ownership');

  // If the sender had already deleted the message, hard delete it
  if($dmessage['pm_deleted_s'])
    query(" DELETE FROM users_private_messages
            WHERE       users_private_messages.id = '$message_id' ");

  // Otherwise soft delete it
  else
    query(" UPDATE  users_private_messages
            SET     users_private_messages.deleted_by_recipient = 1 ,
                    users_private_messages.read_at              = '$timestamp'
            WHERE   users_private_messages.id = '$message_id' ");

  // All went well, return NULL
  return NULL;
}




/**
 * Fetches all years during which the current user got or sent private messages.
 *
 * @param   bool    $sent_messages  (OPTIONAL)  If true, will show years for sent instead of recieved messages.
 *
 * @return  array                               The data, ready for use.
 */

function private_message_years_list( bool $sent_messages = false ) : array
{
  // Require users to be logged in to run this action
  user_restrict_to_users();

  // Fetch the user's id
  $user_id = sanitize(user_get_id(), 'int', 1);

  // Fetch the years during which the user got private messages
  if(!$sent_messages)
    $qyears = query(" SELECT    YEAR(FROM_UNIXTIME(users_private_messages.sent_at)) AS 'pm_year'
                      FROM      users_private_messages
                      WHERE     users_private_messages.fk_users_recipient = '$user_id'
                      GROUP BY  YEAR(FROM_UNIXTIME(users_private_messages.sent_at))
                      ORDER BY  YEAR(FROM_UNIXTIME(users_private_messages.sent_at)) DESC ");

  // Or the years during which the user sent private messages
  else
    $qyears = query(" SELECT    YEAR(FROM_UNIXTIME(users_private_messages.sent_at)) AS 'pm_year'
                      FROM      users_private_messages
                      WHERE     users_private_messages.fk_users_sender = '$user_id'
                      GROUP BY  YEAR(FROM_UNIXTIME(users_private_messages.sent_at))
                      ORDER BY  YEAR(FROM_UNIXTIME(users_private_messages.sent_at)) DESC ");

  // Prepare the data
  for($i = 0; $row = mysqli_fetch_array($qyears); $i++)
    $data[$i]['year'] = sanitize_output($row['pm_year']);

  // Add the number of rows to the data
  $data['rows'] = $i;

  // In ACT debug mode, print debug data
  if($GLOBALS['dev_mode'] && $GLOBALS['act_debug_mode'])
    var_dump(array('file' => 'users/messages.act.php', 'function' => 'private_messages_years_list', 'data' => $data));

  // Return the prepared data
  return $data;
}