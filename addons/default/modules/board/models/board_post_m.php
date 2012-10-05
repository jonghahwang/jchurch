<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Model for Board Post
 *
 * @author      Jongha Hwang
 * @package     Jchurch\Modules\Board\Models
 */
class Board_post_m extends MY_Model
{
    /**
     * Table name comes from controller
     * It is not defined here. Without it the model won't work
     * This is an unual practice, but it is designed this way in order to determine
     * the table name dynamically depend on the board name.
     */
    protected $_table;    
    
    // set the table name of the model using the board name
    public function set_table($table)
    {
        $prefix = $this->config->item('table_prefix', 'board');
        $this->_table = $prefix.$table;
    }
    
    public function get_all()
    {
        $this->db
            ->select($this->_table.'.*, profiles.*')
            ->join($this->_table.'_categories', $this->_table.'.category_id = '.$this->_table.'_categories.id', 'left')
            ->join('profiles', 'profiles.user_id = '.$this->_table.'.author_id', 'left')
            ->order_by('created_on', 'DESC');
        
        return $this->db->get($this->_table)->result();
    }
    
    public function get($id)
    {
        return $this->db
            ->select($this->_table.'.*, profiles.display_name')
            ->join('profiles', 'profiles.user_id = '.$this->_table.'.author_id', 'left')
            ->where(array($this->_table.'.id' => $id))
            ->get($this->_table)
            ->row();
    }
    
    public function get_by($key, $value = '')
    {
        $this->db
            ->select($this->_table.'.*, profiles.display_name')
            ->join('profiles', 'profiles.id = '.$this->_table.'.author_id', 'left');
        
        // if there are multiple where clauses passed in an array
        if (is_array($key))
        {
            $this->db->where($key);
        }
        else
        {
            $this->db->where($key, $value);
        }
        
        return $this->db->get($this->_table)->row();
    }
    
    
    
    /**
     * Check if there is a post with the same slug 
     * @param where clause elements
     * @return 1 or 0
     */
    public function check_exists($field, $value = '', $id = 0)
    {
        // here $param is simply where clause
        if (is_array($field))
        {
            $params = $field;
            $id = $value;
        }
        else
        {
            $params[$field] = $value;
        }
        
        $params['id !='] = (int) $id;
        
        return parent::count_by($params) == 0;
    }
}