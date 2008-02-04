<?php
/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_bbcode_size extends DokuWiki_Syntax_Plugin {
 
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Esther Brunner',
            'email'  => 'esther@kaffeehaus.ch',
            'date'   => '2005-08-05',
            'name'   => 'BBCode Size Plugin',
            'desc'   => 'allows BBCode markup: [size=*]text[/size]',
            'url'    => 'http://wiki.splitbrain.org/plugin:bbcode',
        );
    }
 
    function getType(){ return 'formatting'; }
    function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }   
    function getSort(){ return 105; }
    function connectTo($mode) { $this->Lexer->addEntryPattern('\[size=.*?\](?=.*?\x5B/size\x5D)',$mode,'plugin_bbcode_size'); }
    function postConnect() { $this->Lexer->addExitPattern('\[/size\]','plugin_bbcode_size'); }
 
 
    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        switch ($state) {
          case DOKU_LEXER_ENTER :
            $match = substr($match, 6, -1);
            if (preg_match('/^\d+$/',$match)) $match .= 'px';
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
    function render($mode, &$renderer, $data) {
        if($mode == 'xhtml'){
            list($state, $match) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :      
                $renderer->doc .= '<span style="font-size:'.$renderer->_xmlEntities($match).'">';
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
     
//Setup VIM: ex: et ts=4 enc=utf-8 :