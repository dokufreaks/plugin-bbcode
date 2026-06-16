<?php

use dokuwiki\Extension\SyntaxPlugin;
use dokuwiki\Parsing\Handler;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
class syntax_plugin_bbcode_code extends SyntaxPlugin
{
    public function getType()
    {
        return 'protected';
    }
    public function getPType()
    {
        return 'block';
    }
    public function getSort()
    {
        return 105;
    }
    public function connectTo($mode)
    {
        $this->Lexer->addEntryPattern('\[code\](?=.*?\x5B/code\x5D)', $mode, 'preformatted');
    }
    public function postConnect()
    {
        $this->Lexer->addExitPattern('\[/code\]', 'preformatted');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Handler $handler)
    {
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
