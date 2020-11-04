<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     REGISTER                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Header & Code of conduct
___('users_register_title',     'EN', "Register an account");
___('users_register_title',     'FR', "Créer un compte");
___('users_register_subtitle',  'EN', "Code of conduct to follow when using NoBleme");
___('users_register_subtitle',  'FR', "Code de conduite à respecter sur NoBleme");
___('users_register_coc',       'EN', <<<EOD
We want to make sure that all users of the website understand that, even though there are few rules and little moderation on NoBleme, there still exists a code of conduct that should be followed when interacting with other members of the community. In order to ensure that everyone reads it at least once, you will be asked a few simple questions relating to NoBleme's code of conduct during the account creation process.
EOD
);
___('users_register_coc',       'FR', <<<EOD
Nous voulons nous assurer que toutes les persionnes qui comptent créer un compte comprennent que, même s'il y a très peu de règles ou de modération sur NoBleme, il existe tout de même un code de conduite qui doit être respecté lors de vos interactions avec la communauté du site. Afin de vérifier que tout le monde le lise au moins une fois, quelques questions simples vous serons posées sur ce code de conduite lors de la création de votre compte.
EOD
);


// Registration form
___('users_register_form_nickname',             'EN', "Your username (3 to 15 characters, letters & numbers only)");
___('users_register_form_nickname',             'FR', "Pseudonyme (3 à 15 caractères, chiffres & lettres sans accents)");
___('users_register_form_nickname_is_illegal',  'EN', "This username is not allowed, please choose another one");
___('users_register_form_nickname_is_illegal',  'FR', "Ce pseudonyme n'est pas autorisé, merci d'en choisir un autre");
___('users_register_form_nickname_exists',      'EN', "This username is already in use, please choose another one");
___('users_register_form_nickname_exists',      'FR', "Ce pseudonyme est déjà utilisé, merci d'en choisir un autre");
___('users_register_form_password_1',           'EN', "Password (at least 8 characters long)");
___('users_register_form_password_1',           'FR', "Mot de passe (8 caractères minimum)");
___('users_register_form_password_2',           'EN', "Confirm your password by typing it again");
___('users_register_form_password_2',           'FR', "Entrez à nouveau votre mot de passe pour le confirmer");
___('users_register_form_email',                'EN', "E-mail address (optional, useful if you forget your password)");
___('users_register_form_email',                'FR', "Adresse e-mail (optionnel, utile en cas de mot de passe oublié)");
___('users_register_form_question_1',           'EN', "Is pornography allowed?");
___('users_register_form_question_1',           'FR', "La pornographie est-elle autorisée ?");
___('users_register_form_question_1_maybe',     'EN', "It depends");
___('users_register_form_question_1_maybe',     'FR', "Ça dépend des cas");
___('users_register_form_question_2',           'EN', "Can I share gore images?");
___('users_register_form_question_2',           'FR', "Les images gores sont-elle tolérées ?");
___('users_register_form_question_2_dummy',     'EN', "I didn't read the rules");
___('users_register_form_question_2_dummy',     'FR', "Je n'ai pas lu les règles");
___('users_register_form_question_3',           'EN', "I'm having a tense argument with someone, what should I do?");
___('users_register_form_question_3',           'FR', "Mes échanges avec quelqu'un d'autre dégénèrent, je fais quoi ?");
___('users_register_form_question_3_silly',     'EN', "Spread it publicly");
___('users_register_form_question_3_silly',     'FR', "J'étale ça en public");
___('users_register_form_question_3_good',      'EN', "Try my best to solve it privately");
___('users_register_form_question_3_good',      'FR', "Je tente de résoudre ça en privé");
___('users_register_form_question_4',           'EN', "I'm being aggressive towards others, what will happen to me?");
___('users_register_form_question_4',           'FR', "J'agresse quelqu'un d'autre, qu'est-ce qui va m'arriver ?");
___('users_register_form_question_4_banned',    'EN', "I will get banned");
___('users_register_form_question_4_banned',    'FR', "Je me fais bannir");
___('users_register_form_question_4_freedom',   'EN', "Nothing, free speech protects me!");
___('users_register_form_question_4_freedom',   'FR', "La liberté d'expression me protège !");
___('users_register_form_captcha',              'EN', "Prove that you are a human by copying this number");
___('users_register_form_captcha',              'FR', "Prouvez que vous n'êtes pas un robot en recopiant ce nombre");
___('users_register_form_captcha_alt',          'EN', "You must turn off your image blocker to see this captcha !");
___('users_register_form_captcha_alt',          'FR', "Vous devez désactiver votre bloqueur d'image pour voir ce captcha !");
___('users_register_form_submit',               'EN', "Create my account");
___('users_register_form_submit',               'FR', "Créer mon compte");


// Error messages
___('users_register_error_no_nickname',         'EN', "You must specify a nickname");
___('users_register_error_no_nickname',         'FR', "Vous devez saisir un pseudonyme");
___('users_register_error_no_password',         'EN', "You must specify a password");
___('users_register_error_no_password',         'FR', "Vous devez saisir un mot de passe");
___('users_register_error_passwords',           'EN', "You must enter the same password twice");
___('users_register_error_passwords',           'FR', "Vous devez saisir deux fois le même mot de passe");
___('users_register_error_password_length',     'EN', "Your password is too short (8 characters minimum)");
___('users_register_error_password_length',     'FR', "Votre mot de passe est trop court (8 caractères minimum)");
___('users_register_error_captchas',            'EN', "The number you entered in the last field did not match the number on the image");
___('users_register_error_captchas',            'FR', "Le nombre que vous avez saisi dans le dernier champ ne correspond pas à celui sur l'image");
___('users_register_error_nickname_short',      'EN', "The chosen username is too short");
___('users_register_error_nickname_short',      'FR', "Le pseudonyme choisi est trop court");
___('users_register_error_nickname_long',       'EN', "The chosen username is too long");
___('users_register_error_nickname_long',       'FR', "Le pseudonyme choisi est trop long");
___('users_register_error_password_short',      'EN', "The chosen password is too short");
___('users_register_error_password_short',      'FR', "Le mot de passe choisi est trop court");
___('users_register_error_nickname_illegal',    'EN', "The chosen username contains a forbidden word");
___('users_register_error_nickname_illegal',    'FR', "Le pseudonyme choisi contient un mot interdit");
___('users_register_error_nickname_characters', 'EN', "Your username can only be made from non accentuated latin letters and numbers");
___('users_register_error_nickname_characters', 'FR', "Votre pseudonyme ne peut être composé que de lettres non accentuées et de chiffres");
___('users_register_error_nickname_taken',      'EN', "The chosen username is already taken by another user");
___('users_register_error_nickname_taken',      'FR', "Le pseudonyme choisi est déjà utilisé par quelqu'un d'autre");


// Welcome private message
___('users_register_private_message_title', 'EN', "Welcome to NoBleme!");
___('users_register_private_message_title', 'FR', "Bienvenue sur NoBleme !");
___('users_register_private_message',       'EN', <<<EOT
[size=1.3][b]Welcome to NoBleme![/b][/size]

Now that you have registered, why not join the community where it is most active: on [url={{1}}todo_link]the IRC chat server[/url].

If you are curious about what is happening on the website and within its community, why not check out the [url={{1}}pages/nobleme/activity]recent activity page[/url] - that's what it's here for!

Enjoy your stay on NoBleme!
If you have any questions, feel free to reply to this message.

Your admin,
[url={{1}}todo_link]Bad[/url]
EOT
);
___('users_register_private_message',       'FR', <<<EOT
[size=1.3][b]Bienvenue sur NoBleme ![/b][/size]

Maintenant que vous avez rejoint le site, pourquoi ne pas rejoindre la communauté là où elle est active : sur [url={{1}}todo_link]le serveur de discussion IRC[/url].

Si vous voulez suivre ce qui est publié sur le site et ce qui se passe au sein de sa communauté, vous pouvez le faire via [url={{1}}pages/nobleme/activity]l'activité récente[/url].

Bon séjour sur NoBleme !
Si vous avez la moindre question, n'hésitez pas à répondre à ce message.

Votre administrateur,
[url={{1}}todo_link]Bad[/url]
EOT
);




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                      WELCOME                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Body
___('users_welcome_title',  'EN', "Your account has been created");
___('users_welcome_title',  'FR', "Votre compte a été crée");
___('users_welcome_body',   'EN', <<<EOT
You have successfully registered an account on NoBleme. You can now use the login form above to log into your newly created account and begin using the website as a registered user!
EOT
);
___('users_welcome_body',   'FR', <<<EOT
Votre compte a bien été crée. Vous pouvez maintenant utiliser le formulaire de connexion ci-dessus afin de vous connecter sur votre nouveau compte. Bienvenue dans la communauté NoBlemeuse !
EOT
);