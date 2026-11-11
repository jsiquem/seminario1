<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?><main id="main-content" class="page-content"><div class="container"><span class="eyebrow">Error 404</span><h1 class="section-title">No encontramos esta página.</h1><div class="entry-content"><p>Puede que el enlace haya cambiado o que el producto ya no esté disponible. Busca tu modelo o vuelve a la tienda.</p><div class="hero-search"><?php case_tech_product_search_form(); ?></div><p class="button-row"><a class="button button-primary" href="<?php echo esc_url( case_tech_shop_url() ); ?>">Ver la tienda</a></p></div></div></main><?php get_footer(); ?>
