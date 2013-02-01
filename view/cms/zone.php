<?php
if (isset($this->data['zones'][$data])) {
    $zone = $this->data['zones'][$data];
    // affichage de chaque contenu avec le block adequat
    if (isset($zone['contenus'])) {
        foreach ($zone['contenus'] as $contenu) {
            $contenu['page'] = $this->data['page'];
            $contenu['zone'] = $data;
            $this->getBlock('contenus/' . $contenu['type_contenu'], $contenu);
        }
    }
}
?>
