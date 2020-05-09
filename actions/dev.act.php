<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                 CLOSE THE WEBSITE                                                 */
/*                                                                                                                   */
/*********************************************************************************************************************/

/**
 * Toggles the website's status between open and closed.
 *
 * @return  void
 */

function dev_toggle_website_status()
{
  // Fetch the current update status
  $website_status = system_variable_fetch('update_in_progress');

  // Determine the required new value
  $new_status = ($website_status) ? 0 : 1;

  // Toggle the website status
  system_variable_update('update_in_progress', $new_status, 'int');
}




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                  VERSION NUMBERS                                                  */
/*                                                                                                                   */
/*********************************************************************************************************************/

/**
 * Returns elements related to a version number.
 *
 * @param   int         $version_id The version number's id.
 *
 * @return  array|null              An array containing elements related to the version, or NULL if it does not exist.
 */

function dev_versions_list_one($version_id)
{
  // Sanitize the id
  $version_id = sanitize($version_id, 'int', 0);

  // Check if the version exists
  if(!database_row_exists('system_versions', $version_id))
    return NULL;

  // Fetch the data
  $dversions = mysqli_fetch_array(query(" SELECT    system_versions.major         AS 'v_major'      ,
                                                    system_versions.minor         AS 'v_minor'      ,
                                                    system_versions.patch         AS 'v_patch'      ,
                                                    system_versions.extension     AS 'v_extension'  ,
                                                    system_versions.release_date  AS 'v_date'
                                          FROM      system_versions
                                          WHERE     system_versions.id = '$version_id' "));

  // Assemble an array with the data
  $data['major']        = sanitize_output($dversions['v_major']);
  $data['minor']        = sanitize_output($dversions['v_minor']);
  $data['patch']        = sanitize_output($dversions['v_patch']);
  $data['extension']    = sanitize_output($dversions['v_extension']);
  $data['release_date'] = sanitize_output(date_to_ddmmyy($dversions['v_date']));

  // In ACT debug mode, print debug data
  if($GLOBALS['dev_mode'] && $GLOBALS['act_debug_mode'])
    var_dump(array('dev.act.php', 'dev_versions_list', $data));

  // Return the array
  return $data;
}




/**
 * Returns the website's version numbering history.
 *
 * @return  array   An array containing past version numbers, sorted in reverse chronological order by date.
 */

function dev_versions_list()
{
  // Check if the required files have been included
  require_included_file('functions_time.inc.php');

  // Fetch all versions
  $qversions = query("  SELECT    system_versions.id            AS 'v_id'         ,
                                  system_versions.major         AS 'v_major'      ,
                                  system_versions.minor         AS 'v_minor'      ,
                                  system_versions.patch         AS 'v_patch'      ,
                                  system_versions.extension     AS 'v_extension'  ,
                                  system_versions.release_date  AS 'v_date'
                        FROM      system_versions
                        ORDER BY  system_versions.release_date  DESC  ,
                                  system_versions.id            DESC  ");

  // Prepare the data
  for($i = 0; $row = mysqli_fetch_array($qversions); $i++)
  {
    $data[$i]['id']         = $row['v_id'];
    $data[$i]['version']    = sanitize_output(system_assemble_version_number($row['v_major'], $row['v_minor'], $row['v_patch'], $row['v_extension']));
    $data[$i]['date_raw']   = $row['v_date'];
    $data[$i]['date']       = sanitize_output(date_to_text($row['v_date'], 1));
  }

  // Add the number of rows to the data
  $data['rows'] = $i;

  // Calculate the date differences
  for($i = 0 ; $i < $data['rows']; $i++)
  {
    // If it is the oldest version, there is no possible differential
    if($i == ($data['rows'] - 1))
    {
      $data[$i]['date_diff']  = '-';
      $data[$i]['css']        = '';
    }

    // Otherwise, calculate the time differential
    else
    {
      $temp_diff = time_days_elapsed($data[$i]['date_raw'], $data[$i + 1]['date_raw']);

      // Assemble the formatted string
      $data[$i]['date_diff'] = ($temp_diff) ? sanitize_output($temp_diff.__('day', $temp_diff, 1)) : '-';

      // Give stylings to long delays
      $temp_style       = ($temp_diff > 90) ? ' class="smallglow"' : '';
      $data[$i]['css']  = ($temp_diff > 365) ? ' class="bold glow"' : $temp_style;
    }
  }

  // In ACT debug mode, print debug data
  if($GLOBALS['dev_mode'] && $GLOBALS['act_debug_mode'])
    var_dump(array('dev.act.php', 'dev_versions_list', $data));

  // Return the prepared data
  return $data;
}




/**
 * Releases a new version of the website.
 *
 * The version number will respect the SemVer v2.0.0 standard.
 *
 * @param   int       $major            The major version number.
 * @param   int       $minor            The minor version number.
 * @param   int       $patch            The patch number.
 * @param   string    $extension        The extension string (eg. beta, rc-1, etc.).
 * @param   bool|null $publish_activity Whether to publish an entry in recent activity.
 * @param   bool|null $notify_irc       Whether to send a notification on IRC.
 *
 * @return  string|null                 NULL if all went according to plan, or an error string
 */

function dev_versions_create($major, $minor, $patch, $extension, $publish_activity=1, $notify_irc=0)
{
  // Sanitize the data
  $major            = sanitize($major, 'int', 0);
  $minor            = sanitize($minor, 'int', 0);
  $patch            = sanitize($patch, 'int', 0);
  $extension        = sanitize($extension, 'string');
  $publish_activity = sanitize($publish_activity, 'int', 0, 1);
  $notify_irc       = sanitize($notify_irc, 'int', 0, 1);
  $current_date     = sanitize(date('Y-m-d'), 'string');

  // Check if the version number already exists
  $qversion = query(" SELECT  system_versions.id AS 'v_id'
                      FROM    system_versions
                      WHERE   system_versions.major     =     '$major'
                      AND     system_versions.minor     =     '$minor'
                      AND     system_versions.patch     =     '$patch'
                      AND     system_versions.extension LIKE  '$extension' ");

  // If it already exists, stop the process
  if(mysqli_num_rows($qversion))
    return __('dev_versions_edit_error_duplicate');

  // Create the new version
  query(" INSERT INTO   system_versions
          SET           system_versions.major         = '$major'      ,
                        system_versions.minor         = '$minor'      ,
                        system_versions.patch         = '$patch'      ,
                        system_versions.extension     = '$extension'  ,
                        system_versions.release_date  = '$current_date' ");

  // Fetch the ID of the newly created version
  $version_id = sanitize(query_id(), 'int', 0);

  // Assemble the version number
  $version_number = system_assemble_version_number($major, $minor, $patch, $extension);

  // Log the activity
  if($publish_activity)
    log_activity('dev_version', 0, 'ENFR', $version_id, $version_number, $version_number);

  // Send a message on IRC
  if($notify_irc)
  ircbot_send_message("A new version of NoBleme has been released: $version_number - ".$GLOBALS['website_url']."todo_link", "dev");

  // Return that all went well
  return;
}




/**
 * Edits an entry in the website's version numbering history.
 *
 * @param   int         $di           The version's id.
 * @param   int         $major        The major version number.
 * @param   int         $minor        The minor version number.
 * @param   int         $patch        The patch number.
 * @param   string      $extension    The extension string (eg. beta, rc-1, etc.).
 * @param   string      $release_date The version's release date.
 *
 * @return  string|null               NULL if all went according to plan, or an error string
 */

function dev_versions_edit($id, $major, $minor, $patch, $extension, $release_date)
{
  // Sanitize the data
  $id            = sanitize($id, 'int', 0);
  $major         = sanitize($major, 'int', 0);
  $minor         = sanitize($minor, 'int', 0);
  $patch         = sanitize($patch, 'int', 0);
  $extension     = sanitize($extension, 'string');
  $release_date  = sanitize(date_to_mysql($release_date, date('Y-m-d')), 'string');

  // Check if the version exists
  if(!database_row_exists('system_versions', $id))
    return __('dev_versions_edit_error_id');

  // Check if the version number already exists
  $dversion = mysqli_fetch_array(query("  SELECT  system_versions.id AS 'v_id'
                                          FROM    system_versions
                                          WHERE   system_versions.major     =     '$major'
                                          AND     system_versions.minor     =     '$minor'
                                          AND     system_versions.patch     =     '$patch'
                                          AND     system_versions.extension LIKE  '$extension' "));

  // If it already exists (and isn't the current version), stop the process
  if($dversion['v_id'] && ($dversion['v_id'] != $id))
    return __('dev_versions_edit_error_duplicate');

  // Edit the version
  query(" UPDATE  system_versions
          SET     system_versions.major         = '$major'  ,
                  system_versions.minor         = '$minor'  ,
                  system_versions.patch         = '$patch'  ,
                  system_versions.extension     = '$extension'  ,
                  system_versions.release_date  = '$release_date'
          WHERE   system_versions.id            = '$id' ");

  // Return that all went well
  return;
}




/**
 * Deletes an entry in the website's version numbering history.
 *
 * @param   int         $version_id   The version number's id.
 *
 * @return  string|int                The version number, or 0 if the version does not exist.
 */

function dev_versions_delete($version_id)
{
  // Sanitize the data
  $version_id = sanitize($version_id, 'int', 0);

  // Ensure the version number exists or return 0
  if(!database_row_exists('system_versions', $version_id))
    return 0;

  // Fetch the version number
  $dversion = mysqli_fetch_array(query("  SELECT    system_versions.major     AS 'v_major'      ,
                                                    system_versions.minor     AS 'v_minor'      ,
                                                    system_versions.patch     AS 'v_patch'      ,
                                                    system_versions.extension AS 'v_extension'
                                          FROM      system_versions
                                          WHERE     system_versions.id = '$version_id' "));

  // Assemble the version number
  $version_number = system_assemble_version_number($dversion['v_major'], $dversion['v_minor'], $dversion['v_patch'], $dversion['v_extension']);

  // Delete the entry
  query(" DELETE FROM system_versions
          WHERE       system_versions.id = '$version_id' ");

  // Delete the related activity logs
  log_activity_delete('dev_version', 0, 0, NULL, $version_id);

  // Return the deleted version number
  return $version_number;
}




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                      IRC BOT                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

/**
 * Starts the IRC bot.
 *
 * @param   string|null $path (OPTIONAL)  The path to the root of the website.
 *
 * @return  string|null                   A string if an error happened, nothing if the loop is running as intended.
 */

function irc_bot_start($path='./../../')
{
  // Check if the required files have been included
  require_included_file('dev.lang.php');

  // Bot settings
  $irc_bot_file     = $path.$GLOBALS['irc_bot_file_name'];
  $irc_bot_server   = $GLOBALS['irc_bot_server'];
  $irc_bot_port     = $GLOBALS['irc_bot_port'];
  $irc_bot_channels = $GLOBALS['irc_bot_channels'];
  $irc_bot_nickname = $GLOBALS['irc_bot_nickname'];
  $irc_bot_password = $GLOBALS['irc_bot_password'];

  // Don't run the bot in dev mode
  if($GLOBALS['dev_mode'])
    return __('irc_bot_start_dev_mode');

  // Check if the file used by the bot exists
  if(!file_exists($irc_bot_file))
    return __('irc_bot_start_no_file');

  // Open the IRC socket
  if(!$irc_socket = fsockopen($irc_bot_server, $irc_bot_port))
    return __('irc_bot_start_failed');

  // Remove the time limit so that the script can run forever
  set_time_limit(0);

  // Declare the USER and change the bot's nickname
  fputs($irc_socket, "USER $irc_bot_nickname $irc_bot_nickname $irc_bot_nickname $irc_bot_nickname :$irc_bot_nickname\r\n");
  fputs($irc_socket, "NICK $irc_bot_nickname\r\n");

  // The server will ask for a PING reply, fetch the pings and PONG them back
  $irc_ping = fgets($irc_socket);
  $irc_ping = fgets($irc_socket);
  $irc_ping = fgets($irc_socket);
  fputs($irc_socket, str_replace('PING', 'PONG', $irc_ping)."\r\n");

  // Identify as the bot
  fputs($irc_socket, "NickServ IDENTIFY $irc_bot_nickname $irc_bot_password\r\n");

  // Once the PONG gets accepted, send the request to join the channels
  foreach($irc_bot_channels AS $irc_bot_channel)
    fputs($irc_socket, "JOIN #".$irc_bot_channel."\r\n");

  // Reset the bot's file reading pointer before entering the loop
  $latest_message = file_get_contents($irc_bot_file);

  // The bot will run in this infinite loop
  while(1)
  {
    // Quirk of PHP, if we don't set this constantly the script might hang
    stream_set_timeout($irc_socket, 1);

    // Check for a PING
    while (($irc_socket_contents = fgets($irc_socket, 512)) !== false)
    {
      flush();
      $irc_ping = explode(' ', $irc_socket_contents);

      // If a PING is found, reply with the appropriate PONG
      if($irc_ping[0] == 'PING')
        fputs($irc_socket,"PONG ".$irc_ping[1]."\r\n");
    }

    // Kill the bot in dramatic fashion if its txt file is gone
    if(!file_exists($irc_bot_file))
    {
      fputs($irc_socket,"QUIT :My life-file is gone, so shall I leave\r\n");
      exit();
    }

    // Check the bot's txt file for an order to quit
    if(substr(file_get_contents($irc_bot_file),0,4) == 'quit' || substr(file_get_contents($irc_bot_file),11,4) == 'quit')
    {
      fputs($irc_socket,"QUIT :Getting terminated... I'll be back\r\n");
      exit();
    }

    // Check if the bot's txt file has changed
    if($latest_message != file_get_contents($irc_bot_file))
    {
      // Update the status of the loop
      $latest_message = file_get_contents($irc_bot_file);

      // Send the first line of the bot's txt file on IRC
      $irc_bot_file_contents  = fopen($irc_bot_file, 'r');
      $irc_bot_pointer_line   = fgets($irc_bot_file_contents);
      fputs($irc_socket, substr($irc_bot_pointer_line, 11).PHP_EOL);
      fclose($irc_bot_file_contents);

      // Delete the first line of the bot's txt file
      $irc_bot_file_data = file($irc_bot_file, FILE_IGNORE_NEW_LINES);
      array_shift($irc_bot_file_data);
      file_put_contents($irc_bot_file, implode("\r\n", $irc_bot_file_data));
    }

    // Avoid a potential exponential memory leak by flushing the buffer then manually triggering garbage collection
    flush();
    gc_collect_cycles();
  }
}




/**
 * Stops the IRC bot.
 *
 * @return  void
 */

function irc_bot_stop()
{
  // Execute order 66
  ircbot_send_message('quit');
}