<?php $tag_for = $data['tag_for']; ?>
<div class="content-box well">
    <div class="content-box-header">
        <h3>Tags : <?php echo $tag_for; ?></h3>
    </div>
    <div class="content-box-content">

<?php
if (isset($data['erreurs'])) {
?>
<ul class="clementine_cms_erreurs">
<?php
foreach ($data['erreurs'] as $err) {
    echo '<li>' . $err . '</li>';
}
?>
</ul>
<?php
}
?>

    <form action="<?php echo __WWW__; ?>/cms/<?php echo $tag_for; ?>params?id_<?php echo $tag_for; ?>=<?php echo $data['id_' . $tag_for]; ?>" method="post" accept-charset="<?php echo __HTML_ENCODING__; ?>">

<?php
if ($tag_for == 'contenu') {
?>
        <input name="id_contenu" type="hidden" value="<?php echo $data['id_contenu']; ?>" />
<?php
}
?>
<?php
if ($tag_for == 'zone' || $tag_for == 'contenu') {
?>
        <input name="id_zone" type="hidden" value="<?php echo $data['id_zone']; ?>" />
<?php
}
?>
        <input name="id_page" type="hidden" value="<?php echo $data['id_page']; ?>" />
        <table class="clementine_cms_<?php echo $tag_for; ?>_params">
        <thead>
            <th>Nom</th>
            <th>Valeur</th>
            <th> </th>
        </thead>
        <tbody>
<?php
$ns = $this->getModel('fonctions');
$i = 0;
foreach ($data['params'] as $param_name => $param_val) {
?>
        <tr><td>
            <input name="param_name_<?php echo $i; ?>" type="text" class="textbox_keys form-control" value="<?php echo $ns->htmlentities($param_name); ?>" />
        </td><td>
            <input name="param_val_<?php echo $i; ?>" type="text" class="textbox_vals form-control" value="<?php echo $ns->htmlentities($param_val); ?>" />
        </td><td>
            <span class="clementine_cms_<?php echo $tag_for; ?>_del_param_button btn btn-xs btn-default " href="<?php echo __WWW__ . '/cms'; ?>" title="Supprimer">
                <i class="glyphicon glyphicon-remove"></i><span class="text-hide">Supprimer</span>
            </span>
        </td></tr>
<?php
    ++$i;
}
?>
        </tbody>
        </table>
        <span class="clementine_cms_<?php echo $tag_for; ?>_add_param_button btn btn-xs btn-primary " href="<?php echo __WWW__ . '/cms'; ?>" title="Ajouter">
            <i class="glyphicon glyphicon-plus"></i><span class="text-hide">Ajouter</span>
        </span>
        <div class="clementine_cms_<?php echo $tag_for; ?>params_submit">
            <button
                type="submit"
                class="clementine_cms-savebutton savebutton btn btn-lg btn-primary btn-raised pull-right btn-fab"
                title="Enregistrer">
                <i class="glyphicon glyphicon-ok"></i><span class="text-hide">Enregistrer</span>
            </button>
        </div>
    </form>
</div>
