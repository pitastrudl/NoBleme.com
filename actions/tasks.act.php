<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*  tasks_get                 Returns data related to a task.                                                        */
/*  tasks_list                Fetches a list of tasks.                                                               */
/*  tasks_add                 Creates a new task.                                                                    */
/*  tasks_propose             Creates a new unvalidated task proposal.                                               */
/*  tasks_approve             Approves an unvalidated task proposal.                                                 */
/*  tasks_reject              Rejects and hard deletes an unvalidated task proposal.                                 */
/*                                                                                                                   */
/*  tasks_categories_list     Fetches a list of task categories.                                                     */
/*  tasks_categories_add      Creates a new task category.                                                           */
/*  tasks_categories_edit     Modifies a task category.                                                              */
/*  tasks_categories_delete   Deletes a task category.                                                               */
/*                                                                                                                   */
/*  tasks_milestones_list     Fetches a list of task milestones.                                                     */
/*  tasks_milestones_add      Creates a new task milestone.                                                          */
/*  tasks_milestones_edit     Modifies a task milestone.                                                             */
/*  tasks_milestones_delete   Deletes a task milestone.                                                              */
/*                                                                                                                   */
/*********************************************************************************************************************/


/**
 * Returns data related to a task.
 *
 * @param   int         $task_id  The task's id.
 *
 * @return  array|null            An array containing task related data, or NULL if it does not exist.
 */

function tasks_get( int $task_id ) : mixed
{
  // Check if the required files have been included
  require_included_file('functions_time.inc.php');
  require_included_file('bbcodes.inc.php');

  // Get the user's current language and access rights
  $lang     = sanitize(string_change_case(user_get_language(), 'lowercase'), 'string');
  $is_admin = user_is_administrator();

  // Sanitize the data
  $task_id = sanitize($task_id, 'int', 0);

  // Check if the task exists
  if(!database_row_exists('dev_tasks', $task_id))
    return NULL;

  // Fetch the data
  $dtask = mysqli_fetch_array(query(" SELECT    dev_tasks.is_deleted              AS 't_deleted'    ,
                                                dev_tasks.admin_validation        AS 't_validated'  ,
                                                dev_tasks.is_public               AS 't_public'     ,
                                                dev_tasks.priority_level          AS 't_priority'   ,
                                                dev_tasks.created_at              AS 't_created'    ,
                                                dev_tasks.finished_at             AS 't_solved'     ,
                                                dev_tasks.title_$lang             AS 't_title'      ,
                                                dev_tasks.title_en                AS 't_title_en'   ,
                                                dev_tasks.title_fr                AS 't_title_fr'   ,
                                                dev_tasks.body_$lang              AS 't_body'       ,
                                                dev_tasks.body_en                 AS 't_body_en'    ,
                                                dev_tasks.body_fr                 AS 't_body_fr'    ,
                                                dev_tasks.source_code_link        AS 't_source'     ,
                                                users.id                          AS 'u_id'         ,
                                                users.username                    AS 'u_name'       ,
                                                dev_tasks_categories.title_$lang  AS 'tc_name'      ,
                                                dev_tasks_milestones.title_$lang  AS 'tm_name'
                                      FROM      dev_tasks
                                      LEFT JOIN users
                                      ON        dev_tasks.fk_users                = users.id
                                      LEFT JOIN dev_tasks_categories
                                      ON        dev_tasks.fk_dev_tasks_categories = dev_tasks_categories.id
                                      LEFT JOIN dev_tasks_milestones
                                      ON        dev_tasks.fk_dev_tasks_milestones = dev_tasks_milestones.id
                                      WHERE     dev_tasks.id                      = '$task_id' "));

  // Return null if the task should not be displayed
  if(!$is_admin && $dtask['t_deleted'])
    return NULL;
  if(!$is_admin && !$dtask['t_validated'])
    return NULL;
  if(!$is_admin && !$dtask['t_public'])
    return NULL;

  // Assemble an array with the data
  $data['validated']      = $dtask['t_validated'];
  $data['public']         = $dtask['t_public'];
  $data['title']          = sanitize_output($dtask['t_title']);
  $temp                   = ($dtask['t_title_en']) ? $dtask['t_title_en'] : $dtask['t_title_fr'];
  $data['title_flex']     = sanitize_output($temp);
  $data['title_en_raw']   = $dtask['t_title_en'];
  $data['title_fr_raw']   = $dtask['t_title_fr'];
  $data['created']        = sanitize_output(date_to_text($dtask['t_created'], strip_day: 1));
  $data['created_full']   = sanitize_output(date_to_text($dtask['t_created'], include_time: 1));
  $data['created_since']  = sanitize_output(time_since($dtask['t_created']));
  $data['creator']        = sanitize_output($dtask['u_name']);
  $data['creator_id']     = sanitize_output($dtask['u_id']);
  $temp                   = sanitize_output(date_to_text($dtask['t_solved'], strip_day: 1));
  $data['solved']         = ($dtask['t_solved']) ? $temp : '';
  $data['solved_full']    = sanitize_output(date_to_text($dtask['t_solved'], include_time: 1));
  $data['solved_since']   = sanitize_output(time_since($dtask['t_solved']));
  $data['priority']       = sanitize_output($dtask['t_priority']);
  $data['category']       = sanitize_output($dtask['tc_name']);
  $data['milestone']      = sanitize_output($dtask['tm_name']);
  $data['body']           = bbcodes(sanitize_output($dtask['t_body'], preserve_line_breaks: true));
  $temp                   = ($dtask['t_body_en']) ? $dtask['t_body_en'] : $dtask['t_body_fr'];
  $data['body_flex']      = bbcodes(sanitize_output($temp, preserve_line_breaks: true));
  $data['body_proposal']  = sanitize_output($temp);
  $data['source']         = ($dtask['t_source']) ? sanitize_output($dtask['t_source']) : '';
  $temp                   = ($dtask['t_title_en']) ? $dtask['t_title_en'] : $dtask['t_title_fr'];
  $data['summary']        = sanitize_output('Task #'.$task_id.': '.string_truncate($temp, 100, '…'));

  // Return the array
  return $data;
}




/**
 * Fetches a list of tasks.
 *
 * @param   string  $sort_by  (OPTIONAL)  How the returned data should be sorted.
 * @param   array   $search   (OPTIONAL)  Search for specific field values.
 *
 * @return  array                         An array containing tasks.
 */

function tasks_list(  string  $sort_by  = 'status'  ,
                      array   $search   = array()   ) : array
{
  // Check if the required files have been included
  require_included_file('functions_time.inc.php');
  require_included_file('functions_numbers.inc.php');
  require_included_file('functions_mathematics.inc.php');

  // Get the user's current language and access rights
  $lang     = sanitize(string_change_case(user_get_language(), 'lowercase'), 'string');
  $is_admin = user_is_administrator();

  // Sanitize the search parameters
  $search_id        = isset($search['id'])        ? sanitize($search['id'], 'int', 0)         : 0;
  $search_title     = isset($search['title'])     ? sanitize($search['title'], 'string')      : '';
  $search_status    = isset($search['status'])    ? sanitize($search['status'], 'int', -1)    : 0;
  $search_status_id = sanitize($search_status - 1, 'int', 0, 5);
  $search_created   = isset($search['created'])   ? sanitize($search['created'], 'int', 0)    : 0;
  $search_reporter  = isset($search['reporter'])  ? sanitize($search['reporter'], 'string')   : '';
  $search_category  = isset($search['category'])  ? sanitize($search['category'], 'int', -1)  : 0;
  $search_goal      = isset($search['goal'])      ? sanitize($search['goal'], 'int', -1)      : 0;
  $search_admin     = isset($search['admin'])     ? sanitize($search['admin'], 'int', 0, 5)   : 0;

  // Fetch the tasks
  $qtasks = "     SELECT    dev_tasks.id                      AS 't_id'           ,
                            dev_tasks.is_deleted              AS 't_deleted'      ,
                            dev_tasks.created_at              AS 't_created'      ,
                            dev_tasks.finished_at             AS 't_finished'     ,
                            dev_tasks.is_public               AS 't_public'       ,
                            dev_tasks.admin_validation        AS 't_validated'    ,
                            dev_tasks.priority_level          AS 't_status'       ,
                            dev_tasks.title_en                AS 't_title_en'     ,
                            dev_tasks.title_fr                AS 't_title_fr'     ,
                            dev_tasks_categories.id           AS 't_category_id'  ,
                            dev_tasks_categories.title_$lang  AS 't_category'     ,
                            dev_tasks_milestones.id           AS 't_milestone_id' ,
                            dev_tasks_milestones.title_$lang  AS 't_milestone'    ,
                            users.username                    AS 't_author'
                  FROM      dev_tasks
                  LEFT JOIN dev_tasks_categories  ON dev_tasks.fk_dev_tasks_categories  = dev_tasks_categories.id
                  LEFT JOIN dev_tasks_milestones  ON dev_tasks.fk_dev_tasks_milestones  = dev_tasks_milestones.id
                  LEFT JOIN users                 ON dev_tasks.fk_users                 = users.id
                  WHERE     1 = 1 ";

  // Do not show deleted, unvalidated, or private tasks to regular users
  if(!$is_admin)
    $qtasks .= "  AND       dev_tasks.is_deleted        = 0
                  AND       dev_tasks.is_public         = 1
                  AND       dev_tasks.admin_validation  = 1                     ";

  // Regular users should only see tasks with a title matching their current language
  if(!$is_admin)
    $qtasks .= "  AND       dev_tasks.title_$lang      != ''                    ";

  // Search the data
  if($search_id)
    $qtasks .= "  AND       dev_tasks.id                                = '$search_id'          ";
  if($search_title)
    $qtasks .= "  AND       dev_tasks.title_$lang                    LIKE '%$search_title%'     ";
  if($search_status == -1)
    $qtasks .= "  AND       dev_tasks.finished_at                       > 0                     ";
  else if($search_status > 0 && $search_status <= 6)
    $qtasks .= "  AND       dev_tasks.priority_level                    = '$search_status_id'
                  AND       dev_tasks.finished_at                       = 0                     ";
  else if($search_status > 6)
    $qtasks .=  " AND       YEAR(FROM_UNIXTIME(dev_tasks.finished_at))  = '$search_status'      ";
  if($search_created)
    $qtasks .= "  AND       YEAR(FROM_UNIXTIME(dev_tasks.created_at))   = '$search_created'     ";
  if($search_reporter)
    $qtasks .= "  AND       users.username                           LIKE '%$search_reporter%'  ";
  if($search_category == -1)
    $qtasks .= "  AND       dev_tasks.fk_dev_tasks_categories           = 0                     ";
  else if($search_category)
    $qtasks .= "  AND       dev_tasks.fk_dev_tasks_categories           = '$search_category'    ";
  if($search_goal == -1)
    $qtasks .= "  AND       dev_tasks.fk_dev_tasks_milestones           = 0                     ";
  else if($search_goal)
    $qtasks .= "  AND       dev_tasks.fk_dev_tasks_milestones           = '$search_goal'        ";
  if($search_admin == 1)
    $qtasks .= "  AND       dev_tasks.admin_validation                  = 0                     ";
  else if($search_admin == 2)
    $qtasks .= "  AND       dev_tasks.is_deleted                        = 1                     ";
  else if($search_admin == 3)
    $qtasks .= "  AND       dev_tasks.is_public                         = 0                     ";
  else if($search_admin == 4)
    $qtasks .= "  AND       dev_tasks.title_en                          = ''                    ";
  else if($search_admin == 5)
    $qtasks .= "  AND       dev_tasks.title_fr                          = ''                    ";

  // Sort the data
  if($sort_by == 'id')
    $qtasks .= "  ORDER BY    dev_tasks.id                      ASC     ";
  else if($sort_by == 'description')
    $qtasks .= "  ORDER BY    dev_tasks.title_$lang             ASC     ";
  else if($sort_by == 'created')
    $qtasks .= "  ORDER BY    dev_tasks.created_at              DESC    ";
  else if($sort_by == 'reporter')
    $qtasks .= "  ORDER BY    users.username                    ASC     ,
                              dev_tasks.finished_at             != ''   ,
                              dev_tasks.finished_at             DESC    ,
                              dev_tasks.priority_level          DESC    ,
                              dev_tasks.created_at              DESC    ";
  else if($sort_by == 'category')
    $qtasks .= "  ORDER BY    dev_tasks_categories.id           IS NULL ,
                              dev_tasks_categories.title_$lang  ASC     ,
                              dev_tasks.finished_at             != ''   ,
                              dev_tasks.finished_at             DESC    ,
                              dev_tasks.priority_level          DESC    ,
                              dev_tasks.created_at              DESC    ";
  else if($sort_by == 'goal')
    $qtasks .= "  ORDER BY    dev_tasks.finished_at               != ''   ,
                              dev_tasks_milestones.id             IS NULL ,
                              dev_tasks_milestones.sorting_order  DESC    ,
                              dev_tasks.finished_at               DESC    ,
                              dev_tasks.priority_level            DESC    ,
                              dev_tasks.created_at                DESC    ";
  else if($sort_by == 'admin')
    $qtasks .= "  ORDER BY    dev_tasks.admin_validation          = 1     ,
                              dev_tasks.is_deleted                = 1     ,
                              dev_tasks.title_en                  != ''   ,
                              dev_tasks.title_fr                  != ''   ,
                              dev_tasks.finished_at               != ''   ,
                              dev_tasks.finished_at               DESC    ,
                              dev_tasks.priority_level            DESC    ,
                              dev_tasks.created_at                DESC    ";
  else
    $qtasks .= "  ORDER BY    dev_tasks.admin_validation        = 1     ,
                              dev_tasks.finished_at             != ''   ,
                              dev_tasks.finished_at             DESC    ,
                              dev_tasks.priority_level          DESC    ,
                              dev_tasks.created_at              DESC    ";

  // Run the query
  $qtasks = query($qtasks);

  // Initialize the finished task counter
  $total_tasks_finished = 0;

  // Initialize the years array
  $tasks_created_years  = array();
  $tasks_solved_years   = array();

  // Loop through the results
  for($i = 0; $row = mysqli_fetch_array($qtasks); $i++)
  {
    // Prepare the data
    $data[$i]['id']         = sanitize_output($row['t_id']);
    $data[$i]['css_row']    = ($row['t_finished']) ? 'task_solved' : 'task_status_'.sanitize_output($row['t_status']);
    $temp                   = ($row['t_status'] < 2) ? ' italics' : '';
    $temp                   = ($row['t_status'] > 3) ? ' bold' : $temp;
    $temp                   = ($row['t_status'] > 4) ? ' bold uppercase underlined' : $temp;
    $data[$i]['css_status'] = ($temp && !$row['t_finished']) ? $temp : '';
    $temp                   = ($lang == 'en') ? $row['t_title_en'] : $row['t_title_fr'];
    $temp                   = ($lang == 'en' && !$row['t_title_en']) ? $row['t_title_fr'] : $temp;
    $temp                   = ($lang != 'en' && !$row['t_title_fr']) ? $row['t_title_en'] : $temp;
    $data[$i]['title']      = sanitize_output(string_truncate($temp, 42, '…'));
    $data[$i]['fulltitle']  = (strlen($temp) > 42) ? sanitize_output($temp) : '';
    $data[$i]['shorttitle'] = sanitize_output(string_truncate($temp, 38, '…'));
    $temp                   = sanitize_output(__('tasks_list_solved'));
    $data[$i]['status']     = ($row['t_finished']) ? $temp : sanitize_output(__('tasks_list_state_'.$row['t_status']));
    $data[$i]['created']    = sanitize_output(time_since($row['t_created']));
    $data[$i]['author']     = sanitize_output($row['t_author']);
    $data[$i]['category']   = sanitize_output($row['t_category']);
    $data[$i]['milestone']  = sanitize_output($row['t_milestone']);
    $data[$i]['nolang_en']  = (!$row['t_title_en']);
    $data[$i]['nolang_fr']  = (!$row['t_title_fr']);
    $data[$i]['deleted']    = ($row['t_deleted']);
    $data[$i]['private']    = (!$row['t_public']);
    $data[$i]['new']        = (!$row['t_validated']);

    // Count the finished tasks
    $total_tasks_finished += ($row['t_finished']) ? 1 : 0;

    // Fill up the years arrays
    if(!in_array(date('Y', $row['t_created']), $tasks_created_years))
      array_push($tasks_created_years, sanitize_output(date('Y', $row['t_created'])));
    if($row['t_finished'] && !in_array(date('Y', $row['t_finished']), $tasks_solved_years))
      array_push($tasks_solved_years, sanitize_output(date('Y', $row['t_finished'])));
  }

  // Add the number of rows to the data
  $data['rows'] = $i;

  // Calculate the remaining totals
  $total_tasks_todo       = $data['rows'] - $total_tasks_finished;
  $total_tasks_percentage = maths_percentage_of($total_tasks_finished, $data['rows']);

  // Add the totals to the data
  $data['finished'] = sanitize_output($total_tasks_finished);
  $data['todo']     = sanitize_output($total_tasks_todo);
  $data['percent']  = sanitize_output(number_display_format($total_tasks_percentage, 'percentage', 0));

  // Sort the years arrays
  rsort($tasks_created_years);
  rsort($tasks_solved_years);

  // Add the years to the data
  $data['years_created']  = $tasks_created_years;
  $data['years_solved']   = $tasks_solved_years;

  // Return the prepared data
  return $data;
}




/**
 * Creates a new task.
 *
 * @param   array       $contents   The contents of the task.
 *
 * @return  string|int              An error string, or the task's id if it was properly created.
 */

function tasks_add( array $contents ) : mixed
{
  // Check if the required files have been included
  require_included_file('tasks.lang.php');

  // Only administrators can run this action
  user_restrict_to_administrators();

  // Sanitize and prepare the data
  $task_title_en  = (isset($contents['title_en']))  ? sanitize($contents['title_en'], 'string')     : '';
  $task_title_fr  = (isset($contents['title_fr']))  ? sanitize($contents['title_fr'], 'string')     : '';
  $task_body_en   = (isset($contents['body_en']))   ? sanitize($contents['body_en'], 'string')      : '';
  $task_body_fr   = (isset($contents['body_fr']))   ? sanitize($contents['body_fr'], 'string')      : '';
  $task_priority  = (isset($contents['priority']))  ? sanitize($contents['priority'], 'int', 0, 5)  : 0;
  $task_category  = (isset($contents['category']))  ? sanitize($contents['category'], 'int', 0)     : 0;
  $task_milestone = (isset($contents['milestone'])) ? sanitize($contents['milestone'], 'int', 0)    : 0;
  $task_private   = (isset($contents['private']))   ? sanitize($contents['private'], 'int', 0, 1)   : 0;
  $task_public    = ($task_private)                 ? 0                                             : 1;
  $task_silent    = (isset($contents['silent']))    ? sanitize($contents['silent'], 'int', 0, 1)    : 0;

  // Error: No title
  if(!$task_title_en && !$task_title_fr)
    return __('tasks_add_error_title');

  // Fetch the current user's ID and timestamp
  $user_id    = sanitize(user_get_id(), 'int', 0);
  $timestamp  = sanitize(time(), 'int', 0);

  // Create the task
  query(" INSERT INTO dev_tasks
          SET         dev_tasks.fk_users                  = '$user_id'        ,
                      dev_tasks.created_at                = '$timestamp'      ,
                      dev_tasks.admin_validation          = 1                 ,
                      dev_tasks.is_public                 = '$task_public'    ,
                      dev_tasks.priority_level            = '$task_priority'  ,
                      dev_tasks.title_en                  = '$task_title_en'  ,
                      dev_tasks.title_fr                  = '$task_title_fr'  ,
                      dev_tasks.body_en                   = '$task_body_en'   ,
                      dev_tasks.body_fr                   = '$task_body_fr'   ,
                      dev_tasks.fk_dev_tasks_categories   = '$task_category'  ,
                      dev_tasks.fk_dev_tasks_milestones   = '$task_milestone' ");

  // Fetch the newly created task's id
  $task_id = query_id();

  // If the task is private or must be created silently, stop here and return the task's id
  if($task_private || $task_silent)
    return $task_id;

  // Determine the activity language
  $activity_lang  = ($task_title_en) ? 'EN' : '';
  $activity_lang .= ($task_title_fr) ? 'FR' : '';

  // Fetch the raw task title
  $task_title_en_raw = ($task_title_en) ? $contents['title_en'] : '';
  $task_title_fr_raw = ($task_title_fr) ? $contents['title_fr'] : '';

  // Fetch the current user's username
  $admin_username = user_get_username();

  // Activity log
  log_activity( 'dev_task_new'                            ,
                language:             $activity_lang      ,
                activity_id:          $task_id            ,
                activity_summary_en:  $task_title_en_raw  ,
                activity_summary_fr:  $task_title_fr_raw  ,
                username:             $admin_username     );

  // IRC bot message
  if($task_title_en)
    irc_bot_send_message("A new task has been added to the to-do list by $admin_username : $task_title_en_raw - ".$GLOBALS['website_url']."pages/tasks/$task_id", 'dev');

  // Return the task's id
  return $task_id;
}




/**
 * Creates a new unvalidated task proposal.
 *
 * @param   string        $body   The task proposal's body.
 *
 * @return  string|int            A string if an error happened, or the newly created task's id.
*/

function tasks_propose( string $body ) : mixed
{
  // Require users to be logged in to run this action
  user_restrict_to_users();

  // Check if the required files have been included
  require_included_file('tasks.lang.php');

  // Sanitize the data
  $user_id    = sanitize(user_get_id(), 'int', 1);
  $username   = user_get_username();
  $body       = sanitize($body, 'string');
  $timestamp  = sanitize(time(), 'int', 0);

  // Error: No body
  if(!str_replace(' ', '', $body))
    return __('tasks_proposal_error');

  // Check if the user is flooding the website
  if(!flood_check(error_page: false))
    return __('tasks_proposal_flood');

  // Determine the body's language and the task's title
  $lang       = string_change_case(user_get_language(), 'lowercase');
  $task_title = ($lang == 'en') ? "Unvalidated task" : "Tâche non validée";

  // Create the unvalidated task
  query(" INSERT INTO dev_tasks
          SET         dev_tasks.fk_users          = '$user_id'    ,
                      dev_tasks.created_at        = '$timestamp'  ,
                      dev_tasks.is_public         = 1             ,
                      dev_tasks.title_$lang       = '$task_title' ,
                      dev_tasks.body_$lang        = '$body'       ");

  // Get the newly created task's id
  $task_id = query_id();

  // IRC bot notification
  irc_bot_send_message("Task proposal submitted by $username - ".$GLOBALS['website_url']."pages/tasks/$task_id", 'admin');

  // Return the task's id
  return $task_id;
}




/**
 * Approves an unvalidated task proposal.
 *
 * @param   int           $task_id    The ID of the task being approved.
 * @param   array         $contents   The contents of the task being approved.
 *
 * @return  string|null               An error string, or null if the task was properly approved.
 */

function tasks_approve( int   $task_id  ,
                        array $contents ) : mixed
{
  // Check if the required files have been included
  require_included_file('tasks.lang.php');

  // Only administrators can run this action
  user_restrict_to_administrators();

  // Sanitize and prepare the data
  $task_id        = sanitize($task_id, 'int', 0);
  $task_title_en  = (isset($contents['title_en']))  ? sanitize($contents['title_en'], 'string')     : '';
  $task_title_fr  = (isset($contents['title_fr']))  ? sanitize($contents['title_fr'], 'string')     : '';
  $task_body_en   = (isset($contents['body_en']))   ? sanitize($contents['body_en'], 'string')      : '';
  $task_body_fr   = (isset($contents['body_fr']))   ? sanitize($contents['body_fr'], 'string')      : '';
  $task_priority  = (isset($contents['priority']))  ? sanitize($contents['priority'], 'int', 0, 5)  : 0;
  $task_category  = (isset($contents['category']))  ? sanitize($contents['category'], 'int', 0)     : 0;
  $task_milestone = (isset($contents['milestone'])) ? sanitize($contents['milestone'], 'int', 0)    : 0;
  $task_private   = (isset($contents['private']))   ? sanitize($contents['private'], 'int', 0, 1)   : 0;
  $task_public    = ($task_private)                 ? 0                                             : 1;
  $task_silent    = (isset($contents['silent']))    ? sanitize($contents['silent'], 'int', 0, 1)    : 0;

  // Error: No title
  if(!$task_title_en && !$task_title_fr)
    return __('tasks_add_error_title');

  // Error: Task does not exist
  if(!database_row_exists('dev_tasks', $task_id))
    return __('tasks_details_error');

  // Update the task
  query(" UPDATE      dev_tasks
          SET         dev_tasks.admin_validation          = 1                 ,
                      dev_tasks.is_public                 = '$task_public'    ,
                      dev_tasks.priority_level            = '$task_priority'  ,
                      dev_tasks.title_en                  = '$task_title_en'  ,
                      dev_tasks.title_fr                  = '$task_title_fr'  ,
                      dev_tasks.body_en                   = '$task_body_en'   ,
                      dev_tasks.body_fr                   = '$task_body_fr'   ,
                      dev_tasks.fk_dev_tasks_categories   = '$task_category'  ,
                      dev_tasks.fk_dev_tasks_milestones   = '$task_milestone'
          WHERE       dev_tasks.id                        = '$task_id'        ");

  // Fetch the task's author
  $dtask = mysqli_fetch_array(query(" SELECT    dev_tasks.id    AS 't_id'   ,
                                                users.id        AS 'tu_id'  ,
                                                users.username  AS 'tu_nick'
                                      FROM      dev_tasks
                                      LEFT JOIN users ON dev_tasks.fk_users = users.id
                                      WHERE     dev_tasks.id = '$task_id' "));
  $username = $dtask['tu_nick'];
  $user_id  = $dtask['tu_id'];

  // Fetch some data about the user
  $admin_id = sanitize(user_get_id(), 'int', 0);
  $user_id  = sanitize($user_id, 'int', 0);
  $lang     = user_get_language($user_id);
  $path     = root_path();

  // Prepare the message's title
  $message_title = ($lang == 'FR') ? 'Tâche acceptée' : 'Task proposal approved';

  // Prepare the message's body
  if($lang == 'FR')
    $message_body = <<<EOT
[url=${path}pages/tasks/${task_id}]Votre proposition de tâche a été approuvée.[/url]

Nous vous remercions pour votre participation au développement de NoBleme.
EOT;
  else
    $message_body = <<<EOT
[url=${path}pages/tasks/${task_id}]Your task proposal has been approved.[/url]

Thank you for helping NoBleme's development.
EOT;

  // If the task is private, prepare a different message
  if($task_private && $lang == 'FR')
    $message_body = <<<EOT
Une proposition de [url=${path}pages/tasks/list]tâche[/url] que vous avez fait récemment a été acceptée.

La tâche va rester privée pour le moment : soit elle est sensible pour la sécurité du site, soit il s'agit d'une idée qui sera meilleure sous la forme d'une surprise pour le reste des personnes utilisant le site.

Merci d'en conserver le secret. Nous vous remercions pour votre participation au développement de NoBleme.
EOT;
  else if($task_private)
    $message_body = <<<EOT
You made a [url=${path}pages/tasks/list]task[/url] proposal which has been approved.

The task will remain private for now: it is either too sensitive for the website's security, or is a great idea that would be better kept secret in order to surprise everyone else once it is done.

Thank you for helping NoBleme's development. Please keep this secret for now!
EOT;

  // Send the notification message unless it's being sent to self
  if($user_id != $admin_id)
    private_message_send( $message_title          ,
                          $message_body           ,
                          recipient: $user_id     ,
                          sender: 0               ,
                          true_sender: $admin_id  ,
                          hide_admin_mail: true   );

  // If the task is private or must be created silently, stop here and return null
  if($task_private || $task_silent)
    return null;

  // Determine the activity language
  $activity_lang  = ($task_title_en) ? 'EN' : '';
  $activity_lang .= ($task_title_fr) ? 'FR' : '';

  // Fetch the raw task title
  $task_title_en_raw = ($task_title_en) ? $contents['title_en'] : '';
  $task_title_fr_raw = ($task_title_fr) ? $contents['title_fr'] : '';

  // Activity log
  log_activity( 'dev_task_new'                            ,
                language:             $activity_lang      ,
                activity_id:          $task_id            ,
                activity_summary_en:  $task_title_en_raw  ,
                activity_summary_fr:  $task_title_fr_raw  ,
                username:             $username           );

  // IRC bot message
  if($task_title_en)
    irc_bot_send_message("A new task has been added to the to-do list by $username : $task_title_en_raw - ".$GLOBALS['website_url']."pages/tasks/$task_id", 'dev');

  // All went well, return NULL
  return null;
}




/**
 * Rejects and hard deletes an unvalidated task proposal.
 *
 * @param   int           $task_id                The ID of the task being rejected.
 * @param   string        $reason     (OPTIONAL)  The reason for which the proposal was rejected.
 * @param   bool          $is_silent  (OPTIONAL)  Whether to make it a silent rejection (no private message).
 *
 * @return  string|null                           An error string, or null if the task was properly rejected.
 */

function tasks_reject(  int     $task_id    ,
                        string  $reason     ,
                        bool    $is_silent  ) : mixed
{
  // Check if the required files have been included
  require_included_file('tasks.lang.php');

  // Only administrators can run this action
  user_restrict_to_administrators();

  // Sanitize the task's id
  $task_id = sanitize($task_id, 'int', 0);

  // Error: Task does not exist
  if(!database_row_exists('dev_tasks', $task_id))
    return __('tasks_details_error');

  // Fetch the task's author
  $dtask = mysqli_fetch_array(query(" SELECT    dev_tasks.id    AS 't_id' ,
                                                users.id        AS 'tu_id'
                                      FROM      dev_tasks
                                      LEFT JOIN users ON dev_tasks.fk_users = users.id
                                      WHERE     dev_tasks.id = '$task_id' "));
  $user_id  = $dtask['tu_id'];

  // Delete the task
  query(" DELETE FROM dev_tasks
          WHERE       dev_tasks.id = '$task_id' ");

  // If silent mode is requested, stop here
  if($is_silent)
    return null;

  // Fetch some data about the user
  $lang     = user_get_language($user_id);
  $path     = root_path();
  $admin_id = user_get_id();

  // Prepare the message's title
  $message_title = ($lang == 'FR') ? 'Tâche refusée' : 'Task proposal rejected';

  // If a reason is specified, prepare it
  if($reason && $lang == 'FR')
    $reason = <<<EOT

Votre proposition a été rejetée pour la raison suivante : ${reason}

EOT;
  else if($reason)
    $reason = <<<EOT

Your proposal has been rejected for the following reason: ${reason}

EOT;

  // Prepare the message's body
  if($lang == 'FR')
    $message_body = <<<EOT
Vous avez fait une [url=${path}pages/tasks/proposal]proposition de tâche[/url], qui a été rejetée.
${reason}
Malgré ce rejet, nous vous encourageons à continuer à contribuer dans le futur en rapportant des bugs ou en proposant vos idées.

Nous vous remercions pour votre participation au développement de NoBleme.
EOT;
  else
    $message_body = <<<EOT
You made a [url=${path}pages/tasks/proposal]task proposal[/url], which has been rejected.
${reason}
Despite this rejection, we encourage you to continue contributing to NoBleme in the future by reporting bugs or suggesting your ideas.

Thank you for helping NoBleme's development.
EOT;

  // Send the notification message unless it's being sent to self
  if($user_id != $admin_id)
    private_message_send( $message_title          ,
                          $message_body           ,
                          recipient: $user_id     ,
                          sender: 0               ,
                          true_sender: $admin_id  ,
                          hide_admin_mail: true   );

  // All went well, return NULL
  return null;
}




/**
 * Fetches a list of task categories.
 *
 * @return  array   An array containing task categories.
 */

function tasks_categories_list() : array
{
  // Get the user's current language and access rights
  $lang = sanitize(string_change_case(user_get_language(), 'lowercase'), 'string');

  // Fetch the categories
  $qcategories  = " SELECT    dev_tasks_categories.id           AS 'c_id'       ,
                              dev_tasks_categories.title_en     AS 'c_title_en' ,
                              dev_tasks_categories.title_fr     AS 'c_title_fr' ,
                              dev_tasks_categories.title_$lang  AS 'c_title'
                    FROM      dev_tasks_categories
                    ORDER BY  dev_tasks_categories.title_$lang  ASC ";

  // Run the query
  $qcategories = query($qcategories);

  // Prepare the data
  for($i = 0; $row = mysqli_fetch_array($qcategories); $i++)
  {
    $data[$i]['id']       = sanitize_output($row['c_id']);
    $data[$i]['title']    = sanitize_output($row['c_title']);
    $data[$i]['title_en'] = sanitize_output($row['c_title_en']);
    $data[$i]['title_fr'] = sanitize_output($row['c_title_fr']);
  }

  // Add the number of rows to the data
  $data['rows'] = $i;

  // Return the prepared data
  return $data;
}




/**
 * Creates a new task category.
 *
 * @param   array   $contents   The contents of the task category.
 *
 * @return  void
 */

function tasks_categories_add( array $contents ) : void
{
  // Only administrators can run this action
  user_restrict_to_administrators();

  // Sanitize and prepare the data
  $title_en = (isset($contents['title_en']))  ? sanitize($contents['title_en'], 'string') : '';
  $title_fr = (isset($contents['title_fr']))  ? sanitize($contents['title_fr'], 'string') : '';

  // Set default values in case some are missing
  $title_en = ($title_en) ? $title_en : '-';
  $title_fr = ($title_fr) ? $title_fr : '-';

  // Create the task category
  query(" INSERT INTO dev_tasks_categories
          SET         dev_tasks_categories.title_en = '$title_en' ,
                      dev_tasks_categories.title_fr = '$title_fr' ");
}




/**
 * Modifies a task category.
 *
 * @param   int     $category_id  The id of the task category to edit.
 * @param   array   $contents     The updated task category contents.
 *
 * @return  void
 */

function tasks_categories_edit( int   $category_id  = 0       ,
                                array $contents     = array() ) : void
{
  // Only administrators can run this action
  user_restrict_to_administrators();

  // Sanitize the task category id
  $category_id = sanitize($category_id, 'int', 0);

  // Stop here if the task category doesn't exist
  if(!database_row_exists('dev_tasks_categories', $category_id))
    return;

  // Sanitize and prepare the data
  $title_en = (isset($contents['title_en']))  ? sanitize($contents['title_en'], 'string') : '';
  $title_fr = (isset($contents['title_fr']))  ? sanitize($contents['title_fr'], 'string') : '';

  // Update the task category
  query(" UPDATE  dev_tasks_categories
          SET     dev_tasks_categories.title_en = '$title_en' ,
                  dev_tasks_categories.title_fr = '$title_fr'
          WHERE   dev_tasks_categories.id       = '$category_id' ");
}




/**
 * Deletes a task category
 *
 * @param   int   $category_id  The id of the task category to delete.
 *
 * @return  void
 */

function tasks_categories_delete( int $category_id = 0 ) : void
{
  // Only administrators can run this action
  user_restrict_to_administrators();

  // Sanitize the task category id
  $category_id = sanitize($category_id, 'int', 0);

  // Fetch the category name
  $dcategory = mysqli_fetch_array(query(" SELECT  dev_tasks_categories.title_en  AS 'tc_name'
                                          FROM    dev_tasks_categories
                                          WHERE   dev_tasks_categories.id = '$category_id' "));
  $category_name = $dcategory['tc_name'];

  // Fetch the number of tasks linked to the category
  $dtasks = mysqli_fetch_array(query("  SELECT  COUNT(*) AS 't_count'
                                        FROM    dev_tasks
                                        WHERE   dev_tasks.fk_dev_tasks_categories = '$category_id' "));
  $task_count = $dtasks['t_count'];

  // Unlink all tasks currently linked to this category
  query(" UPDATE  dev_tasks
          SET     dev_tasks.fk_dev_tasks_categories = 0
          WHERE   dev_tasks.fk_dev_tasks_categories = '$category_id' ");

  // Delete the task category
  query(" DELETE FROM dev_tasks_categories
          WHERE       dev_tasks_categories.id = '$category_id' ");

  // Fetch the username of the admin deleting the category
  $admin_name = user_get_username();

  // IRC bot message
  irc_bot_send_message("$admin_name deleted the task category named \"$category_name\" - $task_count task(s) were linked to it and are now uncategorized - ".$GLOBALS['website_url']."pages/tasks/list", 'admin');
}




/**
 * Fetches a list of task milestones.
 *
 * @return  array   An array containing task milestones.
 */

function tasks_milestones_list() : array
{
  // Get the user's current language and access rights
  $lang = sanitize(string_change_case(user_get_language(), 'lowercase'), 'string');

  // Fetch the milestones
  $qmilestones  = " SELECT    dev_tasks_milestones.id             AS 'm_id'       ,
                              dev_tasks_milestones.title_en       AS 'm_title_en' ,
                              dev_tasks_milestones.title_fr       AS 'm_title_fr' ,
                              dev_tasks_milestones.title_$lang    AS 'm_title'    ,
                              dev_tasks_milestones.sorting_order  AS 'm_order'    ,
                              dev_tasks_milestones.summary_en     AS 'm_body_en'  ,
                              dev_tasks_milestones.summary_fr     AS 'm_body_fr'
                    FROM      dev_tasks_milestones
                    ORDER BY  dev_tasks_milestones.sorting_order DESC ";

  // Run the query
  $qmilestones = query($qmilestones);

  // Prepare the data
  for($i = 0; $row = mysqli_fetch_array($qmilestones); $i++)
  {
    $data[$i]['id']       = sanitize_output($row['m_id']);
    $data[$i]['title']    = sanitize_output($row['m_title']);
    $data[$i]['title_en'] = sanitize_output($row['m_title_en']);
    $data[$i]['title_fr'] = sanitize_output($row['m_title_fr']);
    $data[$i]['order']    = sanitize_output($row['m_order']);
    $data[$i]['body_en']  = sanitize_output($row['m_body_en']);
    $data[$i]['body_fr']  = sanitize_output($row['m_body_fr']);
  }

  // Add the number of rows to the data
  $data['rows'] = $i;

  // Return the prepared data
  return $data;
}




/**
 * Creates a new task milestone.
 *
 * @param   array   $contents   The contents of the task milestone.
 *
 * @return  void
 */

function tasks_milestones_add( array $contents ) : void
{
  // Only administrators can run this action
  user_restrict_to_administrators();

  // Sanitize and prepare the data
  $order    = (isset($contents['order']))     ? sanitize($contents['order'], 'int', 0)    : 0;
  $title_en = (isset($contents['title_en']))  ? sanitize($contents['title_en'], 'string') : '';
  $title_fr = (isset($contents['title_fr']))  ? sanitize($contents['title_fr'], 'string') : '';

  // Set default values in case some are missing
  $title_en = ($title_en) ? $title_en : '-';
  $title_fr = ($title_fr) ? $title_fr : '-';

  // Create the task milestone
  query(" INSERT INTO dev_tasks_milestones
          SET         dev_tasks_milestones.sorting_order  = '$order'  ,
                      dev_tasks_milestones.title_en       = '$title_en' ,
                      dev_tasks_milestones.title_fr       = '$title_fr' ");
}




/**
 * Modifies a task milestone.
 *
 * @param   int     $milestone_id   The id of the task milestone to edit.
 * @param   array   $contents       The updated task milestone contents.
 *
 * @return  void
 */

function tasks_milestones_edit( int   $milestone_id = 0       ,
                                array $contents     = array() ) : void
{
  // Only administrators can run this action
  user_restrict_to_administrators();

  // Sanitize the task milestone id
  $milestone_id = sanitize($milestone_id, 'int', 0);

  // Stop here if the task milestone doesn't exist
  if(!database_row_exists('dev_tasks_milestones', $milestone_id))
    return;

  // Sanitize and prepare the data
  $order    = (isset($contents['order']))     ? sanitize($contents['order'], 'int', 0)    : 0;
  $title_en = (isset($contents['title_en']))  ? sanitize($contents['title_en'], 'string') : '';
  $title_fr = (isset($contents['title_fr']))  ? sanitize($contents['title_fr'], 'string') : '';
  $body_en  = (isset($contents['body_en']))   ? sanitize($contents['body_en'], 'string')  : '';
  $body_fr  = (isset($contents['body_fr']))   ? sanitize($contents['body_fr'], 'string')  : '';

  // Update the task milestone
  query(" UPDATE  dev_tasks_milestones
          SET     dev_tasks_milestones.sorting_order  = '$order'    ,
                  dev_tasks_milestones.title_en       = '$title_en' ,
                  dev_tasks_milestones.title_fr       = '$title_fr' ,
                  dev_tasks_milestones.summary_en     = '$body_en'  ,
                  dev_tasks_milestones.summary_fr     = '$body_fr'
          WHERE   dev_tasks_milestones.id             = '$milestone_id' ");
}




/**
 * Deletes a task milestone.
 *
 * @param   int   $milestone_id   The id of the task milestone to delete.
 *
 * @return  void
 */

function tasks_milestones_delete( int $milestone_id = 0 ) : void
{
  // Only administrators can run this action
  user_restrict_to_administrators();

  // Sanitize the task milestone id
  $milestone_id = sanitize($milestone_id, 'int', 0);

  // Fetch the milestone name
  $dmilestone = mysqli_fetch_array(query("  SELECT  dev_tasks_milestones.title_en AS 'tm_name'
                                            FROM    dev_tasks_milestones
                                            WHERE   dev_tasks_milestones.id = '$milestone_id' "));
  $milestone_name = $dmilestone['tm_name'];

  // Fetch the number of tasks linked to the milestone
  $dtasks = mysqli_fetch_array(query("  SELECT  COUNT(*) AS 't_count'
                                        FROM    dev_tasks
                                        WHERE   dev_tasks.fk_dev_tasks_milestones = '$milestone_id' "));
  $task_count = $dtasks['t_count'];

  // Unlink all tasks currently linked to this milestone
  query(" UPDATE  dev_tasks
          SET     dev_tasks.fk_dev_tasks_milestones = 0
          WHERE   dev_tasks.fk_dev_tasks_milestones = '$milestone_id' ");

  // Delete the task milestone
  query(" DELETE FROM dev_tasks_milestones
          WHERE       dev_tasks_milestones.id = '$milestone_id' ");

  // Fetch the username of the admin deleting the milestone
  $admin_name = user_get_username();

  // IRC bot message
  irc_bot_send_message("$admin_name deleted the task milestone named \"$milestone_name\" - $task_count task(s) were linked to it and are not roadmapped anymore - ".$GLOBALS['website_url']."pages/tasks/list", 'admin');
}