<?php 


// Register Custom Parceiro
function product_warranty() {

	$labels = array(
		'name'                  => _x( 'Página de Contato', 'Página de Contato General Name', 'product-warranty' ),
		'singular_name'         => _x( 'Página de Contato', 'Página de Contato Singular Name', 'product-warranty' ),
		'menu_name'             => __( 'Página de Contato', 'product-warranty' ),
		'name_admin_bar'        => __( 'Página de Contato', 'product-warranty' ),
		'archives'              => __( 'Item Archives', 'product-warranty' ),
		'attributes'            => __( 'Item Attributes', 'product-warranty' ),
		'parent_item_colon'     => __( 'Parent Item:', 'product-warranty' ),
		'all_items'             => __( 'All Items', 'product-warranty' ),
		'add_new_item'          => __( 'Add New Item', 'product-warranty' ),
		'add_new'               => __( 'Add New', 'product-warranty' ),
		'new_item'              => __( 'New Item', 'product-warranty' ),
		'edit_item'             => __( 'Edit Item', 'product-warranty' ),
		'update_item'           => __( 'Update Item', 'product-warranty' ),
		'view_item'             => __( 'View Item', 'product-warranty' ),
		'view_items'            => __( 'View Items', 'product-warranty' ),
		'search_items'          => __( 'Search Item', 'product-warranty' ),
		'not_found'             => __( 'Not found', 'product-warranty' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'product-warranty' ),
		'featured_image'        => __( 'Featured Image', 'product-warranty' ),
		'set_featured_image'    => __( 'Set featured image', 'product-warranty' ),
		'remove_featured_image' => __( 'Remove featured image', 'product-warranty' ),
		'use_featured_image'    => __( 'Use as featured image', 'product-warranty' ),
		'insert_into_item'      => __( 'Insert into item', 'product-warranty' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'product-warranty' ),
		'items_list'            => __( 'Items list', 'product-warranty' ),
		'items_list_navigation' => __( 'Items list navigation', 'product-warranty' ),
		'filter_items_list'     => __( 'Filter items list', 'product-warranty' ),
	);
	$args = array(
		'label'                 => __( 'Página de Contato', 'product-warranty' ),
		'description'           => __( 'Página de Contato Description', 'product-warranty' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail','page-attributes' ),
		'taxonomies'            => array( 'product-warranty-category' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 21,
		'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'menu_icon'             =>'dashicons-yes-alt',
		'can_export'            => true,
        'has_archive'           => true,
        //'rewrite'               => array('slug'=>'/','with_front'=>false),
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'product-warranty', $args );

}//end function
//add_action( 'init', 'product_warranty', 0 );

























//CaMPOS PERSONALIZADOS
function get_meta_box( $meta_boxes ) {
	$prefix = 'pw-';

	$meta_boxes[] = array(
		'id' => 'geral',
		'title' => esc_html__( 'Informações Gerais', 'product-warranty' ),
		'post_types' => array('product-warranty' ),
		'context' => 'advanced',
		'priority' => 'default',
		'autosave' => 'true',
		'fields' => array(
			array(
				'id' => $prefix . 'vip',
				'name' => esc_html__( 'Usuário VIP', 'product-warranty' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Este parceiro é VIP ou não?', 'product-warranty' ),
			),

			array(
				'id' => $prefix . 'endereco',
				'type' => 'textarea',
				'name' => esc_html__( 'Endereço', 'product-warranty' ),
				'desc' => esc_html__( 'Endereço do Parceiro', 'product-warranty' ),
				'placeholder' => esc_html__( 'Endereço do Parceiro', 'product-warranty' ),
				'rows' => 4,
				'cols' => 1,
			),

			array(
				'id' => $prefix . 'cidade',
				'type' => 'text',
				'name' => esc_html__( 'Cidade', 'product-warranty' ),
				'desc' => esc_html__( 'Cidade do Parceiro', 'product-warranty' ),
				'placeholder' => esc_html__( 'Cidade do Parceiro', 'product-warranty' ),
				'size' => 40,
			),
			array(
				'id' => $prefix . 'email',
				'name' => esc_html__( 'Email', 'product-warranty' ),
				'type' => 'email',
				'desc' => esc_html__( 'Email do Parceiro', 'product-warranty' ),
				'placeholder' => esc_html__( 'Email do Parceiro', 'product-warranty' ),
				'clone' => 'true',
				'size' => 40,
				'sort_clone' => 'true',
				'max_clone' => 20,
			),

			array(
				'id' => $prefix . 'telefone',
				'type' => 'text',
				'name' => esc_html__( 'Telefone', 'product-warranty' ),
				'desc' => esc_html__( 'Telefone do Parceiro', 'product-warranty' ),
				'placeholder' => esc_html__( 'Telefone do Parceiro', 'product-warranty' ),
				'size' => 40,
				'clone' => 'true',
				'max_clone' => 20,
				'sort_clone' => 'true',
			),

		),

		'validation' => array(
			'rules'  => array(
				'pw-endereco' => array(
					'required'  => true,
				),
				'pw-cidade' => array(
					'required'  => true,
				),
				'pw-email[0]' => array(
					'required'  => true,
					'email'=>true,
				),
				
				// Rules for other fields
			),
		),
		


	);




	$meta_boxes[] = array(
		'id' => 'imagem',
		'title' => esc_html__( 'Imagem', 'product-warranty' ),
		'post_types' => array('product-warranty' ),
		'context' => 'advanced',
		'priority' => 'default',
		'autosave' => 'true',
		'fields' => array(
			array(
				'id' => $prefix . 'imagem',
				'type' => 'image_advanced',
				'name' => esc_html__( 'Galeria de Imagens', 'product-warranty' ),
				'desc' => esc_html__( 'Galeria de Imagens', 'product-warranty' ),
				'max_file_uploads' => '20',
				'force_delete' => 'true',
			),			

		),
	);

	return $meta_boxes;
}
//add_filter( 'rwmb_meta_boxes', 'get_meta_box' );





























// Register Custom Taxonomy
function product_warranty_category() {

	$labels = array(
		'name'                       => _x( 'Categoria de Produto', 'Categoria de Produto General Name', 'product-warranty' ),
		'singular_name'              => _x( 'Categoria de Produto', 'Categoria de Produto Singular Name', 'product-warranty' ),
		'menu_name'                  => __( 'Categoria de Produto', 'product-warranty' ),
		'all_items'                  => __( 'All Items', 'product-warranty' ),
		'parent_item'                => __( 'Parent Item', 'product-warranty' ),
		'parent_item_colon'          => __( 'Parent Item:', 'product-warranty' ),
		'new_item_name'              => __( 'New Item Name', 'product-warranty' ),
		'add_new_item'               => __( 'Add New Item', 'product-warranty' ),
		'edit_item'                  => __( 'Edit Item', 'product-warranty' ),
		'update_item'                => __( 'Update Item', 'product-warranty' ),
		'view_item'                  => __( 'View Item', 'product-warranty' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'product-warranty' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'product-warranty' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'product-warranty' ),
		'popular_items'              => __( 'Popular Items', 'product-warranty' ),
		'search_items'               => __( 'Search Items', 'product-warranty' ),
		'not_found'                  => __( 'Not Found', 'product-warranty' ),
		'no_terms'                   => __( 'No items', 'product-warranty' ),
		'items_list'                 => __( 'Items list', 'product-warranty' ),
		'items_list_navigation'      => __( 'Items list navigation', 'product-warranty' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'product-warranty-category', array( 'product-warranty' ), $args );

}
//add_action( 'init', 'product_warranty_category', 0 );












?>