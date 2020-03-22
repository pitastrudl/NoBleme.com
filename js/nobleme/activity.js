/**
 * Submits the dropdwon menus that control which activity logs are shown.
 *
 * @param   {string}  fetch_url       The url that should be called dynamically.
 * @param   {bool}    toggle_deleted  If set, toggles between visible and deleted view.
 *
 * @returns {void}
 */

function activity_submit_menus(fetch_url, toggle_deleted=null)
{
  // Toggle between visible and deleted view if required
  if(toggle_deleted)
  {
    deleted_status = document.getElementById('activity_deleted').value;
    deleted_status = (deleted_status == 1) ? 0 : 1;
    document.getElementById('activity_deleted').value = deleted_status;
  }

  // Prepare the postdata
  postdata  = 'activity_amount='    + fetch_sanitize_id('activity_amount');
  postdata += '&activity_type='     + fetch_sanitize_id('activity_type');
  postdata += '&activity_deleted='  + fetch_sanitize_id('activity_deleted');

  // Fetch the data
  fetch_page(fetch_url, 'activity_body', postdata);
}




/**
 * Displays the details of an activity log.
 *
 * @param   {int}     log_id  The id of the activity log.
 *
 * @returns {void}
 */

function activity_show_details(log_id)
{
  // Toggle the visiblity of the details
  toggle_element('activity_details_' + log_id, 1);

  // Prepare the postdata
  postdata = 'log_id=' + log_id;

  // Fetch the data
  fetch_page('activity_details.php', 'activity_details_' + log_id, postdata);
}




/**
 * Triggers the deletion of an activity log.
 *
 * @param   {int}     log_id        The id of the activity log.
 * @param   {string}  message       The confirmation message which will be displayed.
 * @param   {bool}    deletion_type Whether this call should trigger a soft (0) or hard (1) delete.
 *
 * @returns {void}
 */

function activity_delete_log(log_id, message, deletion_type=0)
{
  // Make sure the user knows what they're doing
  if(confirm(message))
  {
    // Hide the details if they exist and are currently visible
    if(document.getElementById('activity_details_' + log_id))
      toggle_element_oneway('activity_details_' + log_id, 0, 1);

    // Prepare the postdata
    postdata  = 'log_id='         + log_id;
    postdata += '&deletion_type=' + deletion_type;

    // Fetch the data
    fetch_page('activity_delete.php', 'activity_row_' + log_id, postdata);
  }
}




/**
 * Restores a soft deleted activity log.
 *
 * @param   {int}     log_id        The id of the activity log.
 *
 * @returns {void}
 */

function activity_restore_log(log_id)
{
  // Hide the details if they exist and are currently visible
  if(document.getElementById('activity_details_' + log_id))
    toggle_element_oneway('activity_details_' + log_id, 0, 1);

  // Prepare the postdata
  postdata = 'log_id=' + log_id;

  // Fetch the data
  fetch_page('activity_restore.php', 'activity_row_' + log_id, postdata);
}