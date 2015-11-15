<?php
class DB_Stock extends CI_Model
{

    /**
     * Retona somente se um determinado estoque_id existir
     *
     * @param integer $estoque_id
     * @return boolean
     */
    public function exists(&$estoque_id)
    {
        $this->db->from('db_estoque');
        $this->db->where('estoque_id', $estoque_id);
        $query = $this->db->get();
    
        return ($query->num_rows() == 1);
    }
    
    
    public function get_fields()
    {
        return $this->db->list_fields('db_estoque');
    }
    
    public function get_all()
    {   
        $this->db->from('db_estoque');        
        return $this->db->get();
    }
       
    
    
    public function get_info($estoque_id)
    {
        $this->db->from('db_estoque');
        $this->db->where('estoque_id', $estoque_id);
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) 
        {
            return $query->row();
        } 
        else 
        {
            // criar o objeto com propriedades vazias
            $fields = $this->db->list_fields('db_estoque');
            $obj = new stdClass();
            
            // anexar esses campos ao objeto pai de base, nós temos um objeto completo e vazio
            foreach ($fields as $field) 
            {
                $obj->$field = '';
            }
            return $obj;
        }
    }
    
    
    public function save(&$estoque_data, $estoque_id=-1)
    {
        $success = false;
    
        if ($estoque_data && $estoque_id) {
            if (! $estoque_id or ! $this->exists($estoque_id)) {
                $success = $this->db->insert('db_estoque', $estoque_data);
            } else {
                $this->db->where('estoque_id', $estoque_id);
                $success = $this->db->update('db_estoque', $estoque_data);
            }
        }
    
        return $success;
    }
    
    public function delete(&$estoque_id)
    {
        $this->db->where('estoque_id', $estoque_id);       
        return $this->db->delete('db_estoque');
    }    
    
    
    public function clear()
    {
    	return $this->db->truncate('db_estoque');    	 
    }
}