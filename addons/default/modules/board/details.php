<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Board module defail infomation
 *
 * @package     Jchurch
 * @subpackage  board
 * @category    Message Board Application
 */
class Module_Board extends Module {
    
    public $version = '1.0';
    
    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Board'
            ),
            'description' => array(
                'en' => 'Board'
            ),
            'frontend' => true,
            'backend' => true,
            'skip_xss' => true,
            'menu'  => 'content',
            'role' => array('put_live', 'edit_live', 'delete_live'),
            'section' => array(
                'posts' => array(
                    'name' => 'board:message_title',
                    'uri' => 'board',
                    'shortcuts' => array(
                        array(
                            'name' => 'board:create_title',
                            'uri' => 'board/create',
                            'class' => 'add'
                        ),
                    ),
                ),
                'categories' => array(
                    'name' => 'cat_list_title',
                    'uri' => 'board/categories',
                    'shortcuts' => array(
                        array(
                            'name' => 'cat_create_title',
                            'uri' => 'board/categories/create',
                            'class' => 'add'
                        ),
                    ),
                ),
            ),            
        );
    }
    
    public function install()
    {
        /**
         * Board module allows you to have many boards and each board will have its own table
         * For example. 'default_board_temp' and 'default_board_temp_category'.
         * If you want to add a new board, either you rename the temp table or just create a new table 
         * with a different table name and using the same table definition. 
         * For example, if you want to add a 'news' board you need to create a table, 
         * 'default_board_news' and 'default_board_news_categories'
         */
        
        $this->dbforge->drop_table('board_temp');
        $this->dbforge->drop_table('board_temp_categories');
        
        $table = array(
            'board_temp_categories' => array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'auto_Increment' => true, 'primary' => true),
                'slug' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => false, 'unique' => true, 'key' => true),
                'title' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => false, 'unique' => true),
            ),
            'board_temp' => array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
                'title' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false),
                'slug' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true),
                'board_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
                'category_id' => array('type' => 'INT', 'constraint' => 11, 'key' => true),
                'attachment' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
                'intro' => array('type' => 'TEXT'),
                'body' => array('type' => 'TEXT'),
                'parsed' => array('type' => 'TEXT'),
                'keywords' => array('type' => 'VARCHAR', 'constraint' => 32, 'default' => ''),
                'author_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
                'created_on' => array('type' => 'INT', 'constraint' => 11),
                'updated_on' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
                'comments_enabled' => array('type' => 'INT', 'constraint' => 1, 'default' => 1),
                'status' => array('type' => 'ENUM', 'constraint' => array('draft', 'live'), 'default' => 'draft'),
                'type' => array('type' => 'VARCHAR', 'constraint' => 20),
                'preview_hash' => array('type' => 'CHAR', 'constraint' => 32, 'default' => ''),
            )
        );
        
        return $this->install_tables($table);
    }
    
    public function uninstall()
    {
        $this->dbforge->drop_table('board_temp_categories');
        $this->dbforge->drop_table('board_temp');
    }
    
    public function upgrade($old_version)
    {
        return true;
    }
} 
 
 
 