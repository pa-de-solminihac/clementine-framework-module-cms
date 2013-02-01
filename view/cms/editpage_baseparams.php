    <input name="id_page" type="hidden" value="<?php 
    if (isset($data['page']['id'])) {
        echo $data['page']['id']; 
    }
?>" />
    <table class="contenus_index_list" id="clementine_cms_page_params">
        <thead>
            <tr>
                <th class="col1"> Nom de la page </th>
                <th class="col2"> Alias URL </th>
                <th class="col3"> Modèle de page </th>
                <th class="col4"> </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col1">
                    <input name="nom_page" type="text" value="<?php 
    if (isset($data['page']['nom'])) { 
        echo $data['page']['nom']; 
    }
?>" />
                </td>
                <td class="col2">
                    <input name="slug_page" type="text" value="<?php 
    if (isset($data['page']['slug'])) { 
        echo $data['page']['slug']; 
    }
?>" />
                </td>
                <td class="col3">
                    <select name="template_page">
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
                </td>
                <td class="col4">
                    <div class="clementine_cms_editpage_submit">
                        <input type="submit" value="Enregistrer" />
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
