<?php
if(!defined('DOKU_INC')) die();

class action_plugin_fancysearch extends DokuWiki_Action_Plugin {

    /**
     * Register its handlers with the DokuWiki's event controller
     */
    function register(Doku_Event_Handler &$controller) {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, '_fixquery');
    }

    /**
     * Put namespace into search
     */
    function _fixquery(Doku_Event &$event, $param) {
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
}
