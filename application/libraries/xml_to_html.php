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
  private $xmlDTD         = NULL; // XML DTD
  private $CI             = NULL; // Code Igniter


  /**
   * Constructor
   */
  public function Xml_to_html(array $config = array()) {

    //Loading the config
    $this->xsl_path  = $config['xsl_path'];
    $this->dtd_path  = $config['dtd_path'];
    $this->tags      = $config['tags'];
    $this->skip_tags = $config['skip_tags']; 
    
    $this->CI =& get_instance();
    $this->CI->load->library('Typography');

    $this->xmlImp = new DOMImplementation();
    
    $this->xmlDTD = $this->xmlImp->createDocumentType('code', '', $this->dtd_path);
    

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
    
    //DEBUG
    //echo $this->xmlDoc->saveXML();
    
    if($this->xmlDoc->validate())
      echo '<p>Le document XML est valide par rapport à la DTD</p>';
    else 
      echo '<p style="color:red">Le document XML n\'a pu être chargé !</p>';
    
    
    
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
    
    $this->xmlDoc = $this->xmlImp->createDocument('', 'code', $this->xmlDTD);
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

    //We delete all the newlines that we don't want.
		$data = preg_replace("#\n\n+#", "\n\n", $data);
		//DEBUG
		//echo $data;
		$data = $this->_format($data);
    //DEBUG
    //echo $data;
    return $data;
  }
  
  
  /**
   * Format
   *
   * Remove somes new lines and wrap text with <p>...</p> when necessary.
   *
   * @access private
   * @param string
   * @return string
   */
  private function _format($str) {
    $reg = "#\s*(<(" . $this->skip_tags . ")(?:(?:\s+[\w]*?=\"[\s\S]*?\")+)?>(?:[\s\S]*?)</\\2>)\s*#i";
    $data = preg_split($reg, $str, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
   
    //DEBUG
    //var_dump($data);
    
    $str = '';
    for($i = 0; $i < count($data); $i++) {
    
      //We don't wrap !!
      if(preg_match("#<(" . $this->skip_tags . ")#i", $data[$i])) {
        $str .= $data[$i];
        $i++;
      } else {
        //We wrap the data :
        $s = trim($data[$i]);
        $s = preg_replace("#\n\n#", "</p><p>", $s);
		    $s = preg_replace("#\n#", "<br />", $s);
        $str .= '<p>' . $s . '</p>';
      }
    
    }
   
    return $str;
  }

}


/* fin du fichier */

