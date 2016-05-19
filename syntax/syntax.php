<?php
/**
 * DokuWiki Plugin codebender (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Christian Moll <christian@chrmoll.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class syntax_plugin_codebender_syntax extends DokuWiki_Syntax_Plugin {
    /**
     * @return string Syntax mode type
     */
    public function getType() {
        return 'substition';
    }
    /**
     * @return string Paragraph type
     */
    public function getPType() {
        return 'block';
    }
    function getAllowedTypes() {
	return array('container','substition','protected','disabled','formatting','paragraphs'); 
    }
    /**
     * @return int Sort order - Low numbers go before high numbers
     */
    public function getSort() {
        return 200;
    }

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode Parser mode
     */
    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('<codebender>(?=.*?</codebender.*?>)',$mode,'plugin_codebender_syntax');
    }

    public function postConnect() {
        $this->Lexer->addExitPattern('</codebender>','plugin_codebender_syntax');
    }

    /**
     * Handle matches of the codebender syntax
     *
     * @param string $match The match of the syntax
     * @param int    $state The state of the handler
     * @param int    $pos The position in the document
     * @param Doku_Handler    $handler The handler
     * @return array Data for the renderer
     */
    public function handle($match, $state, $pos, Doku_Handler &$handler){
        switch($state) {
           case DOKU_LEXER_ENTER: return array($state, '');
           case DOKU_LEXER_UNMATCHED: return array($state, $match);
           case DOKU_LEXER_EXIT: return array($state, '');
        }
    }

    /**
     * Render xhtml output or metadata
     *
     * @param string         $mode      Renderer mode (supported modes: xhtml)
     * @param Doku_Renderer  $renderer  The renderer
     * @param array          $data      The data from the handler() function
     * @return bool If rendering was successful.
     */
    public function render($mode, Doku_Renderer &$renderer, $data) {
        if($mode == 'xhtml') {
	  list($state, $match) = $data;

	  switch($state) {
	    case DOKU_LEXER_ENTER: 
	      $renderer->doc .= "<div class='codebender'>"; 
	      break;
	    case DOKU_LEXER_UNMATCHED: 
	      $renderer->doc .=  '<iframe style="height: 510px; width: 100%; margin: 10px 0 10px;" allowTransparency="true" src="https://codebender.cc/embed/sketch:'.$match.'" frameborder="0"></iframe>'; 
	      break;
	    case DOKU_LEXER_EXIT: 
	      $renderer->doc .= "</div>"; 
	      break;
	  }
	  
	  return true;
	  }
        return false;
    }
}

// vim:ts=4:sw=4:et:
