<?php /***************************************************************************************************************/
/*                                                                                                                   */
/*                            THIS PAGE CAN ONLY BE RAN IF IT IS INCLUDED BY ANOTHER PAGE                            */
/*                                                                                                                   */
// Include only /*****************************************************************************************************/
if(substr(dirname(__FILE__),-8).basename(__FILE__) == str_replace("/","\\",substr(dirname($_SERVER['PHP_SELF']),-8).basename($_SERVER['PHP_SELF']))) { exit(header("Location: ./../404")); die(); }


/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                      QUERIES                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

// All's OK
___('dev_queries_ok', 'EN', 'ALL QUERIES HAVE SUCCESSFULLY BEEN RAN');
___('dev_queries_ok', 'FR', 'LES REQUÊTES ONT ÉTÉ EFFECTUÉES AVEC SUCCÈS');




/*********************************************************************************************************************/
/*                                                                                                                   */
/*                                                     SNIPPETS                                                      */
/*                                                                                                                   */
/*********************************************************************************************************************/

// Section titles
___('dev_snippets_title_blocks', 'EN', 'COMMENT BLOCKS');
___('dev_snippets_title_blocks', 'FR', 'BLOCS DE COMMENTAIRES');
___('dev_snippets_title_header', 'EN', 'HEADERS');
___('dev_snippets_title_header', 'FR', 'EN-TÊTES');
___('dev_snippets_title_full', 'EN', 'FULL PAGE');
___('dev_snippets_title_full', 'FR', 'PAGE COMPLÈTE');