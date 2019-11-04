<?php

namespace Tainacan;
use Tainacan\Repositories\Repository;

class Roles {


	public static $dependencies = [
		"tainacan-items" => [
			'edit_posts'           => 'upload_files',
			"edit_private_posts"   => 'upload_files',
			"edit_published_posts" => 'upload_files',
			"edit_others_posts"    => 'upload_files'
		]
	];

	private static $instance = null;

	private $capabilities;

	public static function get_instance()
	{
		if(!isset(self::$instance))
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	*
	*/
	private function __construct() {

		$this->capabilities = [
			'manage_tainacan' => [
				'display_name' => __('Manage Tainacan', 'tainacan'),
				'description' => __('Manage all Tainacan features and all Collections', 'tainacan')
			],
			'tnc_rep_edit_users' => [
				'display_name' => __('Manage Users', 'tainacan'),
				'description' => __('Manage users roles and permissions', 'tainacan')
			],
			'tnc_rep_edit_collections' => [
				'display_name' => __('Create Collections', 'tainacan'),
				'description' => __('Create new collections to the repository and edit its details', 'tainacan'),
				'dependencies' => [
					'upload_files'
				]
			],
			'tnc_rep_delete_collections' => [
				'display_name' => __('Delete Collections', 'tainacan'),
				'description' => __('Delete their own collections from the repository', 'tainacan')
			],
			'tnc_rep_edit_taxonomies' => [
				'display_name' => __('Create and edit taxonomies', 'tainacan'),
				'description' => __('Create new taxonomies and edit its terms', 'tainacan')
			],
			'tnc_rep_edit_others_taxonomies' => [
				'display_name' => __('Edit all Taxonomies', 'tainacan'),
				'description' => __('Edit all taxonomies and terms, including taxonomies created by other users', 'tainacan')
			],
			'tnc_rep_delete_taxonomies' => [
				'display_name' => __('Delete Taxonomies', 'tainacan'),
				'description' => __('Delete taxonomies', 'tainacan')
			],
			'tnc_rep_delete_others_taxonomies' => [
				'display_name' => __('Delete all Taxonomies', 'tainacan'),
				'description' => __('Delete all taxonomies and terms, including taxonomies created by other users', 'tainacan')
			],
			'tnc_rep_edit_metadata' => [
				'display_name' => __('Manage Repository Metadata', 'tainacan'),
				'description' => __('Create/edit metadata in repository level', 'tainacan')
			],
			'tnc_rep_edit_filters' => [
				'display_name' => __('Manage Repository Filters', 'tainacan'),
				'description' => __('Create/edit filters in repository level', 'tainacan')
			],
			'tnc_rep_delete_metadata' => [
				'display_name' => __('Delete Repository Metadata', 'tainacan'),
				'description' => __('Delete metadata in repository level', 'tainacan')
			],
			'tnc_rep_delete_filters' => [
				'display_name' => __('Delete Repository Filters', 'tainacan'),
				'description' => __('Delete filters in repository level', 'tainacan')
			],
			'tnc_rep_read_private_collections' => [
				'display_name' => __('View private collections', 'tainacan'),
				'description' => __('Access to view and browse private collections', 'tainacan')
			],
			'tnc_rep_read_private_taxonomies' => [
				'display_name' => __('View private taxonomies', 'tainacan'),
				'description' => __('Access to private taxonomies information', 'tainacan')
			],
			'tnc_rep_read_private_metadata' => [
				'display_name' => __('View private repository metadata', 'tainacan'),
				'description' => __('Access to private metadata in repository level', 'tainacan')
			],
			'tnc_rep_read_private_filters' => [
				'display_name' => __('View private repository filters', 'tainacan'),
				'description' => __('Access to private filters in repository level', 'tainacan')
			],
			'tnc_rep_read_logs' => [
				'display_name' => __('View Logs', 'tainacan'),
				'description' => __('Access to activities logs. Note that activity logs might contain information on private collections, items and metadata.', 'tainacan')
			],

			/**
			 * Collections capabilities
			 * There is a set of this capabilities for each collection, where %d is collection ID
			 * If %d is "all" then the user will have this capability to all collections
			 */
			'manage_tainacan_collection_%d' => [
				'display_name' => __('Manage Collection', 'tainacan'),
				'description' => __('Manage all collection settings, items, metadata, filters, etc.', 'tainacan')
			],
			'tnc_col_%d_bulk_edit' => [
				'display_name' => __('Bulk edit items', 'tainacan'),
				'description' => __('Access to the Bulk edit items feature.', 'tainacan')
			],
			'tnc_col_%d_edit_metadata' => [
				'display_name' => __('Manage metadata', 'tainacan'),
				'description' => __('Create/edit metadata in this collection', 'tainacan')
			],
			'tnc_col_%d_edit_filters' => [
				'display_name' => __('Manage filters', 'tainacan'),
				'description' => __('Create/edit filters in this collection', 'tainacan')
			],
			'tnc_col_%d_delete_metadata' => [
				'display_name' => __('Delete metadata', 'tainacan'),
				'description' => __('Delete metadata in this collection', 'tainacan')
			],
			'tnc_col_%d_delete_filters' => [
				'display_name' => __('Delete filters', 'tainacan'),
				'description' => __('Delete filters in this collection', 'tainacan')
			],
			'tnc_col_%d_read_private_metadata' => [
				'display_name' => __('View private metadata', 'tainacan'),
				'description' => __('Access private metadata in this collection', 'tainacan')
			],
			'tnc_col_%d_read_private_filters' => [
				'display_name' => __('View private filters', 'tainacan'),
				'description' => __('Access private filters in this collection', 'tainacan')
			],
			'tnc_col_%d_read_private_items' => [
				'display_name' => __('View private items', 'tainacan'),
				'description' => __('Access to view private items in this collection', 'tainacan')
			],
			'tnc_col_%d_edit_items' => [
				'display_name' => __('Edit items', 'tainacan'),
				'description' => __('Create and edit items in this collection', 'tainacan'),
				'dependencies' => [
					'upload_files'
				]
			],
			'tnc_col_%d_publish_items' => [
				'display_name' => __('Publish items', 'tainacan'),
				'description' => __('Publish items in this collection', 'tainacan'),
				'dependencies' => [
					'upload_files'
				]
			],
			'tnc_col_%d_edit_others_items' => [
				'display_name' => __('Edit others items', 'tainacan'),
				'description' => __('Edit items created by other users in this collection', 'tainacan'),
				'dependencies' => [
					'upload_files'
				]
			],
			'tnc_col_%d_edit_published_items' => [
				'display_name' => __('Edit published items', 'tainacan'),
				'description' => __('Edit items in this collection after they are published', 'tainacan'),
				'dependencies' => [
					'upload_files'
				]
			],
			'tnc_col_%d_delete_items' => [
				'display_name' => __('Delete items', 'tainacan'),
				'description' => __('Delete items in this collection', 'tainacan'),
			],
			'tnc_col_%d_delete_others_items' => [
				'display_name' => __('Delete others items', 'tainacan'),
				'description' => __('Delete items created by other users in this collection', 'tainacan'),
			],
			'tnc_col_%d_delete_published_items' => [
				'display_name' => __('Delete published items', 'tainacan'),
				'description' => __('Delete items in this collection after they are published', 'tainacan'),
			],


		];

		add_filter( 'user_has_cap', [$this, 'user_has_cap_filter'], 10, 4 );
		add_filter( 'map_meta_cap', [$this, 'map_meta_cap'], 10, 4 );

	}

	public function get_all_caps() {
		return $this->capabilities;
	}

	public function get_all_caps_slugs() {
		return array_keys($this->capabilities);
	}

	/**
	 * Gets the capabilty generic name as present in
	 * Tainacan\Roles::capabilities
	 *
	 * For example: tnc_col_12_edit or tnc_col_all_edit will return tnc_col_%d_edit
	 *
	 * @param string $cap
	 * @return string Capability slug as in the keys of $this->capabilities
	 */
	public function get_cap_generic_name($cap) {
		$cap = preg_replace('/^(.+_)[0-9]+(_.+)$/', '${1}%d${2}', $cap);
		$cap = preg_replace('/^(.+_)all(_.+)$/', '${1}%d${2}', $cap);
		return $cap;
	}

	public function user_has_cap_filter( $allcaps, $caps, $args, $user ) {

		$requested_cap = $args[0];

		foreach ( $caps as $cap ) {

			if ( array_key_exists($cap, $allcaps) && $allcaps[$cap] === true ) {
				continue;
			}

			if ( \strpos($cap, 'tnc_') === 0 ) {

				if ( $user->has_cap('manage_tainacan') ) {

					$allcaps = array_merge($allcaps, [ $cap => true ]);

				} elseif ( \strpos($cap, 'tnc_col_') === 0 ) {

					$col_id = preg_replace('/[a-z_]+(\d+)[a-z_]+?$/', '$1', $cap );

					if ( ! is_numeric($col_id) ) {
						continue;
					}

					// check for tnc_col_all_* capabilities
					$all_collections_cap = preg_replace('/([a-z_]+)(\d+)([a-z_]+?)$/', '${1}all${3}', $cap );

					if (
							$user->has_cap('manage_tainacan_collection_' . $col_id) ||
							$user->has_cap('manage_tainacan_collection_all') ||
							$user->has_cap($all_collections_cap) ) {
						$allcaps = array_merge($allcaps, [ $cap => true ]);
					} else {
						// check if the user is the owner
						$collection = tainacan_collections()->fetch( (int) $col_id );
						if ( $collection instanceof \Tainacan\Entities\Collection ) {
							if ( $collection->get_author_id() == $user->ID ) {
								$allcaps = array_merge($allcaps, [ $cap => true ]);
							}
						}
					}

				}
			}
		}

		return $allcaps;


	}


	public function add_dependencies($role, $cap) {
		// convert cap name to the name declared in the roles of this class. tnc_col_12_edit or tnc_col_all_edit should become tnc_col_%d_edit
		$cap = $this->get_cap_generic_name($cap);

		if ( isset( $this->capabilities[$cap] ) && isset( $this->capabilities[$cap]['dependencies'] ) ) {
			$role = \get_role($role);
			if ( ! $role instanceof \WP_Role ) {
				return;
			}
			foreach ( $this->capabilities[$cap]['dependencies'] as $dep ) {
				$role->add_cap($dep);
			}
		}

	}

	public function map_meta_cap( $caps, $cap, $user_id, $args ) {
		$meta_caps = new \Tainacan\Entities\Metadatum();
		$meta_caps = $meta_caps->get_capabilities();

		$filters_caps = new \Tainacan\Entities\Filter();
		$filters_caps = $filters_caps->get_capabilities();

		$edit_meta = [
			$meta_caps->edit_posts,
			$meta_caps->edit_others_posts,
			$meta_caps->publish_posts,
			$meta_caps->delete_posts,
			$meta_caps->delete_private_posts,
			$meta_caps->delete_published_posts,
			$meta_caps->delete_others_posts,
			$meta_caps->edit_private_posts,
			$meta_caps->edit_published_posts,
			$meta_caps->create_posts,
		];

		$read_private_meta = [
			$meta_caps->read_private_posts
		];

		$edit_filters = [
			$filters_caps->edit_posts,
			$filters_caps->edit_others_posts,
			$filters_caps->publish_posts,
			$filters_caps->delete_posts,
			$filters_caps->delete_private_posts,
			$filters_caps->delete_published_posts,
			$filters_caps->delete_others_posts,
			$filters_caps->edit_private_posts,
			$filters_caps->edit_published_posts,
			$filters_caps->create_posts,
		];

		$read_private_filters = [
			$filters_caps->read_private_posts
		];

		if ( !is_array( $args ) || !array_key_exists( 0, $args ) ) {
			return $caps;
		}

		$object = $args[0];

		switch ($cap) {
			case 'edit_post':
			case 'delete_post':

				$action = \str_replace('_post', '', $cap);

				foreach ($caps as $i => $c) {

					// Handle edit metadata
					if ( in_array($c, $edit_meta) ) {
						if (is_numeric($object)) {
							$object = tainacan_metadata()->fetch ( (int) $object );
						}
						if ( $object instanceof \Tainacan\Entities\Metadatum ) {
							if ( $object->get_collection_id() == 'default' ) {
								unset($caps[$i]);
								$caps[] = 'tnc_rep_' . $action. '_metadata';
							} elseif ( is_numeric($object->get_collection_id()) ) {
								unset($caps[$i]);
								$caps[] = 'tnc_col_' . $object->get_collection_id() . '_' . $action. '_metadata';
							}
						}
					}


					// Handle edit filters
					if ( in_array($c, $edit_filters) ) {
						if (is_numeric($object)) {
							$object = tainacan_filters()->fetch ( (int) $object );
						}
						if ( $object instanceof \Tainacan\Entities\Filter ) {
							if ( $object->get_collection_id() == 'default' ) {
								unset($caps[$i]);
								$caps[] = 'tnc_rep_' . $action. '_filters';
							} elseif ( is_numeric($object->get_collection_id()) ) {
								unset($caps[$i]);
								$caps[] = 'tnc_col_' . $object->get_collection_id() . '_' . $action. '_filters';
							}
						}
					}

				}


				break;

			case 'read_post':

				foreach ($caps as $i => $c) {

					// Handle read private metadata
					if ( in_array($c, $read_private_meta) ) {
						if (is_numeric($object)) {
							$object = tainacan_metadata()->fetch ( (int) $object );
						}
						if ( $object instanceof \Tainacan\Entities\Metadatum ) {
							if ( $object->get_collection_id() == 'default' ) {
								unset($caps[$i]);
								$caps[] = 'tnc_rep_read_private_metadata';
							} elseif ( is_numeric($object->get_collection_id()) ) {
								unset($caps[$i]);
								$caps[] = 'tnc_col_' . $object->get_collection_id() . '_read_private_metadata';
							}
						}
					}

					// Handle read private filters
					if ( in_array($c, $read_private_filters) ) {
						if (is_numeric($object)) {
							$object = tainacan_filters()->fetch ( (int) $object );
						}
						if ( $object instanceof \Tainacan\Entities\Filter ) {
							if ( $object->get_collection_id() == 'default' ) {
								unset($caps[$i]);
								$caps[] = 'tnc_rep_read_private_filters';
							} elseif ( is_numeric($object->get_collection_id()) ) {
								unset($caps[$i]);
								$caps[] = 'tnc_col_' . $object->get_collection_id() . '_read_private_filters';
							}
						}
					}

				}


				break;

			default:
				# code...
				break;
		}

		return $caps;

	}


}
