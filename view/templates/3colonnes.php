<?php
    if (Clementine::$config['module_jstools']['use_google_cdn']) {
        $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
    } else {
        $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
    }
    $this->getBlock('design/header', $data);
?>

<div id="col1" class="<?php echo implode(' ', $this->getZoneParamValues('edito', 'css')); ?>">
    <?php $this->getBlock('cms/zone', 'edito'); ?>
</div>

<div id="col2" class="<?php echo implode(' ', $this->getZoneParamValues('texte_principal', 'css')); ?>">
    <?php $this->getBlock('cms/zone', 'texte_principal'); ?>
</div>

<div id="col3" class="<?php echo implode(' ', $this->getZoneParamValues('colonne_droite', 'css')); ?>">
    <?php $this->getBlock('cms/zone', 'colonne_droite'); ?>
</div>

<?php
    $this->getBlock('design/footer', $data);
?>
