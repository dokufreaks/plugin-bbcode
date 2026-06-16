<?php

use dokuwiki\Extension\SyntaxPlugin;
use dokuwiki\Parsing\Handler;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
class syntax_plugin_bbcode_email extends SyntaxPlugin
{
    public function getType()
    {
        return 'substition';
    }
    public function getSort()
    {
        return 105;
    }
    public function connectTo($mode)
    {
        $this->Lexer->addSpecialPattern('\[email.+?\[/email\]', $mode, 'plugin_bbcode_email');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Handler $handler)
    {
        $match = trim(substr($match, 7, -8));
        $match = preg_split('/\]/u', $match, 2);
        if (!isset($match[0])) {
            $url   = $match[1];
            $title = null;
        } else {
            $url   = $match[0];
            $title = $match[1];
        }
        $handler->_addCall('emaillink', [$url, $title], $pos);
        return true;
    }

    /**
     * Create output
     */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        return true;
    }
}
