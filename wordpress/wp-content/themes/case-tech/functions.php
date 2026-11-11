<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function case_tech_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    register_nav_menus( array( 'primary' => __( 'Navegación principal', 'case-tech' ) ) );
}
add_action( 'after_setup_theme', 'case_tech_setup' );

function case_tech_assets() {
    wp_enqueue_style( 'case-tech-style', get_stylesheet_uri(), array(), '0.1.3' );
    wp_enqueue_script( 'case-tech-menu', get_theme_file_uri( 'assets/js/menu.js' ), array(), '0.1.0', true );
}
add_action( 'wp_enqueue_scripts', 'case_tech_assets' );

// woocommerce.php ya imprime el título del catálogo; evita el H1 duplicado.
add_filter( 'woocommerce_show_page_title', '__return_false' );

// La lógica vive en el plugin case-tech-custom; el tema solo tiene un respaldo mínimo.
function case_tech_shop_page_id() {
    if ( function_exists( 'case_tech_custom_shop_page_id' ) ) {
        return case_tech_custom_shop_page_id();
    }
    return function_exists( 'wc_get_page_id' ) ? max( 0, (int) wc_get_page_id( 'shop' ) ) : 0;
}

function case_tech_shop_url() {
    $shop_id = case_tech_shop_page_id();
    if ( $shop_id ) {
        $shop_url = get_permalink( $shop_id );
        if ( $shop_url && untrailingslashit( $shop_url ) !== untrailingslashit( home_url( '/' ) ) ) {
            return $shop_url;
        }
    }

    $archive_url = get_post_type_archive_link( 'product' );
    if ( $archive_url && untrailingslashit( $archive_url ) !== untrailingslashit( home_url( '/' ) ) ) {
        return $archive_url;
    }

    $shop_page = get_page_by_path( 'shop', OBJECT, 'page' );
    if ( $shop_page ) {
        return get_permalink( $shop_page );
    }

    return home_url( '/shop/' );
}

// La marca es la categoría hija de "cases" (la crea el importador).
function case_tech_product_brand( $product_id ) {
    $terms = get_the_terms( $product_id, 'product_cat' );
    foreach ( is_array( $terms ) ? $terms : array() as $term ) {
        if ( 'cases' !== $term->slug ) {
            return $term->name;
        }
    }
    return 'Case-Tech';
}

function case_tech_cart_url() {
    return function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
}

function case_tech_product_search_form() {
    if ( function_exists( 'get_product_search_form' ) ) {
        get_product_search_form();
        return;
    }
    get_search_form();
}

function case_tech_brand_url( $brand ) {
    if ( taxonomy_exists( 'product_cat' ) ) {
        $term = get_term_by( 'name', $brand, 'product_cat' );
        if ( $term && ! is_wp_error( $term ) ) {
            $url = get_term_link( $term, 'product_cat' );
            if ( ! is_wp_error( $url ) ) {
                return $url;
            }
        }
    }

    return add_query_arg(
        array(
            'post_type' => 'product',
            's'         => $brand,
        ),
        case_tech_shop_url()
    );
}

function case_tech_page_url( $slug ) {
    $page = get_page_by_path( sanitize_title( $slug ), OBJECT, 'page' );
    return $page ? get_permalink( $page ) : home_url( '/' . trim( $slug, '/' ) . '/' );
}
