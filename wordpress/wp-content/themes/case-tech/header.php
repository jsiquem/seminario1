<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Saltar al contenido</a>
<div class="site-shell">
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Case-Tech, inicio">
            <img class="brand-mark" src="<?php echo esc_url( get_theme_file_uri( 'case-tech-logo-concept.png' ) ); ?>" alt="">
            <span class="brand-word">case<span>tech</span></span>
        </a>
        <nav class="main-nav" id="primary-navigation" aria-label="Navegación principal">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a>
            <a href="<?php echo esc_url( case_tech_shop_url() ); ?>">Tienda</a>
            <a href="<?php echo esc_url( home_url( '/#marcas' ) ); ?>">Marcas</a>
            <a href="<?php echo esc_url( home_url( '/#confianza' ) ); ?>">Por qué Case-Tech</a>
        </nav>
        <div class="header-search">
            <?php case_tech_product_search_form(); ?>
        </div>
        <div class="header-actions">
            <a class="cart-link" href="<?php echo esc_url( case_tech_cart_url() ); ?>">Carrito<?php echo function_exists( 'WC' ) && WC()->cart ? ' · ' . esc_html( WC()->cart->get_cart_contents_count() ) : ''; ?></a>
            <button class="menu-toggle" type="button" aria-controls="primary-navigation" aria-expanded="false" aria-label="Abrir menú">☰</button>
        </div>
    </div>
</header>
