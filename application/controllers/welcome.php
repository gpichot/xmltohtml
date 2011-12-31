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
	  
	  $string = 'Ce petit guide qui va suivre va vous permettre de vous familiarisez avec les éléments de mise en forme.

<titre>Les listes</titre>

<stitre>Liste non ordonnée</stitre>
<liste>
  <puce>premier élément ;</puce>
  <puce>second élément ;</puce>
  <puce>dernier élément.</puce>
</liste>

<stitre>Liste ordonnée</stitre>
<liste type="1">
  <puce>premier élément ;</puce>
  <puce>second élément ;</puce>
  <puce>dernier élément.</puce>
</liste>

<stitre>Liste de définition</stitre>
<liste type="definition">
  <puce nom="Premier">premier élément ;</puce>
  <puce nom="Second">second élément ;</puce>
  <puce nom="Dernier">dernier élément.</puce>
</liste>

<titre>Mise en forme du texte</titre>
<stitre>Les plus utilisés</stitre>
<i>Italic</i>
<g>Bold</g>
<s>Underline</s>
<b>Strike</b>

<stitre>Citations</stitre>
D\'abord les longues :
<citation>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</citation>
Et puis les petites qui peuvent se <cite>glisser</cite> partout.
';
	  $string_parsed = $this->xml_to_html->parse($string);
	  
		$this->load->view('welcome_message', array(
		 // 'string'        => $string,
		  'string_parsed' => $string_parsed
		));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
