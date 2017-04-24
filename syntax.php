<?php
/**
 * Search with Scopes
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     i-net software <tools@inetsoftware.de>
 * @author     Gerry Weissbach <gweissbach@inetsoftware.de>
 */

 // must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_namespacesearch extends DokuWiki_Syntax_Plugin {

    function getType() { return 'substition'; }
    function getPType() { return 'block'; }
    function getSort() { return 98; }

    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('{{namespacesearch>.*?}}', $mode, 'plugin_namespacesearch');
    }

    function handle($match, $state, $pos, Doku_Handler $handler) {
		global $ID;

		$match = substr($match, 14, -2); // strip markup
		
		$spaces = array();
		foreach( explode(' ', $match) as $ns ) {
			$ns = cleanID($ns);

			if (($ns == '*') || ($ns == ':')) { $ns = ''; }
		    elseif ($ns == '.') { $ns = getNS($ID); }
			
			$spaces[] = $ns;
		}

		return array($state, $spaces);

	}            

    function render($mode, Doku_Renderer $renderer, $data) {
        global $conf, $ID, $FANCYDATA;
		
		list($state, $spaces) = $data;

        if ($mode == 'xhtml' && $state = DOKU_LEXER_SPECIAL ) {
		
			$renderer->nocache();
			
			$helper = &plugin_load('helper', 'namespacesearch');

			$form = new Doku_Form('namespacesearchForm', wl($ID));
			$form->addElement(form_makeTextField('namespacesearchField', $FANCYDATA['namespacesearchField'], $this->getLang('keywords') . ':', 'namespacesearchField', null, array('autocomplete'=>'off')));

			/* List of Namespaces for the scope */
			$names = array();
			
			$allScopes = implode(' ', $spaces);
			$names[] = array("$allScopes", $this->getLang('all_scopes'), 'allscopes');

			foreach ( $spaces as $dir ) {
			
				$namespaces = array();
				list($dir, $class) = explode('|', $dir);
				search($namespaces,$conf['datadir'],'search_namespaces',null,$dir);
				
				foreach ($namespaces as $namespace) {
					list($id, $type, $lvl) = array_values($namespace);
					$names[] = array($id, ucwords(preg_replace('/[-_:]/', ' ', str_replace("$ID:", '', $id))), $class);
				}
			}

			$renderer->doc .= $helper->tpl_searchform($names, true);
			// $this->namespacesearchSuggestionInserter($renderer);
			
            return true;
        }
        return false;
    }

	function _capture_form_output($form) {

		if ( !$form ) { return ''; }
		ob_start();
		$form->printForm();
		$output = ob_get_contents();
		ob_end_clean();

		return trim($output);
	}
	
	function namespacesearchSuggestionInserter(&$renderer) {
		global $FANCYDATA, $lang, $conf, $ID;

		if ( !empty($FANCYDATA['query']) ) {
			
			//do fulltext search
			$ns = explode('@', $FANCYDATA['query']);
			
			$query = array();
			foreach ( explode(' ', array_shift($ns)) as $part) {
				if (empty($part)) { continue; }
				if ( substr($part, 0, 1) != '"' && substr($part, 0, 1) != '*' ) $part = '*' . $part;
				if ( substr($part, -1) != '"' && substr($part, -1) != '*' ) $part = $part. '*';
				$query[] = $part;
			}
			
			$query = implode(' ', $query);
			array_unshift($ns, $query);
			$query2 = array();
			foreach( $ns as $namespace ) {
				if (empty($namespace)) { continue; }
				$query2[] = trim(cleanID($namespace));
			}

			$query = implode('@', $query2);
			$data = ft_pageSearch($query,$regex);
			$renderer->doc .= '<h2>Search Result</h2>';
			if(count($data)){
				$num = 1;
				foreach($data as $id => $cnt){
					if ( $id == $ID ) { continue; }
					$renderer->doc .= '<div class="search_result">';
					$renderer->doc .= html_wikilink(':'.$id, $conf['useheading']?NULL:$id, $regex);
					$renderer->doc .= ': <span class="search_cnt">'.$cnt.' '.$lang['hits'].'</span><br />';

					if($num < 15){ // create snippets for the first number of matches only #FIXME add to conf ?
						$renderer->doc .= '<div class="search_snippet">'.ft_snippet($id,$regex).'</div>';
					}
					$renderer->doc .= '</div>';
					$num++;
				}
			}else{
				$renderer->doc .= '<div class="nothing">'.$lang['nothingfound'].'</div>';
			}

		}
	}
}
// vim:ts=4:sw=4:et:enc=utf-8: 
