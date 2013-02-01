<div class="page_edit">
<?php
if (isset($data['page']) && $data['page']) {
    $page = $data['page']; 
    if (isset($data['zones'])) {
        $zones = $data['zones']; 
    }
?>
    <form name="edit_page" method="post" action="<?php echo __WWW__; ?>/cms/editpage?id=<?php echo $page['id']; ?>" enctype="multipart/form-data">
    <h2>Paramètres de la page</h2>
<?php
    $this->getBlock('cms/editpage_baseparams', $data);
?>
<?php
    if (isset($zones) && count($zones)) {
?>
    <h2>Contenus de la page</h2>
<?php
    $this->getBlock('cms/editpage_contenus', $data);
?>
        </div>
<?php 
    }
?>
    </form>
<?php
} elseif (isset($data['erreurs'])) {
    print_r($data['erreurs']);
} else {
?>
La page que vous avez demandé n'existe pas
<?php
}
?>
</div>
