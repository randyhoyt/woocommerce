<?php
/**
 * Loop Add to Cart
 */
 
global $post, $product; 

if( $product->get_price() === '' && $product->product_type!=='external') return;
?>

<?php if (!$product->is_in_stock()) : ?>
		
	<a href="<?php echo get_permalink($post->ID); ?>" class="button"><?php echo apply_filters('out_of_stock_add_to_cart_text', __('Read More', 'woothemes')); ?></a>

<?php 
else :
		
	switch ($product->product_type) :
		case "variable" :
			$link 	= get_permalink($post->ID);
			$label 	= apply_filters('variable_add_to_cart_text', __('Select options', 'woothemes'));
		break;
		case "grouped" :
			$link 	= get_permalink($post->ID);
			$label 	= apply_filters('grouped_add_to_cart_text', __('View options', 'woothemes'));
		break;
		case "external" :
			$link 	= get_permalink($post->ID);
			$label 	= apply_filters('external_add_to_cart_text', __('Read More', 'woothemes'));
		break;
		default :
			$link 	= esc_url( $product->add_to_cart_url() );
			$label 	= apply_filters('add_to_cart_text', __('Add to cart', 'woothemes'));
		break;
	endswitch;

	echo sprintf('<a href="%s" data-product_id="%s" class="button add_to_cart_button product_type_%s">%s</a>', $link, $product->id, $product->product_type, $label);

endif; 
?>