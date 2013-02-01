<?php
$this->getParentBlock($data);
?>
                <!-- CMS -->
                <li>
                    <li><a href="<?php echo __WWW__; ?>/cms" <?php echo (isset($data['current']) && $data['current'] == "cms") ? 'class="current"' : ''; ?>>Gérer les contenus</a></li>
                </li>
