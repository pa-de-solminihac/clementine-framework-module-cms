ALTER TABLE `clementine_cms_contenu` ADD `lang` CHAR( 2 ) NOT NULL DEFAULT 'fr' AFTER `nom_contenu`;

ALTER TABLE `clementine_cms_contenu_html` ADD `lang` CHAR( 2 ) NOT NULL DEFAULT 'fr';
ALTER TABLE `clementine_cms_contenu_html` DROP PRIMARY KEY , ADD PRIMARY KEY ( `id` , `lang` );

ALTER TABLE `clementine_cms_contenu_html_nicedit` ADD `lang` CHAR( 2 ) NOT NULL DEFAULT 'fr';
ALTER TABLE `clementine_cms_contenu_html_nicedit` DROP PRIMARY KEY , ADD PRIMARY KEY ( `id` , `lang` );

ALTER TABLE `clementine_cms_contenu_html_ckeditor` ADD `lang` CHAR( 2 ) NOT NULL DEFAULT 'fr';
ALTER TABLE `clementine_cms_contenu_html_ckeditor` DROP PRIMARY KEY , ADD PRIMARY KEY ( `id` , `lang` );

ALTER TABLE `clementine_cms_contenu_html_introck` ADD `lang` CHAR( 2 ) NOT NULL DEFAULT 'fr';
ALTER TABLE `clementine_cms_contenu_html_introck` DROP PRIMARY KEY , ADD PRIMARY KEY ( `id` , `lang` );
