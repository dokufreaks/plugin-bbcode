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
    /** @inheritdoc */
    public function getType()
    {
        return 'protected';
    }
    /** @inheritdoc */
    public function getPType()
    {
        return 'block';
    }
    /** @inheritdoc */
    public function getSort()
    {
        return 105;
    }
    /** @inheritdoc */
    public function connectTo($mode)
    {
        $this->Lexer->addEntryPattern('\[code\](?=.*?\x5B/code\x5D)', $mode, 'preformatted');
    }
    /** @inheritdoc */
    public function postConnect()
    {
        $this->Lexer->addExitPattern('\[/code\]', 'preformatted');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Handler $handler)
    {
        return true;
    }

    /** @inheritdoc */
    public function render($format, Doku_Renderer $renderer, $data)
    {
        return true;
    }
}
