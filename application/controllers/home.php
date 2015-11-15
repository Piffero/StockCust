<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/home
	 *	- or -  
	 * 		http://example.com/index.php/home/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{   
        if ($this->DB_User->is_logged_in()) 
        {  

            $info_user = $this->DB_User->get_logged_in_user_info();            
            $info_user_permissions = $this->DB_Permission->get_permission($info_user->usuario_id, 'principal');         
            $info_all_permissions =  $this->DB_Permission->get_permission($info_user->usuario_id);
            
            $num_linhas = $info_all_permissions->num_rows();
            $margem = ($num_linhas * 100) / 10;
            
            if($info_user_permissions->permissao_checked == 1)
            {
                $app_header['user_info'] = $info_user;
                $app_header['mrg'] = $margem;
                
                $this->load->view('blocks/app_header', $app_header);
                $this->load->view('blocks/app_aside');
                $this->load->view('content');
            }
            else 
            {
                redirect('login');
            }            
        }
        else 
        {
            redirect('login');
        }
	    
        
	}

	
	public function logout()
	{
	    $this->DB_User->logout();
	}
	
	
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */