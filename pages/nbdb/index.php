<?php /***********************************************************************************************************************************/
/*                                                                                                                                       */
/*                                                             INITIALISATION                                                            */
/*                                                                                                                                       */
// Inclusions /***************************************************************************************************************************/
include './../../inc/includes.inc.php'; // Inclusions communes

// Menus du header
$header_menu      = 'Lire';
$header_sidemenu  = 'NBDBIndex';

// Identification
$page_nom = "Espère que le wiki reviendra un jour";
$page_url = "pages/nbdb/index";

// Langages disponibles
$langage_page = array('FR');

// Titre et description
$page_titre = "NBDB";
$page_desc  = "NoBlemeDataBase, compilation de savoir utile et inutile.";




/*****************************************************************************************************************************************/
/*                                                                                                                                       */
/*                                                         AFFICHAGE DES DONNÉES                                                         */
/*                                                                                                                                       */
/************************************************************************************************/ include './../../inc/header.inc.php'; ?>

      <div class="texte">

        <h1>NBDB</h1>

        <h5>Le successeur spirituel du Wiki NoBleme... pour Bientôt™</h5>

        <p>Êtes-vous assez ancien NoBlemeux pour vous souvenir du Wiki NoBleme ? Si oui, la bonne nouvelle, c'est qu'il est prévu qu'il revienne un de ces jours, sous une forme plus moderne (et sous un nom différent). La mauvaise nouvelle, c'est que c'est pas vraiment en haut de la liste des priorité, ça demande une grande quantité d'investissement personnel de maintenir une encyclopédie entière. Patience et espoir.</p>

        <br>
        <br>
        <br>

        <div class="align_center">
          <img src="<?=$chemin?>img/divers/construction.png" alt="Under construction">
        </div>

      </div>

<?php /***********************************************************************************************************************************/
/*                                                                                                                                       */
/*                                                              FIN DU HTML                                                              */
/*                                                                                                                                       */
/***************************************************************************************************/ include './../../inc/footer.inc.php';