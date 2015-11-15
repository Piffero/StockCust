<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

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
	    
	    if($this->DB_User->is_logged_in())
	    {
	        redirect('home');
	    }
	    else
	    {
	        if($this->input->post()){
	            $username = $this->input->post('username');
	            $password = $this->input->post('password');
	            if ($this->login_check($username, $password)) {
	                redirect('home');
	            } else {
	                $data['alert_massager'] = '<div class="alert alert-danger">
							<button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
							<i class="fa fa-times sign"></i><strong>Opss!</strong>
							Usuário ou Senha não conferem.
							</div>';
	            
	                $this->load->view('login', $data);
	            }
	        }
	        else
	        {
	           $this->load->view('login');
	        }
	    }
	    
	}
	
	
	public function login_check($username, $password)
	{
	    if (! $this->DB_User->login($username, $password)) {
	        return false;
	    }	    
	    return true;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */