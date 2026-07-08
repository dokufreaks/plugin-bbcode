<?php

use dokuwiki\Extension\SyntaxPlugin;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 * @author     Luis Machuca Bezzaza <luis.machuca@gulix.cl>
 */
class syntax_plugin_bbcode_size extends SyntaxPlugin
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
        $this->Lexer->addEntryPattern('\[size=.*?\](?=.*?\x5B/size\x5D)', $mode, 'plugin_bbcode_size');
    }

    /** @inheritdoc */
    public function postConnect()
    {
        $this->Lexer->addExitPattern('\[/size\]', 'plugin_bbcode_size');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                $match = substr($match, 6, -1);
                if (preg_match('/".+?"/', $match)) $match = substr($match, 1, -1); // addition #1: unquote
                if (preg_match('/^[0-6]$/', $match)) {
                    $match = $this->relsz(intval($match));
                } elseif (preg_match('/^\d+$/', $match)) {
                    $match .= 'px';
                }
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
                    $renderer->doc .= '<span style="font-size:' . $renderer->_xmlEntities($match) . '">';
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

    /**
     * Returns a relative-size CSS keyword based on numbering.
     *
     * Provides a mapping to the series of size-related keywords in CSS 2.1
     * (http://www.w3.org/TR/REC-CSS1/#font-size)
     * Valid values are [0-6], with 3 for "medium" (as recommended by standard)
     *
     * @param int $value The size value as a number (0-6)
     * @return string|false The corresponding CSS keyword, or false if invalid
     * @author Luis Machuca Bezzaza <luis.machuca@gulix.cl>
     */
    protected function relsz($value)
    {
        switch ($value) {
            case 0:
                return 'xx-small';
            case 1:
                return 'x-small';
            case 2:
                return 'small';
            case 4:
                return 'large';
            case 5:
                return 'x-large';
            case 6:
                return 'xx-large';
            case 3:
                return 'medium';
            default:
                return false;
        }
    }
}
