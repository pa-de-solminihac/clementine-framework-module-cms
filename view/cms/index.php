<div class="pages_index">
    <table class="pages_index_list clementine-dataTables">
<?php 
if (isset($data['pages']) && is_array($data['pages']) && count($data['pages'])) {
?>
        <thead>
            <tr>
                <th class="col1"> Nom de la page </th>
                <th class="col2"> Alias URL </th>
                <th class="col3"> Actions </th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach ($data['pages'] as $id => $pages) {
?>
            <tr>
                <td class="col1">
                    <a title="modifier" href="<?php echo __WWW__; ?>/cms/editpage?id=<?php echo $id; ?>" >
                        <?php echo $pages['nom']; ?>
                    </a>
                    <a class="modifier" href="<?php echo __WWW__; ?>/cms/editpage?id=<?php echo $id; ?>" >
                        <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/icons/write.png" alt="modifier" />
                    </a>
                </td>
                <td class="col2">
                    <a class="voir" target="_blank" href="<?php echo __WWW__; ?>/<?php echo $pages['slug']; ?>" >
                        <?php echo $pages['slug']; ?>
                    </a>
                    <a class="voir" target="_blank" href="<?php echo __WWW__; ?>/<?php echo $pages['slug']; ?>" >
                        <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/icons/voir.png" alt="voir" />
                    </a>
                </td>
                <td class="col3">
                    <a rel="modal" class="params" href="<?php echo __WWW__; ?>/cms/pageparams?id_page=<?php echo $id; ?>" >
                        <img src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/icons/tools.png" alt="parametres" />
                    </a>
                    <a class="supprimer" title="supprimer" onclick="return delete_page('<?php echo $id; ?>');" href="" >
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
} else {
?>
        Il n'y a aucune page.
<?php 
}
?>
</div>
