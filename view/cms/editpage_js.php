<script type="text/javascript" charset="<?php echo __HTML_ENCODING__; ?>">
function delete_contenu(id_contenu, type_contenu, id_page) {
    if( confirm('Etes-vous sûr de vouloir supprimer ce contenu ?') ) {
        document.location.href="<?php echo __WWW__; ?>/contenus/deletecontenu?id=" + id_contenu + "&type=" + type_contenu + "&id_page=" + id_page;
    }
    return false;
}

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
