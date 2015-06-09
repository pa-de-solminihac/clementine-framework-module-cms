<?php $tag_for = $data['tag_for']; ?>
<script type="text/javascript" charset="<?php echo __HTML_ENCODING__; ?>">
    if (typeof jQuery != 'undefined') {

        /* ajout d'une ligne */
        jQuery('.clementine_cms_<?php echo $tag_for; ?>_add_param_button').click(function() {
            var numero = jQuery('.clementine_cms_<?php echo $tag_for; ?>_params .clementine_cms_<?php echo $tag_for; ?>_new_param').length;
            var del_param_button = ' <span class="clementine_cms_<?php echo $tag_for; ?>_del_param_button btn btn-xs btn-default " href="<?php echo __WWW__ . '/cms'; ?>" title="Supprimer"> <i class="glyphicon glyphicon-remove"></i><span class="text-hide">Supprimer</span> </span> ';
            jQuery('.clementine_cms_<?php echo $tag_for; ?>_params tbody').append(' <tr class="clementine_cms_<?php echo $tag_for; ?>_new_param"><td> <input name="new_param_name_' + numero + '" class="form-control" type="text" value="" /> </td><td> <input name="new_param_val_' + numero  + '" class="form-control" type="text" value="" /> </td><td> ' + del_param_button + ' </td></tr> ');
        });

        /* suppression d'une ligne */
        jQuery('.clementine_cms_<?php echo $tag_for; ?>_params').delegate('.clementine_cms_<?php echo $tag_for; ?>_del_param_button', 'click', function() {
            jQuery(this).parent().parent().remove();
        });
    }
</script>
