<?php
/**
 * My Account
 */
 
global $woocommerce, $current_user, $recent_orders;
?>

<?php $woocommerce->show_messages(); ?>

<p><?php echo sprintf( __('Hello, <strong>%s</strong>. From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">change your password</a>.', 'woothemes'), $current_user->display_name, get_permalink(get_option('woocommerce_change_password_page_id'))); ?></p>

<?php do_action('woocommerce_before_my_account'); ?>

<?php if ($downloads = $woocommerce->customer->get_downloadable_products()) : ?>
<h2><?php _e('Available downloads', 'woothemes'); ?></h2>
<ul class="digital-downloads">
	<?php foreach ($downloads as $download) : ?>
		<li><?php if (is_numeric($download['downloads_remaining'])) : ?><span class="count"><?php echo $download['downloads_remaining'] . _n(' download Remaining', ' downloads Remaining', $download['downloads_remaining'], 'woothemes'); ?></span><?php endif; ?> <a href="<?php echo esc_url( $download['download_url'] ); ?>"><?php echo $download['download_name']; ?></a></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>	

<h2><?php _e('Recent Orders', 'woothemes'); ?></h2>
<?php
$customer_id = get_current_user_id();

$args = array(
    'numberposts'     => $recent_orders,
    'meta_key'        => '_customer_user',
    'meta_value'	  => $customer_id,
    'post_type'       => 'shop_order',
    'post_status'     => 'publish' 
);
$customer_orders = get_posts($args);
if ($customer_orders) :
?>
	<table class="shop_table my_account_orders">
	
		<thead>
			<tr>
				<th><span class="nobr"><?php _e('#', 'woothemes'); ?></span></th>
				<th><span class="nobr"><?php _e('Date', 'woothemes'); ?></span></th>
				<th><span class="nobr"><?php _e('Ship to', 'woothemes'); ?></span></th>
				<th><span class="nobr"><?php _e('Total', 'woothemes'); ?></span></th>
				<th colspan="2"><span class="nobr"><?php _e('Status', 'woothemes'); ?></span></th>
			</tr>
		</thead>
		
		<tbody><?php
			foreach ($customer_orders as $customer_order) :
				$order = &new woocommerce_order();
				$order->populate($customer_order);
				?><tr class="order">
					<td><?php echo $order->id; ?></td>
					<td><time title="<?php echo esc_attr( strtotime($order->order_date) ); ?>"><?php echo date(get_option('date_format'), strtotime($order->order_date)); ?></time></td>
					<td><address><?php if ($order->formatted_shipping_address) echo $order->formatted_shipping_address; else echo '&ndash;'; ?></address></td>
					<td><?php echo woocommerce_price($order->order_total); ?></td>
					<td><?php 
						$status = get_term_by('slug', $order->status, 'shop_order_status');
						echo __($status->name, 'woothemes'); 
					?></td>
					<td style="text-align:right; white-space:nowrap;">
						<?php if (in_array($order->status, array('pending', 'failed'))) : ?>
							<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e('Pay', 'woothemes'); ?></a>
							<a href="<?php echo esc_url( $order->get_cancel_order_url() ); ?>" class="button cancel"><?php _e('Cancel', 'woothemes'); ?></a>
						<?php endif; ?>
						<a href="<?php echo esc_url( add_query_arg('order', $order->id, get_permalink(get_option('woocommerce_view_order_page_id'))) ); ?>" class="button"><?php _e('View', 'woothemes'); ?></a>
					</td>
				</tr><?php
			endforeach;
		?></tbody>
	
	</table>
<?php
else : 
	_e('You have no recent orders.', 'woothemes');
endif;
?>

<h2><?php _e('My Addresses', 'woothemes'); ?></h2>	
<p><?php _e('The following addresses will be used on the checkout page by default.', 'woothemes'); ?></p>
<div class="col2-set addresses">

	<div class="col-1">
	
		<header class="title">				
			<h3><?php _e('Billing Address', 'woothemes'); ?></h3>
			<a href="<?php echo esc_url( add_query_arg('address', 'billing', get_permalink(get_option('woocommerce_edit_address_page_id'))) ); ?>" class="edit"><?php _e('Edit', 'woothemes'); ?></a>	
		</header>
		<address>
			<?php
				$address = array(
					'first_name' 	=> get_user_meta( $customer_id, 'billing_first_name', true ),
					'last_name'		=> get_user_meta( $customer_id, 'billing_last_name', true ),
					'company'		=> get_user_meta( $customer_id, 'billing_company', true ),
					'address_1'		=> get_user_meta( $customer_id, 'billing_address_1', true ),
					'address_2'		=> get_user_meta( $customer_id, 'billing_address_2', true ),
					'city'			=> get_user_meta( $customer_id, 'billing_city', true ),			
					'state'			=> get_user_meta( $customer_id, 'billing_state', true ),
					'postcode'		=> get_user_meta( $customer_id, 'billing_postcode', true ),
					'country'		=> get_user_meta( $customer_id, 'billing_country', true )
				);

				$formatted_address = $woocommerce->countries->get_formatted_address( $address );
				
				if (!$formatted_address) _e('You have not set up a billing address yet.', 'woothemes'); else echo $formatted_address;
			?>
		</address>
	
	</div><!-- /.col-1 -->
	
	<div class="col-2">
	
		<header class="title">
			<h3><?php _e('Shipping Address', 'woothemes'); ?></h3>
			<a href="<?php echo esc_url( add_query_arg('address', 'shipping', get_permalink(get_option('woocommerce_edit_address_page_id'))) ); ?>" class="edit"><?php _e('Edit', 'woothemes'); ?></a>
		</header>
		<address>
			<?php
				$address = array(
					'first_name' 	=> get_user_meta( $customer_id, 'shipping_first_name', true ),
					'last_name'		=> get_user_meta( $customer_id, 'shipping_last_name', true ),
					'company'		=> get_user_meta( $customer_id, 'shipping_company', true ),
					'address_1'		=> get_user_meta( $customer_id, 'shipping_address_1', true ),
					'address_2'		=> get_user_meta( $customer_id, 'shipping_address_2', true ),
					'city'			=> get_user_meta( $customer_id, 'shipping_city', true ),			
					'state'			=> get_user_meta( $customer_id, 'shipping_state', true ),
					'postcode'		=> get_user_meta( $customer_id, 'shipping_postcode', true ),
					'country'		=> get_user_meta( $customer_id, 'shipping_country', true )
				);

				$formatted_address = $woocommerce->countries->get_formatted_address( $address );
				
				if (!$formatted_address) _e('You have not set up a shipping address yet.', 'woothemes'); else echo $formatted_address;
			?>
		</address>
	
	</div><!-- /.col-2 -->

</div><!-- /.col2-set -->
<?php
do_action('woocommerce_after_my_account');