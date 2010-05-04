<?php
/**
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');

class action_plugin_fancysearch extends DokuWiki_Action_Plugin {

    /**
     * Register its handlers with the DokuWiki's event controller
     */
    function register(&$controller) {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, '_fixquery');
    }

    /**
     * Put namespace into search
     */
    function _fixquery(&$event, $param) {
        global $QUERY;
        global $ACT;

        if($ACT != 'search'){
            $QUERY = '';
            return;
        }

        if(trim($_REQUEST['namespace'])){
            $QUERY .= ' @'.trim($_REQUEST['namespace']);
        }
    }

    function tpl_searchbox($namespaces, $all_img) {
        $cur_val = isset($_REQUEST['namespace']) ? $_REQUEST['namespace'] : '';
        $namespaces = array_merge(array('' => array('txt' => $this->getLang('all'), 'img' => $all_img)), $namespaces);

        echo '<select class="fancysearch_namespace" name="namespace">';
        foreach ($namespaces as $id => $ns) {
            echo '<option value="' . $id . '"' . ($cur_val === $id ? ' selected="selected"' : '') . '>' . $ns['txt'] . '</option>';
        }
        ?>
        </select>

        <div id="fancysearch__ns_custom" class="closed" style="display: none;">
            <ul>
        <?php
        foreach ($namespaces as $id => $ns) {
            echo '<li class="' . $id . '_fancysearch"><img src="' . $ns['img'] . '" alt="' . $ns['txt'] . '" /></li>';
        }
        ?>
            </ul>
        </div>
        <?php
    }
}
