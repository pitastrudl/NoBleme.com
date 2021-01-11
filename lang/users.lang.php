<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                   WHO'S ONLINE                                                    */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Header
___('users_online_title',         'EN', "Who's online?");
___('users_online_title',         'FR', "Qui est en ligne ?");
___('users_online_header_intro',  'EN', <<<EOD
This page lists the most recently visited page of all users that were active on NoBleme in the last month. In the case of guests (users without an account), only the 1000 most recent ones are displayed, and randomly generated silly usernames are assigned to each of them. If you fear that it might enable stalking in ways you're not comfortable with and want to be hidden from this page, you can do that in your account's {{link|todo_link|privacy options}}.
EOD
);
___('users_online_header_intro',  'FR', <<<EOD
Cette page recense la dernière activité des visiteurs de NoBleme ce mois-ci. Dans le cas des invités (visiteurs non connectés), seuls les 1000 entrées les plus récentes sont affichées, et de petits surnoms stupides leur sont aléatoirement assignés. Si vous craignez que cette page permette à des gens de vous traquer ou n'êtes juste pas confortable avec le fait d'avoir votre activité listée publiquement, vous pouvez retirer votre compte de la liste via vos {{link|todo_link|options de vie privée}}.
EOD
);
___('users_online_header_colors', 'EN', <<<EOD
In order to tell them apart from each other, users are color coded:
<ul class="nopadding">
  <li>Guests do not have any specific formatting.</li>
  <li>{{link|pages/users/list|Registered users}} appear in <span class="bold">bold</span>.</li>
  <li>{{link|pages/users/admins|Moderators}} appear in <span class="text_orange bold">orange</span>.</li>
  <li>{{link|pages/users/admins|Administrators}} appear in <span class="text_red glow bold">red</span>.</li>
</ul>
EOD
);
___('users_online_header_colors', 'FR', <<<EOD
Afin de les distinguer, les visiteurs suivent un code couleur :
<ul class="nopadding">
  <li>Les invités n'ont pas de formattage spécifique.</li>
  <li>{{link|pages/users/list|Les membres du site}} apparaissent en <span class="bold">gras</span>.</li>
  <li>{{link|pages/users/admins|La modération}} apparait en <span class="text_orange bold">orange.</span></li>
  <li>{{link|pages/users/admins|L'administration}} apparait en <span class="text_red glow bold">rouge.</span></li>
</ul>
EOD
);


// Options
___('users_online_hide_gests',      'EN', "Do not show guests in the list");
___('users_online_hide_gests',      'FR', "Ne pas afficher les invités dans la liste");
___('users_online_admin_view',      'EN', "See the table like a regular user would");
___('users_online_admin_view',      'FR', "Voir la page comme un utilisateur normal");
___('users_online_refresh',         'EN', "Automatically reload the table every 10 seconds");
___('users_online_refresh',         'FR', "Recharger automatiquement la liste toutes les 10 secondes");
___('users_online_refresh_mobile',  'EN', "Refresh the table every 10 seconds");
___('users_online_refresh_mobile',  'FR', "Actualiser la liste toutes les 10 secondes");


// Table
___('users_online_activity',  'EN', "LATEST ACTIVITY");
___('users_online_activity',  'FR', "DERNIÈRE ACTIVITÉ");
___('users_online_page',      'EN', "LAST VISITED PAGE");
___('users_online_page',      'FR', "DERNIÈRE PAGE VISITÉE");




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                 REGISTERED USERS                                                  */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Header
___('users_list_description_intro',  'EN',  <<<EOT
The table below lists all accounts that have been registered on NoBleme, from the most recent to the oldest. Clicking on a username will bring you to the account's profile page.
EOT
);
___('users_list_description_intro',  'FR',  <<<EOT
Le tableau ci-dessous recense tous les comptes qui ont été crées sur NoBleme, du plus récent au plus ancien. Cliquer sur un pseudonyme vous amènera sur le profil du compte.
EOT
);
___('users_list_description_colors', 'EN', <<<EOD
Some users will appear with color coding:
<ul class="nopadding">
  <li>Actively used accounts have a <span class="green text_white spaced bold">green</span> background.</li>
  <li>Banned accounts have a <span class="brown text_white spaced bold">brown</span> background.</li>
  <li>{{link|pages/users/admins|Moderators}} have an <span class="orange text_white spaced bold">orange</span> background.</li>
  <li>{{link|pages/users/admins|Administrators}} have a <span class="red text_white spaced bold">red</span> background.</li>
</ul>
EOD
);
___('users_list_description_colors', 'FR', <<<EOD
Certains comptes apparaissent avec un code couleur :
<ul class="nopadding">
  <li>Les comptes activement utilisés sur un fond <span class="green text_white spaced bold">vert</span>.</li>
  <li>Les comptes bannis sur un fond <span class="brown text_white spaced bold">marron</span>.</li>
  <li>{{link|pages/users/admins|La modération}} sur un fond <span class="orange text_white spaced bold">orange</span>.</li>
  <li>{{link|pages/users/admins|L'administration}} sur un fond <span class="red text_white spaced bold">rouge</span>.</li>
</ul>
EOD
);


// Table
___('users_list_registered',  "EN", "Registered");
___('users_list_registered',  "FR", "Création");
___('users_list_languages',   "EN", "Languages");
___('users_list_languages',   "FR", "Langues");
___('users_list_active',      "EN", "Actively used");
___('users_list_active',      "FR", "Activement utilisés");
___('users_list_count',       "EN", "{{1}} NoBleme user account");
___('users_list_count',       "FR", "{{1}} membre de NoBleme");
___('users_list_count+',      "EN", "{{1}} NoBleme user accounts");
___('users_list_count+',      "FR", "{{1}} membres de NoBleme");




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                ADMINISTRATIVE TEAM                                                */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Header
___('users_admins_intro',   'EN', <<<EOT
NoBleme's administrative team are a group of volunteers that ensure the smooth running of the website and its community by dealing with any potential issues that could arise within them. If you need their help with an issue - be it of a human or technical nature - you can ask for it {{link|todo_link|through our irc server}} or {{link|pages/messages/admins|through the website}}.
EOT
);
___('users_admins_intro',   'FR', <<<EOT
L'équipe administrative de NoBleme est un groupe de volontaires qui maintiennent le site internet et sa communauté en gérant les potentiels problèmes qui peuvent y apparaître. Si vous avez besion de leur aide avec un sujet - qu'il soit humain ou technique - vous pouvez les contacter {{link|todo_link|via le serveur IRC}} ou {{link|pages/messages/admins|via le site web}}.
EOT
);
___('users_admins_mods',    'EN', <<<EOT
<span class="text_orange bold">Moderators</span> have full power over any content on the website that involves users: they can ban accounts, reset forgotten passwords, delete accounts, manage real life meetups, etc.
EOT
);
___('users_admins_mods',    'FR', <<<EOT
<span class="text_orange bold">La modération</span> dispose des pleins pouvoirs sur tous les contenus du site impliquant des utilisateurs : bannissements, suppression de comptes, remise à zéro de mots de passe, gestion de rencontres IRL, etc.
EOT
);
___('users_admins_admins',  'EN', <<<EOT
<span class="text_red bold">Adminstrators</span> maintain the technical aspects of the website: they share the same powers as moderators, but can also manage the content of pages, close the website for maintenance, etc.
EOT
);
___('users_admins_admins',  'FR', <<<EOT
<span class="text_red bold">L'administration</span> gère les aspiects technique du site : elle possède les mêmes pouvoirs que la modération, et peut également gérer le contenu des pages, fermer le site lors des maintenances techniques, etc.
EOT
);


// Table
___('users_admins_title', "EN", "Title");
___('users_admins_title', "FR", "Titre");




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                  PUBLIC  PROFILE                                                  */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Header
___('users_profile_ban',  'EN', "Ban");
___('users_profile_ban',  'FR', "Bannir");


// Deleted account
___('users_profile_deleted',    'EN', "[deleted account]");
___('users_profile_deleted',    'FR', "[compte supprimé]");


// Banned account
___('users_profile_banned',     'EN', "[banned account]");
___('users_profile_banned',     'FR', "[compte banni]");
___('users_profile_unban',      'EN', "Unban");
___('users_profile_unban',      'FR', "Débannir");
___('users_profile_ban_end',    'EN', "Ban scheduled to end {{1}}");
___('users_profile_ban_end',    'FR', "Fin du bannissement prévue {{1}}");


// Account summary
___('users_profile_summary',    'EN', "Account #{{1}}");
___('users_profile_summary',    'FR', "Compte #{{1}}");
___('users_profile_languages',  'EN', "Languages");
___('users_profile_languages',  'FR', "Langues");
___('users_profile_pronouns',   'EN', "Pronouns");
___('users_profile_pronouns',   'FR', "Pronoms");
___('users_profile_country',    'EN', "Country / Location");
___('users_profile_country',    'FR', "Pays / Localisation");
___('users_profile_created',    'EN', "Account registration");
___('users_profile_created',    'FR', "Création du compte");
___('users_profile_activity',   'EN', "Latest visit");
___('users_profile_activity',   'FR', "Dernière visite");
___('users_profile_age',        'EN', "Age");
___('users_profile_age',        'FR', "Âge");
___('users_profile_age_years',  'EN', "{{1}} years old");
___('users_profile_age_years',  'FR', "{{1}} ans");
___('users_profile_birthday',   'EN', "Birthday");
___('users_profile_birthday',   'FR', "Anniversaire");


// Admin info
___('users_profile_admin',    'EN', "Administrative toolbox - keep this private");
___('users_profile_admin',    'FR', "Informations administratives - à garder privé");
___('users_profile_ip',       'EN', "Latest IP address");
___('users_profile_ip',       'FR', "Dernière adresse IP");
___('users_profile_email',    'EN', "E-mail address");
___('users_profile_email',    'FR', "Adresse e-mail");
___('users_profile_unknown',  'EN', "Unknown");
___('users_profile_unknown',  'FR', "Inconnue");
___('users_profile_page',     'EN', "Last visited page");
___('users_profile_page',     'FR', "Dernière page visitée");
___('users_profile_action',   'EN', "Latest action");
___('users_profile_action',   'FR', "Dernière action");
___('users_profile_noaction', 'EN', "None");
___('users_profile_noaction', 'FR', "Aucune");




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                EDIT PUBLIC PROFILE                                                */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Header
___('users_profile_edit_subtitle',  'EN', "Customize your public profile");
___('users_profile_edit_subtitle',  'FR', "Personnaliser mon profil public");
___('users_profile_edit_intro',     'EN', <<<EOT
Your {{link|pages/users/profile|public profile}} contains a summary of your account on NoBleme for all other users to see. Some elements of your public profile will appear only if you customize them on this page. It is up to you whether you want to leave your public profile bare to preserve your anonymity or whether you'd rather fill it up to give a summary of yourself to others. Every single field below is optional, you can choose to fill in only some of them.
EOT
);
___('users_profile_edit_intro',     'FR', <<<EOT
Votre {{link|pages/users/profile|profil public}} sert de vitrine à destination des autres membres du site, résumant qui vous êtes au sein de NoBleme. Certains éléments de votre profil public n'apparaîtront que si vous les renseignez sur cette page. C'est à vous de choisir si vous préférez laisser votre profil vide afin de préserver votre anonymat, ou si vous préférez le remplir pour que les autres sachent qui vous êtes. Chacun des champs ci-dessous est optionnel, vous pouvez opter pour ne remplir que certains d'entre eux. Le site étant bilingue, il vous est proposé de remplir votre profil en français et en anglais - libre à vous de laisser les parties anglaises vides si vous n'êtes pas confortable dans cette langue.
EOT
);


// Edit form
___('users_profile_edit_bilingual', 'EN', "Spoken languages");
___('users_profile_edit_bilingual', 'FR', "Langues parlées");

___('users_profile_edit_birthday',  'EN', "Birth date (day - month - year)");
___('users_profile_edit_birthday',  'FR', "Date de naissance (jour - mois - année)");

___('users_profile_edit_residence', 'EN', "Your place of residence (country, city, etc.)");
___('users_profile_edit_residence', 'FR', "Votre localisation (pays, ville, etc.)");

___('users_profile_edit_english',   'EN', " (in english)");
___('users_profile_edit_english',   'FR', " (en anglais)");
___('users_profile_edit_french',    'EN', " (in french)");
___('users_profile_edit_french',    'FR', " (en français)");

___('users_profile_edit_pronouns',  'EN', "Your {{external|https://en.wikipedia.org/wiki/Preferred_gender_pronoun|preferred pronouns}}");
___('users_profile_edit_pronouns',  'FR', "Vos {{external|https://scfp.ca/sites/cupe/files/pronouns_fr.pdf|pronoms}}");

___('users_profile_edit_text',      'EN', "Custom text - You can use {{link|pages/doc/bbcodes|BBCodes}}");
___('users_profile_edit_text',      'FR', "Texte libre - {{link|pages/doc/bbcodes|BBCodes}} autorisés");

___('users_profile_edit_submit',    'EN', "Customize my public profile");
___('users_profile_edit_submit',    'FR', "Personnaliser mon profil profil");