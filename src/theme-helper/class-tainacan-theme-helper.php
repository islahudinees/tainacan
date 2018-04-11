<?php

namespace Tainacan;
use Tainacan\Entities;


class Theme_Helper {

	private static $instance = null;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

		add_filter( 'the_content', [&$this, 'the_content_filter'] );
		
		
		/**
		 * Replace collection single template with the respective post type archive
		 * TODO: if collections is not set to use a cover page
		 */
		add_filter('post_type_link', array(&$this, 'permalink_filter'), 10, 3);
		
		
		add_action('pre_get_posts', array(&$this, 'tax_archive_pre_get_posts'));

	}
	
	public function is_post_an_item(\WP_Post $post) {
		$post_type = $post->post_type;
		$prefix = substr( $post_type, 0, strlen( Entities\Collection::$db_identifier_prefix ) );
		return $prefix == Entities\Collection::$db_identifier_prefix;
	}
	
	public function is_taxonomy_a_tainacan_tax($tax_slug) {
		$prefix = substr( $tax_slug, 0, strlen( Entities\Taxonomy::$db_identifier_prefix ) );
		return $prefix == Entities\Taxonomy::$db_identifier_prefix;
	}
	
	public function is_term_a_tainacan_term( \WP_Term $term ) {
		return $this->is_taxonomy_a_tainacan_tax($term->taxonomy);
	}
	
	public function the_content_filter($content) {
		global $post;
		
		if (!is_single())
			return $content;
		
		// Is it a collection Item?
		if ( !$this->is_post_an_item($post) ) {
			return $content;
		}
		
		$item = new Entities\Item($post);
		$content = '';
		
		// document
		
		// metadata
		$content .= $item->get_metadata_as_html();
		
		// attachments
		
		return $content;
		
	}
	
	/**
     * Filters the permalink for posts to:
     *
     * * Replace Collectino single permalink with the link to the post type archive for items of that collection
     * 
     * @return string new permalink
     */
    function permalink_filter($permalink, $post, $leavename) {
        
        $collection_post_type = \Tainacan\Entities\Collection::get_post_type();
        
        if (!is_admin() && $post->post_type == $collection_post_type) {
            
            $collection = new \Tainacan\Entities\Collection($post);
            $items_post_type = $collection->get_db_identifier();
            
            $post_type_object = get_post_type_object($items_post_type);
            
            if (isset($post_type_object->rewrite) && is_array($post_type_object->rewrite) && isset($post_type_object->rewrite['slug']))
                return site_url($post_type_object->rewrite['slug']);
                
        }
        
        return $permalink;
        
    }
	
	function tax_archive_pre_get_posts($wp_query) {
		
		if (!is_tax() || !$wp_query->is_main_query())
			return $wp_query;
		
		$term = get_queried_object();
		
		if ($this->is_term_a_tainacan_term($term)) {
			// TODO: Why post_type = any does not work?
			$wp_query->set( 'post_type', \Tainacan\Repositories\Repository::get_collections_db_identifiers() );
		}
		
		return $wp_query;
		
	}
	
	

}

