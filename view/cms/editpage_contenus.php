<?php
$ns = $this->getModel('fonctions');
if (isset($data['page']) && $data['page']) {
    $page = $data['page']; 
    if (isset($data['zones'])) {
        $zones = $data['zones']; 

        foreach ($zones as $nom_zone => $zone) {
?>
    <div class="well zones_list<?php 
            if ($zone['status'] == 'inactive') {
                echo '_disabled';
            } 
?>">

                <div class="content-box">

                    <div class="content-box-header">
                        <h3 class="zone_nom">Zone <strong><?php echo str_replace('_', ' ', $nom_zone); ?></strong>
                            <a class="parametres btn btn-xs btn-default " rel="modal" href="<?php echo __WWW__ . '/cms/zoneparams?id_zone=' . $zone['id_zone'] . '&amp;id_page=' . $page['id']; ?>" title="Gérer les tags">
                                <i class="glyphicon glyphicon-tags"></i><span class="text-hide">Gérer les tags</span>
                            </a>
                            <div class="spacer"></div>
                        </h3>


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
                            <table class="contenus_index_list clementine-dataTables table table-striped table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th class="col1"> 
                                            <input name="zone_content_order_<?php echo $zone['id_zone']; ?>" type="hidden" class="cms_content_order" value="<?php echo implode(',', array_keys($zone['contenus'])); ?>" />
                                            Contenu 
                                        </th>
                                        <th class="col2"> Publication </th>
                                        <th class="col3"> </th>
                                    </tr>
                                </thead>
                                <tbody id="cms_contenus_<?php echo $nom_zone; ?>">
<?php 
                foreach ($zone['contenus'] as $rang_contenu => $contenu) {
                    $type_contenu = $contenu['type_contenu'];
                    $type_contenu = substr($type_contenu, 15);
?>
                                    <tr id="cms_contenu_id_<?php echo $contenu['id_contenu']; ?>">
                                        <td class="col1">
                                            <input name="content_type_<?php echo $contenu['id_contenu']; ?>" type="hidden" value="<?php echo $type_contenu; ?>" />
                                            <div class="contenus_list">
                                                <a title="modifier" href="<?php echo __WWW__; ?>/contenus/editcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;id_page=<?php echo $page['id']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>" >
                                                    <strong><?php echo $contenu['nom_contenu']; ?></strong>
                                                    <span>
<?php
                    if (!isset($contenu['contenu'][$type_contenu])) {
                        // affichage de l'apercu indisponible... 
                        if (!$contenu['traductions'][$request->LANG]) {
?>
                                        <span class="notrad"> non traduit </span>
<?php 
                        } else {
                            if (__DEBUGABLE__) {
                                // RAPPEL : la table de contenu " . $contenu['type_contenu'] . " doit contenir un champ " . $type_contenu . ", qui est utilisé automatiquement pour l'aperçu
                                echo "aperçu indisponible (à cause d'un bug)";
                            } else {
                                echo "aperçu indisponible";
                            }
                        }
                    } else {
                        $str = preg_replace('@<br */*>@i', "\n", $contenu['contenu'][$type_contenu]);
                        $str = preg_replace('@<img [^>]*/*>@i', "#IMAGE#", $contenu['contenu'][$type_contenu]);
                        $str = $ns->strip_tags($str);
                        $str = preg_replace("/([[:blank:]]|&nbsp;)+/", ' ', $str);
                        $str = preg_replace("/ *(\r*\n)+/", "\\1", $str); // premier passage
                        $str = preg_replace("/ *(\r*\n)+/", "\\1", $str); // deuxieme passage
                        $str = trim($str);
                        $str = $ns->truncate($str, 200);
                        $str = str_replace('#IMAGE#', '<i class="glyphicon glyphicon-picture"></i>', $str);
                        echo $str;
                    }
?>
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="col2">
                                    <a class="modifier" title="modifier" href="<?php echo __WWW__; ?>/contenus/editcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;id_page=<?php echo $page['id']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>" >
<?php
                    if ($zone['infos'][$rang_contenu]['valide'] == 1) {
?>
<?php
                        if ($zone['infos'][$rang_contenu]['date_lancement'] && $zone['infos'][$rang_contenu]['date_arret']) {
?>
                                        <span class="publish">
                                            publié
                                            <span>
                                                du <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$rang_contenu]['date_lancement'])); ?> 
                                                au <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$rang_contenu]['date_arret'])); ?> 
                                            </span>
                                        </span>
<?php 
                        } elseif ($zone['infos'][$rang_contenu]['date_lancement']) {
                            if (strtotime($zone['infos'][$rang_contenu]['date_lancement']) > time()) {
?>
                                        <span class="publish">
                                            publié
                                            <span>
                                                à partir du <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$rang_contenu]['date_lancement'])); ?> 
                                            </span>
                                        </span>
<?php 
                            } else {
?>
                                        <span class="publish">
                                            publié
                                            <span>
                                                depuis le <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$rang_contenu]['date_lancement'])); ?> 
                                            </span>
                                        </span>
<?php 
                            }
                        } elseif ($zone['infos'][$rang_contenu]['date_arret']) {
                            if (strtotime($zone['infos'][$rang_contenu]['date_arret']) > time()) {
?>
                                        <span class="publish">
                                            publié
                                            <span>
                                                jusqu'au <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$rang_contenu]['date_arret'])); ?>
                                            </span>
                                        </span>
<?php 
                            } else {
?>
                                        <span class="nopublish">
                                            périmé
                                            <span>
                                                depuis le <?php echo strftime('%d/%m/%Y', strtotime($zone['infos'][$rang_contenu]['date_arret'])); ?>
                                            </span>
                                        </span>
<?php 
                            }
                        }
                    } else {
?>
                                        <span class="nopublish">
                                            non publié
                                        </span>
<?php 
                    }
?>
                                    </a>
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



<div class="dropdown">
    <i class="cms_handle glyphicon glyphicon-move"></i>
    <button class="btn-link dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
        <span class="glyphicon glyphicon-option-vertical"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" role="menu">
        <li>
            <a
                class="modifier"
                href="<?php echo __WWW__; ?>/contenus/editcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;id_page=<?php echo $page['id']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>"
                title="modifier" >
                    <i class="glyphicon glyphicon-pencil"></i>
                    Modifier
            </a>
        </li>
        <li>
            <a
                class="parametres" 
                rel="modal" 
                href="<?php echo __WWW__ . '/cms/contenuparams?id_contenu=' . $contenu['id_contenu'] . '&amp;id_zone=' . $zone['id_zone'] . '&amp;id_page=' . $page['id']; ?>" 
                title="Tags">
                    <i class="glyphicon glyphicon-tags"></i>
                    Tags
            </a>
        </li>
        <li>
<?php
                    if ($zone['infos'][$rang_contenu]['valide'] == 1) {
?>
            <a
                class="publier" 
                href="<?php echo __WWW__; ?>/contenus/publishcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>&amp;publish=0&amp;id_page=<?php echo $page['id']; ?>" 
                title="cliquez pour dépublier">
                    <i class="glyphicon glyphicon-eye-close"></i>
                    Dépublier
            </a>
<?php 
                    } else {
?>
            <a
                class="publier" 
                href="<?php echo __WWW__; ?>/contenus/publishcontenu?id=<?php echo $contenu['id_contenu']; ?>&amp;type=<?php echo $contenu['type_contenu']; ?>&amp;publish=1&amp;id_page=<?php echo $page['id']; ?>" 
                title="cliquez pour publier">
                    <i class="glyphicon glyphicon-eye-open"></i>
                    Publier
            </a>
<?php 
                    }
?>


        </li>
        <li class="divider"></li>
        <li>
            <a
                class="supprimer btn-danger" 
                href="" 
                onclick="return delete_contenu('<?php echo $contenu['id_contenu']; ?>', '<?php echo $contenu['type_contenu']; ?>', '<?php echo $page['id']; ?>');" 
                title="supprimer">
                    <i class="glyphicon glyphicon-trash"></i>
                    Supprimer
            </a>
        </li>
    </ul>
</div>


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
                            <a class="ajouter btn btn-xs btn-primary pull-right " rel="modal" href="<?php echo __WWW__ . '/contenus/addcontenu?id=' . $zone['id_zone'] . '&amp;page=' . $request->get('int', 'id'); ?>" title="Ajouter un contenu">
                                <i class="glyphicon glyphicon-plus"></i><span class="text-hide">Ajouter un contenu</span>
                            </a>
                            <div class="spacer"></div>
                        </div>
                    </div>
                </div>
            </div>
<?php 
        }
    }
}
?>
