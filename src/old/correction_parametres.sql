-- supprime valeur de la cle primaire
ALTER TABLE `clementine_cms_parametres_page` DROP PRIMARY KEY , ADD PRIMARY KEY ( `page_id_page` , `nom` ); 
ALTER TABLE `clementine_cms_parametres_zone` DROP PRIMARY KEY , ADD PRIMARY KEY ( `nom` , `instance_zone_id_instance_zone` ); 
ALTER TABLE `clementine_cms_parametres_contenu` DROP PRIMARY KEY , ADD PRIMARY KEY ( `nom` , `contenu_id_contenu` , `contenu_table_contenu` ); 

-- change le type de valeur
ALTER TABLE `clementine_cms_parametres_contenu` CHANGE `valeur` `valeur` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; 
ALTER TABLE `clementine_cms_parametres_page` CHANGE `valeur` `valeur` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; 
ALTER TABLE `clementine_cms_parametres_zone` CHANGE `valeur` `valeur` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; 
