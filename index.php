<?php 
/*
 Plugin Name: TS Lawyer pro Post Types
 Plugin URI: https://themeshopy.com
 Description: Creating new post type for TS Lawyer pro Pro Theme
 Author: ThemeShopy
 Version: 1.1
 Author URI: https://themeshopy.com
*/

define( 'TS_LAWYER_PRO_POSTTYPE_VERSION', '1.0' );

add_action( 'init', 'lawyer_pro_posttype_create_post_type' );

function lawyer_pro_posttype_create_post_type() {
	register_post_type( 'practice_area',
    array(
        'labels' => array(
            'name' => __( 'Practice Area','lawyer-pro-posttype' ),
            'singular_name' => __( 'Practice Area','lawyer-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
	);
  register_post_type( 'attorney',
    array(
        'labels' => array(
            'name' => __( 'Attorney','lawyer-pro-posttype' ),
            'singular_name' => __( 'Attorney','lawyer-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'testimonials',
	array(
		'labels' => array(
			'name' => __( 'Testimonials','lawyer-pro-posttype-pro' ),
			'singular_name' => __( 'Testimonials','lawyer-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-businessman',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
  register_post_type( 'faq',
	array(
		'labels' => array(
			'name' => __( 'Faq','lawyer-pro-posttype-pro' ),
			'singular_name' => __( 'Faq','lawyer-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-media-spreadsheet',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
}
// Serives section
function lawyer_pro_posttype_images_metabox_enqueue($hook) {
	if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
		wp_enqueue_script('lawyer-pro-posttype-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

		global $post;
		if ( $post ) {
			wp_enqueue_media( array(
					'post' => $post->ID,
				)
			);
		}

	}
}
add_action('admin_enqueue_scripts', 'lawyer_pro_posttype_images_metabox_enqueue');
// Practice Area Meta
function lawyer_pro_posttype_bn_custom_meta_practice_area() {

    add_meta_box( 'bn_meta', __( 'Practice Area Meta', 'lawyer-pro-posttype' ), 'lawyer_pro_posttype_bn_meta_callback_practice_area', 'practice_area', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
	add_action('admin_menu', 'lawyer_pro_posttype_bn_custom_meta_practice_area');
}

function lawyer_pro_posttype_bn_meta_callback_practice_area( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $practice_area = get_post_meta( $post->ID, 'meta-image', true );

    ?>
	<div id="property_stuff">
		<table id="list-table">			
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<p>
						<label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
						<input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo esc_attr( $practice_area ); ?>">
						<input type="button" class="button image-upload" value="Browse">
					</p>
					<div class="image-preview"><img src="<?php echo $bn_stored_meta['meta-image'][0]; ?>" style="max-width: 250px;"></div>
				</tr>
        
			</tbody>
		</table>
	</div>
	<?php
}

function lawyer_pro_posttype_bn_meta_save_practice_area( $post_id ) {

	if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	// Save Image
	if( isset( $_POST[ 'meta-image' ] ) ) {
	    update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
	}
  if( isset( $_POST[ 'meta-url' ] ) ) {
      update_post_meta( $post_id, 'meta-url', esc_url_raw($_POST[ 'meta-url' ]) );
  }
}
add_action( 'save_post', 'lawyer_pro_posttype_bn_meta_save_practice_area' );

/* Attorney */
function lawyer_pro_posttype_bn_designation_meta() {
    add_meta_box( 'lawyer_pro_posttype_bn_meta', __( 'Enter Details','lawyer-pro-posttype' ), 'lawyer_pro_posttype_bn_meta_callback', 'attorney', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'lawyer_pro_posttype_bn_designation_meta');
}
/* Adds a meta box for custom post */
function lawyer_pro_posttype_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'lawyer_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $meta_designation = get_post_meta( $post->ID, 'meta-designation', true );
    $meta_address = get_post_meta( $post->ID, 'meta-address', true );
    $meta_call = get_post_meta( $post->ID, 'meta-call', true );
    $meta_desig = get_post_meta( $post->ID, 'meta-desig', true );
    $meta_facebook = get_post_meta( $post->ID, 'meta-facebookurl', true );
    $meta_twit = get_post_meta( $post->ID, 'meta-twitterurl', true );
    $meta_gplus = get_post_meta( $post->ID, 'meta-googleplusurl', true );
    $meta_linkden = get_post_meta( $post->ID, 'meta-linkdenurl', true );
    $meta_biography = get_post_meta( $post->ID, 'meta-biography', true );
    ?>

    <div id="attorney_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                    <td class="left">
                        <?php esc_html_e( 'Email', 'lawyer-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-desig" id="meta-desig" value="<?php echo esc_attr($meta_desig); ?>"/>
                    </td>
                </tr>
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Phone Number', 'lawyer-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-call" id="meta-call" value="<?php echo esc_attr($meta_call); ?>"/>
                    </td>
                </tr>
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Address', 'lawyer-pro-posttype' )?>
                    </td>
                    <td class="left" >
                      <input type="text" name="meta-address" id="meta-address" value="<?php echo esc_attr($meta_address); ?>"/>
                    </td>
                </tr>
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Biography', 'lawyer-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-biography" id="meta-biography" value="<?php echo esc_attr($meta_biography); ?>"/>
                    </td>
                </tr>
                <tr id="meta-3">
                  <td class="left">
                    <?php esc_html_e( 'Facebook Url', 'lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_attr($meta_facebook); ?>"/>
                  </td><?php echo esc_html(get_post_meta($post->ID,'meta-facebookurl',true)); ?>
                </tr>
                <tr id="meta-4">
                  <td class="left">
                    <?php esc_html_e( 'Linkedin URL', 'lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-linkdenurl" id="meta-linkdenurl" value="<?php echo esc_attr($meta_linkden); ?>"/>
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php esc_html_e( 'Twitter Url', 'lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-twitterurl" id="meta-twitterurl"value="<?php echo esc_attr($meta_twit); ?>"/>
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php esc_html_e( 'GooglePlus URL', 'lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_attr($meta_gplus); ?>"/>
                  </td>
                </tr>
                <tr id="meta-7">
                  <td class="left">
                    <?php esc_html_e( 'Designation', 'lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_attr($meta_designation); ?>"/>
                  </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function lawyer_pro_posttype_bn_metadesig_attorney_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', sanitize_text_field($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', sanitize_text_field($_POST[ 'meta-call' ]) );
    }
    if( isset( $_POST[ 'meta-address' ] ) ) {
        update_post_meta( $post_id, 'meta-address', sanitize_text_field($_POST[ 'meta-address' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url_raw($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url_raw($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url_raw($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url_raw($_POST[ 'meta-googleplusurl' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', sanitize_text_field($_POST[ 'meta-designation' ]) );
    }
    if( isset( $_POST[ 'meta-attorney-url' ] ) ) {
        update_post_meta( $post_id, 'meta-attorney-url', esc_url_raw($_POST[ 'meta-attorney-url' ]) );
    }
    if( isset( $_POST[ 'meta-biography' ] ) ) {
        update_post_meta( $post_id, 'meta-biography',sanitize_textarea_field($_POST[ 'meta-biography' ]) );
    }
}
add_action( 'save_post', 'lawyer_pro_posttype_bn_metadesig_attorney_save' );

/* Attorney shorthcode */
function lawyer_pro_posttype_attorney_func( $atts ) {
    $attorney = ''; 
    $custom_url ='';
    $attorney = '<div class="row shot-att-sec">';
    $query = new WP_Query( array( 'post_type' => 'attorney' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=attorney'); 
    while ($new->have_posts()) : $new->the_post();
    	$post_id = get_the_ID();
    	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
		  $url = $thumb['0'];
      $excerpt = ts_lawyer_pro_string_limit_words(get_the_excerpt(),20);
      $designation= get_post_meta($post_id,'meta-designation',true);
      $call= get_post_meta($post_id,'meta-call',true);
      $email= get_post_meta($post_id,'meta-desig',true);
      $address= get_post_meta($post_id,'meta-address',true);
      $facebookurl= get_post_meta($post_id,'meta-facebookurl',true);
      $linkedin=get_post_meta($post_id,'meta-linkdenurl',true);
      $twitter=get_post_meta($post_id,'meta-twitterurl',true);
      $googleplus=get_post_meta($post_id,'meta-googleplusurl',true);
      $biography=get_post_meta($post_id,'meta-biography',true);
      if(get_post_meta($post_id,'meta-attorney-url',true !='')){$custom_url =get_post_meta($post_id,'meta-attorney-url',true); } else{ $custom_url = get_permalink(); }
      $attorney .= '
                <div class="attorneys_box col-lg-4 col-md-6 col-sm-6">
                  <?php if (has_post_thumbnail()){ ?>
                    <div class="image-box">
                      <div class="att-image-box"><img class="attorney-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" /></div>
                      <div class="attorneys-box w-100 float-left">
                        <h4 class="attorney_name"><a href="'.esc_url($custom_url).'">'.get_the_title().'</a></h4>
                        <p class="att-desig">'.esc_html($designation).'</p>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="content_box w-100 float-left">
                    <div class="short_text">'.$biography.'</div>
                    <div class="about-socialbox">
                      <div class="short-att-det">
                        <p> <i class="fas fa-map-marker-alt"></i>'.$address.'</p>
                        <p><i class="fas fa-phone"></i>'.$call.'</p>
                        <p><i class="far fa-envelope"></i>'.$email.'</p>
                      </div>
                      <div class="att_socialbox">';
                        if($facebookurl != ''){
                          $attorney .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                        } if($twitter != ''){
                          $attorney .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                        } if($googleplus != ''){
                          $attorney .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                        } if($linkedin != ''){
                          $attorney .= '<a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                        }
                      $attorney .= '</div>
                    </div>
                   
                  </div>
                </div>';
      if($k%2 == 0){
          $attorney.= '<div class="clearfix"></div>'; 
      } 
      $k++;         
  endwhile; 
  wp_reset_postdata();
  $attorney.= '</div>';
  else :
    $attorney = '<h2 class="center">'.esc_html_e('Not Found','lawyer-pro-posttype').'</h2>';
  endif;
  return $attorney;
}
add_shortcode( 'attorney', 'lawyer_pro_posttype_attorney_func' );

/* Testimonial section */
/* Adds a meta box to the Testimonial editing screen */
function lawyer_pro_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'lawyer-pro-posttype-pro-testimonial-meta', __( 'Enter Details', 'lawyer-pro-posttype-pro' ), 'lawyer_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'lawyer_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function lawyer_pro_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'lawyer_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
	$desigstory = get_post_meta( $post->ID, 'lawyer_pro_posttype_posttype_testimonial_desigstory', true );
	$company = get_post_meta( $post->ID, 'lawyer_pro_posttype_posttype_testimonial_company', true );
	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<td class="left">
						<?php esc_html_e( 'Designation', 'lawyer-pro-posttype-pro' )?>
					</td>
					<td class="left" >
						<input type="text" name="lawyer_pro_posttype_posttype_testimonial_desigstory" id="lawyer_pro_posttype_posttype_testimonial_desigstory" value="<?php echo esc_attr($desigstory); ?>" />
					</td>
				</tr>
        <tr id="meta-1">
          <td class="left">
            <?php esc_html_e( 'Company', 'lawyer-pro-posttype-pro' )?>
          </td>
          <td class="left" >
            <input type="text" name="lawyer_pro_posttype_posttype_testimonial_company" id="lawyer_pro_posttype_posttype_testimonial_company" value="<?php echo esc_attr( $company ); ?>" />
          </td>
        </tr>
       
			</tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function lawyer_pro_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['lawyer_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['lawyer_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'lawyer_pro_posttype_posttype_testimonial_desigstory' ] ) ) {
		update_post_meta( $post_id, 'lawyer_pro_posttype_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'lawyer_pro_posttype_posttype_testimonial_desigstory']) );
	}
  if( isset( $_POST[ 'lawyer_pro_posttype_posttype_testimonial_company' ] ) ) {
    update_post_meta( $post_id, 'lawyer_pro_posttype_posttype_testimonial_company', sanitize_text_field($_POST[ 'lawyer_pro_posttype_posttype_testimonial_company']) );
  }
  if( isset( $_POST[ 'meta-testimonial-url' ] ) ) {
    update_post_meta( $post_id, 'meta-testimonial-url', esc_url($_POST[ 'meta-testimonial-url']) );
  }

}

add_action( 'save_post', 'lawyer_pro_posttype_bn_metadesig_save' );

/* Testimonials shortcode */
function lawyer_pro_posttype_testimonial_func( $atts ) {
	$testimonial = '';
	$company='';
	$testimonial = '<div class="row">';
	$query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials');

	while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
      	$post_id = get_the_ID();
      	$excerpt = wp_trim_words(get_the_excerpt(),25);
      	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
		    if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
      	$desigstory= get_post_meta($post_id,'lawyer_pro_posttype_posttype_testimonial_desigstory',true);
	    	$company= get_post_meta($post_id,'lawyer_pro_posttype_posttype_testimonial_company',true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $testimonial .= '
          <div class="myTestimonial col-md-6 col-sm-12">
            <div class="testimonial_box w-100 mb-3">
              <div class="image-box media">
                <div class="testimonial-img"><img class="testi-img w-100 d-flex align-self-center mr-3" src="'.esc_url($thumb_url).'" alt="testimonial-thumbnail" /></div>
                <div class="content_box w-100">
                   <h4 class="testimonial_name mt-0"><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                    <p class="testimonial-desig">'.esc_html($desigstory).'&nbsp;'.esc_html($company).'</p>
                  <div class="short_text"><p>'.$excerpt.'</p></div>
                </div>
                <div class="testimonial-box media-body">
                 
                 
                </div>
              </div>
            </div>
          </div>';
		if($k%3 == 0){
			$testimonial.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$testimonial = '<h2 class="center">'.esc_html__('Post Not Found','lawyer-pro-posttype-pro').'</h2>';
  endif;
  $testimonial .= '</div>';
  return $testimonial;
}

add_shortcode( 'testimonials', 'lawyer_pro_posttype_testimonial_func' );

/* Practice Area shortcode */
function lawyer_pro_posttype_practice_area_func( $atts ) {
  $practice_area = '';
  $practice_area = '<div class="row">';
  $query = new WP_Query( array( 'post_type' => 'practice_area') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=practice_area');
  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $practice_area_image= get_post_meta(get_the_ID(), 'meta-image', true);
        if(get_post_meta($post_id,'meta-practice-area-url',true !='')){$custom_url =get_post_meta($post_id,'meta-practice-area-url',true); } else{ $custom_url = get_permalink(); }
        $practice_area .= '

            <div class="our_practice_area_outer col-md-6 col-sm-6">
              <div class="practice_area_inner">
                <div class="row hover_border">
                  <div class="col-md-3 pra-img-box">
                     <a href="'.esc_url($custom_url).'"><img src="'.esc_url($practice_area_image).'" class="pra-img"></a>
                  </div>
                  <div class="col-md-9">
                    <h4 class="mt-0 pra-title"> <a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                    <div class="short_text">'.$excerpt.'</div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $practice_area.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $practice_area = '<h2 class="center">'.esc_html__('Post Not Found','lawyer-pro-posttype-pro').'</h2>';
  endif;
  return $practice_area;
}

add_shortcode( 'list-practice-area', 'lawyer_pro_posttype_practice_area_func' );

/* Faq shortcode */
function lawyer_pro_posttype_faq_func( $atts ) {
  $faq = '';
  $faq = '<div id="accordion" class="row">';
  $query = new WP_Query( array( 'post_type' => 'faq') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=faq');

  while ($new->have_posts()) : $new->the_post();
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $desigstory= get_post_meta($post_id,'lawyer_pro_posttype_posttype_testimonial_desigstory',true);
        $faq .= '
        <div class="panel-group col-md-6 w-100 mb-3">
          <div class="panel">
            <div class="panel-heading">
            <h4 class="panel-title">
              <a href="#panelBody'.esc_attr($k).'" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"><i class="fas fa-plus"></i>'.get_the_title().' </a>
              </h4>
            </div>
            <div id="panelBody'.esc_attr($k).'" class="panel-collapse collapse in">
            <div class="panel-body">
                <p>'.get_the_content().'</p>
              </div>
            </div>
          </div>
          </div>';
    if($k%2 == 0){
      $faq.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $faq = '<h2 class="center">'.esc_html__('Post Not Found','lawyer-pro-posttype-pro').'</h2>';
  endif;
  $faq .= '</div>';
  return $faq;
}
add_shortcode( 'list-faq', 'lawyer_pro_posttype_faq_func' );