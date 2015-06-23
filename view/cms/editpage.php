<div class="page_edit">
<?php
if (isset($data['erreurs'])) {
    print_r($data['erreurs']);
}
$form_url = __WWW__ . '/cms/editpage';
if (isset($data['page']) && $data['page']) {
    $form_url.= '?id=' . $data['page']['id'];
}
?>
    <form
        name="edit_page"
        method="post"
        enctype="multipart/form-data"
        action="<?php echo $form_url; ?>">
<?php
    $this->getBlock('cms/editpage_form', $data, $request);
?>
    </form>
</div>
