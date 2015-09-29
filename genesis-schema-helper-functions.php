<?php

// Remove the schema markup from an element
function yoast_schema_empty( $attr ) {
    	$attr['itemtype'] = '';
		$attr['itemprop'] = '';
		$attr['itemscope'] = '';
	return $attr;
}

// Change the schema type of an element to Product
function yoast_schema_product( $attr ) {
		$attr['itemtype'] = 'http://schema.org/Product';
		$attr['itemprop'] = '';
		$attr['itemscope'] = 'itemscope';
	return $attr;
	}


// Change the schema type of an element to Event
function yoast_schema_event( $attr ) {
		$attr['itemtype'] = 'http://schema.org/Event';
		$attr['itemprop'] = '';
		$attr['itemscope'] = 'itemscope';
	return $attr;
}

// Change the schema type of an element to Review
// Make sure the itemprop is set to review so this can be used in conjunction with Event or Product
function yoast_schema_review( $attr ) {
		$attr['itemtype'] = 'http://schema.org/Review';
		$attr['itemprop'] = 'review';
		$attr['itemscope'] = 'itemscope';
	return $attr;
}

// Set the itemprop of an element to description
function yoast_itemprop_description( $attr ) {
		$attr['itemprop'] = 'description';
	return $attr;
}

// Set the itemprop of an element to name
function yoast_itemprop_name( $attr ) {
		$attr['itemprop'] = 'name';
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
