<?php
/**
 * Adds last_video_Widget widget.
 */
class last_video_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'last_video_widget', // Base ID
			__( 'Последние записи', 'text_domain' ), // Name
			array( 'description' => __( 'Выводит последние записи', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		function last_video_w(){
			
			$VideoPage = new WP_Query( array('post_type' => 'VideoPage', 'posts_per_page' => 5, 'paged' => get_query_var('paged'), 'order' => 'DESC') );

			if( $VideoPage->have_posts() ) :
				while( $VideoPage->have_posts() ) : $VideoPage->the_post(); ?>
					
					<div class="mx-widget_wrap">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php the_post_thumbnail( 'full' ); ?>
						</a>					
					</div>

				<?php endwhile;
			else : ?>
			<h2>Нет записей</h2>
			
			<?php endif;

		}

		echo __( last_video_w(), 'text_domain' );
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Последние записи', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class last_video_Widget

// register last_video_Widget widget
function register_last_video_widget() {
    register_widget( 'last_video_Widget' );
}
add_action( 'widgets_init', 'register_last_video_widget' );