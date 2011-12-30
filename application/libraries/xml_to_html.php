<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * XML To (x)HTML Class
 *
 *
 */
class Xml_to_html {

  private $xmlImp         = NULL; // DOMImplementation
  private $xmlDoc         = NULL; // DomDocument
  private $xslt           = NULL; // DomDocument
  private $xsltProc       = NULL; // XSLTProcessor
  private $xpath          = NULL; // XPath
  private $xmlDTD         = NULL; // XML DTD not used


  /**
   * Constructor
   */
  public function Xml_to_html(array $config = array()) {

    //Loading the config
    $this->xsl_path = $config['xsl_path'];
    $this->tags     = $config['tags'];

    $this->xmlImp = new DOMImplementation();
    
    //Later...
    //$this->xmlDTD = $this->xmlImp->createDocumentType('lpcode');//, '', 'application/helpers/lpcode.dtd');
    
   /* $this->xmlDoc = $this->xmlImp->createDocument('', 'lpcode');//, $this->xmldtd);
    $this->xmlDoc->encoding = 'UTF-8';
    $this->xmlDoc->formatOutput = true;*/

    $this->xslt = new DomDocument();
    $this->xslt->load($this->xsl_path);

    $this->xsltProc = new XSLTProcessor();
    $this->xsltProc->registerPhpFunctions();
    $this->xsltProc->importStyleSheet($this->xslt);

  }

  
  /**
   * Parse
   *
   * This function takes a string and returns the html string parsed.
   *
   * @access public
   * @param string
   * @return string
   */
  public function parse($data) {
    //We clean the dom.
    $this->_cleanDom();

    //We convert the data in prettier format (that can be read by the xml parser)
    $data = $this->_prettify($data);

    $xml = $this->xmlDoc->createDocumentFragment();
    
    //We convert it into a DOM node...
    if(!$xml->appendXML($data)) {
      return 'Erreur de parsage <br/><pre>'.htmlspecialchars($data).'</pre>';
    }
    
    //... and append it to the document.
    $this->xmlDoc->documentElement->appendChild($xml);

    // Then we parse it.
    return $this->_apply_xsl();
  }
  
  /**
   * Apply XSL
   *
   * This function convert the xml document into another one.
   *
   * @access private
   * @return string
   */
  private function _apply_xsl() {
    //We apply the XSL stylesheet
    return $this->xsltProc->transformToXML($this->xmlDoc);;
  }
  
  /**
   * Clean DOM
   *
   * Erase all the elements from the DOM Document.
   *
   * @access private
   * return void
   */
  private function _cleanDom() {
  
   /* if($this->xmlDoc != NULL) {
      foreach( $this->xmlDoc->childNodes as $child)
        $this->xmlDoc->removeChild($child);
    }*/
    
    $this->xmlDoc = $this->xmlImp->createDocument('', 'code');//, $this->xmldtd);
    $this->xmlDoc->encoding = 'UTF-8';
    $this->xmlDoc->formatOutput = true;
  }

  /**
   * Prettify
   *
   * Prettify the data to be readable by the xml parser.
   *
   * @access private
   * @param string
   * @return string
   */
  private function _prettify($data) {

    //We replace all the "bad" entities.
    $data = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $data);

    //We replace all the good entities.
      //#1 : <tag>...</tag>
      $reg = "#&lt;(" . $this->tags . ")&gt;([\s\S]*?)&lt;/\\1&gt;#i";
      while(preg_match($reg, $data)) {
        $data = preg_replace($reg, '<$1>$2</$1>', $data);
      }
      
      //#2 : <tag attr="value"...>...</tag>
      $reg = "#&lt;(" . $this->tags . ")((?:\s+[\w]*?=\"[\s\S]*?\")+)&gt;([\s\S]*?)&lt;/\\1&gt;#i";
      while(preg_match($reg, $data)) {
        $data = preg_replace($reg, '<$1$2>$3</$1>', $data );
      }
      
      //#3 : <tag attr="value".../>
      $reg = "#&lt;(" . $this->tags . ")((?:\s+[\w]*?=\"[\s\S]*?\")+) */&gt;#i";
      while(preg_match($reg, $data)) {
        $data = preg_replace($reg, '<$1$2/>', $data );
    }

    return $data;
  }

}


/* fin du fichier */

