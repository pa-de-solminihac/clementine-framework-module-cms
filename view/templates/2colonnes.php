<?php 
$this->getBlock('design/header', $data);
?>
<div class="zone_edito <?php echo implode(' ', $this->getZoneParamValues('edito', 'class')); ?>">
<?php
$this->getBlock('cms/zone', 'edito');
?>
</div>
<div class="zone_texte_principal <?php echo implode(' ', $this->getZoneParamValues('texte_principal', 'class')); ?>">
<?php
$this->getBlock('cms/zone', 'texte_principal');
?>
</div>
<?php
$this->getBlock('design/footer', $data);
?>
