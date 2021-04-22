<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                       SETUP                                                       */
/*                                                                                                                   */
// File inclusions /**************************************************************************************************/
include_once './../../inc/includes.inc.php';  # Core
include_once './../../lang/irc.lang.php';     # Translations

// Page summary
$page_lang        = array('FR', 'EN');
$page_url         = "pages/irc/faq";
$page_title_en    = "IRC chat";
$page_title_fr    = "Chat IRC";
$page_description = "NoBleme's primary communication method, our real time IRC chat server";

// Extra CSS & JS
$css  = array('irc');
$js   = array('irc/faq', 'common/toggle');




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     BACK END                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Display the correct FAQ section

// Prepare a list of all FAQ sections
$irc_faq_sections = array('main', 'why', 'browser', 'client', 'bouncer', 'guide', 'commands', 'nickserv', 'chanserv', 'bots', 'channels', 'others');

// Prepare the CSS for each FAQ section
foreach($irc_faq_sections as $irc_faq_section_name)
{
  // If a FAQ section is selected, display it and select the correct dropdown menu entry
  if(!isset($irc_faq_section_is_selected) && isset($_GET[$irc_faq_section_name]))
  {
    $irc_faq_section_is_selected              = true;
    $irc_faq_hide[$irc_faq_section_name]      = '';
    $irc_faq_selected[$irc_faq_section_name]  = ' selected';
  }

  // Hide every other FAQ section
  else
  {
    $irc_faq_hide[$irc_faq_section_name]      = ' hidden';
    $irc_faq_selected[$irc_faq_section_name]  = '';
  }
}

// If no FAQ section is selected, select the main one by default
if(!isset($irc_faq_section_is_selected))
{
  $irc_faq_hide['main']     = '';
  $irc_faq_selected['main'] = ' selected';
}




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     FRONT END                                                     */
/*                                                                                                                   */
if(!page_is_fetched_dynamically()) { /***************************************/ include './../../inc/header.inc.php'; ?>

<div class="width_50">

  <h1>
    <?=__('irc_faq_title')?>
  </h1>

  <form method="POST">
    <fieldset>
      <h5 class="smallpadding_bot">
        <select class="inh align_left" id="irc_faq_section_selector" onchange="irc_faq_display_section();">
          <option value="main"<?=$irc_faq_selected['main']?>><?=__('irc_faq_select_main')?></option>
          <option value="why"<?=$irc_faq_selected['why']?>><?=__('irc_faq_select_why')?></option>
          <option value="browser"<?=$irc_faq_selected['browser']?>><?=__('irc_faq_select_browser')?></option>
          <option value="client"<?=$irc_faq_selected['client']?>><?=__('irc_faq_select_client')?></option>
          <option value="bouncer"<?=$irc_faq_selected['bouncer']?>><?=__('irc_faq_select_bouncer')?></option>
          <option value="guide"<?=$irc_faq_selected['guide']?>><?=__('irc_faq_select_guide')?></option>
          <option value="commands"<?=$irc_faq_selected['commands']?>><?=__('irc_faq_select_commands')?></option>
          <option value="nickserv"<?=$irc_faq_selected['nickserv']?>><?=__('irc_faq_select_nickserv')?></option>
          <option value="chanserv"<?=$irc_faq_selected['chanserv']?>><?=__('irc_faq_select_chanserv')?></option>
          <option value="bots"<?=$irc_faq_selected['bots']?>><?=__('irc_faq_select_bots')?></option>
          <option value="channels"<?=$irc_faq_selected['channels']?>><?=__('irc_faq_select_channels')?></option>
          <option value="others"<?=$irc_faq_selected['others']?>><?=__('irc_faq_select_others')?></option>
        </select>
      </h5>
    </fieldset>
  </form>

</div>

<?php /************************************************ MAIN ****************************************************/ ?>

<div class="width_50 irc_faq_section<?=$irc_faq_hide['main']?>" id="irc_faq_main">

  <p>
    <?=__('irc_faq_main_body')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_main_what_title')?>
  </h5>

  <p>
    <?=__('irc_faq_main_what_1')?>
  </p>

  <p>
    <?=__('irc_faq_main_what_2')?>
  </p>

  <div class="flexcontainer padding_top">

    <div class="flex align_right bold spaced_right monospace">
      <?=__('irc_faq_main_what_server')?><br>
      <?=__('irc_faq_main_what_port')?><br>
      <?=__('irc_faq_main_what_channel')?><br>
      <?=__('irc_faq_main_what_encoding')?>
    </div>

    <div class="spaced_left monospace" style="flex:5">
      <?=__('irc_faq_main_what_url')?><br>
      <?=__('irc_faq_main_what_ports')?><br>
      <?=__('irc_faq_main_what_hub')?><br>
      <?=__('irc_faq_main_what_utf')?>
    </div>

  </div>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_main_join_title')?>
  </h5>

  <p>
    <?=__('irc_faq_main_join_1')?>
  </p>

  <p>
    <?=__('irc_faq_main_join_2')?>
  </p>

  <p>
    <?=__('irc_faq_main_join_3')?>
  </p>

  <p>
    <?=__('irc_faq_main_join_4')?>
  </p>

  <h5 id="faq" class="bigpadding_top">
    <?=__('irc_faq_questions_title')?>
  </h5>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_1')?></span><br>
    <?=__('irc_faq_answer_1')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_2')?></span><br>
    <?=__('irc_faq_answer_2')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_3')?></span><br>
    <?=__('irc_faq_answer_3')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_4')?></span><br>
    <?=__('irc_faq_answer_4')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_5')?></span><br>
    <?=__('irc_faq_answer_5')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_6')?></span><br>
    <?=__('irc_faq_answer_6')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_7')?></span><br>
    <?=__('irc_faq_answer_7')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_8')?></span><br>
    <?=__('irc_faq_answer_8')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_9')?></span><br>
    <?=__('irc_faq_answer_9')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_10')?></span><br>
    <?=__('irc_faq_answer_10')?>
  </p>

  <p>
    <span class="text_red bold indented"><?=__('irc_faq_question_11')?></span><br>
    <?=__('irc_faq_answer_11')?>
  </p>

</div>

<?php /************************************************ WHY IRC ***************************************************/ ?>

<div class="width_50 irc_faq_section<?=$irc_faq_hide['why']?>" id="irc_faq_why">

  <p>
    <?=__('irc_faq_why_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_why_body_2')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_why_freedom_title')?>
  </h5>

  <p>
    <?=__('irc_faq_why_freedom_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_why_freedom_body_2')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_why_flex_title')?>
  </h5>

  <p>
    <?=__('irc_faq_why_flex_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_why_flex_body_2')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_why_simple_title')?>
  </h5>

  <p>
    <?=__('irc_faq_why_simple_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_why_simple_body_2')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_why_habit_title')?>
  </h5>

  <p>
    <?=__('irc_faq_why_habit_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_why_habit_body_2')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_why_others_title')?>
  </h5>

  <p>
    <?=__('irc_faq_why_others_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_why_others_body_2')?>
  </p>

  <?=__('irc_faq_why_others_list')?>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_why_summary_title')?>
  </h5>

  <p>
    <?=__('irc_faq_why_summary_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_why_summary_body_2')?>
  </p>

</div>

<?php /************************************************ BROWSER ***************************************************/ ?>

<div class="width_70 padding_top irc_faq_section<?=$irc_faq_hide['browser']?>" id="irc_faq_browser">

  <?php if($lang == 'EN') { ?>
  <iframe src="https://kiwiirc.com/nextclient/?settings=d88c482df59c1ae0cca6627751a32973" class="indiv irc_client_iframe"></iframe>
  <?php } else { ?>
  <iframe src="https://kiwiirc.com/nextclient/?settings=5f080fa3340afd85b53f47188d628b10" class="indiv irc_client_iframe"></iframe>
  <?php } ?>

</div>

<?php /************************************************* CLIENT ***************************************************/ ?>

<div class="width_50 padding_top irc_faq_section<?=$irc_faq_hide['client']?>" id="irc_faq_client">

  <p>
    <?=__('irc_faq_client_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_client_body_2')?>
  </p>

  <p>
    <?=__('irc_faq_client_body_3')?>
  </p>

  <div class="flexcontainer padding_top">

    <div class="flex align_right bold spaced_right monospace">
      <?=__('irc_faq_main_what_server')?><br>
      <?=__('irc_faq_main_what_port')?><br>
      <?=__('irc_faq_main_what_channel')?><br>
      <?=__('irc_faq_main_what_encoding')?>
    </div>

    <div class="spaced_left monospace" style="flex:5">
      <?=__('irc_faq_main_what_url')?><br>
      <?=__('irc_faq_main_what_ports')?><br>
      <?=__('irc_faq_main_what_hub')?><br>
      <?=__('irc_faq_main_what_utf')?>
    </div>

  </div>

  <p>
    <?=__('irc_faq_client_body_4')?>
  </p>

  <p>
    <?=__('irc_faq_client_body_5')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_client_web_title')?>
  </h5>

  <p>
    <?=__('irc_faq_client_web_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_client_web_body_2')?>
  </p>

  <p>
    <?=__('irc_faq_client_web_body_3')?>
  </p>

  <p>
    <?=__('irc_faq_client_web_body_4')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_client_computer_title')?>
  </h5>

  <p>
    <?=__('irc_faq_client_computer_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_client_computer_body_2')?>
  </p>

  <p>
    <?=__('irc_faq_client_computer_body_3')?>
  </p>

  <p>
    <?=__('irc_faq_client_computer_body_4')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_client_mobile_title')?>
  </h5>

  <p>
    <?=__('irc_faq_client_mobile_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_client_mobile_body_2')?>
  </p>

  <p>
    <?=__('irc_faq_client_mobile_body_3')?>
  </p>

  <p>
    <?=__('irc_faq_client_mobile_body_4')?>
  </p>

</div>

<?php /************************************************ BOUNCER ***************************************************/ ?>

<div class="width_50 padding_top irc_faq_section<?=$irc_faq_hide['bouncer']?>" id="irc_faq_bouncer">

  <p>
    <?=__('irc_faq_bouncer_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_bouncer_body_2')?>
  </p>

  <p>
    <?=__('irc_faq_bouncer_body_3')?>
  </p>

  <p>
    <?=__('irc_faq_bouncer_body_4')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_bouncer_third_title')?>
  </h5>

  <p>
    <?=__('irc_faq_bouncer_third_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_bouncer_third_body_2')?>
  </p>

  <p>
    <?=__('irc_faq_bouncer_third_body_3')?>
  </p>

  <h5 class="bigpadding_top">
    <?=__('irc_faq_bouncer_tech_title')?>
  </h5>

  <p>
    <?=__('irc_faq_bouncer_tech_body_1')?>
  </p>

  <p>
    <?=__('irc_faq_bouncer_tech_body_2')?>
  </p>

  <p>
    <?=__('irc_faq_bouncer_tech_body_3')?>
  </p>

</div>

<?php /****************************************** VOCABULARY & SYMBOLS ********************************************/ ?>

<div class="width_50 padding_top irc_faq_section<?=$irc_faq_hide['guide']?>" id="irc_faq_guide">

  <h5 id="server"><?=__('irc_faq_vocabulary_title_1')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_1')?>
  </p>

  <h5 id="client"><?=__('irc_faq_vocabulary_title_2')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_2')?>
  </p>

  <h5 id="bouncer"><?=__('irc_faq_vocabulary_title_3')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_3')?>
  </p>

  <h5 id="channel"><?=__('irc_faq_vocabulary_title_4')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_4')?>
  </p>

  <h5 id="operator"><?=__('irc_faq_vocabulary_title_5')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_5')?>
  </p>

  <h5 id="kick"><?=__('irc_faq_vocabulary_title_6')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_6')?>
  </p>

  <h5 id="services"><?=__('irc_faq_vocabulary_title_7')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_7')?>
  </p>

  <h5 id="command"><?=__('irc_faq_vocabulary_title_8')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_8')?>
  </p>

  <h5 id="mode"><?=__('irc_faq_vocabulary_title_9')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_9')?>
  </p>

  <h5 id="highlight"><?=__('irc_faq_vocabulary_title_10')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_10')?>
  </p>

  <h5 id="lurk"><?=__('irc_faq_vocabulary_title_11')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_11')?>
  </p>

  <h5 id="bot"><?=__('irc_faq_vocabulary_title_12')?></h5>
  <p class="tinypadding_top padding_bot">
    <?=__('irc_faq_vocabulary_body_12')?>
  </p>

  <h5 id="symbols" class="bigpadding_top">
    <?=__('irc_faq_symbols_title')?>
  </h5>

  <p>
    <?=__('irc_faq_symbols_body_1')?>
  </p>

  <p class="bigpadding_bot">
    <?=__('irc_faq_symbols_body_2')?>
  </p>

  <table>
    <thead>

      <tr class="bold uppercase spaced noflow">
        <th>
          <?=__('irc_faq_symbols_name')?>
        </th>
        <th>
          <?=__('irc_faq_symbols_symbol')?>
        </th>
        <th>
          <?=__('irc_faq_symbols_mode')?>
        </th>
        <th>
          <?=__('irc_faq_symbols_abilities')?>
        </th>
      </tr>

    </thead>
    <tbody class="altc">

      <tr>
        <td class="align_center bold noflow">
          <?=__('irc_faq_symbols_user')?>
        </td>
        <td class="align_center bold noflow">
          &nbsp;
        </td>
        <td class="align_center bold noflow">
          &nbsp;
        </td>
        <td>
          <?=__('irc_faq_symbols_user_desc')?>
        </td>
      </tr>

      <tr>
        <td class="align_center bold noflow">
          <?=__('irc_faq_symbols_voice')?>
        </td>
        <td class="align_center bold noflow">
          +
        </td>
        <td class="align_center bold noflow">
          +v
        </td>
        <td>
          <?=__('irc_faq_symbols_voice_desc')?>
        </td>
      </tr>

      <tr>
        <td class="align_center bold noflow">
          <?=__('irc_faq_symbols_halfop')?>
        </td>
        <td class="align_center bold noflow">
          %
        </td>
        <td class="align_center bold noflow">
          +h
        </td>
        <td>
          <?=__('irc_faq_symbols_halfop_desc')?>
        </td>
      </tr>

      <tr>
        <td class="align_center bold noflow">
          <?=__('irc_faq_symbols_operator')?>
        </td>
        <td class="align_center bold noflow">
          @
        </td>
        <td class="align_center bold noflow">
          +o
        </td>
        <td>
          <?=__('irc_faq_symbols_operator_desc')?>
        </td>
      </tr>

      <tr>
        <td class="align_center bold noflow">
          <?=__('irc_faq_symbols_admin')?>
        </td>
        <td class="align_center bold noflow">
          &
        </td>
        <td class="align_center bold noflow">
          +a
        </td>
        <td>
          <?=__('irc_faq_symbols_admin_desc')?>
        </td>
      </tr>

      <tr>
        <td class="align_center bold noflow">
          <?=__('irc_faq_symbols_founder')?>
        </td>
        <td class="align_center bold noflow">
          ~
        </td>
        <td class="align_center bold noflow">
          +q
        </td>
        <td>
          <?=__('irc_faq_symbols_founder_desc')?>
        </td>
      </tr>

    </tbody>
  </table>

</div>

<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                                                    END OF PAGE                                                    */
/*                                                                                                                   */
/*****************************************************************************/ include './../../inc/footer.inc.php'; }