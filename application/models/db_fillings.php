<?php
class DB_Fillings extends CI_Model
{

    /**
     * Retona somente se um determinado recheio_id existir
     *
     * @param integer $recheio_id
     * @return boolean
     */
    public function exists(&$recheio_id)
    {
        $this->db->from('db_recheio');
        $this->db->where('recheio_id', $recheio_id);
        $query = $this->db->get();
    
        return ($query->num_rows() == 1);
    }
    
    
    public function get_fields()
    {
        return $this->db->list_fields('db_recheio');
    }
    
    
    public function get_all($sta, $end)
    {   
        $this->db->from('db_recheio');
        $this->db->limit($end,$sta);
        return $this->db->get();
    }
   
    public function get_all_active($sta, $end)
    {
    	$this->db->from('db_recheio');
    	$this->db->where('recheio_deletado', 0);
    	$this->db->limit($end,$sta);
    	return $this->db->get();
    }
    
    public function get_info($recheio_id)
    {
        $this->db->from('db_recheio');
        $this->db->where('recheio_id', $recheio_id);
        $query = $this->db->get();
    
        if ($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            // criar o objeto com propriedades vazias
            $fields = $this->db->list_fields('db_recheio');
            $obj = new stdClass();
    
            // anexar esses campos ao objeto pai de base, nós temos um objeto completo e vazio
            foreach ($fields as $field)
            {
                $obj->$field = '';
            }
            return $obj;
        }
    }
    
    
    public function save(&$recheio_data, $recheio_id=-1)
    {        
        $success = false;        
        if ($recheio_data && $recheio_id) {
            if (! $recheio_id or ! $this->exists($recheio_id)) {
                $success = $this->db->insert('db_recheio', $recheio_data);
            } else {
                $this->db->where('recheio_id', $recheio_id);
                $success = $this->db->update('db_recheio', $recheio_data);
            }
        }
    
        return $success;
    }
    
    
    public function save_item(&$item_data)
    {
        
        $success = false;        
        if ($item_data) {            
           $success = $this->db->insert('db_recheio_items', $item_data);            
        }
        
        return $success;
    }
    
    
    public function update_item($item_data, $item_id=false)
    {
    	$success = false;    	
    	if($item_data && $item_id)
    	{      		
    		$this->db->where('r_items_id', $item_id);
    		$success = $this->db->update('db_recheio_items',$item_data);
    	}    	    
    	return $success;
    }
    
    public function delete(&$recheio_ids)
    {
        $this->db->where_in('recheio_id', $recheio_ids);       
        return $this->db->delete('db_recheio');
    }

    
    public function delete_items(&$recheio_item_id)
    {
        $this->db->where('r_items_id', $recheio_item_id);
        return $this->db->delete('db_recheio_items');
    }
        
    
    public function get_items($materia_id)
    {
        $this->db->from('db_recheio_items');
        $this->db->where('db_recheio_id', $materia_id);
        return $this->db->get();
    }
    
}