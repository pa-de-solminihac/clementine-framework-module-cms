<?php
/**
 * cmsCmsController : gestion de contenus
 *
 * @package 
 * @version $id$
 * @copyright 
 * @author Pierre-Alexis <pa@quai13.com> 
 * @license 
 */
class cmsCmsController extends cmsCmsController_Parent
{

    /**
     * viewpageAction : affiche une page (recupere les infos sur la page - template, zone, contenus... - et charge le template approprie)
     * 
     * @access public
     * @return void
     */
    function viewpageAction($params = null) 
    {
        // exemple : charge les CSS et JS necessaires dans le block head
        // $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));

        $ns = $this->getModel('fonctions');
        if (isset($params['id_page'])) {
            $id_page = $params['id_page'];
        } else {
            $id_page = $ns->ifGet("int", "id"); 
        }

        $cms = $this->getModel('cms');
        $this->data['page'] = $cms->getPage($id_page);
        if (isset($params['template'])) {
            $this->data['template'] = $params['template'];
        } else {
            $template = $cms->getPageTemplate($id_page);
            $this->data['template'] = $template['chemin'];
        }
        $params = $cms->getPageParams($id_page);
        $this->data['params'] = $params;

        // gestion des erreurs 404
        if (!$this->data['template']) {
            if (__DEBUGABLE__ && Clementine::$config['clementine_debug']['display_errors']) {
                trigger_error("Erreur 404 : pas de template pour cette page (si elle existe)", E_USER_WARNING);
            }
            return false;
        }

        $this->data['zones']    = $cms->getPageZones($id_page);
        foreach ($this->data['zones'] as $nom_zone => $id_zone) {
            $this->data['zones'][$nom_zone] = array('params'   => $cms->getPageZonesParams($id_zone, $id_page),
                                                    'id_zone'  => $id_zone,
                                                    'status'   => 'active',
                                                    'infos'    => $cms->getPageZoneContentsInfos($id_zone, $id_page),
                                                    'contenus' => $cms->getPageZoneContents($id_zone, $id_page));
        }
        // appelle le template adequat s'il existe, renvoie une 404 sinon, qui précisera quel bloc manque si le debug est active
        if ($this->getBlock($this->data['template'], $this->data)) {
            return array('dont_getblock' => true);
        }
    }

    /**
     * indexAction : affiche la liste des pages
     * 
     * @access public
     * @return void
     */
    function indexAction()
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            if (Clementine::$config['module_jstools']['use_google_cdn']) {
                $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
            } else {
                $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
            }
            $this->getModel('cssjs')->register_css('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/colorbox.css', 'media' => 'screen'));
            $this->getModel('cssjs')->register_js('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/jquery.colorbox-min.js'));
            $this->getModel('cssjs')->register_css('clementine_cms_css', array('src' => __WWW_ROOT_CMS__ . '/skin/css/cms.css'));
            $this->data['pages'] = $this->getModel('cms')->getPages('nom_page ASC');
            $cssjs = $this->getModel('cssjs');
            // jQuery
            if (Clementine::$config['module_jstools']['use_google_cdn']) {
                $cssjs->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
            } else {
                $cssjs->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
            }
            // dataTables : sortable tables
            $cssjs->register_css('jquery.dataTables',  array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/js/jquery.dataTables/dataTables.css'));
            $cssjs->register_js('jquery.dataTables', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/js/jquery.dataTables/jquery.dataTables.min.js'));
            $cssjs->register_foot('clementine_jstools-datatables', $this->getBlockHtml('jstools/js_datatables', $this->data));
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * editpageAction : edition de la page (liste les zones et contenus, et edite les parametres)
     * 
     * @access public
     * @return void
     */
    function editpageAction()
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            if (Clementine::$config['module_jstools']['use_google_cdn']) {
                $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
                $this->getModel('cssjs')->register_js('jquery.ui', array('src' => 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js'));
            } else {
                $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
                $this->getModel('cssjs')->register_js('jquery.ui', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery-ui/js/jquery-ui-1.8.16.custom.min.js'));
            }
            $this->getModel('cssjs')->register_css('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/colorbox.css', 'media' => 'screen'));
            $this->getModel('cssjs')->register_js('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/jquery.colorbox-min.js'));
            $this->getModel('cssjs')->register_css('clementine_cms_css', array('src' => __WWW_ROOT_CMS__ . '/skin/css/cms.css'));
            $script = $this->getBlockHtml('cms/editpage_js', $this->data);
            $this->getModel('cssjs')->register_foot('clementine_cms_editpage_js', $script);
            $ns = $this->getModel('fonctions');
            $cms = $this->getModel('cms');
            if ($_POST) {
                // recupere les parametres 
                $id_page        = $ns->ifPost("int", "id_page"); 
                $nom_page       = stripslashes($ns->ifPost("html", "nom_page")); 
                $slug_page      = $ns->urlize($ns->ifPost("string", "slug_page")); 
                $template_page  = $ns->ifPost("int", "template_page"); 
                // verification des donnees requises
                $erreurs = array(); 
                if (!strlen($id_page)) {
                    $erreurs['id_page'][] = 'Le champ id est obligatoire'; 
                }
                if (!strlen($nom_page)) {
                    $erreurs['nom_page'][] = 'Le champ nom est obligatoire'; 
                }
                if (!strlen($template_page)) {
                    $erreurs['template_page'][] = 'Le champ template est obligatoire'; 
                }
                if (count($erreurs)) {
                    $this->data['erreurs'] = $erreurs; 
                } else {
                    // enregistre les modifs sur la page et redirection pour eviter les problemes de rafraichissement
                    $cms = $this->getModel('cms');
                    if ($new_id_page = $cms->modPage($id_page, $nom_page, $template_page, $slug_page)) {
                        // enregistre l'ordre des contenus
                        $nom_param = 'zone_content_order_';
                        $len = $ns->strlen($nom_param);
                        $zones_a_reordonner = array();
                        foreach ($_POST as $key => $val) {
                            if ($ns->substr($key, 0, $len) == $nom_param) {
                                $zones_a_reordonner[] = $ns->substr($key, $len);
                            }
                        }
                        foreach ($zones_a_reordonner as $id_zone) {
                            $ordre_serie = $ns->ifPost('string', $nom_param . $id_zone);
                            if ($ordre_serie) {
                                $ordre = explode(',', $ordre_serie);
                                $instance_zone = $cms->getInstanceZone($id_zone, $id_page);
                                $cms->sortInstanceZoneContents($instance_zone['id_instance_zone'], $ordre);
                            }
                        }
                        $ns->redirect(__WWW__ . '/cms/editpage_ok?id=' . $new_id_page);
                    } else {
                        $this->data['erreurs']['sauvegarde'] = 'Erreur lors de la sauvegarde';
                    }
                }
            } else {
                $id_page = $ns->ifGet("int", "id"); 
                // recupere les infos generales pour toute page
                $this->data['templates_dispo'] = $cms->getAllTemplates();
                $template = $cms->getPageTemplate($id_page);
                $this->data['template_page'] = $template['chemin'];
                // recupere les infos sur la page et ses zones
                $this->data['page']  = $cms->getPage($id_page);
                $zones = $cms->getPageZones($id_page, 1);
                // pour chaque zone, recupere les parametres et les contenus
                foreach ($zones as $nom_zone => $instances) {
                    foreach ($instances as $instance) {
                        if ($instance['id_template'] == $template['id_template']) {
                            $this->data['zones'][$nom_zone] = array('params'    => $cms->getPageZonesParams($instance['id_zone'], $id_page),
                                                                    'id_zone'   => $instance['id_zone'],
                                                                    'status'    => 'active',
                                                                    'infos'     => $cms->getPageZoneContentsInfos($instance['id_zone'], $id_page, 1),
                                                                    'contenus'  => $cms->getPageZoneContents($instance['id_zone'], $id_page, 1));
                        }
                    }
                    foreach ($instances as $instance) {
                        if ($instance['id_template'] != $template['id_template'] && !isset($this->data['zones'][$nom_zone])) {
                            $this->data['zones'][$nom_zone] = array('params'    => $cms->getPageZonesParams($instance['id_zone'], $id_page),
                                                                    'id_zone'   => $instance['id_zone'],
                                                                    'status'    => 'inactive',
                                                                    'infos'     => $cms->getPageZoneContentsInfos($instance['id_zone'], $id_page, 1),
                                                                    'contenus'  => $cms->getPageZoneContents($instance['id_zone'], $id_page, 1));
                        }
                    }
                }
            }
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * editpage_okAction : page de confirmation d'edition d'une page
     * 
     * @access public
     * @return void
     */
    function editpage_okAction() 
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            $ns = $this->getModel('fonctions');
            $this->data['id'] = $ns->ifGet("int", "id"); 
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * delpageAction : edition de la page (liste les zones et contenus, et edite les parametres)
     * 
     * @access public
     * @return void
     */
    function delpageAction()
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            $ns = $this->getModel('fonctions');
            $cms = $this->getModel('cms');
            if ($_GET) {
                // recupere les parametres 
                $id_page        = $ns->ifGet("int", "id_page"); 
                $cms = $this->getModel('cms');
                $cms->delPage($id_page);
                echo "toto";
                die();
            }
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * pageparamsAction : edite les parametres de la page
     * 
     * @access public
     * @return void
     */
    function pageparamsAction()
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            $ns = $this->getModel('fonctions');
            $cms = $this->getModel('cms');
            if ($_POST) {
                // recupere les parametres 
                $id_page = $ns->ifPost("int", "id_page"); 
                // verification des donnees requises
                $erreurs = array(); 
                if (!strlen($id_page)) {
                    $erreurs['id_page'][] = 'Le champ id est obligatoire'; 
                }
                if (count($erreurs)) {
                    $this->data['erreurs'] = $erreurs; 
                } else {
                    // enregistre les modifs de la page et redirection pour eviter les problemes de rafraichissement
                    $params = array();
                    // prise en compte des parametres existants
                    foreach ($_POST as $key => $val) {
                        $val = $ns->ifPost('html', $key); // on recupere $val de la maniere "standard"
                        if ($ns->substr($key, 0, $ns->strlen('param_name_')) == 'param_name_') {
                            $rang = (int) $ns->substr($key, $ns->strlen('param_name_'));
                            $param_name = $val;
                            $param_val = $ns->ifPost('html', 'param_val_' . $rang);
                            if ($param_name) {
                                $params[$param_name] = $param_val;
                            }
                        }
                    }
                    // prise en compte des nouveaux parametres
                    foreach ($_POST as $key => $val) {
                        $val = $ns->ifPost('html', $key); // on recupere $val de la maniere "standard"
                        if ($ns->substr($key, 0, $ns->strlen('new_param_name_')) == 'new_param_name_') {
                            $rang = (int) $ns->substr($key, $ns->strlen('new_param_name_'));
                            $param_name = $val;
                            $param_val = $ns->ifPost('html', 'new_param_val_' . $rang);
                            if ($param_name) {
                                $params[$param_name] = $param_val;
                            }
                        }
                    }
                    if ($this->getModel('cms')->modPageParams($id_page, $params)) {
                        $ns->redirect(__WWW__ . '/cms/pageparams_ok?id_page=' . $id_page);
                    } else {
                        $this->data['erreurs']['sauvegarde'] = 'Erreur lors de la sauvegarde';
                    }
                }
            }
            $id_page = $ns->ifGet("int", "id_page"); 
            $this->data['id_page'] = $id_page;
            $this->data['params'] = $cms->getPageParams($id_page);
            $param_keys = array_keys($this->data['params']);
            $param_vals = array_values($this->data['params']);
            foreach ($param_keys as $key => $val) {
                $param_keys[$key] = $ns->htmlentities($val);
            }
            foreach ($param_vals as $key => $val) {
                $param_vals[$key] = $ns->htmlentities($val);
            }
            $this->data['params_keys'] = '["' . implode('","', $param_keys) . '"]';
            $this->data['params_vals'] = '["' . implode('","', $param_vals) . '"]';
            // recupere le contenu du script a injecter dans le footer
            $script = $this->getBlockHtml('cms/jquery_textbox_replace', $this->data);
            // charge les js et css necessaires
            if (Clementine::$config['module_jstools']['use_google_cdn']) {
                $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
            } else {
                $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
            }
            $this->getModel('cssjs')->register_js('jquery.textbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/js/jquery.textbox/jquery.textbox.js'));
            $this->getModel('cssjs')->register_css('jquery.textbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/js/jquery.textbox/jquery.textbox.css'));
            $this->getModel('cssjs')->register_css('clementine_cms_css', array('src' => __WWW_ROOT_CMS__ . '/skin/css/cms.css'));
            $this->getModel('cssjs')->register_foot('jquery.textbox.replace', $script);
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * pageparams_okAction : page de confirmation d'edition des parametres d'une page
     * 
     * @access public
     * @return void
     */
    function pageparams_okAction() 
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            $ns = $this->getModel('fonctions');
            $this->data['id_page'] = $ns->ifGet("int", "id_page"); 
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * zoneparamsAction : edite les parametres de la zone
     * 
     * @access public
     * @return void
     */
    function zoneparamsAction()
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            $ns = $this->getModel('fonctions');
            $cms = $this->getModel('cms');
            if ($_POST) {
                // recupere les parametres 
                $id_zone = $ns->ifPost("int", "id_zone"); 
                $id_page = $ns->ifPost("int", "id_page"); 
                // verification des donnees requises
                $erreurs = array(); 
                if (!strlen($id_zone)) {
                    $erreurs['id_zone'][] = 'Le champ zone est obligatoire'; 
                }
                if (!strlen($id_page)) {
                    $erreurs['id_page'][] = 'Le champ id est obligatoire'; 
                }
                if (count($erreurs)) {
                    $this->data['erreurs'] = $erreurs; 
                } else {
                    // enregistre les modifs de la zone et redirection pour eviter les problemes de rafraichissement
                    $params = array();
                    // prise en compte des parametres existants
                    foreach ($_POST as $key => $val) {
                        $val = $ns->ifPost('html', $key); // on recupere $val de la maniere "standard"
                        if ($ns->substr($key, 0, $ns->strlen('param_name_')) == 'param_name_') {
                            $rang = (int) $ns->substr($key, $ns->strlen('param_name_'));
                            $param_name = $val;
                            $param_val = $ns->ifPost('html', 'param_val_' . $rang);
                            if ($param_name) {
                                $params[$param_name] = $param_val;
                            }
                        }
                    }
                    // prise en compte des nouveaux parametres
                    foreach ($_POST as $key => $val) {
                        $val = $ns->ifPost('html', $key); // on recupere $val de la maniere "standard"
                        if ($ns->substr($key, 0, $ns->strlen('new_param_name_')) == 'new_param_name_') {
                            $rang = (int) $ns->substr($key, $ns->strlen('new_param_name_'));
                            $param_name = $val;
                            $param_val = $ns->ifPost('html', 'new_param_val_' . $rang);
                            if ($param_name) {
                                $params[$param_name] = $param_val;
                            }
                        }
                    }
                    if ($this->getModel('cms')->modZone($id_zone, $id_page, $params)) {
                        $ns->redirect(__WWW__ . '/cms/zoneparams_ok?id_page=' . $id_page . '&id_zone=' . $id_zone);
                    } else {
                        $this->data['erreurs']['sauvegarde'] = 'Erreur lors de la sauvegarde';
                    }
                }
            }
            $id_zone = $ns->ifGet("int", "id_zone"); 
            $id_page = $ns->ifGet("int", "id_page"); 
            // pour chaque zone, recupere les parametres et les contenus
            $this->data['id_zone'] = $id_zone;
            $this->data['id_page'] = $id_page;
            $this->data['params'] = $cms->getPageZonesParams($id_zone, $id_page);
            $param_keys = array_keys($this->data['params']);
            $param_vals = array_values($this->data['params']);
            foreach ($param_keys as $key => $val) {
                $param_keys[$key] = $ns->htmlentities($val);
            }
            foreach ($param_vals as $key => $val) {
                $param_vals[$key] = $ns->htmlentities($val);
            }
            $this->data['params_keys'] = '["' . implode('","', $param_keys) . '"]';
            $this->data['params_vals'] = '["' . implode('","', $param_vals) . '"]';
            // recupere le contenu du script a injecter dans le footer
            $script = $this->getBlockHtml('cms/jquery_textbox_replace', $this->data);
            // charge les js et css necessaires
            if (Clementine::$config['module_jstools']['use_google_cdn']) {
                $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
            } else {
                $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
            }
            $this->getModel('cssjs')->register_js('jquery.textbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/js/jquery.textbox/jquery.textbox.js'));
            $this->getModel('cssjs')->register_css('jquery.textbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/js/jquery.textbox/jquery.textbox.css'));
            $this->getModel('cssjs')->register_css('clementine_cms_css', array('src' => __WWW_ROOT_CMS__ . '/skin/css/cms.css'));
            $this->getModel('cssjs')->register_foot('jquery.textbox.replace', $script);
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * zoneparams_okAction : page de confirmation d'edition des parametres d'une zone
     * 
     * @access public
     * @return void
     */
    function zoneparams_okAction() 
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            $ns = $this->getModel('fonctions');
            $this->data['id_page'] = $ns->ifGet("int", "id_page"); 
            $this->data['id_zone'] = $ns->ifGet("int", "id_zone"); 
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * contenuparamsAction : edite les parametres d'un contenu
     * 
     * @access public
     * @return void
     */
    function contenuparamsAction ()
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            $this->getModel('cssjs')->register_css('clementine_cms_css', array('src' => __WWW_ROOT_CMS__ . '/skin/css/cms.css'));
            $ns = $this->getModel('fonctions');
            $cms = $this->getModel('cms');
            if ($_POST) {
                // recupere les parametres 
                $id_contenu = $ns->ifPost("int", "id_contenu"); 
                $id_zone = $ns->ifPost("int", "id_zone"); 
                $id_page = $ns->ifPost("int", "id_page"); 
                // verification des donnees requises
                $erreurs = array(); 
                if (!strlen($id_contenu)) {
                    $erreurs['id_contenu'][] = 'Le champ contenu est obligatoire'; 
                }
                if (!strlen($id_zone)) {
                    $erreurs['id_zone'][] = 'Le champ zone est obligatoire'; 
                }
                if (!strlen($id_page)) {
                    $erreurs['id_page'][] = 'Le champ id est obligatoire'; 
                }
                if (count($erreurs)) {
                    $this->data['erreurs'] = $erreurs; 
                } else {
                    // enregistre les modifs des parametres du contenu et redirection pour eviter les problemes de rafraichissement
                    $params = array();
                    // prise en compte des parametres existants
                    foreach ($_POST as $key => $val) {
                        $val = $ns->ifPost('html', $key); // on recupere $val de la maniere "standard"
                        if ($ns->substr($key, 0, $ns->strlen('param_name_')) == 'param_name_') {
                            $rang = (int) $ns->substr($key, $ns->strlen('param_name_'));
                            $param_name = $val;
                            $param_val = $ns->ifPost('html', 'param_val_' . $rang);
                            if ($param_name) {
                                $params[$param_name] = $param_val;
                            }
                        }
                    }
                    // prise en compte des nouveaux parametres
                    foreach ($_POST as $key => $val) {
                        $val = $ns->ifPost('html', $key); // on recupere $val de la maniere "standard"
                        if ($ns->substr($key, 0, $ns->strlen('new_param_name_')) == 'new_param_name_') {
                            $rang = (int) $ns->substr($key, $ns->strlen('new_param_name_'));
                            $param_name = $val;
                            $param_val = $ns->ifPost('html', 'new_param_val_' . $rang);
                            if ($param_name) {
                                $params[$param_name] = $param_val;
                            }
                        }
                    }
                    $contenu = $cms->getContenu($id_contenu, $id_zone, $id_page);
                    if ($this->getModel('cms')->modContenuParams($id_contenu, $contenu['table_contenu'], $params)) {
                        $ns->redirect(__WWW__ . '/cms/contenuparams_ok?id_page=' . $id_page . '&id_zone=' . $id_zone . '&id_contenu=' . $id_contenu);
                    } else {
                        $this->data['erreurs']['sauvegarde'] = 'Erreur lors de la sauvegarde';
                    }
                }
            }
            $id_contenu = $ns->ifGet("int", "id_contenu"); 
            $id_zone = $ns->ifGet("int", "id_zone"); 
            $id_page = $ns->ifGet("int", "id_page"); 
            // pour chaque zone, recupere les parametres et les contenus
            $this->data['id_contenu'] = $id_contenu;
            $this->data['id_zone'] = $id_zone;
            $this->data['id_page'] = $id_page;
            $contenu = $cms->getContenu($id_contenu, $id_zone, $id_page);
            $this->data['params'] = $cms->getContenuParams($id_contenu, $contenu['table_contenu']);
            $param_keys = array_keys($this->data['params']);
            $param_vals = array_values($this->data['params']);
            foreach ($param_keys as $key => $val) {
                $param_keys[$key] = $ns->htmlentities($val);
            }
            foreach ($param_vals as $key => $val) {
                $param_vals[$key] = $ns->htmlentities($val);
            }
            $this->data['params_keys'] = '["' . implode('","', $param_keys) . '"]';
            $this->data['params_vals'] = '["' . implode('","', $param_vals) . '"]';
            // recupere le contenu du script a injecter dans le footer
            $script = $this->getBlockHtml('cms/jquery_textbox_replace', $this->data);
            // charge les js et css necessaires
            if (Clementine::$config['module_jstools']['use_google_cdn']) {
                $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
            } else {
                $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
            }
            $this->getModel('cssjs')->register_js('jquery.textbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/js/jquery.textbox/jquery.textbox.js'));
            $this->getModel('cssjs')->register_css('jquery.textbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/js/jquery.textbox/jquery.textbox.css'));
            $this->getModel('cssjs')->register_css('clementine_cms_css', array('src' => __WWW_ROOT_CMS__ . '/skin/css/cms.css'));
            $this->getModel('cssjs')->register_foot('jquery.textbox.replace', $script);
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * contenuparams_okAction : page de confirmation d'edition des parametres d'un contenu
     * 
     * @access public
     * @return void
     */
    function contenuparams_okAction ()
    {
        if ($this->getModel('users')->needPrivilege('manage_pages')) {
            $ns = $this->getModel('fonctions');
            $this->data['id_page']      = $ns->ifGet("int", "id_page"); 
            $this->data['id_zone']      = $ns->ifGet("int", "id_zone"); 
            $this->data['id_contenu']   = $ns->ifGet("int", "id_contenu"); 
        } else {
            $this->getModel('fonctions')->redirect(__WWW__);
        }
    }

    /**
     * getZoneParamValues : recupere les valeurs de $param_name pour la zone $zone_name sur la page courante dans un tableau
     * 
     * @param mixed $zone_name 
     * @param mixed $param_name 
     * @access public
     * @return void
     */
    function getZoneParamValues ($zone_name, $param_name)
    {
        $params = array();
        if (isset($this->data['zones'][$zone_name])) {
            foreach ($this->data['zones'][$zone_name]['params'] as $key => $val) {
                if ($key == $param_name) {
                    $params[] = $val;
                }
            }
        }
        return $params;
    }

}
?>
