<?php

use dokuwiki\Extension\SyntaxPlugin;

/**
 * BBCode plugin: allows BBCode markup familiar from forum software
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */
class syntax_plugin_bbcode_olist extends SyntaxPlugin
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
        $this->Lexer->addEntryPattern('\[list=.*?\]\s*?\[\*\](?=.*?\x5B/list\x5D)', $mode, 'plugin_bbcode_olist');
    }
    /** @inheritdoc */
    public function postConnect()
    {
        $this->Lexer->addExitPattern('\[/list\]', 'plugin_bbcode_olist');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        switch ($state) {
            case DOKU_LEXER_ENTER:
              // get the list type
                $match = substr($match, 6, -4);
                $match = explode(']', $match, 2);
                return [$state, $match[0]];

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
                    switch ($match) {
                        case 'i':
                            $type = 'lower-roman';
                            break;
                        case 'I':
                            $type = 'upper-roman';
                            break;
                        case 'a':
                            $type = 'lower-alpha';
                            break;
                        case 'A':
                            $type = 'upper-alpha';
                            break;
                        default:
                            $type = 'decimal';
                    }
                    $renderer->doc .= '<ol style="list-style-type:' . $type . '"><li class="level1"><div class="li">';
                    break;

                case DOKU_LEXER_UNMATCHED:
                    $match = $renderer->_xmlEntities($match);
                    $renderer->doc .= str_replace('[*]', '</div></li><li class="level1"><div class="li">', $match);
                    break;

                case DOKU_LEXER_EXIT:
                    $renderer->doc .= '</div></li></ol>';
                    break;
            }
            return true;
        }
        return false;
    }
}
