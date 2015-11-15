<?php

class DB_Permission extends CI_Model
{
    /**
     * Retona somente se um determinado regras_id existir
     *
     * @param integer $regras_id
     * @return boolean
     */
    public function exists(&$regras_id)
    {
        $this->db->from('db_permissao');
        $this->db->where('permissao_id', $regras_id);
        $query = $this->db->get();
    
        return ($query->num_rows() == 1);
    }
    
    
    public function get_fields()
    {
        return $this->db->list_fields('db_permissao');
    }
    
    public function get_all($sta, $end)
    {
        $this->db->from('db_permissao');
        $this->db->limit($end,$sta);
        return $this->db->get();
    }
    
    public function get_permission($user_id, $modulo=false)
    {
        $this->db->from('db_permissao');
        $this->db->where('db_usuario_id', $user_id);
        
        if($modulo){ 
            $this->db->where('modulo_descricao', $modulo);            
            $query = $this->db->get();
            
            if($query->num_rows() == 1)
            {
                return $query->row();
            }
            else
            {
                $obj = new stdClass();
            
                // anexar esses campos ao objeto pai de base, nós temos um objeto completo e vazio                    
                $obj->permissao_id = -1;
                $obj->modulo_descricao = $modulo;
                $obj->db_usuario_id = $user_id;
                $obj->permissao_checked = 0;                        
                $obj->permissao_original = 0;
                
                return $obj;
            }
        }
        else 
        {
             return $this->db->get();
        }
    }
    
    public function get_info(&$permission_id)
    {
        $this->db->from('db_permissao');
        $this->db->where('permissao_id', $permission_id);
        $query = $this->db->get();
    
        if($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            // criar o objeto com propriedades vazias
            $fields = $this->db->list_fields('db_permissao');
            $obj = new stdClass();
    
            // anexar esses campos ao objeto pai de base, nós temos um objeto completo e vazio
            foreach ($fields as $field)
            {
                $obj->$field = '';
            }
            return $obj;
        }
    }
    
    
    public function save(&$regra_data, $regras_id=-1)
    {
        $success = false;
    
        if ($regra_data && $regras_id) {
            if (! $regras_id or ! $this->exists($regras_id)) {
                $success = $this->db->insert('db_permissao', $regra_data);
            } else {
                $this->db->where('permissao_id', $regras_id);
                $success = $this->db->update('db_permissao', $regra_data);
            }
        }
    
        return $success;
    }
    
    public function delete(&$regras_ids)
    {
        $this->db->where_in('permissao_id', $regras_ids);
        return $this->db->delete('db_permissao');
    }
    
    
}
