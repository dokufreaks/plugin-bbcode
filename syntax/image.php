<?php

use dokuwiki\Extension\SyntaxPlugin;
use dokuwiki\Parsing\Handler;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
class syntax_plugin_bbcode_image extends SyntaxPlugin
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
        $this->Lexer->addSpecialPattern('\[img.+?\[/img\]', $mode, 'plugin_bbcode_image');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Handler $handler)
    {
        $match = trim(substr($match, 5, -6));
        [$url, $title] = sexplode(']', $match, 2, null);

        // Check whether this is a local or remote image
        if (preg_match('#^(https?|ftp)#i', $url)) {
            $call = 'externalmedia';
        } else {
            $call = 'internalmedia';
        }

        $handler->addCall($call, [$url,$title,null,null,null,'cache'], $pos);
        return true;
    }

    /** @inheritdoc */
    public function render($format, Doku_Renderer $renderer, $data)
    {
        return true;
    }
}
