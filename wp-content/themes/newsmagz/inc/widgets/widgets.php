<?php


/************************************************************
Widget Name: Category Widget												
Description: The Widget will display your Category posts,in your sidebar .
Author: ThemePacific Team
Author URI: https://themepacific.com
************************************************************************************/
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'newsmagz_themepacific_recent_category_widget' );
 /**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */
 function newsmagz_themepacific_recent_category_widget() {
 	register_widget( 'newsmagz_themepacific_recent_category_widget' );
 }
/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class newsmagz_themepacific_recent_category_widget extends WP_Widget {
	
	public  function __construct() {
		$widget_ops = array('classname' => 'newsmagz_themepacific_recent_category_widget','description' => __( 'A Widget to dispaly Category Posts With Thumbs', 'newsmagz' ));
		parent::__construct('newsmagz-tpcrn-cat-posts-widget', __( 'newsmagz - Category Posts Widget', 'newsmagz' ), $widget_ops);	
	}



/**
* Display the widget
*/	
function widget( $args, $instance ) {
	extract($args);
	if ( ! empty( $instance['title'] ) ) {
		$title = $instance['title'];
	}else{

		$title = __('Recent Posts','newsmagz');
	}

	$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

	if ( ! empty( $instance['get_catego'] ) ) {
		$get_catego = $instance['get_catego'];
	}else{

		$get_catego = 'all';
	}
	if ( ! empty( $instance['getnumpost'] ) ) {
		$getnumpost = $instance['getnumpost'];
	}else{

		$getnumpost = 5;
	}
	

	/* Before widget (defined by themes). */

	/* Display the widget title if one was input (before and after defined by themes). */

	echo $args['before_widget'];


	if ( ! empty( $title ) ) {
			//echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		echo $args['before_title']; ?>
		<a href="<?php echo esc_url(get_category_link($get_catego)); ?>"> <?php echo esc_html( $title );?></a>
		<?php echo $args['after_title'];


	} ?>

	<div class="popular-rec">

		<!-- Begin category posts -->
		<ul class="sb-tabs-wrap">
			<?php  	global $post;$tpcrn_recent_cat_query = new WP_Query(array(
				'showposts' => $getnumpost,
				'cat' => $get_catego,
				'no_found_rows' => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				
			)); 
			while ( $tpcrn_recent_cat_query -> have_posts() ) : $tpcrn_recent_cat_query -> the_post(); ?>
			<li>
				<div class="sb-post-thumbnail">
					<?php if ( has_post_thumbnail() ) { ?>
							<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"

"><?php  
							$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'newsmagz_small_thumb_crop');   ?>
							<img src="<?php echo $image[0];?>">
						</a>
						<?php } else { ?>
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"

"><img  src="<?php echo get_template_directory_uri(); ?>/assets/images/default-image.jpg" width="60" height="60" alt="<?php the_title(); ?>" /></a>
						<?php } ?>
					</div>
					<div class="sb-post-list-title">
						<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"

" rel="bookmark"><?php the_title(); ?></a></h4>
						<div class="entry-meta">
							<span class="tp-post-item-date"> <?php echo get_the_date( ); ?></span>
						</div>
					</div>							
				</li>
			<?php endwhile; //wp_reset_query(); ?>
		</ul>
		<!-- End category posts -->

	</div>
	<!-- End  container -->
	<?php	
	/* After widget (defined by themes). */
	echo $args['after_widget'];	

}

/**
	 * Update the widget settings.
	 */	function update( $new_instance, $old_instance ) {
$instance = $old_instance;
$instance['title'] = sanitize_text_field($new_instance['title']);
$instance['get_catego'] = sanitize_text_field(absint($new_instance['get_catego']));
$instance['getnumpost'] = sanitize_text_field( absint($new_instance['getnumpost']));
return $instance;
}

	// Widget form

function form( $instance ) {

	$defaults = array( 'title' => __('Category Name', 'newsmagz'),'getnumpost' => '5','get_catego' => 'all');
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Enter category Name:', 'newsmagz'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
	</p>


	<p>
		<label for="<?php echo $this->get_field_id('getnumpost'); ?>"><?php _e('Number of Posts to Show:','newsmagz'); ?></label>
		<input id="<?php echo $this->get_field_id('getnumpost'); ?>" type="text" name="<?php echo $this->get_field_name('getnumpost'); ?>" value="<?php echo $instance['getnumpost'];?>"  maxlength="2" size="3" /> 
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('get_catego'); ?>">Filter by Category:</label> 
		<select id="<?php echo $this->get_field_id('get_catego'); ?>" name="<?php echo $this->get_field_name('get_catego'); ?>" class="widefat categories" style="width:100%;">
			<option value='all' <?php if ('all' == $instance['get_catego']) echo 'selected="selected"'; ?>>Select categories</option>
			<?php $get_catego = get_categories('hide_empty=0&depth=1&type=post');  
			foreach($get_catego as $category) { ?>
			<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['get_catego']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
			<?php } ?>
		</select>
	</p>
	<?php

}	

}


/*************************************************************************************
	Plugin Name: Social Network Icons Widget
	Description: It will display Social Nw Icons.
	Author: ThemePacific
	Author URI: https://themepacific.com					
	***************************************************************************/
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'newsmagz_themepacific_social_widget_box' );
/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 * 
 * @since 0.1
 */
function newsmagz_themepacific_social_widget_box() {
	register_widget( 'newsmagz_themepacific_social_widget' );
}
/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class newsmagz_themepacific_social_widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'tpcrn-social-icons-widget', 'description' => 'Display Social Icons' );
		$control_ops = array($control_ops = array('id_base' => 'newsmagz_themepacific_social_icons-widget'));
		parent::__construct('newsmagz-social-widget', __( 'NewsMagZ: Social Icons', 'newsmagz' ), $widget_ops,$control_ops);	

	}
	
	function widget( $args, $instance ) {
		extract( $args );
		if ( ! empty( $instance['title'] ) ) {
			$title = $instance['title'];
		}else{

			$title = __('Social Icons','newsmagz');
		}
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$fb = $instance['fb'];		 		
		$gp = $instance['gp'];		 		
		$rss = $instance['rss'];		 		
		$tw = $instance['tw'];		
		$in = $instance['in'];		
		$yt = $instance['yt'];		
		$fr = $instance['fr'];		
		/* Before widget (defined by themes). */
		echo $before_widget;
		if($title)
			echo $before_title . $title . $after_title;
		/* Display the widget title if one was input (before and after defined by themes). */ 		
		?>

		<div class="widget">
			<div class="social-icons">
				<?php
				$rss = esc_url(get_bloginfo('rss2_url')); 
				?>
				<?php if($rss){ ?>	
				<a   title="RSS Feed" class="newsmagz-rss" href="<?php echo esc_url($rss) ; ?>" > <i class="fa fa-feed"></i></a> 
				<?php } if($gp){ ?>
				<a  title="Google+" class="newsmagz-gp" href="<?php echo esc_url($gp); ?>" > <i class="fa fa-google-plus"></i></a> 
				<?php } if($fb){ ?>
				<a  title="Facebook" class="newsmagz-fb" href="<?php echo esc_url($fb); ?>" > <i class="fa fa-facebook"></i></a> 
				<?php } if($tw){ ?>
				<a  title="Twitter" class="newsmagz-tw" href="<?php echo esc_url($tw); ?>" > <i class="fa fa-twitter"></i></a> 
				<?php } if($yt){ ?>
				<a  title="YouTube" class="newsmagz-yt" href="<?php echo esc_url($yt); ?>" > <i class="fa fa-youtube"></i></a> 
				<?php } if($in){ ?>
				<a  title="LinkdeIn" class="newsmagz-in" href="<?php echo esc_url($in); ?>" > <i class="fa fa-linkedin"></i></a> 
				<?php } if($fr){ ?>
				<a  title="Flickr" class="newsmagz-fl" href="<?php echo esc_url($fr); ?>" > <i class="fa fa-flickr"></i></a> 
				<?php } ?>
			</div>
		</div>
		<?php	
		/* After widget (defined by themes). */
		echo $after_widget;		

	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['rss'] = sanitize_text_field( $new_instance['rss'] );
		$instance['fb'] = sanitize_text_field( $new_instance['fb'] );
		$instance['gp'] = sanitize_text_field( $new_instance['gp'] );
		$instance['tw'] = sanitize_text_field( $new_instance['tw'] );
		$instance['in'] = sanitize_text_field( $new_instance['in'] );
		$instance['yt'] = sanitize_text_field( $new_instance['yt'] );
		$instance['fr'] = sanitize_text_field( $new_instance['fr'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' =>__('Social' , 'newsmagz') , 'rss' =>'' , 'fb' =>'' , 'gp' =>'' , 'tw' =>'' , 'in' =>'' , 'yt' =>'' , 'fr' =>''  );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title :','newsmagz'); ?> </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'rss' ); ?>"><?php _e('RSS:','newsmagz'); ?> </label>
			<input id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" value="<?php echo $instance['rss']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fb' ); ?>"><?php _e('Facebook : ','newsmagz'); ?></label>
			<input id="<?php echo $this->get_field_id( 'fb' ); ?>" name="<?php echo $this->get_field_name( 'fb' ); ?>" value="<?php echo $instance['fb']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'gp' ); ?>"><?php _e('Google+ : ','newsmagz'); ?></label>
			<input id="<?php echo $this->get_field_id( 'gp' ); ?>" name="<?php echo $this->get_field_name( 'gp' ); ?>" value="<?php echo $instance['gp']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tw' ); ?>"><?php _e('Twitter :','newsmagz'); ?> </label>
			<input id="<?php echo $this->get_field_id( 'tw' ); ?>" name="<?php echo $this->get_field_name( 'tw' ); ?>" value="<?php echo $instance['tw']; ?>" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'in' ); ?>"><?php _e(' LinkedIn :','newsmagz'); ?> </label>
			<input id="<?php echo $this->get_field_id( 'in' ); ?>" name="<?php echo $this->get_field_name( 'in' ); ?>" value="<?php echo $instance['in']; ?>" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'yt' ); ?>"><?php _e('YouTube :','newsmagz'); ?> </label>
			<input id="<?php echo $this->get_field_id( 'yt' ); ?>" name="<?php echo $this->get_field_name( 'yt' ); ?>" value="<?php echo $instance['yt']; ?>" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'fr' ); ?>"><?php _e('Flickr :','newsmagz'); ?> </label>
			<input id="<?php echo $this->get_field_id( 'fr' ); ?>" name="<?php echo $this->get_field_name('fr'); ?>" value="<?php echo $instance['fr']; ?>" class="widefat" type="text" />
		</p>




		


		<?php
	}
}




?>
