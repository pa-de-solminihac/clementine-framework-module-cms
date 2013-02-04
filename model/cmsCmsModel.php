<?php
/**
 * cmsCmsModel : gestion de contenus
 *
 * @package 
 * @version $id$
 * @copyright 
 * @author Pierre-Alexis <pa@quai13.com> 
 * @license 
 */
class cmsCmsModel extends cmsCmsModel_Parent
{

    public $table_cms_page                      = 'clementine_cms_page';
    public $table_cms_template                  = 'clementine_cms_template';
    public $table_cms_template_has_zone         = 'clementine_cms_template_has_zone';
    public $table_cms_zone                      = 'clementine_cms_zone';
    public $table_cms_instance_zone             = 'clementine_cms_instance_zone';
    public $table_cms_instance_zone_has_contenu = 'clementine_cms_instance_zone_has_contenu';
    public $table_cms_parametres_contenu        = 'clementine_cms_parametres_contenu';
    public $table_cms_parametres_zone           = 'clementine_cms_parametres_zone';
    public $table_cms_parametres_page           = 'clementine_cms_parametres_page';
    public $table_cms_contenu                   = 'clementine_cms_contenu';
    public $table_traduction_contenu            = 'clementine_traduction_contenu';
    public $table_cms_page_categories           = 'clementine_cms_page_categories';
    public $table_categories                    = 'clementine_categories';

    /**
     * getPageTemplate : renvoie les infos du template de la page $id_page
     * 
     * @param mixed $id_page 
     * @access public
     * @return void
     */
    public function getPageTemplate ($id_page) 
    {
        $id_page = (int) $id_page; 
        $db = $this->getModel('db');
        $sql = "SELECT * FROM `$this->table_cms_page` INNER JOIN `$this->table_cms_template` ON template_id_template = id_template 
                 WHERE id_page = '$id_page' LIMIT 1";
        return $db->fetch_assoc($db->query($sql)); 
    }

    /**
     * getTemplate : renvoie les infos du template $id_template
     * 
     * @param mixed $id_template 
     * @access public
     * @return void
     */
    public function getTemplate ($id_template) 
    {
        $id_template = (int) $id_template;
        $db = $this->getModel('db');
        $sql = "SELECT * FROM `$this->table_cms_template` 
                 WHERE id_template = '" . $id_template . "' LIMIT 1";
        return $db->fetch_assoc($db->query($sql)); 
    }

    /**
     * getTemplateByName : renvoie les infos du template a partir de son nom
     * 
     * @param mixed $id_page 
     * @access public
     * @return void
     */
    public function getTemplateByName ($chemin) 
    {
        $db = $this->getModel('db');
        $sql = "SELECT * FROM `$this->table_cms_template` 
                 WHERE chemin = '" . $db->escape_string($chemin) . "' LIMIT 1";
        return $db->fetch_assoc($db->query($sql)); 
    }

    /**
     * getTemplateZones : renvoie les zones du template $id_template
     * 
     * @param mixed $id_template 
     * @access public
     * @return void
     */
    public function getTemplateZones ($id_template) 
    {
        $id_template = (int) $id_template;
        $db = $this->getModel('db');
        $sql = "SELECT template_id_template, id_zone, nom_zone FROM `$this->table_cms_template_has_zone` 
                   INNER JOIN `" . $this->table_cms_zone . "` ON `zone_id_zone` = `id_zone` 
                 WHERE template_id_template = '" . $id_template . "' ";
        $stmt = $db->query($sql); 
        $zones = Array();
        for (true; $res = $db->fetch_assoc($stmt); true) {
            $zones[$res['nom_zone']][] = array('id_zone' => $res['id_zone'], 'id_template' => $res['template_id_template']);
        }
        return $zones;
    }

    /**
     * getAllTemplates : renvoie un tableau contenant la liste des templates disponibles : id_template => chemin
     * 
     * @access public
     * @return void
     */
    public function getAllTemplates ()
    {
        $db = $this->getModel('db');
        $sql = "SELECT id_template, chemin FROM `$this->table_cms_template` ";
        $result = array();
        $stmt = $db->query($sql);
        for (true; $res = $db->fetch_assoc($stmt); true) {
            $result[$res['id_template']] = $res['chemin'];
        }
        return $result;
    }

    /**
     * getPages : renvoie la liste de toutes les pages
     * 
     * @param mixed $order_by 
     * @param mixed $id_category : restreindre les resultats aux pages associées à la catégorie demandée
     * @access public
     * @return void
     */
    public function getPages ($order_by = null, $id_category = null)
    {
        $id_category = (int) $id_category;
        $db = $this->getModel('db');
        $sql = "SELECT * FROM `$this->table_cms_page` ";
        if ($id_category) {
            $sql .= "INNER JOIN `" . $this->table_cms_page_categories . "` ON `id_page` = `page_id_page` 
                     INNER JOIN `" . $this->table_categories . "` cat ON `categories_id_categorie` = cat.`id` 
                     WHERE categories_id_categorie = '$id_category' 
                       AND cat.active = 1 ";
        }
        if ($order_by) {
            $sql .= 'ORDER BY ' . $db->escape_string($order_by);
        }
        $stmt = $db->query($sql);
        $pages = array();
        for (true; $res = $db->fetch_assoc($stmt); true) {
            $pages[$res['id_page']]['id'] = $res['id_page'];
            $pages[$res['id_page']]['nom'] = $res['nom_page'];
            $pages[$res['id_page']]['slug'] = $res['slug'];
            if ($id_category) {
                $pages[$res['id_page']]['rang_tri']           = $res['page_rang_tri'];
                $pages[$res['id_page']]['category_id']        = $res['id'];
                $pages[$res['id_page']]['category_id_parent'] = $res['id_parent'];
                $pages[$res['id_page']]['category_nom']       = $res['nom'];
                $pages[$res['id_page']]['category_rang_tri']  = $res['rang_tri'];
                $pages[$res['id_page']]['category_active']    = $res['active'];
            }
        }
        return $pages;
    }

    /**
     * getPage : renvoie les infos de la page $id_page
     * 
     * @access public
     * @return void
     */
    public function getPage ($id_page) 
    {
        $id_page = (int) $id_page; 
        $db = $this->getModel('db');
        $sql = "SELECT * FROM `$this->table_cms_page` WHERE id_page = '$id_page'";
        $stmt = $db->query($sql);
        $page = array();
        for (true; $res = $db->fetch_assoc($stmt); true) {
            $page['id'] = $res['id_page'];
            $page['nom'] = $res['nom_page'];
            $page['slug'] = $res['slug'];
        }
        return $page;
    }

    /**
     * getPageCategories : renvoie les id des categories auxquelles est attachée la page $id_page
     * 
     * @access public
     * @return void
     */
    public function getPageCategories ($id_page) 
    {
        $id_page = (int) $id_page; 
        $db = $this->getModel('db');
        $sql = "SELECT categories_id_categorie FROM `$this->table_cms_page_categories` WHERE page_id_page = '$id_page'";
        $stmt = $db->query($sql);
        $categories = array();
        for (true; $res = $db->fetch_assoc($stmt); true) {
            $categories[] = $res['categories_id_categorie'];
        }
        return $categories;
    }

    /**
     * getPageZones : renvoie un tableau contenant pour chaque noms des zones instanciees pour la page $id_page le couple id_zone, id_zone_instanciee correspondant
     * 
     * @param mixed $id_page 
     * @param mixed $event_inactive 
     * @param mixed $id_template : restreint les resultats aux instances de zones pour ce template
     * @access public
     * @return void
     */
    public function getPageZones ($id_page, $even_inactive = 0, $id_template = 0) 
    {
        $id_page = (int) $id_page; 
        $id_template = (int) $id_template; 
        $db = $this->getModel('db');
        if (!$even_inactive) {
            $sql = "SELECT id_zone, nom_zone
                      FROM `$this->table_cms_page` 
                INNER JOIN `$this->table_cms_instance_zone` ON id_page = page_id_page 
                INNER JOIN `$this->table_cms_zone` ON `$this->table_cms_instance_zone`.zone_id_zone = id_zone 
                INNER JOIN `$this->table_cms_template_has_zone` ON id_zone = `$this->table_cms_template_has_zone`.zone_id_zone
                     WHERE id_page = '$id_page' 
                       AND `$this->table_cms_template_has_zone`.template_id_template = `$this->table_cms_page`.template_id_template ";
            if ($id_template) {
                   $sql .= " AND `$this->table_cms_page`.template_id_template = '" . $id_template . "' ";
            }
            $stmt = $db->query($sql); 
            $zones = Array();
            for (true; $res = $db->fetch_assoc($stmt); true) {
                $zones[$res['nom_zone']] = $res['id_zone'];
            }
        } else {
            $sql = "SELECT id_zone, nom_zone, `$this->table_cms_template_has_zone`.template_id_template 
                      FROM `$this->table_cms_page` 
                INNER JOIN `$this->table_cms_instance_zone` ON id_page = page_id_page 
                INNER JOIN `$this->table_cms_zone` ON `$this->table_cms_instance_zone`.zone_id_zone = id_zone 
                 LEFT JOIN `$this->table_cms_template_has_zone` ON id_zone = `$this->table_cms_template_has_zone`.zone_id_zone
                     WHERE id_page = '$id_page' ";
            if ($id_template) {
                   $sql .= " AND `$this->table_cms_template_has_zone`.template_id_template = '" . $id_template . "' ";
            }
            $stmt = $db->query($sql); 
            $zones = Array();
            for (true; $res = $db->fetch_assoc($stmt); true) {
                $zones[$res['nom_zone']][] = array('id_zone' => $res['id_zone'], 'id_template' => $res['template_id_template']);
            }
        }
        return $zones;
    }

    /**
     * getPageParams : renvoie un tableau contenant les couples (parametre, valeur) de la page $id_page
     * 
     * @param mixed $id_page 
     * @access public
     * @return void
     */
    public function getPageParams ($id_page) 
    {
        $id_page = (int) $id_page; 
        $db = $this->getModel('db');
        $sql = "SELECT `" . $this->table_cms_parametres_page . "`.nom, `" . $this->table_cms_parametres_page . "`.valeur 
                  FROM `$this->table_cms_parametres_page` 
                 WHERE page_id_page = '$id_page'";
        $stmt = $db->query($sql); 
        $params = Array();
        for (true; $res = $db->fetch_assoc($stmt); true) {
            // stripslashes car les contenus sont echappes avant d'etre enregistres en BD
            $params[stripslashes($res['nom'])] = stripslashes($res['valeur']);
        }
        return $params;
    } 

    /**
     * getPageZonesParams : renvoie un tableau contenant les couples (parametre, valeur) de la zone $id_zone pour la page $id_page
     * 
     * @param mixed $id_zone 
     * @param mixed $id_page 
     * @access public
     * @return void
     */
    public function getPageZonesParams ($id_zone, $id_page) 
    {
        $id_zone = (int) $id_zone; 
        $id_page = (int) $id_page; 
        $db = $this->getModel('db');
        $sql = "SELECT `" . $this->table_cms_parametres_zone . "`.nom, `" . $this->table_cms_parametres_zone . "`.valeur 
                  FROM `$this->table_cms_zone` INNER JOIN `$this->table_cms_instance_zone` ON id_zone = zone_id_zone 
                                               INNER JOIN `$this->table_cms_parametres_zone` ON instance_zone_id_instance_zone = id_instance_zone 
                 WHERE page_id_page = '$id_page' 
                   AND id_zone = '$id_zone' ";
        $stmt = $db->query($sql); 
        $params = Array();
        for (true; $res = $db->fetch_assoc($stmt); true) {
            // stripslashes car les contenus sont echappes avant d'etre enregistres en BD
            $params[stripslashes($res['nom'])] = stripslashes($res['valeur']);
        }
        return $params;
    }

    /**
     * getPageZoneContentsInfos : renvoie un tableau contenant les infos permettant de recuperer les contenus de la zone $id_zone pour la page $id_page
     * 
     * @param mixed $id_zone 
     * @param mixed $id_page 
     * @param mixed $even_unpublished : renvoie aussi les contenus non publies si vrai
     * @access public
     * @return void
     */
    public function getPageZoneContentsInfos ($id_zone, $id_page, $even_unpublished = 0) 
    {
        $id_zone = (int) $id_zone; 
        $id_page = (int) $id_page; 
        $db = $this->getModel('db');
        $sql = "SELECT c.id_contenu, c.table_contenu, nom_contenu, valide, date_lancement, date_arret 
                 FROM `$this->table_cms_instance_zone` iz
           INNER JOIN `$this->table_cms_instance_zone_has_contenu` izc ON iz.id_instance_zone = izc.id_instance_zone 
           INNER JOIN `$this->table_cms_contenu` c ON izc.id_contenu = c.id_contenu AND izc.table_contenu = c.table_contenu
                 WHERE page_id_page = '$id_page' 
                   AND zone_id_zone = '$id_zone' ";
        if (!$even_unpublished) {
            $sql .= "AND c.`valide` = '1' 
                     AND (date_lancement IS NULL OR date_lancement <= '" . date('Y-m-d H:i:s') . "')
                     AND (date_arret IS NULL OR date_arret >= '" . date('Y-m-d H:i:s') . "') ";
        }
        $sql .= "ORDER BY poids ASC, id_contenu DESC ";
        $stmt = $db->query($sql); 
        $infos = Array();
        for (true; $res = $db->fetch_assoc($stmt); true) {
            // stripslashes car les contenus sont echappes avant d'etre enregistres en BD
            if ($res) {
                foreach ($res as $key => $val) {
                    $res[$key] = stripslashes($val);
                }
            }
            $infos[] = array('id_contenu' => $res['id_contenu'], 'table_contenu' => $res['table_contenu'], 'nom' => $res['nom_contenu'],
                             'valide' => $res['valide'], 'date_lancement' => $res['date_lancement'], 'date_arret' => $res['date_arret']);
        }
        return $infos;
    }

    /**
     * getPageZoneContents : renvoie le tableau ('type_contenu', 'contenu') des contenus de la zone $id_zone pour la page $id_page
     * 
     * @param mixed $id_zone 
     * @param mixed $id_page 
     * @param mixed $even_unpublished : renvoie aussi les contenus non publies si vrai
     * @access public
     * @return void
     */
    public function getPageZoneContents ($id_zone, $id_page, $even_unpublished = 0) 
    {
        $id_zone = (int) $id_zone; 
        $id_page = (int) $id_page; 
        $db = $this->getModel('db');
        $infos_contenus = $this->getPageZoneContentsInfos($id_zone, $id_page, $even_unpublished);
        $contenus = Array();
        $request = $this->getRequest();
        foreach ($infos_contenus as $info_contenu) {
            $sql = "SELECT * 
                      FROM `" . $info_contenu['table_contenu'] . "`
                     WHERE id = '" . (int) $info_contenu['id_contenu'] . "' 
                       AND lang = '" . $request['LANG'] . "' 
                     LIMIT 1 ";
            $stmt = $db->query($sql); 
            $contenu = $db->fetch_assoc($stmt);
            // stripslashes car les contenus sont echappes avant d'etre enregistres en BD
            if ($contenu) {
                foreach ($contenu as $key => $val) {
                    $contenu[$key] = stripslashes($val);
                }
            }
            $contenu = $this->unescape_content($contenu);
            $tab_contenus = array ('type_contenu' => $info_contenu['table_contenu'], 
                                   'contenu' => $contenu, 
                                   'id_contenu' => $info_contenu['id_contenu'], 
                                   'nom_contenu' => $info_contenu['nom'], 
                                   'params_contenu' => $this->getContenuParams($info_contenu['id_contenu'], 
                                   $info_contenu['table_contenu'])); 
            // recupere (seulement en BO pour l'instant) l'info : le contenu est il traduit dans chaque langue ?
            $traductions = array();
            $lang_dispo = array_keys($request['EQUIV']);
            if ($even_unpublished && (count($lang_dispo) > 1)) {
                foreach ($lang_dispo as $lng) {
                    $sql = "SELECT COUNT(*) cnt
                              FROM `" . $info_contenu['table_contenu'] . "`
                             WHERE id = '" . (int) $info_contenu['id_contenu'] . "' 
                               AND lang = '" . $lng . "' 
                             LIMIT 1 ";
                    $stmt = $db->query($sql); 
                    $trad = $db->fetch_assoc($stmt);
                    $traductions[$lng] = $trad['cnt'];
                }
                $tab_contenus['traductions'] = $traductions;
            }
            $contenus[] = $tab_contenus;
        }
        return $contenus;
    }

    public function modPage ($id_page, $nom_page, $template_page, $slug_page)
    {
        // modification 
        $request = $this->getRequest();
        $ns = $this->getModel('fonctions');
        $db = $this->getModel('db');
        // traitement des donnees
        $id_page        = (int) $id_page;
        $template_page  = $ns->ifPost("int", "template_page"); 
        $slug_page      = $ns->urlize($ns->ifPost("string", "slug_page")); 
        // verifie que le slug n'existe pas deja...
        $sql = 'SELECT slug 
                  FROM ' . $this->table_cms_page . ' 
                 WHERE slug = \'' . $db->escape_string($slug_page) . '\' 
                   AND id_page <> \'' . $id_page . '\' LIMIT 1';
        $stmt = $db->query($sql);
        if ($db->fetch_assoc($stmt)) {
            return false;
        }
        if ($id_page) {
            $sql  = "UPDATE " . $this->table_cms_page . " 
                        SET `nom_page` = '" . $db->escape_string($nom_page) . "', 
                            `slug` = '" . $db->escape_string($slug_page) . "',
                            `template_id_template` = '" . $db->escape_string($template_page) . "'
                      WHERE `id_page` =  '$id_page' LIMIT 1 "; 
            $stmt = $db->query($sql); 
        } else {
            $sql  = "INSERT INTO " . $this->table_cms_page . " (id_page, nom_page, slug, template_id_template) 
                          VALUES ('',
                                  '" . $db->escape_string($nom_page) . "',
                                  '" . $db->escape_string($slug_page) . "',
                                  '" . $db->escape_string($template_page) . "')";
            $stmt = $db->query($sql); 
            // recuperation insert id
            $sql  = 'SELECT LAST_INSERT_ID() FROM ' . $this->table_cms_page;
            $stmt = $db->query($sql);
            $last_insert_id_array = $db->fetch_array($stmt);
            $id_page = $last_insert_id_array[0];
        }
        // crée les instances des zones du template pour cette page si nécessaire
        // recupere le template $template_page
        $template = $this->getTemplate($template_page);
        if ($template) {
            // recupere les zones liees au template puis celles liees a la page
            $template_zones = $this->getTemplateZones($template['id_template']);
            $page_zones = $this->getPageZones($id_page, 1, $template['id_template']);
            // instancie chaque zone pour la page si necessaire
            foreach ($template_zones as $nom_zone => $template_zone) {
                if (!isset($page_zones[$nom_zone])) {
                    $sql = "INSERT INTO `" . $this->table_cms_instance_zone . "` (id_instance_zone, page_id_page, zone_id_zone) 
                                  VALUES ('', '" . $id_page . "', '" . $template_zone[0]['id_zone'] . "') ";
                    $db->query($sql);
                }
            }
        }
        return $id_page; 
    }

    public function delPage ($id_page)
    {
        // traitement des donnees
        $ns = $this->getModel('fonctions');
        $id_page        = (int) $id_page;
        if ($id_page) {
            // recupere le template de la page, son template, ses zones, ses contenus (avec les parametres pour chacun) 
            // et efface ces donnees si elles ne sont utilisees nulle part ailleurs
            //
            // supprime les instances de zones de cette page et leurs parametres
            // supprime les contenus de cette page et leurs parametres si pas utilises ailleurs
            // supprime cette page et ses parametres
            $zones = $this->getPageZones($id_page, 1);
            foreach ($zones as $zone) {
                $contenus = $this->getPageZoneContentsInfos($zone[0]['id_zone'], $id_page, 1);
                print_r($contenus);
            }
            die();
        }
        return $id_page; 
    }

    public function modZone ($id_zone, $id_page, $donnees)
    {
        // traitement des donnees
        $ns = $this->getModel('fonctions');
        $id_zone        = (int) $id_zone;
        $id_page        = (int) $id_page;
        // recupere l'id_instance_zone
        $id_instance_zone = 0;
        $db = $this->getModel('db');
        $sql  = "SELECT id_instance_zone FROM `" . $this->table_cms_instance_zone . "` WHERE `zone_id_zone` = '" . $id_zone . "' AND `page_id_page` = '" . $id_page . "' ";
        if ($stmt = $db->query($sql)) {
            $res = $db->fetch_assoc($stmt); 
            $id_instance_zone = $res['id_instance_zone'];
        } else {
            return false;
        }
        // met a jour les parametres : supprime et recree
        $sql  = "DELETE FROM `" . $this->table_cms_parametres_zone . "` WHERE `instance_zone_id_instance_zone` = '" . $id_instance_zone . "' ";
        if (!$db->query($sql)) {
            return false;
        }
        foreach ($donnees as $param_name => $param_val) {
            $sql  = "INSERT INTO `" . $this->table_cms_parametres_zone . "` (instance_zone_id_instance_zone, nom, valeur) 
                          VALUES ('" . $id_instance_zone . "', '" . $db->escape_string($param_name) . "', '" . $db->escape_string($param_val) . "') 
                              ON DUPLICATE KEY UPDATE valeur = '" . $db->escape_string($param_val) . "' ";
            if (!$db->query($sql)) {
                return false;
            }
        }
        return true;
    }

    public function getContenu ($id_contenu, $id_zone, $id_page)
    {
        $id_contenu = (int) $id_contenu; 
        $id_zone = (int) $id_zone; 
        $id_page = (int) $id_page; 
        $db = $this->getModel('db');
        $sql = "SELECT c.* 
                 FROM `" . $this->table_cms_instance_zone . "` iz
           INNER JOIN `$this->table_cms_instance_zone_has_contenu` izc ON iz.id_instance_zone = izc.id_instance_zone 
           INNER JOIN `$this->table_cms_contenu` c ON izc.id_contenu = c.id_contenu AND izc.table_contenu = c.table_contenu
                 WHERE c.id_contenu = '$id_contenu' 
                   AND page_id_page = '$id_page' 
                   AND zone_id_zone = '$id_zone' 
                 LIMIT 1 ";
        $stmt = $db->query($sql); 
        $res = $db->fetch_assoc($stmt); 
        $infos = array('id_contenu' => $res['id_contenu'], 'table_contenu' => $res['table_contenu'], 'nom' => $res['nom_contenu'],
                       'valide' => $res['valide'], 'date_lancement' => $res['date_lancement'], 'date_arret' => $res['date_arret']);
        return $infos;
    }

    public function getContenuParams ($id_contenu, $table_contenu)
    {
        $ns = $this->getModel('fonctions');
        $id_contenu = (int) $id_contenu; 
        $table_contenu = $ns->strip_tags($table_contenu); 
        $db = $this->getModel('db');
        $sql = "SELECT `nom`, `valeur` 
                  FROM `" . $this->table_cms_parametres_contenu . "` 
                 WHERE contenu_table_contenu = '$table_contenu' 
                   AND contenu_id_contenu = '$id_contenu' ";
        $stmt = $db->query($sql); 
        $params = Array();
        // stripslashes car les contenus sont echappes avant d'etre enregistres en BD
        for (true; $res = $db->fetch_assoc($stmt); true) {
            $params[stripslashes($res['nom'])] = stripslashes($res['valeur']);
        }
        return $params;
    }

    public function modContenuParams ($id_contenu, $table_contenu, $donnees)
    {
        // traitement des donnees
        $ns = $this->getModel('fonctions');
        $id_contenu = (int) $id_contenu;
        $table_contenu = $ns->strip_tags($table_contenu); 
        $db = $this->getModel('db');
        $sql  = "DELETE FROM `" . $this->table_cms_parametres_contenu . "` 
                  WHERE `contenu_id_contenu` = '" . $id_contenu . "' 
                    AND `contenu_table_contenu` = '" . $table_contenu . "' ";
        if (!$db->query($sql)) {
            return false;
        }
        foreach ($donnees as $param_name => $param_val) {
            $sql  = "INSERT INTO `" . $this->table_cms_parametres_contenu . "` (contenu_id_contenu, contenu_table_contenu, nom, valeur) 
                          VALUES ('" . $id_contenu . "', '" . $db->escape_string($table_contenu) . "', '" . $db->escape_string($param_name) . "', '" . $db->escape_string($param_val) . "') 
                              ON DUPLICATE KEY UPDATE valeur = '" . $db->escape_string($param_val) . "' ";
            if (!$db->query($sql)) {
                return false;
            }
        }
        return true;
    }

    public function modPageParams ($id_page, $donnees)
    {
        // traitement des donnees
        $ns = $this->getModel('fonctions');
        $id_page = (int) $id_page;
        // met a jour les parametres : supprime et recree
        $db = $this->getModel('db');
        if ($donnees) {
            $sql  = "DELETE FROM `" . $this->table_cms_parametres_page . "` WHERE `page_id_page` = '" . $id_page . "' ";
            if (!$db->query($sql)) {
                return false;
            }
            foreach ($donnees as $param_name => $param_val) {
                $sql  = "INSERT INTO `" . $this->table_cms_parametres_page . "` (page_id_page, nom, valeur) 
                              VALUES ('" . $id_page . "', '" . $db->escape_string($param_name) . "', '" . $db->escape_string($param_val) . "') 
                                  ON DUPLICATE KEY UPDATE valeur = '" . $db->escape_string($param_val) . "' ";
                if (!$db->query($sql)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function getInstanceZone ($id_zone, $id_page)
    {
        $db = $this->getModel('db');
        $sql = "SELECT * FROM `" . $this->table_cms_instance_zone . "` WHERE `page_id_page` = '" . $id_page . "' AND `zone_id_zone` = '" . $id_zone . "' LIMIT 1 ";
        $stmt = $db->query($sql);
        return $db->fetch_assoc($stmt);
    }

    public function sortInstanceZoneContents ($id_zone, $ordre)
    {
        $id_zone = (int) $id_zone;
        $poids = 10;
        $db = $this->getModel('db');
        foreach ($ordre as $id_contenu) {
            $id_contenu = (int) $id_contenu;
            $sql = "UPDATE `" . $this->table_cms_instance_zone_has_contenu . "` SET `poids` = '" . $poids . "' WHERE `id_instance_zone` = '" . $id_zone . "' AND `id_contenu` = '" . $id_contenu . "' ";
            $stmt = $db->query($sql);
            $poids += 10;
        }
    }

    /**
     * escape_content : rend les contenus portables en remplacant l'URL de base et en remplacant les liens vers des slugs par des liens vers les pages réelles
     * 
     * @param mixed $contenu_html 
     * @access public
     * @return void
     */
    public function escape_content ($contenu_html) 
    {
        $contenu_html = str_replace(__WWW_ROOT__, '__CLEMENTINE_CONTENUS_WWW_ROOT__', $contenu_html);
        $contenus_ori = (array) $contenu_html;
        $contenus_esc = array ();
        $db = $this->getModel('db');
        foreach ($contenus_ori as $key => $contenu_html) {
            $preg_results = array();
            // repasse les URL relatives en URL absolues
            $contenu_html = stripslashes($contenu_html);
            $reg = '/(href|src|action)=([\'"]?)(' . str_replace('/', '\/', __BASE_URL__) . '\/)([^\2]+)\2/i';
            $repl = '$1=$2__CLEMENTINE_CONTENUS_WWW_ROOT__/$4$2';
            $contenu_html = preg_replace($reg, $repl, $contenu_html);
            $contenu_html = addslashes($contenu_html);

            if (preg_match_all('/(href|src)=(\\\?[\'"])(__CLEMENTINE_CONTENUS_WWW_ROOT__\/)?([^\'"]+)\2/i', $contenu_html, $preg_results)) {
                $liens = $preg_results[4];
                foreach ($liens as $rang => $lien) {
                    $attr = $preg_results[1][$rang];
                    $delimiteur = $preg_results[2][$rang];
                    $remplacement = '';
                    // cherche l'url d'origine correspondant au slug si possible
                    if (strlen($lien)) {
                        $pattern_ancre = explode('#', $lien, 2);
                        if (isset($pattern_ancre[1]) && (strpos($pattern_ancre[0], '?') === false)) {
                            $slug = $pattern_ancre[0];
                            $params = '#' . $pattern_ancre[1];
                        } else {
                            $pattern = explode('?', $lien, 2);
                            $slug = isset($pattern[0]) ? $pattern[0] : $lien;
                            $params = isset($pattern[1]) ? '&amp;' . $pattern[1] : '';
                        }
                        $sql = 'SELECT id_page FROM ' . $this->table_cms_page . ' WHERE slug = \'' . $slug . '\' AND slug != \'\' LIMIT 1';
                        $stmt = $db->query($sql);
                        if ($result = $db->fetch_assoc($stmt)) {
                            $id_page = $result['id_page'];
                            $remplacement = 'cms/viewpage?id=' . $id_page;
                        }
                    }
                    // remplace le slug utilise dans le lien par son url d'origine si possible
                    if ($remplacement) {
                        $contenu_html = str_replace($attr . '=' . $delimiteur . $lien, $attr . '=' . $delimiteur . $remplacement . $params, $contenu_html);
                    }
                }
            }
            $contenus_esc[$key] = $contenu_html;
        }
        // renvoie un string si on a passé un string, ou un tableau si on a passé un tableau
        if (count($contenus_esc) > 1) {
            return $contenus_esc;
        } else {
            return (string) $contenus_esc[0];
        }
    }

    /**
     * unescape_content : traduit les contenus rendus portables par escape_content pour les rendre affichables
     * 
     * @param mixed $contenu_html 
     * @access public
     * @return void
     */
    public function unescape_content ($contenu_html) 
    {
        $contenu_html = str_replace('__CLEMENTINE_CONTENUS_WWW_ROOT__', __WWW_ROOT__, $contenu_html);
        $contenus_ori = (array) $contenu_html;
        $contenus_esc = array ();
        $from = array();
        $to = array();
        foreach ($contenus_ori as $key => $contenu_html) {
            $preg_results = array();
            if (preg_match_all('/(href|src)=(\\\?[\'"])cms\/viewpage\?id=([0-9]+)([^\'"]*)\2/i', $contenu_html, $preg_results)) {
                $liens = $preg_results[3];
                foreach ($liens as $rang => $lien) {
                    $id_page = $preg_results[3][$rang];
                    $qstring = $preg_results[4][$rang];
                    $pattern_ancre = explode('#', $qstring, 2);
                    if (isset($pattern_ancre[1]) && (strpos($pattern_ancre[0], '&amp;') === false)) {
                        $params = '#' . $pattern_ancre[1];
                    } else {
                        $pattern = explode('&amp;', $qstring, 2);
                        $params = isset($pattern[1]) ? '?' . $pattern[1] : '';
                    }
                    // recupere le slug de cette page
                    $page = $this->getPage($id_page);
                    $slug = $page['slug'];
                    if ($slug) {
                        // recree l'url
                        $url = $slug . $params;
                        $from[] = $preg_results[1][$rang] . '=' . $preg_results[2][$rang] . 'cms/viewpage?id=' . $preg_results[3][$rang] . $preg_results[4][$rang] . $preg_results[2][$rang];
                        $to[] = $preg_results[1][$rang] . '=' . $preg_results[2][$rang] . $url . $preg_results[2][$rang];
                    }
                }
            }
            $contenu_html = str_ireplace($from, $to, $contenu_html);
            $contenus_esc[$key] = $contenu_html;
        }
        // renvoie un string si on a passé un string, ou un tableau si on a passé un tableau
        if (count($contenus_esc) > 1) {
            return $contenus_esc;
        } else {
            return (string) $contenus_esc[0];
        }
    }

}
?>
