<?php
/*
* Creating a function to create our CPT
*/
 
function furik_campaign_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Campaigns', 'Post Type General Name', 'furik' ),
        'singular_name'       => _x( 'Campaign', 'Post Type Singular Name', 'furik' ),
        'menu_name'           => __( 'Campaigns', 'furik' ),
        'parent_item_colon'   => __( 'Parent Campaign', 'furik' ),
        'all_items'           => __( 'All Campaigns', 'furik' ),
        'view_item'           => __( 'View Campaign', 'furik' ),
        'add_new_item'        => __( 'Add New Campaign', 'furik' ),
        'add_new'             => __( 'Add New', 'furik' ),
        'edit_item'           => __( 'Edit Campaign', 'furik' ),
        'update_item'         => __( 'Update Campaign', 'furik' ),
        'search_items'        => __( 'Search Campaign', 'furik' ),
        'not_found'           => __( 'Not Found', 'furik' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'furik' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Campaigns', 'furik' ),
        'description'         => __( 'Donation campaigns', 'furik' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields','page-attributes'),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'campaign', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'furik_campaign_post_type', 0 );