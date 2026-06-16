<?php

use dokuwiki\Extension\SyntaxPlugin;
use dokuwiki\Parsing\Handler;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
class syntax_plugin_bbcode_link extends SyntaxPlugin
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
        $this->Lexer->addSpecialPattern('\[url.+?\[/url\]', $mode, 'plugin_bbcode_link');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Handler $handler)
    {
        $match = substr($match, 5, -6);
        if (preg_match('/".+?"/', $match)) $match = substr($match, 1, -1); // addition #1: unquote
        $match = preg_split('/\]/u', $match, 2);
        if (!isset($match[0])) {
            $url   = $match[1];
            $title = null;
        } else {
            $url   = $match[0];
            $title = $match[1];
        }

        // external link (accepts all protocols)
        if (preg_match('#^([a-z0-9\-\.+]+?)://#i', $url)) {
            $handler->_addCall('externallink', [$url,$title], $pos);

        // local link
        } elseif (preg_match('!^#.+!', $url)) {
            $handler->_addCall('locallink', [substr($url, 1),$title], $pos);

        // internal link
        } else {
            $handler->_addCall('internallink', [$url,$title], $pos);
        }
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
