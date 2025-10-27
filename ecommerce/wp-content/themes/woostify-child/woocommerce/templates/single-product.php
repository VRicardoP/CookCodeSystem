<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>



			
			
			<?php
				global $product;
				$product = wc_get_product(get_the_ID());
				var_dump($product);

				// Verifica si el producto está en stock antes de mostrar el botón "Añadir al carrito"
				/** */

				/** */
				if ( $product->is_in_stock() ) {
				    echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
				        sprintf(  '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s" style="display: block !important;">%s</a>',
				            esc_url( $product->add_to_cart_url() ),
				            esc_attr( $product->get_id() ),
				            esc_attr( $product->get_sku() ),
				            $product->is_purchasable() ? 'add_to_cart_button' : '',
				            esc_html( $product->add_to_cart_text() )
				        ),
				    $product );
				}
			?>
			
		<?php endwhile; // end of the loop. ?>

	

		
	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php
get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
