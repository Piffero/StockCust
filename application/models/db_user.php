<?php
class DB_User extends CI_Model
{

    /**
     * Retona somente se um determinado materia_id existir
     *
     * @param integer $materia_id
     * @return boolean
     */
    public function exists(&$user_id)
    {
        $this->db->from('db_usuario');
        $this->db->where('usuario_id', $user_id);
        $query = $this->db->get();
    
        return ($query->num_rows() == 1);
    }
    
    
    public function get_fields()
    {
        return $this->db->list_fields('db_usuario');
    }
    
    public function get_all($sta, $end)
    {
        $this->db->from('db_usuario');
        $this->db->where_not_in('usuario_deletado',array('3'));
        $this->db->limit($end,$sta);
        return $this->db->get();
    }
    
    
    public function get_info($usuario_id)
    {
        $this->db->from('db_usuario');
        $this->db->where('usuario_id', $usuario_id);
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) 
        {
            return $query->row();
        } 
        else 
        {
            // criar o objeto com propriedades vazias
            $fields = $this->db->list_fields('db_usuario');
            $obj = new stdClass();
            
            // anexar esses campos ao objeto pai de base, nós temos um objeto completo e vazio
            foreach ($fields as $field) 
            {
                $obj->$field = '';
            }
            return $obj;
        }
    }
    
    
    public function save(&$usuario_data, $usuario_id=-1)
    {
        $success = false;
    
        if ($usuario_data && $usuario_id) {
            if (! $usuario_id or ! $this->exists($usuario_id)) {
                $success = $this->db->insert('db_usuario', $usuario_data);
            } else {
                $this->db->where('usuario_id', $usuario_id);
                $success = $this->db->update('db_usuario', $usuario_data);
            }
        }
    
        return $success;
    }
    
    public function delete(&$usuario_ids)
    {
        $this->db->where_in('usuario_id', $usuario_ids);       
        return $this->db->delete('db_usuario');
    }    
    
    function search($search)
    {
    	return $this->db->query("SELECT * FROM `bit_db_usuario` WHERE (
		    `usuario_nome` LIKE '%".$this->db->escape_like_str(trim($search))."%' or
		    `usuario_nome_usuario` LIKE '%".$this->db->escape_like_str(trim($search))."%') and
		    `usuario_deletado` = 0
		    order by `usuario_nome` asc ");
    }
    
    function login($username, $password)
    {
        $CI = & get_instance();
              
        $this->db->from('db_usuario');
        $this->db->where('usuario_nome_usuario',$username);
        $this->db->where('usuario_senha',md5($password));
        $this->db->where_not_in('usuario_deletado',1);
        $query = $this->db->get();
         
    
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $CI->session->set_userdata('usuario_id', $row->usuario_id);
            return true;
        }
        return false;
    
    }    
    
    function get_logged_in_user_info()
    {
    	$CI = & get_instance();
    	if ($this->is_logged_in()) {
    	    $CI->session->sess_update();
    		return $this->get_info($CI->session->userdata('usuario_id'));
    	}
    
    	return false;
    }
    
    public function is_logged_in()
    {
    	$CI = & get_instance();    	
    	return ($CI->session->userdata('usuario_id') != false);
    }
    
    
    public function logout()
    {
        $CI = & get_instance();
        $CI->session->sess_destroy();
        redirect('login');
    }
    
}