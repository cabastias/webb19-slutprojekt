<?php
/**
 Plugin Name: I heart it 
 Description: Like your favorite products.
 Author: Bruna & Cassandra
 Version: 1.0
 */

 /*------------------ CPT */

function create_i_heart_it(){
    register_post_type('heart_it',
        array(
            'labels' => array(
                'name' => 'I heart it',
                'singular_name' => 'heart',
            ),
            'public' => true,
            'has_archive' => true,
            'supports'=> array('title', 'editor', 'author', 'thumbnail')
        )
    );
}

/*-------------------- Meta Boxes */

function post_meta_box_price($item){
 printf('<input type="text" name="price" value="%s">',
        get_post_meta($item->ID, 'price', true));
}

function save_item($item_id){
    update_post_meta($item_id, 'price', $_POST['price']);
} 

function adding_the_meta_boxes_itens(){
    add_meta_box(
        "item_price",
        "Price",
        "post_meta_box_price",
        "heart_it"
    );
}

/*---------------------- Creating the database*/ 
function create_hearts_table(){
    global $wpdb; 

    $table_name = $wpdb->prefix . 'hearts_table';  // table name
    $post_table = $wpdb->prefix . "posts"; 
    $user_table = $wpdb->prefix . "users"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL auto_increment,
        owner_id BIGINT(20) UNSIGNED NOT NULL,
        post_id BIGINT(20) UNSIGNED NOT NULL, 
        PRIMARY KEY (id),
        FOREIGN KEY (owner_id) REFERENCES $user_table(ID),
        FOREIGN KEY (post_id) REFERENCES $post_table(ID)
        ) $charset_collate;";


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/*---------------------- Adding the likes to the database when the botton is clicked */

function adding_the_hearts(){
    
}


/*---------------------- Unlike botton*/

/*---------------------- Deactivate if uninstalled */
function remove_hearts(){
    global $wpdb;
    $table_name = $wpdb->prefix . "hearts_table";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");   
}


add_action('save_post', 'save_item');
add_action('init', 'create_i_heart_it');
add_action('add_meta_boxes', 'adding_the_meta_boxes_itens');

register_activation_hook(__FILE__, 'create_hearts_table'); 
register_deactivation_hook(__FILE__, 'remove_hearts');
register_uninstall_hook(__FILE__, 'remove_hearts');


