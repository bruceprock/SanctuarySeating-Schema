<?php
// create shortcode to list videos visually on video resource page with schema
 
add_shortcode( 'videos', 'videos_shortcode' );
function videos_shortcode( $atts ) {
    ob_start();
	 extract( shortcode_atts( array (
        'category' 	=> '',
	'quantity' 	=> -1,
	'p_id' 		=> ''
    ), $atts ) );
	
if ($p_id !=''){ 
	$query = new WP_Query( array(
	'post_type' 	=> 'post',
    	'posts_per_page'=> $quantity,
    	'order' 	=> 'dsc',
    	'orderby' 	=> 'date',
	'category_name' => $category,
	'p'		=> $p_id
	
    ) );
}
else
	$query = new WP_Query( array(
	'post_type' 	=> 'post',
    	'posts_per_page'=> $quantity,
	'order' 	=> 'dsc',
    	'orderby' 	=> 'date',
	'category_name' => $category	
    ) );

	 
    if ( $query->have_posts() ) { ?>
        <div class="videos" itemscope itemtype="http://schema.org/VideoGallery">
	<?php if ($category != ''){echo '<meta itemprop="name" content="' . ucwords(str_replace('-',' ',$category)) . '" />';}?>
        <script>$(document).ready(function(){$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});});</script>
        <?php 
	while ( $query->have_posts() ) : $query->the_post();
        	global $post;
		$videoValues 	= get_post_meta( $post->ID, '_yoast_wpseo_video_meta');			
		$videoValues 	= $videoValues[0];
		$v_duration	= $videoValues['duration'];
		$v_description 	= $videoValues['description'];
		$v_published 	= $videoValues['publication_date'];
		$v_thumb 	= $videoValues['thumbnail_loc'];
			
		$youtube 	= get_post_meta($post->ID,'youtube',true);
		$start  	= get_post_meta($post->ID,'youtube_start',true);?>
            
            <article itemprop="video" itemscope itemtype="http://schema.org/VideoObject" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            	<a class="youtube cboxElement" href="http://www.youtube.com/embed/<?php echo $youtube; ?>?<?php if ($start != ''){ echo ('start='. $start .'&'); } ?>rel=0&autoplay=1&wmode=transparent">
		<meta itemprop="duration" content="<?php echo $v_duration  ?>" />
  		<meta itemprop="thumbnailURL" content="<?php echo $v_thumb ?>" />
        	<meta itemprop="uploadDate" content="<?php echo $v_published ?>" />
            	<div class="youtubethumb" style="background-image: url(<?php echo $v_thumb ?>)">
            		<div class="playbutton"></div>
            	</div>
            	</a>
            	<h2><a itemprop="url" href="<?php the_permalink(); ?>"><span itemprop="name"><?php the_title(); ?></span></a></h2>
                <?php 	if (is_page(88) || is_page(8)){
				echo '<span itemprop="description">' . $v_description . '</span>';
				}
			else{
				echo '<meta itemprop="description" content="' . $v_description . '" />';
                  		}
		?>
            </article>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
    <?php $myvariable = ob_get_clean();
    return $myvariable;
    }
}

// fix video meta for image url on video posts
add_action( 'genesis_entry_header', 'default_featured_video_meta_when_not_set', 10, 1 );
function default_featured_video_meta_when_not_set( $post ) {
	global $post;
	$post_type = get_post_type( $post );
			
	if ($post_type === 'post') {
		$categories = get_the_category();
		$category = $categories[0];
		$cat_id = $category->cat_ID;
		}

		$videoValues 	= get_post_meta( $post->ID, '_yoast_wpseo_video_meta');			
		$videoValues 	= $videoValues[0];
		$v_thumb 	= $videoValues['thumbnail_loc'];
			
		if ($cat_id == 93 || $cat_id == 112 || $cat_id == 111 || $cat_id == 113 || $cat_id == 114 ) { 
		if ( '' != get_the_post_thumbnail() ){
			return $post;
		}
		else{ 
		if (is_single()){
		   echo '<meta itemprop="thumbnailURL" content="' . $v_thumb .'" />';
			}
		if (is_archive()){
		   echo '<a href="' . get_permalink ($post->id) . '" itemprop="url"><img itemprop="thumbnailURL" src="' . $v_thumb .'" /></a>';
			}
		}
	}
}

/**
 *Replace content-entry schema
 *
 */
 
remove_filter( 'genesis_attr_entry', 'genesis_attributes_entry' );
add_filter( 'genesis_attr_entry', 'my_schema_attributes_entry' );

function my_schema_attributes_entry( $attributes ) {
	
	if ( ! is_main_query() && ! genesis_is_blog_template() ) {
		return $attributes;
	}

	$post_type = get_post_type( $post );
			
	if ($post_type === 'post') {
		$categories = get_the_category();
		$category = $categories[0];
		$cat_id = $category->cat_ID;
		}
		
	if ($post_type === 'church-chairs' || $post_type === 'church-furniture') {
		$attributes['itemtype'] = 'http://schema.org/Product';
		$attributes['itemprop'] = '';
		$attributes['itemscope'] = 'itemscope';
	}
	if ($post_type === 'post') {
		if ($cat_id == 12){  
			$attributes['itemtype'] = 'http://schema.org/Review';
			$attributes['itemprop'] = 'review';
			$attributes['itemscope'] = 'itemscope';
		}
		if ($cat_id == 8 || $cat_id == 9 ) { 
			$attributes['itemtype'] = 'http://schema.org/TechArticle';
			$attributes['itemprop'] = '';
			$attributes['itemscope'] = 'itemscope';
		}
		if ($cat_id == 14) {
			$attributes['itemtype'] = 'http://schema.org/NewsArticle';
			$attributes['itemprop'] = '';
			$attributes['itemscope'] = 'itemscope';
			
		}
		if ($cat_id == 93 || $cat_id == 112 || $cat_id == 111 || $cat_id == 113 || $cat_id == 114 ) { 
			$attributes['itemtype'] = 'http://schema.org/VideoObject';
			$attributes['itemprop'] = 'video';
			$attributes['itemscope'] = 'itemscope';
		}

	}
	return $attributes;	
}

/**
 * Now Add Custom Micro Data To body in Specific Pages In Genesis
 */
 add_filter( 'genesis_attr_body', 'custom_microdata_schema' );

function custom_microdata_schema( $attr ){
	if( is_page( '8' ) ){ //  /church-chairs/
			$attr['itemtype'] = 'http://schema.org/SomeProducts';
	}
	if( is_page( '82' ) ){//  /about-bertolini/
		   $attr['itemtype'] = 'http://schema.org/AboutPage';
	}
	if( is_page( '102' ) ){//  /contact/
			$attr['itemtype'] = 'http://schema.org/ContactPage';
	}
	if( is_page( '88' ) ){//  /church-chair-video-resources/
			$attr['itemtype'] = 'http://schema.org/Webpage';
	}	
	if (is_single() || is_archive()){
		global $post;
		$post_type = get_post_type( $post );
		$categories = get_the_category();
		$category = $categories[0];
		$cat_id = $category->cat_ID;

	 	if ($post_type === 'church-chairs' || $post_type === 'church-furniture') {
			$attr['itemtype'] = 'http://schema.org/SomeProducts';
		}
		if ($cat_id == 12){  
			$attr['itemtype'] = 'http://schema.org/Webpage';
		}
		if ($cat_id == 8 || $cat_id == 9 || $cat_id == 14) { 
			$attr['itemtype'] = 'http://schema.org/CreativeWork';
		}
		if ($cat_id == 93 || $cat_id == 112 || $cat_id == 111 || $cat_id == 113 || $cat_id == 114 ) { 
			$attr['itemtype'] = 'http://schema.org/VideoGallery';
		}
	}
	return $attr;	
}

//fix various schema tags

add_filter( 'genesis_attr_content', 'yoast_schema_empty',20);
add_filter( 'genesis_attr_content', 'yoast_itemprop_description', 20);
add_filter( 'genesis_attr_entry-title', 'bertolini_itemprop_head', 20 );
add_filter( 'genesis_attr_entry-content', 'yoast_itemprop_description', 20 );
add_filter( 'genesis_post_title_output', 'yoast_title_link_schema', 20 );
add_filter( 'genesis_attr_entry-time', 'bertolini_itemprop_v_upload', 20 );

// Remove the schema markup from an element
function yoast_schema_empty( $attr ) {
    $attr['itemtype'] = '';
	$attr['itemprop'] = '';
	$attr['itemscope'] = '';
	return $attr;
}

// Set the itemprop of an element to description
function yoast_itemprop_description( $attr ) {
	$attr['itemprop'] = 'description';
	return $attr;
}

// Set the itemprop of an element 
function bertolini_itemprop_v_upload( $attr ) {
		$post_type = get_post_type( $post );
			
	if ($post_type === 'post') {
		$categories = get_the_category();
		$category = $categories[0];
		$cat_id = $category->cat_ID;
		}
		if ($cat_id == 93 || $cat_id == 112 || $cat_id == 111 || $cat_id == 113 || $cat_id == 114 ) { 
		$attr['itemprop'] = 'uploadDate';
	}
	return $attr;
}


// Set the itemprop of an element to headline or name
function bertolini_itemprop_head( $attr ) {
	$post_type = get_post_type( $post );
			
	if ($post_type === 'post') {
		$categories = get_the_category();
		$category = $categories[0];
		$cat_id = $category->cat_ID;
		}
		if ($cat_id == 8 || $cat_id == 9 || $cat_id == 14) { 
		$attr['itemprop'] = 'headline';
		}
	else{	$attr['itemprop'] = 'name';
		}
	return $attr;
}

// Remove the rel="author" and change it to itemprop="author" as the Structured Data Testing Tool doesn't understand 
// rel="author" in relation to Schema, even though it should according to the spec.
function yoast_author_schema( $output ) {
	return str_replace( 'rel="author"', 'itemprop="author"', $output );
}

// Add the url itemprop to the URL of the entry
function yoast_title_link_schema( $output ) {
	return str_replace( 'rel="bookmark"', 'rel="bookmark" itemprop="url"', $output );
}
