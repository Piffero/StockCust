<?php
class DB_Regras_Base extends CI_Model
{

    /**
     * Retona somente se um determinado regras_id existir
     *
     * @param integer $regras_id
     * @return boolean
     */
    public function exists(&$regras_id)
    {
        $this->db->from('db_regras');
        $this->db->where('regras_id', $regras_id);
        $query = $this->db->get();
    
        return ($query->num_rows() == 1);
    }
    
    
    public function get_fields()
    {
        return $this->db->list_fields('db_regras');
    }
    
    public function get_all($sta, $end)
    {   
        $this->db->from('db_regras');
        $this->db->limit($end,$sta);
        return $this->db->get();
    }
    
    public function get_info(&$regras_id)
    {
        $this->db->from('db_regras');
        $this->db->where('regras_id', $regras_id);
        $query = $this->db->get();
    
        if($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            // criar o objeto com propriedades vazias
            $fields = $this->db->list_fields('db_regras');
            $obj = new stdClass();
    
            // anexar esses campos ao objeto pai de base, nós temos um objeto completo e vazio
            foreach ($fields as $field)
            {
                $obj->$field = '';
            }
            return $obj;
        }
    }
    
    public function get_regra(&$recheio_regra)
    {
        
        $this->db->from('db_regras');
        $this->db->where('regras_basecalculo', $recheio_regra);
        $query = $this->db->get();
        
        if($query->num_rows() == 1)
        {            
            return $query->row();
        }
        else 
        {
            // cria objeto com propredades default
            $obj = new stdClass();
            
            $obj->regras_id = '2';
            $obj->regras_nome = '70g';
            $obj->regras_basecalculo = '0.0700';
            $obj->regras_atualizacao = 'Valor padrão gerado pelo sistema';
            $obj->regras_deletado = '99';
            
            return $obj;            
        }
    }
    
    
    public function save(&$regra_data, $regras_id=-1)
    {
        $success = false;
    
        if ($regra_data && $regras_id) {
            if (! $regras_id or ! $this->exists($regras_id)) {
                $success = $this->db->insert('db_regras', $regra_data);
            } else {
                $this->db->where('regras_id', $regras_id);
                $success = $this->db->update('db_regras', $regra_data);
            }
        }
    
        return $success;
    }
    
    public function delete(&$regras_ids)
    {
        $this->db->where_in('regras_id', $regras_ids);       
        return $this->db->delete('db_regras');
    }    
    
    
}