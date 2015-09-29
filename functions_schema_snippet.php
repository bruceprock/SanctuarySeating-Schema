//Add Custom Micro Data To Specific Pages In Genesis using Yoast Genesis Schema Helper Script
add_filter( 'genesis_attr_body', 'custom_microdata_schema' );

function custom_microdata_schema( $attr ){
	if( is_front_page() ){
    add_filter( 'genesis_attr_entry', 'yoast_schema_product', 20 );    
		add_filter( 'genesis_attr_entry-title', 'yoast_itemprop_name', 20 );
		add_filter( 'genesis_attr_entry-content', 'yoast_itemprop_description', 20 );
		add_filter( 'genesis_post_title_output', 'yoast_title_link_schema', 20 ); 				
	}
  if( is_page( '8' ) ){
    $attr['itemtype'] = 'http://schema.org/SomeProducts';
		add_filter( 'genesis_attr_entry', 'yoast_schema_product', 20 );    
		add_filter( 'genesis_attr_content', 'yoast_schema_empty',20);
		add_filter( 'genesis_attr_content', 'yoast_itemprop_description', 20);
		add_filter( 'genesis_attr_entry-title', 'yoast_itemprop_name', 20 );
		add_filter( 'genesis_attr_entry-content', 'yoast_itemprop_description', 20 );
		add_filter( 'genesis_post_title_output', 'yoast_title_link_schema', 20 ); 				
	}
  if( is_page( '82' ) ){
    $attr['itemtype'] = 'http://schema.org/AboutPage';
	}
  if( is_page( '102' ) ){
    $attr['itemtype'] = 'http://schema.org/ContactPage';
	}
	$post_type = get_post_type( $post );
	if ($post_type === 'church-chairs' || $post_type === 'church-furniture'){
		if (is_single){
			$attr['itemtype'] = 'http://schema.org/SomeProducts';
			add_filter( 'genesis_attr_entry', 'yoast_schema_product', 20 );    
			add_filter( 'genesis_attr_entry-title', 'yoast_itemprop_name', 20 );
			add_filter( 'genesis_attr_entry-content', 'yoast_itemprop_description', 20 );
			add_filter( 'genesis_post_title_output', 'yoast_title_link_schema', 20 );
		} 				
		if(is_archive()){
			$attr['itemtype'] = 'http://schema.org/SomeProducts';
			add_filter( 'genesis_attr_entry', 'yoast_schema_product', 20 );    
			add_filter( 'genesis_attr_content', 'yoast_schema_empty',20);
			add_filter( 'genesis_attr_content', 'yoast_itemprop_description', 20);
			add_filter( 'genesis_attr_entry-title', 'yoast_itemprop_name', 20 );
			add_filter( 'genesis_attr_entry-content', 'yoast_itemprop_description', 20 );
			add_filter( 'genesis_post_title_output', 'yoast_title_link_schema', 20 );
		}
	}
	if ($post_type === 'post') {
		$categories = get_the_category();
		$category = $categories[0]->name;
		if ($category === 'What Customers Say'){
			$attr['itemtype'] = 'http://schema.org/Webpage';
			add_filter( 'genesis_attr_entry', 'yoast_schema_review', 20 );    
			add_filter( 'genesis_attr_content', 'yoast_schema_empty',20);
			add_filter( 'genesis_attr_content', 'yoast_itemprop_description', 20);
			add_filter( 'genesis_attr_entry-title', 'yoast_itemprop_name', 20 );
			add_filter( 'genesis_attr_entry-content', 'yoast_itemprop_description', 20 );
			add_filter( 'genesis_post_title_output', 'yoast_title_link_schema', 20 );
		}
	}
	return $attr;     
}

require('genesis-schema-helper-functions.php');
