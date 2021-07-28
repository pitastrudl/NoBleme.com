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
/*                                                                                                                   */
/*  tasks_categories_list     Fetches a list of task categories.                                                     */
/*                                                                                                                   */
/*  tasks_milestones_list     Fetches a list of task milestones.                                                     */
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

  // Get the user's current language and access rights
  $lang     = sanitize(string_change_case(user_get_language(), 'lowercase'), 'string');
  $is_admin = user_is_administrator();

  // Sanitize the data
  $task_id = sanitize($task_id, 'int', 0);

  // Check if the task exists
  if(!database_row_exists('dev_tasks', $task_id))
    return NULL;

  // Fetch the data
  $dtask = mysqli_fetch_array(query(" SELECT    dev_tasks.is_deleted        AS 't_deleted'    ,
                                                dev_tasks.admin_validation  AS 't_validated'  ,
                                                dev_tasks.is_public         AS 't_public'     ,
                                                dev_tasks.created_at        AS 't_created'    ,
                                                dev_tasks.finished_at       AS 't_solved'     ,
                                                dev_tasks.title_$lang       AS 't_title'      ,
                                                dev_tasks.body_$lang        AS 't_body'       ,
                                                dev_tasks.source_code_link  AS 't_source'     ,
                                                users.id                    AS 'u_id'         ,
                                                users.username              AS 'u_name'
                                      FROM      dev_tasks
                                      LEFT JOIN users ON dev_tasks.fk_users = users.id
                                      WHERE     dev_tasks.id = '$task_id' "));

  // Return null if the task should not be displayed
  if(!$is_admin && $dtask['t_deleted'])
    return NULL;
  if(!$is_admin && !$dtask['t_validated'])
    return NULL;
  if(!$is_admin && !$dtask['t_public'])
    return NULL;

  // Assemble an array with the data
  $data['title']      = sanitize_output($dtask['t_title']);
  $data['created']    = sanitize_output(date_to_text($dtask['t_created'], strip_day: 1));
  $data['creator']    = sanitize_output($dtask['u_name']);
  $data['creator_id'] = sanitize_output($dtask['u_id']);
  $temp               = sanitize_output(date_to_text($dtask['t_solved'], strip_day: 1));
  $data['solved']     = ($dtask['t_solved']) ? $temp : '';
  $data['body']       = bbcodes(sanitize_output($dtask['t_body'], preserve_line_breaks: true));
  $data['source']     = ($dtask['t_source']) ? sanitize_output($dtask['t_source']) : '';

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
    $qtasks .= "  ORDER BY    dev_tasks.finished_at             != ''   ,
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
 * Fetches a list of task categories.
 *
 * @return  array   An array containing task categories.
 */

function tasks_categories_list() : array
{
  // Get the user's current language and access rights
  $lang = sanitize(string_change_case(user_get_language(), 'lowercase'), 'string');

  // Fetch the categories
  $qcategories  = " SELECT    dev_tasks_categories.id           AS 'c_id' ,
                              dev_tasks_categories.title_$lang  AS 'c_title'
                    FROM      dev_tasks_categories
                    ORDER BY  dev_tasks_categories.title_$lang  ASC ";

  // Run the query
  $qcategories = query($qcategories);

  // Prepare the data
  for($i = 0; $row = mysqli_fetch_array($qcategories); $i++)
  {
    $data[$i]['id']     = sanitize_output($row['c_id']);
    $data[$i]['title']  = sanitize_output($row['c_title']);
  }

  // Add the number of rows to the data
  $data['rows'] = $i;

  // Return the prepared data
  return $data;
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
  $qmilestones  = " SELECT    dev_tasks_milestones.id           AS 'm_id' ,
                              dev_tasks_milestones.title_$lang  AS 'm_title'
                    FROM      dev_tasks_milestones
                    ORDER BY  dev_tasks_milestones.sorting_order DESC ";

  // Run the query
  $qmilestones = query($qmilestones);

  // Prepare the data
  for($i = 0; $row = mysqli_fetch_array($qmilestones); $i++)
  {
    $data[$i]['id']     = sanitize_output($row['m_id']);
    $data[$i]['title']  = sanitize_output($row['m_title']);
  }

  // Add the number of rows to the data
  $data['rows'] = $i;

  // Return the prepared data
  return $data;
}