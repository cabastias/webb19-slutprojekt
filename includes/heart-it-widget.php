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
        global $wpdb;
        $user_id = get_current_user_id();
        $id = get_the_ID();
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $before_widget;
        //Sql injection 
        $outcomes = $wpdb->get_results(
            "SELECT wp_hearts_table.post_id, 
            wp_hearts_table.owner_id, 
            wp_posts.post_title          
            FROM wp_hearts_table 
            INNER JOIN wp_posts 
            ON wp_posts.ID = wp_hearts_table.post_id 
            WHERE wp_hearts_table.owner_id = $user_id" ); 
     
        //Widget content output
        echo '<h4>My Heart It Posts &#10084;</h4>';
        //Filter posts by owner_id/current_user
        if(!empty($instance['amount'])){
            //list of posts
            echo "<ul>";
                foreach($outcomes as $outcome){ 
                    echo "<li>";
                    ?>
                    <a href="<?php echo get_permalink($outcome->post_id) ?>">
                    <?php echo $outcome->post_title ?>
                    </a>
                    <?php
                    echo "</li>";
                }
            echo "</ul>";
        }

        echo $after_widget;
    }

     public function form( $instance ) { 
          //what shows in the form in the backend
          ?>
          <h4>Number of posts to show</h4>    
          <?php   
           printf('<input type="number" name="%s" value="%s" placeholder="Choose how many posts"></input>', $this->get_field_name("amount"), $instance['amount']);
     }
     //saves the value to be displayed
     public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['amount'] = ( ! empty( $new_instance['amount'] ) ) ? sanitize_text_field( $new_instance['amount'] ) : '';

		return $instance;
	}
        
}

