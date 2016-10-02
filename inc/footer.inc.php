<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Version actuelle
$qversion = mysqli_fetch_array(query("SELECT version.version, version.build, version.date FROM version ORDER BY version.id DESC LIMIT 1"));
$version = "Version ".$qversion['version'].", build ".$qversion['build']." du ".jourfr($qversion['date']);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Définition du nombre de pageviews
$page_views = isset($pageviews) ? "Cette page a été consultée ".$pageviews." fois" : "";



/*****************************************************************************************************************************************/
/*                                                                                                                                       */
/*                                                         AFFICHAGE DES DONNÉES                                                         */
/*                                                                                                                                       */
/**************************************************************************************************************************************/ ?>

      <?php if(!isset($_GET["popup"]) && !isset($_GET["popout"]) && !isset($_GET["dynamique"])) { ?>

      <div class="footer">
        <br>
        <?php if(loggedin() && getadmin($_SESSION['user'])) { ?>
        <a class="footer_lien" href="<?=$chemin?>pages/admin/stats_pageviews"><?=$page_views?></a><br>
        <?php } ?>
        <a class="footer_lien" href="<?=$chemin?>pages/todo/roadmap"><?=$version?></a><br>
        <a class="footer_lien" href="<?=$chemin?>pages/user/user?id=1">Développé et administré par <span class="gras">Bad</span></a><br>
        <a class="footer_lien" href="<?=$chemin?>pages/doc/nobleme">NoBleme.com: 2005 - <?=date('Y')?></a><br>
        <br>
      </div>
      </div>
    </div>

    <?php } ?>

  </body>
</html>