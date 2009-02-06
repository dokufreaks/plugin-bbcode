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
class syntax_plugin_bbcode_deleted extends DokuWiki_Syntax_Plugin {
 
    /**
     * return some info
     */
    function getInfo() {
        return array(
            'author' => 'Gina Häußge, Michael Klier, Esther Brunner',
            'email'  => 'dokuwiki@chimeric.de',
            'date'   => @file_get_contents(DOKU_PLUGIN.'bbcode/VERSION'),
            'name'   => 'BBCode Deleted Plugin',
            'desc'   => 'allows BBCode markup: [d]text[/d]',
            'url'    => 'http://wiki.splitbrain.org/plugin:bbcode',
        );
    }
 
    function getType() { return 'formatting'; }
    function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }   
    function getSort() { return 105; }
    function connectTo($mode) { $this->Lexer->addEntryPattern('\[s\](?=.*?\x5B/s\x5D)',$mode,'deleted'); }
    function postConnect() { $this->Lexer->addExitPattern('\[/s\]','deleted'); }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler) {
        return array();
    }
 
    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {
        return true;
    }
}
// vim:ts=4:sw=4:et:enc=utf-8:     