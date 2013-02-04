<?php
$ns = $this->getModel('fonctions');
if (isset($data['page']) && $data['page']) {
    $page = $data['page']; 
    if (isset($data['zones'])) {
        $zones = $data['zones']; 

        foreach ($zones as $nom_zone => $zone) {
?>
    <div class="zones_list<?php 
            if ($zone['status'] == 'inactive') {
                echo '_disabled';
            } 
?>">

                <div class="content-box">

                    <div class="content-box-header">
                        <h3 class="zone_nom">Zone <?php echo $nom_zone; ?></h3>

                        <div class="content-box-tools">
                            <a class="parametres" title="parametres" rel="modal" href="<?php echo __WWW__; ?>/cms/zoneparams?id_zone=<?php echo $zone['id_zone']; ?>&amp;id_page=<?php echo $page['id']; ?>" >
                                <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/icons/tools.png" alt="paramètres" />
                            </a>
                            <a class="ajouter" title="ajouter un contenu" rel="modal" href="<?php echo __WWW__; ?>/contenus/addcontenu?id=<?php echo $zone['id_zone']; ?>&amp;page=<?php echo $ns->ifGet('int', 'id'); ?>" >
                                <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/add.png" alt="ajouter un contenu" />
                            </a>
                        </div>

                    </div>
                    <div class="content-box-content">

                        <div class="zones_list_head">
<?php 
            if ($zone['status'] == 'inactive') {
?>
                            <span class="zone_inactive"> zone inactive pour ce modèle de page </span>
                            <br />
<?php 
            }
?>
                        </div>
                        <div class="contenus_index">
<?php
            if (count($zone['contenus'])) { 
?>
                            <table class="contenus_index_list">
                                <thead>
                                    <tr>
                                        <th class="col1"> 
                                            <input name="zone_content_order_<?php echo $zone['id_zone']; ?>" type="hidden" class="cms_content_order" value="" />
                                            Contenu 
                                        </th>
                                        <th class="col2"> Publication </th>
                                        <th class="col3"> Actions </th>
                                    </tr>
                                </thead>
                                <tbody>
<?php 
                foreach ($zone['contenus'] as $id_contenu => $contenu) {
                    $type_contenu = $contenu['type_contenu'];
                    $type_contenu = substr($type_contenu, 15);
?>
                                    <tr id="cms_contenu_id_<?php echo $contenu['id_contenu']; ?>">
                                        <td class="col1">
                                            <div class="contenus_list_title">
                                                <a title="modifier" href="<?php echo __WWW__; ?>/contenus/editcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;id_page=<?php echo $page['id']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>" >
                                                    <strong><?php echo $contenu['nom_contenu']; ?></strong>
                                                </a>
                                            </div>
                                            <div class="contenus_list_content">
                                                <a title="modifier" href="<?php echo __WWW__; ?>/contenus/editcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;id_page=<?php echo $page['id']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>" >
<?php
                    if (!isset($contenu['contenu'][$type_contenu])) {
                        // affichage de l'apercu indisponible... 
                        if (!$contenu['traductions'][$request->LANG]) {
?>
                                        <span class="notrad"> non traduit </span>
<?php 
                        } else {
                            if (__DEBUGABLE__) {
                                echo "aperçu indisponible (RAPPEL : la table de contenu " . $contenu['type_contenu'] . " doit contenir un champ " . $type_contenu . ", qui est utilisé automatiquement pour l'aperçu)";
                            } else {
                                echo "aperçu indisponible";
                            }
                        }
                    } else {
                        $str = preg_replace('@<br */*>@i', "\n", $contenu['contenu'][$type_contenu]);
                        $str = preg_replace('@<img [^>]*/*>@i', "*IMAGE*", $contenu['contenu'][$type_contenu]);
                        $str = $ns->strip_tags($str);
                        $str = preg_replace("/([[:blank:]]|&nbsp;)+/", ' ', $str);
                        $str = preg_replace("/ *(\r*\n)+/", "\\1", $str); // premier passage
                        $str = preg_replace("/ *(\r*\n)+/", "\\1", $str); // deuxieme passage
                        $str = trim($str);
                        echo nl2br($ns->substr($str, 0, 200));
                    }
?>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="col2">
<?php
                    if ($zone['infos'][$id_contenu]['valide'] == 1) {
?>
                                        <span class="publish"> publié </span>
<?php
                        if ($zone['infos'][$id_contenu]['date_lancement'] && $zone['infos'][$id_contenu]['date_arret']) {
?>
                                        du <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$id_contenu]['date_lancement'])); ?> 
                                        au <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$id_contenu]['date_arret'])); ?> 
<?php 
                        } elseif ($zone['infos'][$id_contenu]['date_lancement']) {
?>
                                        à partir du <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$id_contenu]['date_lancement'])); ?> 
<?php 
                        } elseif ($zone['infos'][$id_contenu]['date_arret']) {
?>
                                        jusqu'au <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$id_contenu]['date_arret'])); ?>
<?php 
                        }
                    } else {
?>
                                        <span class="nopublish"> non publié </span>
<?php 
                    }
?>

                <!-- traductions -->
<?php
                    $lang_dispo = array_keys($request->EQUIV);
                    if (count($lang_dispo) > 1) {
?>
                                        <br />
<?php
                        foreach ($lang_dispo as $lng) {
?>
                                        <a title="traduire" href="<?php echo __WWW_ROOT__ . '/' . $lng; ?>/contenus/editcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;id_page=<?php echo $page['id']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>" >
<?php
                            if (!$contenu['traductions'][$lng]) {
?>
                                        <span class="notrad">
                                            <?php echo $lng; ?>
                                        </span>
<?php 
                            } else {
?>
                                        <span class="trad"><?php echo $lng; ?></span>
<?php 
                            }
?>
                                        </a>
<?php 
                        }
                    }
?>

                                        </td>
                                        <td class="col3">
                                                <img class="cms_handle" src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/icons/move.png" alt="déplacer" title="déplacer" />
<?php
                    if ($zone['infos'][$id_contenu]['valide'] == 1) {
?>
                                            <a class="publier" title="cliquez pour dépublier" href="<?php echo __WWW__; ?>/contenus/publishcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>&amp;publish=0&amp;id_page=<?php echo $page['id']; ?>" >
                                                <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/publish.jpg" alt="dépublier" />
                                            </a>
<?php 
                    } else {
?>
                                            <a class="publier" title="cliquez pour publier" href="<?php echo __WWW__; ?>/contenus/publishcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>&amp;publish=1&amp;id_page=<?php echo $page['id']; ?>" >
                                                <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/not_publish.jpg" alt="publier" />
                                            </a>
<?php 
                    }
?>
                                            <a class="modifier" title="modifier" href="<?php echo __WWW__; ?>/contenus/editcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;id_page=<?php echo $page['id']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>" >
                                                <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/icons/write.png" alt="modifier" />
                                            </a>
                                            <a class="parametres" title="parametres" rel="modal" href="<?php echo __WWW__; ?>/cms/contenuparams?id_contenu=<?php echo $contenu['id_contenu']; ?>&amp;id_zone=<?php echo $zone['id_zone']; ?>&amp;id_page=<?php echo $page['id']; ?>" >
                                                <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/icons/tools.png" alt="parametres" />
                                            </a>
                                            <a class="supprimer" title="supprimer" onclick="return delete_contenu('<?php echo $contenu['id_contenu']; ?>', '<?php echo $contenu['type_contenu']; ?>', '<?php echo $page['id']; ?>');" href="" >
                                                <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/icons/delete.png" alt="supprimer" />
                                            </a>
                                        </td>
                                    </tr>
<?php 
                }
?>
                                </tbody>
                            </table>
<?php 
            }
?>
                        </div>
                    </div>
                </div>
            </div>
<?php 
        }
    }
}
?>
