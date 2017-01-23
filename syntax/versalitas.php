<?php
/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Luis Machuca Bezzaza <luis.machuca@gulix.cl>
 */
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_bbcode_versalitas extends DokuWiki_Syntax_Plugin {
 
    function getType() { return 'formatting'; }
    function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }   
    function getSort() { return 105; }
    function connectTo($mode) { $this->Lexer->addEntryPattern('\[c\](?=.*?\x5B/c\x5D)',$mode,'plugin_bbcode_versalitas'); }
    function postConnect() { $this->Lexer->addExitPattern('\[/c\]','plugin_bbcode_versalitas'); }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
          case DOKU_LEXER_ENTER :
            $match = substr($match, 3, -1);
            return array($state, $match);
 
          case DOKU_LEXER_UNMATCHED :
            return array($state, $match);
            
          case DOKU_LEXER_EXIT :
            return array($state, '');
            
        }
        return array();
    }
 
    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        if($mode == 'xhtml') {
            list($state, $match) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :      
                $renderer->doc .= '<span style="font-variant:small-caps;">';
                break;
                
              case DOKU_LEXER_UNMATCHED :
                $renderer->doc .= $renderer->_xmlEntities($match);
                break;
                
              case DOKU_LEXER_EXIT :
                $renderer->doc .= '</span>';
                break;
                
            }
            return true;
        }
        return false;
    }
}
// vim:ts=4:sw=4:et:enc=utf-8:     
