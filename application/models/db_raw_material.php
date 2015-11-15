<?php
class DB_Raw_Material extends CI_Model
{

    /**
     * Retona somente se um determinado materia_id existir
     *
     * @param integer $materia_id
     * @return boolean
     */
    public function exists(&$materia_id)
    {
        $this->db->from('db_materia');
        $this->db->where('materia_id', $materia_id);
        $query = $this->db->get();
    
        return ($query->num_rows() == 1);
    }
    
    
    public function get_fields()
    {
        return $this->db->list_fields('db_materia');
    }
    
    public function get_all($sta, $end)
    {   
        $this->db->from('db_materia');        
        $this->db->limit($end,$sta);
        return $this->db->get();
    }
    
    public function get_all_active($sta, $end)
    {
    	$this->db->from('db_materia');
    	$this->db->where('materia_deletado', 0);
    	$this->db->limit($end,$sta);
    	return $this->db->get();
    }
    
    
    public function get_info($materia_id)
    {
        $this->db->from('db_materia');
        $this->db->where('materia_id', $materia_id);
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) 
        {
            return $query->row();
        } 
        else 
        {
            // criar o objeto com propriedades vazias
            $fields = $this->db->list_fields('db_materia');
            $obj = new stdClass();
            
            // anexar esses campos ao objeto pai de base, nós temos um objeto completo e vazio
            foreach ($fields as $field) 
            {
                $obj->$field = '';
            }
            return $obj;
        }
    }
    
    
    public function save(&$materia_data, $materia_id=-1)
    {
        $success = false;
    
        if ($materia_data && $materia_id) {
            if (! $materia_id or ! $this->exists($materia_id)) {
                $success = $this->db->insert('db_materia', $materia_data);
            } else {
                $this->db->where('materia_id', $materia_id);
                $success = $this->db->update('db_materia', $materia_data);
            }
        }
    
        return $success;
    }
    
    public function delete(&$materia_ids)
    {
        $this->db->where_in('materia_id', $materia_ids);       
        return $this->db->delete('db_materia');
    }    
    
    
}