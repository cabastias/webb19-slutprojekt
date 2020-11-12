<?php
/**
 Plugin Name: I heart it 
 Description: Like your favorite products.
 Author: Bruna & Cassandra
 Version: 1.0
 */


 /*------------------ Custom Post Type */

function create_i_heart_it(){
    
    register_post_type('heart_it',
        array(
            'labels' => array(
                'name' => 'I heart it',
                'singular_name' => 'heart',
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' =>'dashicons-heart',
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
        "price",
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

/*---------------------- Creating the heart button */ 

function create_the_heart_button($content){
    global $wpdb;
    $table_name = $wpdb->prefix . 'hearts_table';
    if( is_singular() && in_the_loop() && is_main_query() ){
        $id = get_the_ID();
        $user_id = wp_get_current_user();

        $wpdb->get_results( "SELECT * FROM $table_name WHERE (owner_id = $user_id->ID AND post_id = $id)" );
        if($wpdb->num_rows == 0){
            return $content .
            // next: create a form and add style input type=submit? &#10084;
                        
            "<form method=POST id=\"heart-btn-form\">   
            <input type=hidden name=heart-btn value=$id>                     
            <button id=\"heart-btn\">&#10084;</button>                                   
            </form>";


        }else {
            /*when the button is clicked so this happens:  &#128540;*/
            return $content .
            "<form method=POST id=\"heart-btn-clicked\">   
            <input type=hidden name=heart-btn-clicked value=$id>         
            <button id=\"heart-btn-clicked\">&#128540;</button>                                    
            </form>"; 
        }  
    }
    return $content;
}

/*---------------------- Adding functionality to the heart button */

function heart_input() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'hearts_table';
    if(isset($_POST['heart-btn'])){
        $post_id = $_POST['heart-btn'];
        $user_id = get_current_user_id();

        $wpdb->query("INSERT INTO $table_name(owner_id, post_id) VALUES ($user_id, $post_id)");
    }
}

/*----------------------	Changin the heart icon
the heart changes to=> 128525	1F60D	 	SMILING FACE WITH HEART-SHAPED EYES*/



/*---------------------- IF THERE IS TIME: create Unlike/unheart botton*/

/*---------------------- Deactivate if uninstalled */

function remove_hearts(){
    global $wpdb;
    $table_name = $wpdb->prefix . "hearts_table";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");   
}


add_action('save_post', 'save_item');
add_action('init', 'create_i_heart_it');
add_action('add_meta_boxes', 'adding_the_meta_boxes_itens');


add_filter('the_content', 'create_the_heart_button'); 
add_action('init', 'heart_input');

register_activation_hook(__FILE__, 'create_hearts_table'); 
register_deactivation_hook(__FILE__, 'remove_hearts');
register_uninstall_hook(__FILE__, 'remove_hearts');

/*----------------------  Scripts */

function adding_the_heart_it_scripts() {
     
    wp_enqueue_style( 'style', plugins_url('assets/css/heart-it.css', __FILE__)); 
    wp_enqueue_script('js', plugins_url('assets/js/heart-it.js', __FILE__));
}

add_action( 'wp_enqueue_scripts', 'adding_the_heart_it_scripts' );

/*----------------------  Load class, register widget and hook */
require_once(plugin_dir_path(__FILE__).'includes/heart-it-widget.php');

function register_heart_it_widget(){
    register_widget('heart_it_widget');
}
add_action('widgets_init', 'register_heart_it_widget'); 
