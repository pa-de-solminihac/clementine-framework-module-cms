    <input name="id_page" type="hidden" value="<?php
    if (isset($data['page']['id'])) {
        echo $data['page']['id'];
    }
?>" />
    <div class="contenus_index_list well" id="clementine_cms_page_params">
        <div class="form-group">
            <label for="clementine_cms_nom_page">Nom de la page</label>
            <input class="form-control" id="clementine_cms_nom_page" name="nom_page" type="text" value="<?php
    if (isset($data['page']['nom'])) {
        echo $data['page']['nom'];
    } ?>" />
        </div>
        <div class="form-group">
            <label for="clementine_cms_slug_page">Alias URL</label>
            <input class="form-control" id="clementine_cms_slug_page" name="slug_page" type="text" value="<?php
    if (isset($data['page']['slug'])) {
        echo $data['page']['slug'];
    }
?>" />
        </div>
        <div class="form-group">
            <label for="clementine_cms_template_page">Modèle de page</label>
            <select class="form-control" id="clementine_cms_template_page" name="template_page">
<?php
if (isset($data['templates_dispo'])) {
    foreach ($data['templates_dispo'] as $tmpl_id => $tmpl_path) {
?>
    <option value="<?php echo $tmpl_id; ?>" <?php
    if ($data['template_page'] == $tmpl_path) {
        echo ' selected="selected"';
    }
    ?>><?php echo $tmpl_path; ?></option>
<?php
    }
}
?>
            </select>
        </div>
    </div>
