<?php
if (isset($data['zones'])) {
    $zones = $data['zones'];
}
$this->getBlock('cms/editpage_baseparams', $data, $request);
?>
        <input class="clementine_cms_editpage_submit btn btn-primary btn-block" type="submit" value="Enregistrer" />
<?php
if (isset($data['page']) && $data['page']) {
    if (isset($zones) && count($zones)) {
        $this->getBlock('cms/editpage_contenus', $data, $request);
    }
}
