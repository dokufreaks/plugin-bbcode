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
    /** @inheritdoc */
    public function getType()
    {
        return 'substition';
    }
    /** @inheritdoc */
    public function getSort()
    {
        return 105;
    }
    /** @inheritdoc */
    public function connectTo($mode)
    {
        $this->Lexer->addSpecialPattern('\[url.+?\[/url\]', $mode, 'plugin_bbcode_link');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Handler $handler)
    {
        $match = substr($match, 5, -6);
        if (preg_match('/".+?"/', $match)) $match = substr($match, 1, -1); // addition #1: unquote
        [$url, $title] = sexplode(']', $match, 2, null);

        // external link (accepts all protocols)
        if (preg_match('#^([a-z0-9\-.+]+?)://#i', $url)) {
            $handler->addCall('externallink', [$url,$title], $pos);

        // local link
        } elseif (preg_match('!^#.+!', $url)) {
            $handler->addCall('locallink', [substr($url, 1),$title], $pos);

        // internal link
        } else {
            $handler->addCall('internallink', [$url,$title], $pos);
        }
        return true;
    }

    /** @inheritdoc */
    public function render($format, Doku_Renderer $renderer, $data)
    {
        return true;
    }
}
