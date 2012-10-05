<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Model for Board Categories
 *
 * @author      Jongha Hwang
 * @package     Jchurch\Modules\Board\Models
 */
class Board_categories_m extends MY_Model
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
        $this->_table = $prefix.$table.'_categories';
    }
    
    /**
     * Override parent insert method by adding slug field
     * when user types just title, slug will be automatically inserted 
     * here url_title() will convert the title into url friendly version such as title-as-something
     *
     * @param array $input - the category data, title
     * @return string
     */
    public function insert($input = array())
    {
        parent::insert(array(
            'title' => $input['title'],
            'slug' => url_title(strtolower(convert_accented_characters($input['title'])))
        ));
        
        return $input['title'];
    }
    
    /**
     * Override parent update method
     * Since we add slug field for insert, update method also need to handle this 
     *
     * @param 
     * @return 
     */
    public function update($id, $input)
    {
        return parent::update($id, array(
           'title' => $input['title'],
           'slug' => url_title(strtolower(convert_accented_characters($input['title'])))
        ));
    }
    
    /**
     * Callback method for validation the title
     *  
     * @param string $title The title to validate
     * @param int $id The id to check
     * @return bool
     */
    public function check_title($title = '', $id = 0)
    {
        return (bool) $this->db->where('slug', url_title($title))
            ->where('id != ', $id)
            ->from($this->_table)
            ->count_all_results();
    }
    
}