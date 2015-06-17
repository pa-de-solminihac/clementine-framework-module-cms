<script type="text/javascript" charset="<?php echo __HTML_ENCODING__; ?>">
function delete_page(id_page) {
    if (confirm('Etes-vous sûr de vouloir supprimer cette page ?')) {
        document.location.href="<?php echo __WWW__; ?>/cms/delpage?id=" + id_page;
    }
    return false;
}
function delete_contenu(id_contenu, type_contenu, id_page) {
    if( confirm('Etes-vous sûr de vouloir supprimer ce contenu ?') ) {
        document.location.href="<?php echo __WWW__; ?>/contenus/deletecontenu?id=" + id_contenu + "&type=" + type_contenu + "&id_page=" + id_page;
    }
    return false;
}
</script>
