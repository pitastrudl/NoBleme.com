<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }

/*********************************************************************************************************************/
/*                                                                                                                   */
/*                        Include this page to display the compendium's admin dropdown menu.                         */
/*                                                                                                                   */
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Limit page access rights
user_restrict_to_administrators();


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Prepare the menu entries

$compendium_admin_menu['notes']       = isset($compendium_admin_menu['notes']) ? ' selected' : '';
$compendium_admin_menu['page_list']   = isset($compendium_admin_menu['page_list']) ? ' selected' : '';
$compendium_admin_menu['images']      = isset($compendium_admin_menu['images']) ? ' selected' : '';
$compendium_admin_menu['page_types']  = isset($compendium_admin_menu['page_types']) ? ' selected' : '';
$compendium_admin_menu['categories']  = isset($compendium_admin_menu['categories']) ? ' selected' : '';
$compendium_admin_menu['eras']        = isset($compendium_admin_menu['eras']) ? ' selected' : '';


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Display the menu                                                                                                  ?>

<div class="padding_bot align_center compendium_admin_menu">
  <fieldset>
    <h5>
      <?=__('compendium_admin_menu_title').__(':')?>
      <select class="inh" id="compendium_admin_menu" name="compendium_admin_menu" onchange="compendium_admin_menu();">
        <option value="admin_notes"<?=$compendium_admin_menu['notes']?>>
          <?=__('compendium_admin_notes_title')?>
        </option>
        <option value="page_list_admin"<?=$compendium_admin_menu['page_list']?>>
          <?=__('compendium_list_admin_menu')?>
        </option>
        <option value="image_admin"<?=$compendium_admin_menu['images']?>>
          <?=__('compendium_image_list_title')?>
        </option>
        <option value="page_type_admin"<?=$compendium_admin_menu['page_types']?>>
          <?=__('compendium_types_title')?>
        </option>
        <option value="category_admin"<?=$compendium_admin_menu['categories']?>>
          <?=__('compendium_categories_title')?>
        </option>
        <option value="cultural_era_admin"<?=$compendium_admin_menu['eras']?>>
          <?=__('compendium_eras_title')?>
        </option>
      </select>
    </h5>
  </fieldset>
</div>

<hr>