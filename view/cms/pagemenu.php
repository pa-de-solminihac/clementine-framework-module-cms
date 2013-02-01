<?php
// affichage du menu dans lequel cette page est située : on affiche le nom de la categorie, puis les pages de meme niveau
$cms = $this->getModel('cms');
$categories = $this->getModel('categories');
$id_page = $data['page']['id'];
$page_categories = $cms->getPageCategories($id_page);
$categorie_mere = 0;
if ($page_categories) {
    $categorie_mere = $categories->getCategorie($page_categories[0]);
}
if ($categorie_mere) {
    $pages = $cms->getPages('page_rang_tri', $categorie_mere['id']);
    if (count($pages) > 1) {
?>
            <li><?php echo $categorie_mere['nom']; ?></li>
<?php
        foreach ($pages as $p_id => $page_similaire) {
?>
            <li><a class="<?php
            // surbrillance page active
            if ($p_id == $id_page) {
                echo ' active';
            }
?>" href="<?php echo __WWW__ . '/' . $page_similaire['slug']; ?>"><?php echo $page_similaire['nom']; ?></a></li>
<?php 
        }
    } else {
?>
            <li><?php echo $categorie_mere['nom']; ?></li>
<?php 
    }
}
if (!$categorie_mere) {
?>
    <li><?php echo $data['page']['nom']; ?></li>
<?php
}
?>
