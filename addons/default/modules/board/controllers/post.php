<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Public Post Controller
 *
 * @author      Jongha Hwang
 * @package     Jchurch\Modules\Board\Controllers
 */
class Post extends Site_Controller
{
    /**
	 * The name of the board that comes from second segment of uri
	 * @var string
	 */
    protected $_board_name;
    
    /**
	 * Array that contains the validation rules
	 * @var array
	 */
	protected $validation_rules = array(
		'title' => array(
			'field' => 'title',
			'label' => 'lang:global:title',
			'rules' => 'trim|htmlspecialchars|required|max_length[100]'
		),
		'slug' => array(
			'field' => 'slug',
			'label' => 'lang:global:slug',
			'rules' => 'trim|required|alpha_dot_dash|max_length[100]|callback__check_slug'
		),
		array(
			'field' => 'category_id',
			'label' => 'lang:board:category_label',
			'rules' => 'trim|numeric'
		),
		array(
			'field' => 'keywords',
			'label' => 'lang:global:keywords',
			'rules' => 'trim'
		),
		array(
			'field' => 'intro',
			'label' => 'lang:board:intro_label',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'body',
			'label' => 'lang:board:content_label',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'type',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'status',
			'label' => 'lang:board:status_label',
			'rules' => 'trim|alpha'
		),
		array(
			'field' => 'created_on',
			'label' => 'lang:board:date_label',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'created_on_hour',
			'label' => 'lang:board:created_hour',
			'rules' => 'trim|numeric|required'
		),
		array(
			'field' => 'created_on_minute',
			'label' => 'lang:board:created_minute',
			'rules' => 'trim|numeric|required'
		),
        array(
			'field' => 'comments_enabled',
			'label'	=> 'lang:board:comments_enabled_label',
			'rules'	=> 'trim|numeric'
		),
        array(
            'field' => 'preview_hash',
            'label' => '',
            'rules' => 'trim'
        )
	);
	
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('board_post_m');
        $this->load->model('board_categories_m');        
        $this->load->model('comments/comments_m');
        $this->load->library(array('keywords/keywords'));
        $this->lang->load('board');
        
        // button language is usually loaded by Admin_Controller
        // but here we are not using Adim_Controller, so we have to load
        $this->lang->load('buttons');
        $this->load->config('board');
        
        // set the table name in the model
        $this->_board_name = $this->uri->segment(2);
        $this->board_post_m->set_table($this->_board_name);
        $this->board_categories_m->set_table($this->_board_name);
        
        // Date ranges for select boxes
        $this->template
            ->set('hours', array_combine($hours = range(0, 23), $hours))
            ->set('minutes', array_combine($minutes = range(0, 59), $minutes));
        
        // build category data object
        $_categories = array();
        
        if ($categories = $this->board_categories_m->order_by('title')->get_all())
        {
            foreach ($categories as $category)
            {
                // categories is database returned object, we need to convert this
                // as pure array to be used in form_dropdown
                $_categories[$category->id] = $category->title;
            }
        }
        
        // set category lookup data in constructor
        $this->template
            ->set('board_name', $this->_board_name)
            ->set('categories', $_categories);
    }
    
    public function index()
    {
        $posts = $this->board_post_m->get_all();
        
        foreach ($posts as $post)
        {
            
        }
                
        $this->template
            ->title('Posts')
            ->set('posts', $posts)
            ->build('list');
    }
    
    /**
     * create a new post
     */
    public function create()
    {
        // load admin assets
        $this->_get_admin_assets();
        
        // check access
        if ( ! $this->_check_access())
        {
            $this->session->set_flashdata('error', lang('board:access_denied'));
            redirect();
        }
        
        $this->form_validation->set_rules($this->validation_rules);
        
        if ($this->input->post('created_on'))
        {
            $created_on = strtotime(sprintf('%s %s:%s', $this->input->post('created_on'), 
                                                        $this->input->post('created_on_hour'), 
                                                        $this->input->post('created_on_minute')));
        }
        else
        {
            $created_on = now();
        }
        
        $hash = $this->_preview_hash();
        
        // run form validation
        if ($this->form_validation->run())
        {
            // build a new post object to insert
            $data = array(
                'title'				=> $this->input->post('title'),
				'slug'				=> $this->input->post('slug'),
				'category_id'		=> $this->input->post('category_id'),
				'keywords'			=> Keywords::process($this->input->post('keywords')),
				'intro'				=> $this->input->post('intro'),
				'body'				=> $this->input->post('body'),
				'status'			=> $this->input->post('status'),
				'created_on'		=> $created_on,
				'comments_enabled'	=> $this->input->post('comments_enabled'),
				'author_id'			=> $this->current_user->id,
				'type'				=> $this->input->post('type'),
				'parsed'			=> ($this->input->post('type') == 'markdown') ? parse_markdown($this->input->post('body')) : '',
                'preview_hash'      => $hash
            );
            
            // if insert is successful there should be a new id
            if ($id = $this->board_post_m->insert($data))
            {
                $this->pyrocache->delete_all('board_post_m');
                $this->session->set_flashdata('success', sprintf($this->lang->line('board:post_add_success'), $this->input->post('title')));
                
                Events::trigger('post_created', $id);
                
                if ($this->input->post('status') == 'live')
                {
                    Events::trigger('post_published', $id);
                }
            }
            else
            {
                $this->session->set_flashdata('error', lang('board:post_add_error'));
            }
            
            // Redirect back to the form or main page
            if ($this->input->post('btnAction') == 'save_exit') 
            {
                redirect('board/' . $this->_board_name . '/index');
            }
            else
            {
                redirect('board/' . $this->_board_name . '/edit/' . $id);
            }
            
        }
        // for new insert
        else
        {   //echo 'invalid';exit;
            $post = new stdClass;
            
            // use validation rules as a field for post object
            foreach ($this->validation_rules as $key => $field)
            {
                $post->$field['field'] = set_value($field['field']);
            }
            
            $post->created_on = $created_on;
        }
        

        $this->template
            ->title(lang('board:create_title'))
            ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
            ->append_js('jquery/jquery.tagsinput.js')
            ->append_js('module::board_form.js')
			->append_css('jquery/jquery.tagsinput.css')
            ->set('post', $post)
            ->build('form');
    }
    
    /**
     * edit post
     */
    public function edit($id = 0) 
    {
        $id OR redirect('board/'.$this->_board_name.'/index');
        
        $post = $this->board_post_m->get($id);
        
        
        // If we have keywords before the update, we'll want to remove them from keywords_applied
		$old_keywords_hash = (trim($post->keywords) != '') ? $post->keywords : null;
		
		$post->keywords = Keywords::get_string($post->keywords);
        
        if ($this->input->post('created_on'))
        {
            $created_on = strtotime(sprintf('%s %s:%s', $this->input->post('created_on'), 
                                                        $this->input->post('created_on_hour'), 
                                                        $this->input->post('created_on_minute')));
        }
        else
        {
            $created_on = $post->created_on;
        }
        
        // why do we reset the validation rules?
        // because the callback function expect post ID.
        $this->form_validation->set_rules(array_merge($this->validation_rules, array(
    		'slug' => array(
    			'field' => 'slug',
    			'label' => 'lang:global:slug',
    			'rules' => 'trim|required|alpha_dot_dash|max_length[100]|callback__check_slug['.$id.']'
    		),
        )));
        
        // set the hash for preview
        $hash = $this->input->post('preview_hash');
        
        if ($this->input->post('status') == 'draft' && $this->input->post('preview_hash') == '')
        {
            $hash = $this->_preview_hash();
        }
        
        // run the form validation
        if ($this->form_validation->run())
        {
            // if user is trying to update the status of the post, check the permission
            if ($post->status != 'live' and $this->input->post('status') == 'live')
            {
                role_or_die('board', 'put_live');
            }
            
            $result = $this->board_post_m->update($id, array(
                
            ));
            
            // successfully updated
            if ($result)
            {
                
            }
            else
            {
                
            }
            
        }
        
        
        
        $this->template
            ->title($this->module_details['name'], sprintf(lang('board:edit_title'), $post->title))
            ->append_metadata($this->load->view('fragments/wysiwyg', array(), TRUE))
            ->append_js('jquery/jquery.tagsinput.js')
            ->append_js('module::board_form.js')
            ->append_css('jquery/jquery.tagsinput.css')
            ->set('post', $post)
            ->build('form');
    }

    /**
     * view post with slug 
     */
    public function view($slug = '')
    {
        if ( ! $slug or ! $post = $this->board_post_m->get_by('slug', $slug))
        {
            redirect('board/'.$this->_board_name.'/index');
        }
        
        if ($post->status != 'live')
        {
            //redirect('board/'.$this->_board_name.'/index');
        }
        
        $this->_single_view($post);
    }


    /**
     * callback function that check the slug of the post 
     * @param string - slug
     * @return bool
     */
    public function _check_slug($slug, $id= null)
    {
        $this->form_validation->set_message('_check_slug', sprintf(lang('board:already_exist_error'), lang('global:title')));
        return $this->board_post_m->check_exists('slug', $slug, $id);
    }
    
    private function _preview_hash()
    {
        return md5(microtime() + mt_rand(0,1000));
    }
    
    private function _single_view($post, $build = 'view')
    {
        // if it uses markdown then display the parsed version
        if ($post->type == 'markdown')
        {
            $post->body = $post->parsed;
        }
        
        // if this post uses a category, grab it
        if ($post->category_id && ($category = $this->board_categories_m->get($post->category_id)))
        {
            $post->category = $category;
        }
        else
        {
            $post->category = (object) array(
                'id' => 0,
                'slug' => '',
                'title' => '',
            );
        }
        
        // save current uri as referrer
        $this->session->set_flashdata(array('referrer' => $this->uri->uri_string));
        
        $this->template
            ->title($post->title, lang('board:blog_title'))
            ->set_metadata('description', $post->intro)
            ->set_metadata('keywords', implode(', ', Keywords::get_array($post->keywords)))
            ->set_breadcrumb(lang('board:blog_title'), 'blog');
        
        if ($post->category->id > 0)
        {
            $this->template->set_breadcrumb($post->category->title, 'blog/category/'.$post->category->slug);
        }
        
        $post->keywords = Keywords::get($post->keywords);
        
        $this->template
            ->set_breadcrumb($post->title)
            ->set('post', $post)
            ->build($build);
    }
    
    /**
     * check if a user object has access right
     */
    private function _check_access()
    {
        if ( ! $this->current_user)
        {
            // save the location they were trying to get to
			$this->session->set_userdata('admin_redirect', $this->uri->uri_string());
			redirect('admin/login');
        }
        
        // Admins can go straight in
		if ($this->current_user->group === 'admin')
		{
			return TRUE;
		}
        
        // otherwise return false
        return FALSE;
    }
    
    /**
     * Include all js and css assets for admin related method
     * Since this controller has all admin related functions such as create, edit, delete, etc.,
     * we need to include many assets that are available only in Admin_Controller
     * These assets are not loaded in the Constructor because of performance reason.
     * This method needs to be called for create, edit, delete methods.
     */
    private function _get_admin_assets()
    {
        //$this->load->helper('admin_theme');
		
		//ci()->admin_theme = $this->theme_m->get_admin();
    }
}