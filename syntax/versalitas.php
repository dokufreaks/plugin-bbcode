<?php

use dokuwiki\Extension\SyntaxPlugin;
use dokuwiki\Parsing\Handler;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Luis Machuca Bezzaza <luis.machuca@gulix.cl>
 */
class syntax_plugin_bbcode_versalitas extends SyntaxPlugin
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
        $this->Lexer->addEntryPattern('\[c\](?=.*?\x5B/c\x5D)', $mode, 'plugin_bbcode_versalitas');
    }
    /** @inheritdoc */
    public function postConnect()
    {
        $this->Lexer->addExitPattern('\[/c\]', 'plugin_bbcode_versalitas');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Handler $handler)
    {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                $match = substr($match, 3, -1);
                return [$state, $match];

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
                    $renderer->doc .= '<span style="font-variant:small-caps;">';
                    break;

                case DOKU_LEXER_UNMATCHED:
                    $renderer->doc .= $renderer->_xmlEntities($match);
                    break;

                case DOKU_LEXER_EXIT:
                    $renderer->doc .= '</span>';
                    break;
            }
            return true;
        }
        return false;
    }
}
