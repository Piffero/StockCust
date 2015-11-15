<?php
class DB_Unid_Medida extends CI_Model
{

    /**
     * Retona somente se um determinado unidade_id existir
     *
     * @param integer $unidade_id
     * @return boolean
     */
    public function exists(&$unidade_id)
    {
        $this->db->from('db_unidade');
        $this->db->where('unidade_id', $unidade_id);
        $query = $this->db->get();
    
        return ($query->num_rows() == 1);
    }
    
    
    public function get_fields()
    {
        return $this->db->list_fields('db_unidade');
    }
    
    public function get_all($sta, $end)
    {   
        $this->db->from('db_unidade');
        $this->db->limit($end,$sta);
        return $this->db->get();
    }
    
    
    public function save(&$unidade_data, $unidade_id=-1)
    {
        $success = false;
    
        if ($unidade_data && $unidade_id) {
            if (! $unidade_id or ! $this->exists($unidade_id)) {
                $success = $this->db->insert('db_unidade', $unidade_data);
            } else {
                $this->db->where('unidade_id', $unidade_id);
                $success = $this->db->update('db_unidade', $unidade_data);
            }
        }
    
        return $success;
    }
    
    public function delete(&$unidade_ids)
    {
        $this->db->where_in('unidade_id', $unidade_ids);       
        return $this->db->delete('db_unidade');
    }    
    
    
}