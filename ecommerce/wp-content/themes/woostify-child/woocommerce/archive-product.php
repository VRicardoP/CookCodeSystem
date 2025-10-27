<?php
/**
 * Plantilla personalizada para mostrar archivos de productos (tienda, categorías, etiquetas)
 *
 * Copiar a tu tema hijo en: woocommerce/archive-product.php
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );
?>

<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<div id="intro" class="mi-banner-productos">
  <h2>DISCOVER A WORLD OF FLAVORS</h2>
</div>

<?php if ( woocommerce_product_loop() ) : ?>

	<?php do_action( 'woocommerce_before_shop_loop' ); ?>

	
		<ul class="products columns-5 tablet-columns-2 mobile-columns-1">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php do_action( 'woocommerce_shop_loop' ); ?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
			<?php endwhile; ?>
		</ul>

		<?php do_action( 'woocommerce_after_shop_loop' ); ?>
	

<?php else : ?>
	<?php do_action( 'woocommerce_no_products_found' ); ?>
<?php endif; ?>

<?php
do_action( 'woocommerce_after_main_content' );
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
