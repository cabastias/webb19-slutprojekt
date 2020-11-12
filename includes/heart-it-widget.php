<?php
/*

 Heart it widget

 */

class heart_it_widget extends WP_Widget{
 
    // Register widget with WordPress
    public function __construct() {
        parent::__construct(
            'my_heart_it_posts', // Base ID
            'My Heart It Posts', // Name
            array( 'description' => 'Displays a list of your liked posts', ) // Args
        );
    }
 
public function widget( $args, $instance ) {

   /*  extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] ); */

    echo $before_widget;
    global $wpdb;

    $results = $wpdb->get_results(
        "SELECT wp_hearts_table.post_id, wp_posts.post_title,           
        FROM wp_hearts_table 
        INNER JOIN wp_posts 
        ON wp_posts.ID = wp_hearts_table.post_id " . $instance['quantity']); 

    //Widget content output
    echo '<h4>My Heart It Posts &#10084;</h4>';
    
    if(!empty($instance['amount'])){

        echo "<ul>";
            foreach($results as $result){ 
                echo "<li>";
                ?>
                <a href="<?php echo get_permalink($result->post_id) ?>">
                <?php echo $result->post_title ?>
                </a>
                <?php
                echo "</li>";
            }
        echo "</ul>";
    }

    echo $after_widget;
}

// public function form( $instance ) { }
    //what is shown in the form in the backend
    
    
   /* ?>
    <p>
        <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php */
   
     
    /* public function update( $new_instance, $old_instance ) {
        $instance = array(); //all fields

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
 
        return $instance;
    } */
 
} 
 
