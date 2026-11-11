<?php
/**
 * Configura la base comercial local de Case-Tech.
 * Ejecutar dentro de wpcli:
 * wp eval-file /var/www/html/wp-content/plugins/case-tech-custom/configure-store.php
 */

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'WooCommerce' ) ) {
    exit;
}

update_option( 'woocommerce_default_country', 'GT' );
update_option( 'woocommerce_currency', 'GTQ' );
update_option( 'woocommerce_currency_pos', 'left' );
update_option( 'woocommerce_price_thousand_sep', ',' );
update_option( 'woocommerce_price_decimal_sep', '.' );
update_option( 'woocommerce_price_num_decimals', '2' );
update_option( 'woocommerce_allowed_countries', 'specific' );
update_option( 'woocommerce_specific_allowed_countries', array( 'GT' ) );
update_option( 'woocommerce_ship_to_countries', 'specific' );
update_option( 'woocommerce_specific_ship_to_countries', array( 'GT' ) );
update_option( 'woocommerce_store_address', 'Ciudad de Guatemala' );
update_option( 'woocommerce_store_city', 'Guatemala' );
update_option( 'woocommerce_store_postcode', '01000' );

$bacs_settings = array(
    'enabled'      => 'yes',
    'title'        => 'Transferencia bancaria directa',
    'description'  => 'Realiza la transferencia y conserva tu comprobante para confirmar el pedido.',
    'instructions' => 'Datos demo: BAM — cuenta de prueba 0000-000000-0. No realizar pagos reales con esta información.',
    'account_name' => 'Case-Tech Demo',
    'account_number' => '0000-000000-0',
    'sort_code'    => '',
    'iban'         => '',
    'bic'          => '',
);
update_option( 'woocommerce_bacs_settings', $bacs_settings );
// La tabla de "Nuestros datos bancarios" de la confirmación y del correo lee esta opción.
update_option(
    'woocommerce_bacs_accounts',
    array(
        array(
            'account_name'   => 'Case-Tech Demo',
            'account_number' => '0000-000000-0',
            'bank_name'      => 'BAM (cuenta demo)',
            'sort_code'      => '',
            'iban'           => '',
            'bic'            => '',
        ),
    )
);

// Checkout: teléfono obligatorio (se valida en el plugin) y sin empresa.
update_option( 'woocommerce_checkout_phone_field', 'required' );
update_option( 'woocommerce_checkout_company_field', 'hidden' );

// Stock: avisar "Solo quedan N" desde 3 unidades; con más solo se indica que hay existencias.
update_option( 'woocommerce_stock_format', 'low_amount' );
update_option( 'woocommerce_notify_low_stock_amount', 3 );

// Catálogo: novedades primero. Por nombre, los "Case …" llenaban la página 1 y las micas quedaban en la 2.
update_option( 'woocommerce_default_catalog_orderby', 'date' );

$zone = null;
foreach ( WC_Shipping_Zones::get_zones() as $zone_data ) {
    if ( isset( $zone_data['zone_name'] ) && 'Guatemala' === $zone_data['zone_name'] ) {
        $zone = new WC_Shipping_Zone( $zone_data['id'] );
        break;
    }
}

if ( ! $zone ) {
    $zone = new WC_Shipping_Zone();
    $zone->set_zone_name( 'Guatemala' );
    $zone->save();
}

$has_guatemala_location = false;
foreach ( $zone->get_zone_locations() as $location ) {
    if ( 'country' === $location->type && 'GT' === $location->code ) {
        $has_guatemala_location = true;
        break;
    }
}
if ( ! $has_guatemala_location ) {
    $zone->add_location( 'GT', 'country' );
    $zone->save();
}

$guatemala_states = array( 'AV', 'BV', 'CM', 'CQ', 'PR', 'ES', 'GU', 'HU', 'IZ', 'JA', 'JU', 'PE', 'QZ', 'QC', 'RE', 'SA', 'SM', 'SR', 'SO', 'SU', 'TO', 'ZA' );
$existing_locations = array();
foreach ( $zone->get_zone_locations() as $location ) {
    $existing_locations[ $location->type . ':' . $location->code ] = true;
}
foreach ( $guatemala_states as $state_code ) {
    $location_key = 'state:GT:' . $state_code;
    if ( empty( $existing_locations[ $location_key ] ) ) {
        $zone->add_location( 'GT:' . $state_code, 'state' );
    }
}
$zone->save();

$flat_rate_instance = 0;
foreach ( $zone->get_shipping_methods( true ) as $method ) {
    if ( 'flat_rate' === $method->id ) {
        $flat_rate_instance = $method->instance_id;
        break;
    }
}
if ( ! $flat_rate_instance ) {
    $flat_rate_instance = $zone->add_shipping_method( 'flat_rate' );
}

if ( $flat_rate_instance ) {
    update_option(
        'woocommerce_flat_rate_' . $flat_rate_instance . '_settings',
        array(
            'enabled'    => 'yes',
            'title'      => 'Envío nacional',
            'tax_status' => 'none',
            'cost'       => '25',
        )
    );
}

// Títulos en español para las páginas de WooCommerce; los slugs no cambian para no romper URLs.
$page_titles = array(
    'shop'      => array( 'Shop', 'Tienda' ),
    'cart'      => array( 'Cart', 'Carrito' ),
    'checkout'  => array( 'Checkout', 'Finalizar compra' ),
    'myaccount' => array( 'My account', 'Mi cuenta' ),
);
foreach ( $page_titles as $wc_page => $titles ) {
    $page_id = wc_get_page_id( $wc_page );
    // Solo renombra si conserva el título original en inglés: respeta ediciones manuales.
    if ( $page_id > 0 && $titles[0] === get_the_title( $page_id ) ) {
        wp_update_post( array( 'ID' => $page_id, 'post_title' => $titles[1] ) );
        WP_CLI::log( 'Página renombrada: ' . $titles[1] );
    }
}

WP_CLI::success( 'Configuración local lista: Guatemala, GTQ, BACS demo y envío plano Q25.00.' );
