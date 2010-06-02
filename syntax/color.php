<?php
/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 * @author     Christopher Smith <chris@jalakai.co.uk>
 * @author     Luis Machuca Bezzaza <luis.machuca@gulix.cl>
 */
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_bbcode_color extends DokuWiki_Syntax_Plugin {
 
    function getType() { return 'formatting'; }
    function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }   
    function getSort() { return 105; }
    function connectTo($mode) { $this->Lexer->addEntryPattern('\[color=.*?\](?=.*?\x5B/color\x5D)',$mode,'plugin_bbcode_color'); }
    function postConnect() { $this->Lexer->addExitPattern('\[/color\]','plugin_bbcode_color'); }
 
    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler) {
        switch ($state) {
          case DOKU_LEXER_ENTER :
            $match = substr($match, 7, -1);
            if (preg_match('/".+?"/',$match)) $match = substr($match, 1, -1); // addition #1: unquote
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
        if($mode == 'xhtml') {
            list($state, $match) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :      
                if ($match = $this->_isValid($match)  || 
                   ($match = $this->_getBrowserColor($match) ) 
                    $renderer->doc .= '<span style="color:'. $match. '">'; // addition #2: SVG browser colors
                else $renderer->doc .= '<span>';
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
    
    // validate color value $c
    // this is cut price validation - only to ensure the basic format is correct and there is nothing harmful
    // three basic formats  "colorname", "#fff[fff]", "rgb(255[%],255[%],255[%])"
    function _isValid($c) {
        $c = trim($c);
        
        $pattern = "/
            ([a-zA-z]+)|                                #colorname - not verified
            (\#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}))|        #colorvalue
            (rgb\(([0-9]{1,3}%?,){2}[0-9]{1,3}%?\))     #rgb triplet
            /x";
        
        if (preg_match($pattern, $c)) return $c;
        
        return "";
    }

    // request browser color $c
    // this validates against accepted "SVG colors" implemented by most browsers,
    // but will convert the values back to #rrggbb instead of using them directly,
    // thus ensuring visual consistency among clients
    function _getBrowserColor($c) {
        require_once(DOKU_PLUGIN.'bbcode/colors.php');
        return lcase(self::$browsercolors ($c));
    }

}
// vim:ts=4:sw=4:et:enc=utf-8:     