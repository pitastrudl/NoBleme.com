<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*  meetups_get                         Fetches data related to a meetup.                                            */
/*  meetups_list                        Fetches a list of meetups.                                                   */
/*                                                                                                                   */
/*  meetups_attendees_list              Fetches a list of people attending a meetup.                                 */
/*  meetups_attendees_add               Adds an attendee to a meetup.                                                */
/*  meetups_attendees_update_count      Recounts the number of people attending a meetup.                            */
/*                                                                                                                   */
/*  meetups_list_years                  Fetches the years at which meetups happened.                                 */
/*  meetups_get_max_attendees           Fetches the highest number of attendees in a meetup.                         */
/*                                                                                                                   */
/*********************************************************************************************************************/


/**
 * Fetches data related to a meetup.
 *
 * @param   int         $meetup_id  The meetup's id.
 *
 * @return  array|null              An array containing related data, or null if it does not exist.
 */

function meetups_get( int $meetup_id ) : mixed
{
  // Check if the required files have been included
  require_included_file('functions_time.inc.php');
  require_included_file('bbcodes.inc.php');

  // Sanitize the data
  $meetup_id = sanitize($meetup_id, 'int', 0);

  // Check if the meetup exists
  if(!database_row_exists('meetups', $meetup_id))
    return NULL;

  // Fetch the user's language
  $lang = sanitize(string_change_case(user_get_language(), 'lowercase'), 'string');

  // Fetch the data
  $dmeetup = mysqli_fetch_array(query(" SELECT    meetups.is_deleted      AS 'm_deleted'    ,
                                                  meetups.event_date      AS 'm_date'       ,
                                                  meetups.location        AS 'm_location'   ,
                                                  meetups.languages       AS 'm_lang'       ,
                                                  meetups.details_$lang   AS 'm_details'
                                        FROM      meetups
                                        WHERE     meetups.id = '$meetup_id' "));

  // Only moderators can see deleted meetups
  if($dmeetup['m_deleted'] && !user_is_moderator())
    return NULL;

  // Assemble an array with the data
  $data['id']             = $meetup_id;
  $data['is_deleted']     = sanitize_output($dmeetup['m_deleted']);
  $data['is_finished']    = (strtotime(date('Y-m-d')) > strtotime($dmeetup['m_date']));
  $data['is_today']       = (date('Y-m-d') == $dmeetup['m_date']);
  $data['date']           = sanitize_output(date_to_text($dmeetup['m_date']));
  $data['date_en']        = sanitize_output(date_to_text($dmeetup['m_date'], lang: 'EN'));
  $data['date_short_en']  = date_to_text($dmeetup['m_date'], strip_day: 1, lang: 'EN');
  $data['date_short_fr']  = date_to_text($dmeetup['m_date'], strip_day: 1, lang: 'FR');
  $data['location']       = sanitize_output($dmeetup['m_location']);
  $temp                   = time_days_elapsed(date('Y-m-d'), $dmeetup['m_date']);
  $data['days_until']     = sanitize_output($temp.__('day', amount: $temp, spaces_before: 1));
  $data['wrong_lang_en']  = ($lang == 'en' && !str_contains($dmeetup['m_lang'], 'EN'));
  $data['wrong_lang_fr']  = ($lang == 'fr' && !str_contains($dmeetup['m_lang'], 'FR'));
  $data['details']        = bbcodes(sanitize_output($dmeetup['m_details'], preserve_line_breaks: true));

  // Return the data
  return $data;
}




/**
 * Returns a list of meetups.
 *
 * @param   string  $sort_by  (OPTIONAL)  How the returned data should be sorted.
 * @param   array   $search   (OPTIONAL)  Search for specific field values.
 *
 * @return  array                         An array containing meetups.
 */

function meetups_list(  string  $sort_by  = 'date'  ,
                        array   $search   = array() ) : array
{
  // Sanitize the search parameters
  $search_date      = isset($search['date'])      ? sanitize($search['date'], 'int', 0)     : NULL;
  $search_lang      = isset($search['lang'])      ? sanitize($search['lang'], 'string')     : NULL;
  $search_location  = isset($search['location'])  ? sanitize($search['location'], 'string') : NULL;
  $search_people    = isset($search['people'])    ? sanitize($search['people'], 'int', 0)   : 0;

  // Fetch the meetups
  $qmeetups = "     SELECT    meetups.id              AS 'm_id'       ,
                              meetups.is_deleted      AS 'm_deleted'  ,
                              meetups.event_date      AS 'm_date'     ,
                              meetups.location        AS 'm_location' ,
                              meetups.languages       AS 'm_lang'     ,
                              meetups.attendee_count  AS 'm_people'   ,
                              meetups.details_en      AS 'm_desc_en'  ,
                              meetups.details_fr      AS 'm_desc_fr'
                    FROM      meetups
                    WHERE     1 = 1 ";

  // Do not show deleted meetups to regular users
  if(!user_is_moderator())
    $qmeetups .= "  AND       meetups.is_deleted        = 0                     ";

  // Search the data
  if($search_date)
    $qmeetups .= "  AND       YEAR(meetups.event_date)  = '$search_date'        ";
  if($search_lang == 'ENFR' || $search_lang == 'FREN')
    $qmeetups .= "  AND     ( meetups.languages      LIKE 'ENFR'
                    OR        meetups.languages      LIKE 'FREN' )              ";
  else if($search_lang)
    $qmeetups .= "  AND       meetups.languages      LIKE '$search_lang'        ";
  if($search_location)
    $qmeetups .= "  AND       meetups.location       LIKE '%$search_location%'  ";
  if($search_people)
    $qmeetups .= "  AND       meetups.attendee_count   >= '$search_people'      ";

  // Sort the data
  if($sort_by == 'location')
    $qmeetups .= " ORDER BY   meetups.location        ASC   ,
                              meetups.event_date      DESC  ";
  else if($sort_by == 'people')
    $qmeetups .= " ORDER BY   meetups.attendee_count  DESC  ,
                              meetups.event_date      DESC  ";
  else
    $qmeetups .= " ORDER BY   meetups.event_date      DESC  ";

  // Run the query
  $qmeetups = query($qmeetups);

  // Prepare the data
  for($i = 0; $row = mysqli_fetch_array($qmeetups); $i++)
  {
    $data[$i]['id']         = $row['m_id'];
    $temp                   = ($row['m_deleted']) ? 'text_red': 'green dark_hover text_white';
    $temp2                  = strtotime(date('Y-m-d'));
    $data[$i]['css']        = ($row['m_deleted'] || (strtotime($row['m_date']) >= $temp2)) ? ' '.$temp : '';
    $data[$i]['css_link']   = ($row['m_deleted']) ? 'text_red text_white_hover' : '';
    $data[$i]['deleted']    = $row['m_deleted'];
    $data[$i]['date']       = sanitize_output(date_to_text($row['m_date']));
    $data[$i]['lang_en']    = str_contains($row['m_lang'], 'EN');
    $data[$i]['lang_fr']    = str_contains($row['m_lang'], 'FR');
    $data[$i]['location']   = sanitize_output($row['m_location']);
    $data[$i]['people']     = sanitize_output($row['m_people']);
  }

  // Add the number of rows to the data
  $data['rows'] = $i;

  // Return the prepared data
  return $data;
}




/**
 * Returns a list of people attending a meetup.
 *
 * @param   string  $meetup_id  (OPTIONAL)  The meetup's id.
 *
 * @return  array                           An array containing meetup attendees, or NULL if it doesn't exist.
 */

function meetups_attendees_list( int $meetup_id = 0 ) : array
{
  // Check if the required files have been included
  require_included_file('bbcodes.inc.php');

  // Sanitize the data
  $meetup_id = sanitize($meetup_id, 'int', 0);

  // Check if the meetup exists
  if(!database_row_exists('meetups', $meetup_id))
    return NULL;

  // Fetch the user's language
  $lang = sanitize(string_change_case(user_get_language(), 'lowercase'), 'string');

  // Fetch the attendees
  $qattendees = query(" SELECT    meetups.is_deleted                      AS 'm_deleted'  ,
                                  users.id                                AS 'u_id'       ,
                                  users.username                          AS 'u_nick'     ,
                                  meetups_people.username                 AS 'm_nick'     ,
                                  meetups_people.attendance_confirmed     AS 'm_lock'     ,
                                  meetups_people.extra_information_$lang  AS 'm_extra'
                        FROM      meetups_people
                        LEFT JOIN users   ON meetups_people.fk_users    = users.id
                        LEFT JOIN meetups ON meetups_people.fk_meetups  = meetups.id
                        WHERE     meetups_people.fk_meetups             = '$meetup_id'
                        ORDER BY  IF(users.username IS NULL, meetups_people.username, users.username) ASC ");

  // Loop through the data
  for($i = 0; $row = mysqli_fetch_array($qattendees); $i++)
  {
    // Only moderators can see deleted meetups
    if(!$i && $row['m_deleted'] && !user_is_moderator())
      return NULL;

    // Prepare the data
    $data[$i]['user_id']  = sanitize_output($row['u_id']);
    $data[$i]['nick']     = ($row['m_nick']) ? sanitize_output($row['m_nick']) : sanitize_output($row['u_nick']);
    $data[$i]['lock']     = $row['m_lock'];
    $data[$i]['extra']    = bbcodes(sanitize_output($row['m_extra']));
  }

  // Add the number of rows to the data
  $data['rows'] = $i;

  // Return the prepared data
  return $data;
}



/**
 * Adds an attendee to a meetup.
 *
 * @param   int     $meetup_id  The ID of the meetup to which the attendee will be added.
 * @param   array   $contents   Data regarding the attendee.
 *
 * @return  void
 */

function meetups_attendees_add( int   $meetup_id  ,
                                array $contents   ) : void
{
  // Check if the required files have been included
  require_included_file('users.inc.php');

  // Only moderators can run this action
  user_restrict_to_moderators();

  // Sanitize and prepare the data
  $meetup_id  = sanitize($meetup_id, 'int', 0);
  $account    = (isset($contents['account']))   ? sanitize($contents['account'], 'string')            : '';
  $nickname   = (isset($contents['nickname']))  ? sanitize($contents['nickname'], 'string', max: 20)  : '';
  $extra_en   = (isset($contents['extra_en']))  ? sanitize($contents['extra_en'], 'string')           : '';
  $extra_fr   = (isset($contents['extra_fr']))  ? sanitize($contents['extra_fr'], 'string')           : '';
  $lock       = (isset($contents['lock']))      ? sanitize($contents['lock'], 'bool')                 : 'false';
  $lock       = ($lock == 'true')               ? 1                                                   : 0;
  $username   = ($nickname)                     ? string_truncate($contents['nickname'], 20) : $contents['account'];

  // Error: No account or nickname provided
  if(!$account && !$nickname)
    return;

  // Error: Meetup does not exist
  if(!database_row_exists('meetups', $meetup_id))
    return;

  // Remove the account's name if it does not exist
  if(!user_check_username($account))
    $account = '';

  // Fetch the account's id
  if($account)
    $account_id = sanitize(database_entry_exists('users', 'username', $account), 'int');
  else
    $account_id = 0;

  // Error: Account is already attending the meetup
  if($account_id)
  {
    $dattendee = mysqli_fetch_array(query(" SELECT  meetups_people.id AS 'p_id'
                                            FROM    meetups_people
                                            WHERE   meetups_people.fk_meetups = '$meetup_id'
                                            AND     meetups_people.fk_users   = '$account_id' "));
    if(isset($dattendee['p_id']))
      return;
  }

  // Error: Nickname is already attending the meetup
  if($nickname)
  {
    $dattendee = mysqli_fetch_array(query(" SELECT    meetups_people.id AS 'p_id'
                                            FROM      meetups_people
                                            LEFT JOIN users ON meetups_people.fk_users = users.id
                                            WHERE     meetups_people.fk_meetups =     '$meetup_id'
                                            AND     ( meetups_people.username   LIKE  '$nickname'
                                            OR        users.username            LIKE  '$nickname' ) "));
    if(isset($dattendee['p_id']))
      return;
  }

  // Add the attendee
  query(" INSERT INTO meetups_people
          SET         meetups_people.fk_meetups           = '$meetup_id'  ,
                      meetups_people.fk_users             = '$account_id' ,
                      meetups_people.username             = '$nickname'   ,
                      meetups_people.attendance_confirmed = '$lock'       ,
                      meetups_people.extra_information_en = '$extra_en'   ,
                      meetups_people.extra_information_fr = '$extra_fr'   ");

  // Recount the meetup's attendee count
  meetup_attendees_update_count($meetup_id);

  // Fetch data on the meetup
  $dmeetup = mysqli_fetch_array(query(" SELECT  meetups.is_deleted  AS 'm_deleted'  ,
                                                meetups.event_date  AS 'm_date'     ,
                                                meetups.languages   AS 'm_lang'
                                        FROM    meetups
                                        WHERE   meetups.id = '$meetup_id' "));

  // Prepare the meetup data
  $meetup_deleted   = $dmeetup['m_deleted'];
  $meetup_date      = sanitize($dmeetup['m_date'], 'string');
  $meetup_date_en   = date_to_text($dmeetup['m_date'], lang: 'EN');
  $meetup_date_fr   = date_to_text($dmeetup['m_date'], lang: 'FR');
  $meetup_lang      = sanitize($dmeetup['m_lang'], 'string');

  // Fetch the username of the moderator adding the attendee
  $mod_username = user_get_username();

  // Activity log, for future meetups only
  if(!$meetup_deleted && strtotime($meetup_date) > strtotime(date('Y-m-d')))
    log_activity( 'meetups_people_new'                ,
                  language:             $meetup_lang  ,
                  activity_id:          $meetup_id    ,
                  activity_summary_en:  $meetup_date  ,
                  activity_summary_fr:  $meetup_date  ,
                  username:             $username     );

  // Moderation log
  log_activity( 'meetups_people_new'                ,
                is_moderators_only:   true          ,
                activity_id:          $meetup_id    ,
                activity_summary_en:  $meetup_date  ,
                activity_summary_fr:  $meetup_date  ,
                username:             $username     ,
                moderator_username:   $mod_username );

  // IRC bot message
  if(str_contains($meetup_lang, 'EN') && !$meetup_deleted && strtotime($meetup_date) > strtotime(date('Y-m-d')))
    irc_bot_send_message("$username has joined the $meetup_date_en real life meetup - ".$GLOBALS['website_url']."pages/meetups/".$meetup_id, 'english');
  if(str_contains($meetup_lang, 'FR') && !$meetup_deleted && strtotime($meetup_date) > strtotime(date('Y-m-d')))
    irc_bot_send_message("$username a rejoint la rencontre IRL du $meetup_date_fr - ".$GLOBALS['website_url']."pages/meetups/".$meetup_id, 'french');
  if($meetup_deleted)
    irc_bot_send_message("$mod_username has added $username to the deleted $meetup_date_en real life meetup - ".$GLOBALS['website_url']."pages/meetups/".$meetup_id, 'mod');
  if(strtotime($meetup_date) == strtotime(date('Y-m-d')))
    irc_bot_send_message("$mod_username has added $username to the currently ongoing $meetup_date_en real life meetup - ".$GLOBALS['website_url']."pages/meetups/".$meetup_id, 'mod');
  if(strtotime($meetup_date) < strtotime(date('Y-m-d')))
    irc_bot_send_message("$mod_username has added $username to the already finished $meetup_date_en real life meetup - ".$GLOBALS['website_url']."pages/meetups/".$meetup_id, 'mod');

  // Discord message
  if(!$meetup_deleted && strtotime($meetup_date) > strtotime(date('Y-m-d')))
  {
    $discord_message = "$username has joined the $meetup_date_en real life meetup";
    $discord_message .= PHP_EOL."$username a rejoint la rencontre IRL du $meetup_date_fr";
    $discord_message .= PHP_EOL.$GLOBALS['website_url']."pages/meetups/".$meetup_id;
    discord_send_message($discord_message, 'main');
  }
}




/**
 * Recounts the number of people attending a meetup.
 *
 * @param   int   $meetup_id  The meetup's ID
 *
 * @return  void
 */

function meetup_attendees_update_count( int $meetup_id ) : void
{
  // Sanitize the id
  $meetup_id = sanitize($meetup_id, 'int', 0);

  // Fetch the meetup's attendee count
  $dattendees = mysqli_fetch_array(query("  SELECT  COUNT(*) AS 'p_count'
                                            FROM    meetups_people
                                            WHERE   meetups_people.fk_meetups = '$meetup_id' "));

  // Sanitize the count
  $attendees = sanitize($dattendees['p_count'], 'int', 0);

  // Update the count
  query(" UPDATE  meetups
          SET     meetups.attendee_count  = '$attendees'
          WHERE   meetups.id              = '$meetup_id' ");
}




/**
 * Fetches the years at which meetups happened.
 *
 * @return  array   An array containing years.
 */

function meetups_list_years() : array
{
  // Fetch the meetup years
  $qmeetups = query(" SELECT    YEAR(meetups.event_date) AS 'm_year'
                      FROM      meetups
                      GROUP BY  YEAR(meetups.event_date)
                      ORDER BY  YEAR(meetups.event_date) DESC ");

  // Prepare the data
  for($i = 0; $row = mysqli_fetch_array($qmeetups); $i++)
    $data[$i]['year'] = sanitize_output($row['m_year']);

  // Add the number of rows to the data
  $data['rows'] = $i;

  // Return the prepared data
  return $data;
}




/**
 * Fetches the highest number of attendees in a meetup.
 *
 * @return  int   The highest number of attendees in a meetup.
 */

function meetups_get_max_attendees() : int
{
  // Look up the most attended meetup
  $dmeetups = mysqli_fetch_array(query("  SELECT  MAX(meetups.attendee_count) AS 'm_max'
                                          FROM    meetups "));

  // Return the result
  return $dmeetups['m_max'];
}