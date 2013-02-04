<?php 
    $data['active'] = $this->data['params']['body_id'];
    $this->getBlock('design/header', $data);
?>         
                    <div id="menu_bulle">
                        <?php $this->getBlock('design/menubulle', $data); ?>
                    </div>
                    <div id="contenu">
                        <div class="left">
<?php
    $this->getBlock('cms/zone', 'colonne_gauche');  
?>      
                        </div>
                        
                        <div class="right">
<?php
    $this->getBlock('cms/zone', 'colonne_droite');
?>
                        </div>
                        
                    </div>
                </div>
                <div class="spacer"></div>
            
<?php 
    $this->getBlock('design/footer', $data);
?>
