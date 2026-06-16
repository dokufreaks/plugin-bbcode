<?php

use dokuwiki\Extension\SyntaxPlugin;
use dokuwiki\Parsing\Handler;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
class syntax_plugin_bbcode_monospace extends SyntaxPlugin
{
    /** @inheritdoc */
    public function getType()
    {
        return 'formatting';
    }
    /** @inheritdoc */
    public function getAllowedTypes()
    {
        return ['formatting', 'substition', 'disabled'];
    }
    /** @inheritdoc */
    public function getSort()
    {
        return 105;
    }
    /** @inheritdoc */
    public function connectTo($mode)
    {
        $this->Lexer->addEntryPattern('\[m\](?=.*?\x5B/m\x5D)', $mode, 'monospace');
    }
    /** @inheritdoc */
    public function postConnect()
    {
        $this->Lexer->addExitPattern('\[/m\]', 'monospace');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Handler $handler)
    {
        return [];
    }

    /** @inheritdoc */
    public function render($format, Doku_Renderer $renderer, $data)
    {
        return true;
    }
}
