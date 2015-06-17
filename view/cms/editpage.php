<div class="page_edit">
<?php
if (isset($data['page']) && $data['page']) {
    $page = $data['page'];
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
    $this->getBlock('cms/editpage_form', $data, $request);
?>
    </form>
<?php
}
?>
</div>
