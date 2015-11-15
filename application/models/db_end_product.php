<?php
class DB_End_Product extends CI_Model
{

    /**
     * Retona somente se um determinado recheio_id existir
     *
     * @param integer $empanada_id
     * @return boolean
     */
    public function exists(&$empanada_id)
    {
        $this->db->from('db_empanada');
        $this->db->where('empanada_id', $empanada_id);
        $query = $this->db->get();
    
        return ($query->num_rows() == 1);
    }
    
    
    public function get_fields()
    {
        return $this->db->list_fields('db_empanada');
    }
    
    public function get_all($sta, $end)
    {
        $this->db->from('db_empanada');
        $this->db->limit($end,$sta);
        return $this->db->get();
    }
    
    public function get_in(&$empanada_ids)
    {
    	$this->db->from('db_empanada');
    	$this->db->where_in('empanada_id',$empanada_ids);
    	return $this->db->get();
    }
    
    
    
    public function get_info(&$empanada_id)
    {
        $this->db->from('db_empanada');
        $this->db->where('empanada_id', $empanada_id);
        $query = $this->db->get();
    
        if($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            // criar o objeto com propriedades vazias
            $fields = $this->db->list_fields('db_empanada');
            $obj = new stdClass();
    
            // anexar esses campos ao objeto pai de base, nós temos um objeto completo e vazio
            foreach ($fields as $field)
            {
                $obj->$field = '';
            }
            return $obj;
        }
    }
    
    public function get_info_for_code(&$empanada_id)
    {
    	$this->db->from('db_empanada');
    	$this->db->where('empanada_codigo', $empanada_id);
    	$query = $this->db->get();
    
    	if($query->num_rows() == 1)
    	{
    		return $query->row();
    	}
    	else
    	{
    		// criar o objeto com propriedades vazias
    		$fields = $this->db->list_fields('db_empanada');
    		$obj = new stdClass();
    
    		// anexar esses campos ao objeto pai de base, nós temos um objeto completo e vazio
    		foreach ($fields as $field)
    		{
    			$obj->$field = '';
    		}
    		return $obj;
    	}
    }
    
    public function save(&$empanada_data, $empanada_id=-1)
    {        
        $success = false;        
        if ($empanada_data && $empanada_id) {
            if (!$empanada_id || !$this->exists($empanada_id)) {
                $success = $this->db->insert('db_empanada', $empanada_data);
            } else {
                $this->db->where('empanada_id', $empanada_id);
                $success = $this->db->update('db_empanada', $empanada_data);
            }
        }
    
        return $success;
    }
    
    
    public function save_item(&$item_data)
    {
        $success = false;        
        if ($item_data) {            
           $success = $this->db->insert('db_empanada_items', $item_data);            
        }
        
        return $success;
    }
    
    public function delete(&$empanada_ids)
    {
        $this->db->where_in('empanada_id', $empanada_ids);       
        return $this->db->delete('db_empanada');
    }    
    
    public function delete_items(&$empanada_item_id)
    {
        $this->db->where_in('e_items_id', $empanada_item_id);
        return $this->db->delete('db_empanada_items');
    }

    public function get_items($empanada_id)
    {
        $this->db->from('db_empanada_items');
        $this->db->where('db_empanada_id', $empanada_id);
        return $this->db->get();
    }
    
}