<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
	
	  $this->load->library('xml_to_html');
	  
	  $string = '<i>Hello World !</i>';
	  $string2 = '<g>Hello World 2 !</g>';
	  $string_parsed = $this->xml_to_html->parse($string);
	  $string2_parsed = $this->xml_to_html->parse($string2);
	  
		$this->load->view('welcome_message', array(
		  'string'        => $string,
		  'string_parsed' => $string_parsed,
		  'string2'        => $string2,
		  'string2_parsed' => $string2_parsed
		));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
