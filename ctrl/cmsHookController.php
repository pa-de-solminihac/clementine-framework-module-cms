<?php
/**
 * cmsHookController 
 * 
 * @package 
 * @version $id$
 * @copyright 
 * @author Pierre-Alexis <pa@quai13.com> 
 * @license 
 */
class cmsHookController extends cmsHookController_Parent
{
    /**
     * before_first_getRequest : fonction appelee avant le premier appel a getRequest()
     * 
     * @access public
     * @return void
     */
    function before_first_getRequest()
    {
        parent::before_first_getRequest();

        $db = $this->getModel('db');
        // recupere la partie specifique de l'URL demandee
        if (!isset(Clementine::$register['request_uri']) || !Clementine::$register['request_uri']) {
            Clementine::$register['request_uri'] = substr($_SERVER['REQUEST_URI'], (strlen(__BASE_URL__) + 1));
        }
        // multilingue : separe l'url demandee et le prefixe langue
        $prefixe_langue = '';
        $cnt = count(explode(',', __LANG_DISPOS__));
        if ($cnt > 1) {
            $matches = array();
            preg_match('/^[a-z]+\//', Clementine::$register['request_uri'], $matches);
            if (isset($matches[0])) {
                $prefixe_langue = $matches[0];
            }
        }
        if (Clementine::$register['request_uri']) {
            $mod = $this->getModel('cms');
            $ns = $this->getModel('fonctions');
            // si on a appelle une URL non reecrite, on redirige en 301 vers l'URL reecrite (= SEO friendly)
            if (strpos(Clementine::$register['request_uri'], $prefixe_langue . 'cms/viewpage') === 0) {
                $id_page = $ns->ifGet('int', 'id');
                if ($id_page) {
                    // renvoie en 301 vers l'URL adequate si elle existe
                    $sql = 'SELECT slug FROM ' . $mod->table_cms_page . ' WHERE id_page = \'' . $id_page . '\' AND slug != \'\' LIMIT 1';
                    $stmt = $db->query($sql);
                    if ($result = $db->fetch_assoc($stmt)) {
                        $url_seo = $result['slug'];
                        // on transmet aussi les parametres $_GET sauf l'id de la page
                        $i = 0;
                        foreach ($_GET as $key => $val) {
                            if ($key != 'id') {
                                $url_seo .= ($i ? '?' : '&') . $key . '=' . $val;
                            }
                            ++$i;
                        }
                        $ns->redirect(__BASE_URL__ . '/' . $prefixe_langue . $url_seo, 301);
                    }
                } else {
                    // pas besoin d'aller plus loin : erreur 404
                    trigger_error("Erreur 404 : aucune page demandee", E_USER_WARNING);
                    Clementine::$register['request_uri'] = 'err404/index';
                }
            } else {
                // cherche cette URL dans les slugs 
                $qstrpos = strpos(Clementine::$register['request_uri'], '?');
                if ($qstrpos === false) {
                    $base_url = Clementine::$register['request_uri'];
                } else {
                    $base_url = substr(Clementine::$register['request_uri'], 0, $qstrpos);
                }
                // multilingue...
                if ($cnt > 1) {
                    $base_url = preg_replace('/^[a-z]+\//', '', $base_url);
                }
                $base_url = $db->escape_string($ns->urlize($base_url));
                $sql = 'SELECT id_page FROM ' . $mod->table_cms_page . ' WHERE slug = \'' . $base_url . '\' LIMIT 1';
                $stmt = $db->query($sql);
                // si l'URL demandee est trouvee
                if ($result = $db->fetch_assoc($stmt)) {
                    $id_page = $result['id_page'];
                    $url_reelle = $prefixe_langue . 'cms/viewpage?id=' . $id_page;
                    $query_string = substr($url_reelle, (strpos($url_reelle, '?') + 1));
                    $params = explode('&', $query_string);
                    // on transmet aussi les parametres $_GET sauf l'id de la page
                    foreach ($_GET as $key => $val) {
                        if ($key != 'id') {
                            $params[] = $key . '=' . $val;
                        }
                    }
                    $_GET = array();
                    foreach ($params as $param) {
                        list($key, $val) = explode('=', $param, 2);
                        $_GET[$key] = $val; /* cas particulier d'utilisation de $_GET */
                    }
                    if (__DEBUGABLE__ && Clementine::$config['clementine_debug']['hook']) {
                        Clementine::$clementine_debug['hook'][] = '<strong>$this->hook(\'before_first_getRequest\') : URL réelle </strong><br /><a href="' . __BASE_URL__ . '/' . $url_reelle . '">' . __BASE_URL__ . '/' . $url_reelle . '</a>';
                    }
                    Clementine::$register['request_uri'] = $url_reelle;
                }
            }
        }
    }

}
?>
