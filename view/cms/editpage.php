<div class="page_edit">
<?php
if (isset($data['page']) && $data['page']) {
    $page = $data['page'];
    if (isset($data['zones'])) {
        $zones = $data['zones'];
    }
} elseif (isset($data['erreurs'])) {
    print_r($data['erreurs']);
}
if (isset($data['page']) && $data['page']) {
?>
    <form
        name="edit_page"
        method="post"
        enctype="multipart/form-data"
        action="<?php echo __WWW__ . '/cms/editpage?id=' . $page['id']; ?>">
<?php
}
$this->getBlock('cms/editpage_baseparams', $data);
?>
        <input class="clementine_cms_editpage_submit btn btn-primary btn-block" type="submit" value="Enregistrer" />
<?php
if (isset($data['page']) && $data['page']) {
    if (isset($zones) && count($zones)) {
        $this->getBlock('cms/editpage_contenus', $data);
    }
?>
    </form>
<?php
}
?>
</div>
