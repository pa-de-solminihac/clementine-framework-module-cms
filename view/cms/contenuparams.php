<script type="text/javascript" charset="<?php echo __HTML_ENCODING__; ?>">
<!--
    // Load jQuery
    if (typeof jQuery == 'undefined') {
        document.write('jQuery is needed here<br />');
    }
// -->
</script>

<h2>Paramètres du contenu</h2>

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

<form action="<?php echo __WWW__; ?>/cms/contenuparams?id_zone=<?php echo $data['id_zone']; ?>&amp;id_page=<?php echo $data['id_page']; ?>" method="post" accept-charset="<?php echo __HTML_ENCODING__; ?>">

<input name="id_contenu" type="hidden" value="<?php echo $data['id_contenu']; ?>" />
<input name="id_zone" type="hidden" value="<?php echo $data['id_zone']; ?>" />
<input name="id_page" type="hidden" value="<?php echo $data['id_page']; ?>" />
<table class="clementine_cms_contenu_params">
<tbody>
<?php
$ns = $this->getModel('fonctions');
$i = 0;
foreach ($data['params'] as $param_name => $param_val) {
?>
<tr><td>
    <input name="param_name_<?php echo $i; ?>" type="text" class="textbox_keys" value="<?php echo $ns->htmlentities($param_name); ?>" />
</td><td>
    <input name="param_val_<?php echo $i; ?>" type="text" class="textbox_vals" value="<?php echo $ns->htmlentities($param_val); ?>" />
</td><td>
    <img class="clementine_cms_contenu_del_param_button" src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/delete.png" />
</td></tr>
<?php
    ++$i;
}
?>
</tbody>
</table>
<img class="clementine_cms_contenu_add_param_button" src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/add.png" />
<div class="clementine_cms_contenuparams_submit">
    <input type="submit" value="Enregistrer" />
</div>
</form>

<script type="text/javascript" charset="<?php echo __HTML_ENCODING__; ?>">
    if (typeof jQuery != 'undefined') {

        /* ajout d'une ligne */
        jQuery('.clementine_cms_contenu_add_param_button').click(function() {
            var numero = jQuery('.clementine_cms_contenu_params .clementine_cms_contenu_new_param').length;
            jQuery('.clementine_cms_contenu_params tbody').append(' <tr class="clementine_cms_contenu_new_param"><td> <input name="new_param_name_' + numero + '" type="text" value="" /> </td><td> <input name="new_param_val_' + numero  + '" type="text" value="" /> </td><td> <img class="clementine_cms_contenu_del_param_button" src="<?php echo __WWW_ROOT_CMS__; ?>/skin/images/delete.png" /> </td></tr> ');
            jQuery('input[name=new_param_name_' + numero + ']').textbox({ items: <?php echo $data['params_keys']; ?>});
            jQuery('input[name=new_param_val_' + numero + ']').textbox({ items: <?php echo $data['params_vals']; ?>});
        });

        /* suppression d'une ligne */
        jQuery('.clementine_cms_contenu_params').delegate('img.clementine_cms_contenu_del_param_button', 'click', function() {
            jQuery(this).parent().parent().remove();
        });
    }
</script>
