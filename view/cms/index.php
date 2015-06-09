<div class="pages_index">
    <table class="pages_index_list clementine-dataTables table table-striped table-hover table-responsive">
<?php 
if (isset($data['pages']) && is_array($data['pages']) && count($data['pages'])) {
?>
        <thead>
            <tr>
                <th class="col1"> Nom de la page </th>
                <th class="col2"> Modèle </th>
                <th class="col3"> </th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach ($data['pages'] as $id => $pages) {
?>
            <tr>
                <td class="col1">
                    <a class="modifier " href="<?php echo __WWW__ . '/cms/editpage?id=' . $id; ?>" title="modifier">
                        <?php echo $pages['nom']; ?>
                    </a>
                </td>
                <td class="col2">
                    <a class="modifier " href="<?php echo __WWW__ . '/cms/editpage?id=' . $id; ?>" title="modifier">
                        <?php echo preg_replace('@^templates/@', '', $pages['template_chemin']); ?>
                    </a>
                </td>
                <td class="col3">
                    <div class="dropdown">
                        <button class="btn-link dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                            <span class="glyphicon glyphicon-option-vertical"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li>
                                <a
                                    class="modifier" 
                                    href="<?php echo __WWW__ . '/cms/editpage?id=' . $id; ?>"
                                    title="Modifier">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                        Modifier
                                </a>
                            </li>
                            <li>
                                <a
                                    class="params" 
                                    rel="modal" 
                                    href="<?php echo __WWW__; ?>/cms/pageparams?id_page=<?php echo $id; ?>" 
                                    title="Tags">
                                        <i class="glyphicon glyphicon-tags"></i>
                                        Tags
                                </a>
                            </li>
                            <li>
                                <a 
                                    target="_blank"
                                    class="voir"
                                    href="<?php echo __WWW__ . '/' . $id; ?>"
                                    title="Voir la page">
                                    <i class="glyphicon glyphicon-eye-open"></i>
                                    Voir
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a
                                    class="supprimer btn-danger" 
                                    href="" 
                                    onclick="return delete_page('<?php echo $id; ?>');" 
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
} else {
?>
        Il n'y a aucune page.
<?php 
}
?>
</div>
