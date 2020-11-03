<?php
/**
 Plugin Name: Shopping Basket
 Description: Order your favorite products.
 Author: Bruna and Cassandra
 */

function create_shopping_basket(){
    register_post_type('shopping_basket',
        array(
            'labels' => array(
                'name' => 'Shopping basket',
                'singular_name' => 'item',
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
}

function post_meta_box_price($item){
    printf('<input type="text" name="price" value="%s">',
        get_post_meta($item->ID, 'price', true)
    );
}

function save_item($item_id){
    update_post_meta($item_id, 'price', $_POST['price']);
}

function adding_the_meta_boxes_itens(){
    add_meta_box(
        "item_price",
        "Price",
        "post_meta_box_price",
        "shopping_basket"
    );
}

add_action('save_post', 'save_item');
add_action('init', 'create_shopping_basket');
add_action('add_meta_boxes', 'adding_the_meta_boxes_itens');


