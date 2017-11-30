<?php
namespace Tainacan\Repositories;
use Tainacan\Entities;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Implement a Logs system
 *  
 * @author medialab
 *
 */
class Logs extends Repository {
	protected $entities_type = '\Tainacan\Entities\Log';
	
	public function __construct() {
		parent::__construct();
		add_action('tainacan-insert', array($this, 'log_inserts'));
	}
	
    public function get_map() {
        return [
            'id'             => [
                'map'        => 'ID',
                //'validation' => ''
            ],
            'title'          =>  [
                'map'        => 'post_title',
                'validation' => ''
            ],
            'order'          =>  [
                'map'        => 'menu_order',
                'validation' => ''
            ],
            'parent'         =>  [
                'map'        => 'parent',
                'validation' => ''
            ],
            'description'    =>  [
                'map'        => 'post_content',
                'validation' => ''
            ],
            'slug'           =>  [
                'map'        => 'post_name',
                'validation' => ''
            ],
            'itens_per_page' =>  [
                'map'        => 'meta',
                'validation' => ''
            ],
        	'user_id'        => [
        		'map'        => 'post_author',
        		'validation' => ''
        	],
        	'blog_id'        => [
        		'map'        => 'meta',
        		'validation' => ''
        	],
        	'value'        => [
        		'map'        => 'meta',
        		'validation' => ''
        	],
        	'old_value'        => [
        		'map'        => 'meta',
        		'validation' => ''
        	],
        ];
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Tainacan\Repositories\Repository::register_post_type()
     */
    public function register_post_type() {
        $labels = array(
            'name'               => 'logs',
            'singular_name'      => 'logs',
            'add_new'            => 'Adicionar Novo',
            'add_new_item'       => 'Adicionar Log',
            'edit_item'          => 'Editar',
            'new_item'           => 'Novo Log',
            'view_item'          => 'Visualizar',
            'search_items'       => 'Pesquisar',
            'not_found'          => 'Nenhum log encontrado',
            'not_found_in_trash' => 'Nenhum log encontrado na lixeira',
            'parent_item_colon'  => 'Log aterior:',
            'menu_name'          => 'Logs'
        );
        $args = array(
            'labels'              => $labels,
            'hierarchical'        => true,
            //'supports'          => array('title'),
            //'taxonomies'        => array(self::TAXONOMY),
            'public'              => false,
            'show_ui'             => tnc_enable_dev_wp_interface(),
            'show_in_menu'        => tnc_enable_dev_wp_interface(),
            //'menu_position'     => 5,
            //'show_in_nav_menus' => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => true,
            'capability_type'     => 'post',
        );
        register_post_type(Entities\Log::get_post_type(), $args);
    }


    /**
     * fetch logs based on ID or WP_Query args
     *
     * Logs are stored as posts. Check WP_Query docs
     * to learn all args accepted in the $args parameter (@see https://developer.wordpress.org/reference/classes/wp_query/)
     * You can also use a mapped property, such as name and description, as an argument and it will be mapped to the
     * appropriate WP_Query argument
     *
     * @param array $args WP_Query args || int $args the log id
     * @param string $output The desired output format (@see \Tainacan\Repositories\Repository::fetch_output() for possible values)
     * @return \WP_Query|Array an instance of wp query OR array of entities;
     */
    public function fetch($args = [], $output = null){
        if(is_numeric($args)){
    	    return new Entities\Log($args);
        } else {
            $args = array_merge([
                'post_status'    => 'publish',
            ], $args);

            $args = $this->parse_fetch_args($args);

            $args['post_type'] =  Entities\Log::get_post_type();

            $wp_query = new \WP_Query($args);
            return $this->fetch_output($wp_query, $output);
        }
    }

    public function delete($object){

    }

    public function update($object){

    }
    
    public function fetch_last() {
    	$args = [
    		'post_type'      => Entities\Log::get_post_type(),
    		'posts_per_page' => 1,
    		'post_status'    => 'publish',
    	];
    	
    	$posts = get_posts($args);
    	
    	foreach ($posts as $post) {
    		$log = new Entities\Log($post);
    	}
    	// TODO: Pegar coleções registradas via código
    	return $log;
    }
    
    public function log_inserts($new_value, $value = null)
    {
    	$msn = "";
   		if(is_object($new_value))
   		{
   			// do not log a log
   			if(method_exists($new_value, 'get_post_type') && $new_value->get_post_type() == 'tainacan-logs') return;
   			
   			$type = get_class($new_value);
   			$msn = sprintf( esc_html__( 'a %s has been created/modified.', 'tainacan' ), $type );
   		}
   		$msn = apply_filters('tainacan-insert-log-message-title', $msn, $type, $new_value);
    	Entities\Log::create($msn, '', $new_value, $value);
    }
}