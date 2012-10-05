<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Board Controller
 *
 * @author      Jongha Hwang
 * @package     Jchurch\Modules\Board\Controllers
 */
class Board extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('board_post_m');
        $this->load->library(array('keywords/keywords'));
        $this->lang->load('board');
    }
    
    /**
     * Show list of boards
     */
    public function index()
    {
        // there is uri routing /board/index/boardname
        $board_name = $this->uri->segment(2);
        // set the board table name in the post model
        $this->board_post_m->table = $board_name;
        
        
        $this->list_posts($board_name);
    }

    
    public function list_posts($board_name)
    {
        $posts = $this->board_post_m->get_all($board_name);
        print_r($posts);exit;
    }
    
    
    
}