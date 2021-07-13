<?php
class Comman_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();        
    }
    
    function insertData($table,$data)
    {
        $this->db->insert($table,$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function updateData($table,$data,$where)
    {
        $update_data = $this->db->update($table,$data,$where);
        return $update_data;
    }
    
    function deleteData($table,$where)
    {
        $this->db->delete($table,$where);
    }
    
    function select_all($table)
    {
        $query = $this->db->get($table);
        return $query->result_array();
    }

    function select_where($table,$id)
    {
        $query = $this->db->get_where($table,$id);
        return $query->result_array();
    }
    
    public function getData($options)
    {
            $select = false;
            $table = false;
            $join = false;
            $order = false;
            $group = false;
            $limit = false;
            $offset = false;
            $where = false;
            $get_where = false;
            $or_where = false;
            $single = false;
            $where_not_in = false;
            $like = false;
            $having = false;

            extract($options);

            if ($select != false)
                $this->db->select($select);

            if ($table != false)
                $this->db->from($table);

            if ($where != false)
                $this->db->where($where);

            if ($where_not_in != false) {
                foreach ($where_not_in as $key => $value) {
                    if (count($value) > 0)
                        $this->db->where_not_in($key, $value);
                }
            }

            if ($like != false) {
                $this->db->like($like);
            }

            if ($or_where != false)
                $this->db->or_where($or_where);

            if ($limit != false) 
            {
                $limitval = $limit[0];
                $offset = $limit[1];
                
                if(!is_array($limit))
                {
                    $this->db->limit($limitval);
                }
                else 
                {
                    $this->db->limit($limitval,$offset);   
                }
            }

            if($having != false) {
                $this->db->having($having);
            }


            if ($order != false) {

                foreach ($order as $key => $value) {

                    if (is_array($value)) {
                        foreach ($order as $orderby => $orderval) {
                            $this->db->order_by($orderby, $orderval);
                        }
                    } else {
                        $this->db->order_by($key, $value);
                    }
                }
            }
            
            if ($group != false) 
            {
                foreach ($group as $value) 
                {
                    if(is_array($value)) 
                    {
                        foreach ($group as $groupval) 
                        {
                            $this->db->group_by($groupval);
                        }
                    }
                    else
                    {
                        $this->db->group_by($value);
                    }
                }
            }
            
            if ($join != false) 
            {
                foreach ($join as $key => $value)
                {
                    $this->db->join($value[0], $value[1], $value[2]);
                }
            }


            $query = $this->db->get();

            if ($single) {
                return $query->row();
            }

            if($get_where =='1' && $get_where !='')
            {
                $result['resultData'] = $query->row_array(); 
            }
            else
            {
                $result['resultData'] = $query->result_array(); 
            }
            
            $result['resultNumRows'] = $query->num_rows(); 
            
            return $result;
    }
}
?>