<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) === str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*  query           Execute a MySQL query.                                                                           */
/*  query_id        Returns the ID of the latest inserted row.                                                       */
/*                                                                                                                   */
/*********************************************************************************************************************/


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Open a connexion to the MySQL database - include it any time you need to run a query

// Initialize the connexion and store it for into a global variable - if requested, don't connect to the nobleme db
if(isset($GLOBALS['sql_database_agnostic']))
  $GLOBALS['db'] = @mysqli_connect($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_pass']) or die ('MySQL error: Connexion failed.');
else
  $GLOBALS['db'] = @mysqli_connect($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_pass'], 'nobleme') or die ('MySQL error: Connexion failed.');

// Initialize a session specific global query counter, used by admins for metrics (number of extra queries in a page)
$GLOBALS['query'] = 0;

// Set the global charset in order to avoid encoding mishaps
mysqli_set_charset($GLOBALS['db'], "utf8mb4");
query(' SET NAMES utf8mb4 ', description: "Specify the charset for the session");




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Place all useful system variables in an array

// Fetch the variables in the database
if(!isset($GLOBALS['sql_skip_system_variables']))
  $system_variables = mysqli_fetch_array(query("  SELECT  system_variables.website_is_closed          ,
                                                          system_variables.latest_query_id            ,
                                                          system_variables.last_scheduler_execution   ,
                                                          system_variables.last_pageview_check        ,
                                                          system_variables.unread_mod_mail_count      ,
                                                          system_variables.unread_admin_mail_count    ,
                                                          system_variables.current_version_number_en  ,
                                                          system_variables.current_version_number_fr  ,
                                                          system_variables.irc_bot_is_silenced
                                                  FROM    system_variables
                                                  LIMIT   1 " ,
                                                  description: "Fetch globally required system variables" ));

// If necessary, mock system variables that need to be there even in special circumstances
else
  $system_variables = array('website_is_closed' => 0);




/**
 * Execute a MySQL query.
 *
 * This function will use the global connexion to the database to execute a MySQL query.
 * As it is basically a global wrapper for MySQL usage, you should always use this function when executing a query.
 * Keep in mind that no sanitization/escaping is being done here, you must add your own (see sanitization.inc.php).
 *
 * @param   string      $query                      The query that you want to run.
 * @param   bool        $ignore_errors  (OPTIONAL)  Do not stop execution if an error is encountered.
 * @param   bool        $fetch_row      (OPTIONAL)  Fetch the first row and return it instead of the query object.
 * @param   string      $row_format     (OPTIONAL)  Format the fetched row uses ('both', 'num'), defaults to 'assoc'.
 * @param   string      $description    (OPTIONAL)  Describe the query, will only be used in SQL debug mode.
 *
 * @return  object|bool|array|null                  A mysqli_object, a bool, an array, null: depends on the parameters.
 */

function query( string  $query                    ,
                bool    $ignore_errors  = false   ,
                bool    $fetch_row      = false   ,
                string  $row_format     = 'assoc' ,
                string  $description    = NULL    ) : mixed
{
  // First off let's increment the global query counter for this session
  $GLOBALS['query']++;

  // If errors are ignored, just run the query and whatever happens happens
  if($ignore_errors)
    $query_result = @mysqli_query($GLOBALS['db'],$query);

  // Otherwise, run the query and stop the script if anything goes wrong
  else
    $query_result = mysqli_query($GLOBALS['db'],$query) or die ("MySQL error:<br>".mysqli_error($GLOBALS['db']));

  // SQL debug mode antics
  if($GLOBALS['dev_mode'] && $GLOBALS['sql_debug_mode'])
  {
    // Open the debug message
    echo '<div class="debug_query_container">';

    // Show the query's description
    echo '<div class="debug_query"><pre>[#'.$GLOBALS['query'].'] '.$description.'</pre></div>';

    // Show the query itself
    echo '<div class="debug_query"><pre>'.$query.'</pre></div>';

    // Check if the query returns any result
    if(substr(str_replace(' ', '', $query), 0, 6) === 'SELECT' && mysqli_num_rows($query_result))
    {
      // Prepare an array for the query results
      $full_query_results = array();

      // Fetch the query results
      for($i = 0 ; $dquery = mysqli_fetch_array($query_result); $i++)
      {
        foreach($dquery as $j => $result)
        {
          // Get rid of the duplicate numbered entry
          if(is_numeric($j))
            unset($dquery[$j]);

          // Add the regular entries into the query results
          else
            $full_query_results[$i][$j] = $result;
        }
      }

      // Display the query results
      echo '<div class="debug_query">';
      var_dump($full_query_results);
      echo '</div>';

      // Reset the query array so that it can be used again by the regular page
      mysqli_data_seek($query_result, 0);
    }

    // Show the stacktrace by raising an exception
    echo '<div class="debug_query">';
    $stacktrace_exception = new Exception();
    var_dump($stacktrace_exception -> getTraceAsString());
    echo '</div>';

    // Close the debug message
    echo '</div>';
  }

  // Fetch and return the first row of the query if requested
  if($fetch_row === true)
    return query_row($query_result, $row_format);

  // Return the result of the query
  return $query_result;
}




/**
 * Fetch the next row of a query
 *
 * @param   object        $query_object               The query object obtained by using the query() function.
 * @param   string        $return_format  (OPTIONAL)  Format of the returned array ('num', 'both') defaults to 'assoc'.
 *
 * @return  array|null                                A mysqli_object or a boolean, depending on the type of query.
 */

function query_row( object  $query_object             ,
                    string  $return_format  = 'assoc' ) : ?array
{
  // Return null if the variable is not a query object
  if(!is_a($query_object, 'mysqli_result'))
    return NULL;

  // Set the returned format constant to a value allowed by MySQL
  $return_format = match($return_format)
  {
    'assoc' => MYSQLI_ASSOC ,
    'num'   => MYSQLI_NUM   ,
    default => MYSQLI_BOTH
  };

  // Fetch the next row of the query
  $return = mysqli_fetch_array($query_object, $return_format);

  // If the result is not an array, set it to null
  $return = is_array($return) ? $return : NULL;

  // Return the row
  return $return;
}




/**
 * Returns the ID of the latest inserted row.
 *
 * @return  int   The ID of the latest inserted row.
 */

function query_id() : int
{
  return mysqli_insert_id($GLOBALS['db']);
}