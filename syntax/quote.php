<?php

use dokuwiki\Extension\SyntaxPlugin;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
class syntax_plugin_bbcode_quote extends SyntaxPlugin
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
        $this->Lexer->addEntryPattern('\[quote.*?\](?=.*?\x5B/quote\x5D)', $mode, 'plugin_bbcode_quote');
    }
    /** @inheritdoc */
    public function postConnect()
    {
        $this->Lexer->addExitPattern('\[/quote\]', 'plugin_bbcode_quote');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                $match = explode('"', substr($match, 6, -1));
                return [$state, $match[1] ?? ''];

            case DOKU_LEXER_UNMATCHED:
                return [$state, $match];

            case DOKU_LEXER_EXIT:
                return [$state, ''];
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
                    if ($match !== '') {
                        $renderer->doc .= '<p><sub>' . $renderer->_xmlEntities($match) . ':</sub></p>';
                    }
                    $renderer->doc .= '<blockquote>';
                    break;

                case DOKU_LEXER_UNMATCHED:
                    $match = $renderer->_xmlEntities($match);
                    $renderer->doc .= str_replace("\n", '<br />', $match);
                    break;

                case DOKU_LEXER_EXIT:
                    $renderer->doc .= '</blockquote>';
                    break;
            }
            return true;
        }
        return false;
    }
}
