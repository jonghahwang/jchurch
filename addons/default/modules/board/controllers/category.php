<?php defined('BASEPATH') or exit('No direct script access allowed');

class Category extends Public_Controller
{
    /**
	 * The name of the board that comes from second segment of uri
	 * @var string
	 */
    protected $_board_name;
    
    /**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
	protected $validation_rules = array(
		array(
			'field' => 'title',
			'label' => 'lang:global:title',
			'rules' => 'trim|required|max_length[100]|callback__check_title'
		),
		array(
			'field' => 'id',
			'rules' => 'trim|is_numeric'			
		),
	);
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('board_categories_m');
        $this->load->config('board');
        $this->lang->load('categories');
        $this->lang->load('board');
        $this->lang->load('buttons');
        // Load the validation library along with the rules
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->validation_rules);
		
        // set the table name in the model
        $this->_board_name = $this->uri->segment(2);
        $this->board_categories_m->set_table($this->_board_name);
        
    }
    
    public function index()
    {
        $this->pyrocache->delete_all('modules_m');
        
        // Create pagination links
        $total_rows = $this->board_categories_m->count_all();
        $pagination = create_pagination('board/'.$this->_board_name.'/category/index', $total_rows, NULL, 5);
        
        // pull category data
        $categories = $this->board_categories_m->order_by('title')->get_all();
        
        $this->template
            ->title($this->module_details['name'], lang('cat_list_title'))
            ->set('categories', $categories)
            ->set('board_name', $this->_board_name)
            ->build('categories/index');
    }
    
    /**
     * Create a new category
     */
    public function create()
    {
        // validate the data
        if ($this->form_validation->run())
        {
            $data = array('title' => $this->input->post('title'));
            
            if ($id = $this->board_categories_m->insert($data))
            {
                // fire an event. A new category has been created
                Events::trigger('board_category_created', $id);
                
                $this->session->set_flashdata('success', sprintf(lang('cat_add_success'), $this->input->post('title')));
            }
            else
            {
                $this->session->set_flashdata('error', lang('cat_add_error'));
            }
            
            redirect('board/'.$this->_board_name.'/category/index');
        }
        
        $category = new stdClass();
        
        // loop through each validation rules
        foreach ($this->validation_rules as $rule)
        {
            $category->{$rule['field']} = set_value($rule['field']);
        }
        
        $this->template
            ->title($this->module_details['name'], lang('cat_create_title'))
            ->set('category', $category)
            ->build('categories/form');
    }
    
    /**
     * Edit category data
     */
    public function edit($id)
    {
        $category = $this->board_categories_m->get($id);
        
        // if there is no category for $id, back to index page
        $category or redirect('board/'.$this->_board_name.'/category/index');
         
        $this->form_validation->set_rules('id', 'ID', 'trim|required|is_numeric');
        
        if ($this->form_validation->run())
        {
            $data = array('title' => $this->input->post('title'));
            
            if ($this->board_categories_m->update($id, $data))
            {
                $this->session->set_flashdata('success', sprintf(lang('cat_edit_success'), $this->input->post('title')));
            }
            else
            {
                $this->session->set_flashdata('error', lang('cat_edit_error'));
            }
            
            Events::trigger('board_category_updated', $id);
            
            redirect('board/'.$this->_board_name.'/category/index');
        }
        
        // Loop through each rules. Here field is database table column name, title and id
        foreach ($this->validation_rules as $rule)
        {
            if ($this->input->post($rule['field']) !== FALSE)
            {
                $category->{$rule['field']} = $this->input->post($rule['field']);
            }
        }
        
        $this->template
            ->title($this->module_details['name'], sprintf(lang('cat_edit_title'), $category->title))
            ->set('category', $category)
            ->build('categories/form');
    }
        
    /**
     * Delete category
     */
    public function delete($id) 
    {
        if ($this->board_category_m->delete($id))
        {
            redirect('board/'.$this->_board_name.'/category/index');
        }        
    }
     
    /**
     * Callback function to check if the title already exists
     * @param string title
     * @return bool
     */
    public function _check_title($title = '')
    {
        $id = $this->input->post('id');
        if ($this->board_categories_m->check_title($title, $id))
        {
            $this->form_validation->set_message('_check_title', sprintf(lang('cat_already_exists_error'), $title));
            return FALSE;
        }
        return TRUE;
    }
    
    
    
    
}