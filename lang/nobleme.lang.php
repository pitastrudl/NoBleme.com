<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     HOMEPAGE                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Page settings
___('nobleme_home_page_title', 'EN', "Homepage");
___('nobleme_home_page_title', 'FR', "Accueil");

// Welcome paragraph
___('nobleme_home_welcome_title', 'EN', "Welcome to NoBleme. What's this place all about?");
___('nobleme_home_welcome_title', 'FR', "Bienvenue sur NoBleme. Qu'est-ce que ce lieu ?");
___('nobleme_home_welcome', 'EN', <<<EOT
<p>A relic of the internet's past, NoBleme is a french community which has been online for over {{1}} years.</p>
<p>Before the days of social networks, the internet was decentralized: split into a lot of small communities, most of them without a specific theme or purpose. NoBleme is an attempt to preserve the almost familial spirit of those small communities of the past.</p>
<p>However, NoBleme is not meant to be a museum. It is a living place, the website is still being developed, and the community is continuously accepting new members with open arms whilst making sure to get rid of any source of drama in order to preserve its friendly atmosphere.</p>
EOT
);
___('nobleme_home_welcome', 'FR', <<<EOT
<p>Existant dans son coin depuis plus de {{1}} ans, NoBleme est un vestige du passé d'internet.</p>
<p>Avant l'ère des réseaux sociaux, le web était décentralisé : composé de plein de petites communautés qui n'avaient pas spécialement de thème ou de raison d'être. Aujourd'hui, NoBleme continue à préserver l'esprit quasi-familial de ces petites communautés d'antan.</p>
<p>Toutefois, NoBleme n'est pas fait pour être un musée. C'est une communauté vivante, activement développée, qui continue à accueillir les nouveaux à bras ouverts et à éjecter les causeurs de drames afin de préserver l'ambiance amicale qui fait son charme.</p>
EOT
);


// Website tour
___('nobleme_home_tour_title', 'EN', "Guided tour of the website");
___('nobleme_home_tour_title', 'FR', "Visite guidée du site");
___('nobleme_home_tour', 'EN', <<<EOT
<p>NoBleme was originally created as a french community. This means that some of the website's features have no english translation and are only available in french. It does not mean that english speakers are not desired, as absolutely everyone is welcome on NoBleme, and many if not most of our users speak english.</p>
<p>When switching from french to english, you will probably have noticed that you have access to fewer elements on the left side navigation menu. This is because the non translated (french only) pages are removed from the navigation menu when browsing the website in english, for your convenience. Keep in mind that most of these removed pages are about things that do not translate to english, such as quotes from conversations in french, games that are played solely in french, etc.</p>
<p>Don't hesitate to look around the website, and enjoy your stay on NoBleme!</p>
{{link+++|pages/users/user?id=1|- Bad|indented bold|1|}}
EOT
);
___('nobleme_home_tour', 'FR', <<<EOT
<p>Si vous vous demandez d'où NoBleme vient et à quoi sert NoBleme, vous pouvez trouver la réponse à ces questions dans la page {{link+++|pages/doc/nobleme|qu'est-ce que NoBleme ?|bold|1|}} de la {{link+++|pages/doc/index|documentation du site|bold|1|}}.</p>
<p>Notre attraction principale pour les visiteurs perdus est {{link+++|pages/nbdb/index|l'encyclopédie de la culture Internet|bold|1|}}, une documentation de l'histoire d'internet et des memes qu'on y trouve.</p>
<p>Maintenant que vous avez une vague idée de ce que NoBleme représente, peut-être êtes vous assez curieux pour avoir envie d'intéragir avec la communauté NoBlemeuse. Si c'est le cas, venez nous rejoindre là où nous sommes toujours actifs : sur notre {{link+++|pages/irc/index|serveur de discussion IRC|bold|1|}}.</p>
<p>N'hésitez pas à vous balader sur le site pour découvrir son contenu, et bon séjour sur NoBleme !</p>
{{link+++|pages/users/user?id=1|- Bad|indented bold|1|}}
EOT
);




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     404 ERROR                                                     */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Page settings
___('nobleme_404_page_title', 'EN', "Lost in space");
___('nobleme_404_page_title', 'FR', "Perdus dans l'espace");
___('nobleme_404_description', 'EN', "Error 404: Page not found…");
___('nobleme_404_description', 'FR', "Erreur 404 : Page non trouvée…");