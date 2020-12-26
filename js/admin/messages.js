/*********************************************************************************************************************/
/*                                                                                                                   */
/*  admin_mail_display          Displays the contents of the desired admin mail.                                     */
/*                                                                                                                   */
/*********************************************************************************************************************/

/**
 * Displays the contents of the desired admin mail.
 *
 * @param   {int}  [message_id]   The ID of the message to display.
 *
 * @returns {void}
*/

function admin_mail_display( message_id = 0 )
{
  // Make the message appear read by removing its red glow in the message list
  document.getElementById('admin_mail_list_' + message_id).classList.remove('text_red');
  document.getElementById('admin_mail_list_' + message_id).classList.remove('glow');
  document.getElementById('admin_mail_list_' + message_id).classList.remove('bold');

  // Assemble the postdata
  postdata = 'admin_mail_display_message=' + fetch_sanitize(message_id);

  // Trigger the message displaying
  fetch_page('inbox_message', 'admin_mail_main_panel', postdata);
}