<?php

use dokuwiki\Extension\SyntaxPlugin;
use dokuwiki\Parsing\Handler;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
class syntax_plugin_bbcode_italic extends SyntaxPlugin
{
    public function getType()
    {
        return 'formatting';
    }
    public function getAllowedTypes()
    {
        return ['formatting', 'substition', 'disabled'];
    }
    public function getSort()
    {
        return 105;
    }
    public function connectTo($mode)
    {
        $this->Lexer->addEntryPattern('\[i\](?=.*?\x5B/i\x5D)', $mode, 'emphasis');
    }
    public function postConnect()
    {
        $this->Lexer->addExitPattern('\[/i\]', 'emphasis');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Handler $handler)
    {
        return [];
    }

    /**
     * Create output
     */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        return true;
    }
}
