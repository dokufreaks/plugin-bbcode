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
        $this->Lexer->addSpecialPattern('\[img.+?\[/img\]', $mode, 'plugin_bbcode_image');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Handler $handler)
    {
        $match = trim(substr($match, 5, -6));
        $match = preg_split('/\]/u', $match, 2);
        if (!isset($match[0])) {
            $url   = $match[1];
            $title = null;
        } else {
            $url   = $match[0];
            $title = $match[1];
        }

        // Check whether this is a local or remote image
        if (preg_match('#^(https?|ftp)#i', $url)) {
            $call = 'externalmedia';
        } else {
            $call = 'internalmedia';
        }

        $handler->_addCall($call, [$url,$title,null,null,null,'cache'], $pos);
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
