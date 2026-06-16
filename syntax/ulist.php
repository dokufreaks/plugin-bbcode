<?php

use dokuwiki\Extension\SyntaxPlugin;
use dokuwiki\Parsing\Handler;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
class syntax_plugin_bbcode_ulist extends SyntaxPlugin
{
    /** @inheritdoc */
    public function getType()
    {
        return 'container';
    }
    /** @inheritdoc */
    public function getPType()
    {
        return 'block';
    }
    /** @inheritdoc */
    public function getAllowedTypes()
    {
        return ['formatting', 'substition', 'disabled', 'protected'];
    }
    /** @inheritdoc */
    public function getSort()
    {
        return 105;
    }
    /** @inheritdoc */
    public function connectTo($mode)
    {
        $this->Lexer->addEntryPattern('\[list\]\s*?\[\*\](?=.*?\x5B/list\x5D)', $mode, 'plugin_bbcode_ulist');
    }
    /** @inheritdoc */
    public function postConnect()
    {
        $this->Lexer->addExitPattern('\[/list\]', 'plugin_bbcode_ulist');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Handler $handler)
    {
        switch ($state) {
            case DOKU_LEXER_ENTER:
            case DOKU_LEXER_EXIT:
                return [$state, ''];
            case DOKU_LEXER_UNMATCHED:
                return [$state, $match];
        }
        return [];
    }

    /** @inheritdoc */
    public function render($format, Doku_Renderer $renderer, $data)
    {
        if ($format == 'xhtml') {
            [$state, $match] = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER:
                    $renderer->doc .= '<ul><li class="level1"><div class="li">';
                    break;

                case DOKU_LEXER_UNMATCHED:
                    $match = $renderer->_xmlEntities($match);
                    $renderer->doc .= str_replace('[*]', '</div></li><li class="level1"><div class="li">', $match);
                    break;

                case DOKU_LEXER_EXIT:
                    $renderer->doc .= '</div></li></ul>';
                    break;
            }
            return true;
        }
        return false;
    }
}
