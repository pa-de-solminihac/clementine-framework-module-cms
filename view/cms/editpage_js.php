<?php
$this->getBlock('cms/js', $data, $request);
?>

<script type="text/javascript" charset="<?php echo __HTML_ENCODING__; ?>">
jQuery('.contenus_index_list tbody').sortable({
    handle: '.cms_handle',
    connectWith: '.contenus_index_list > tbody', // pas encore prêt car le CMS n'enregistre pas le contenu dans sa nouvelle zone
    forcePlaceholderSize: 'true',
    placeholder: 'cms_sortable_placeholder',
    update: function () {
        order = jQuery(this).sortable('toArray');
        neworder = Array();
        lngth = 'cms_contenu_id_'.length;
        for (var i in order) {
            id = order[i].substring(lngth);
            neworder.push(id);
        }
        jQuery(this).parent().find('.cms_content_order').val(neworder);
<?php 
$this->getBlock('cms/hook_update_content_order', $data);
?>
    }
});

</script>
