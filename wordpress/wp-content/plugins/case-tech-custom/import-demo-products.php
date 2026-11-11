<?php
/**
 * Importa o actualiza el catálogo demo de Case-Tech en WooCommerce.
 * Ejecutar con WP-CLI (contenedor wpcli o Playground):
 *   wp eval-file wp-content/plugins/case-tech-custom/import-demo-products.php
 *
 * El script es idempotente: si el SKU ya existe, actualiza el producto.
 */

if ( ! function_exists( 'wc_get_product_id_by_sku' ) ) {
    WP_CLI::error( 'WooCommerce no está activo en esta instalación.' );
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$catalog = array(
    array( 'brand' => 'Apple',   'model' => 'iPhone 11',      'slug' => 'iphone-11',      'sku' => 'CASE-APPLE-11',       'price' => '149.00', 'image' => 'demo-iphone-11-case.jpg' ),
    array( 'brand' => 'Apple',   'model' => 'iPhone 13',      'slug' => 'iphone-13',      'sku' => 'CASE-APPLE-13',       'price' => '159.00', 'image' => 'demo-iphone-13-case.jpg' ),
    array( 'brand' => 'Apple',   'model' => 'iPhone 15',      'slug' => 'iphone-15',      'sku' => 'CASE-APPLE-15',       'price' => '169.00', 'image' => 'demo-iphone-15-case.jpg' ),
    array( 'brand' => 'Samsung', 'model' => 'Galaxy A15',    'slug' => 'galaxy-a15',     'sku' => 'CASE-SAMSUNG-A15',    'price' => '129.00', 'image' => 'demo-samsung-a15-case.jpg' ),
    array( 'brand' => 'Samsung', 'model' => 'Galaxy A55',    'slug' => 'galaxy-a55',     'sku' => 'CASE-SAMSUNG-A55',    'price' => '149.00', 'image' => 'demo-samsung-a55-case.jpg' ),
    array( 'brand' => 'Samsung', 'model' => 'Galaxy S24',    'slug' => 'galaxy-s24',     'sku' => 'CASE-SAMSUNG-S24',    'price' => '169.00', 'image' => 'demo-samsung-s24-case.jpg' ),
    array( 'brand' => 'Huawei',  'model' => 'Y9a',           'slug' => 'huawei-y9a',     'sku' => 'CASE-HUAWEI-Y9A',     'price' => '129.00', 'image' => 'demo-huawei-y9a-case.jpg' ),
    array( 'brand' => 'Huawei',  'model' => 'nova 11i',      'slug' => 'huawei-nova-11i', 'sku' => 'CASE-HUAWEI-NOVA11I', 'price' => '139.00', 'image' => 'demo-huawei-nova-11i-case.jpg' ),
    array( 'brand' => 'Huawei',  'model' => 'P30 lite',      'slug' => 'huawei-p30-lite', 'sku' => 'CASE-HUAWEI-P30LITE',  'price' => '119.00', 'image' => 'demo-huawei-p30-lite-case.jpg' ),
    array( 'brand' => 'Xiaomi',  'model' => 'Redmi 13C',     'slug' => 'redmi-13c',      'sku' => 'CASE-XIAOMI-13C',     'price' => '119.00', 'image' => 'demo-xiaomi-redmi-13c-case.jpg' ),
    array( 'brand' => 'Xiaomi',  'model' => 'Redmi Note 13', 'slug' => 'redmi-note-13',  'sku' => 'CASE-XIAOMI-NOTE13',  'price' => '139.00', 'image' => 'demo-xiaomi-redmi-note-13-case.jpg' ),
    array( 'brand' => 'Xiaomi',  'model' => 'POCO X6',        'slug' => 'poco-x6',        'sku' => 'CASE-XIAOMI-POCOX6',  'price' => '149.00', 'image' => 'demo-xiaomi-poco-x6-case.jpg' ),
);

$base_dir = WP_CONTENT_DIR . '/themes/case-tech/';
$cases_term = term_exists( 'Cases', 'product_cat' );
if ( ! $cases_term ) {
    $cases_term = wp_insert_term( 'Cases', 'product_cat', array( 'slug' => 'cases' ) );
}
$cases_term_id = is_array( $cases_term ) ? (int) $cases_term['term_id'] : (int) $cases_term;

$brand_terms = array();
foreach ( array( 'Apple', 'Samsung', 'Huawei', 'Xiaomi' ) as $brand ) {
    $term = term_exists( $brand, 'product_cat', $cases_term_id );
    if ( ! $term ) {
        $term = wp_insert_term( $brand, 'product_cat', array( 'parent' => $cases_term_id, 'slug' => strtolower( $brand ) ) );
    }
    $brand_terms[ $brand ] = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
}

$attribute_taxonomy = 'pa_modelo-compatible';
if ( ! taxonomy_exists( $attribute_taxonomy ) ) {
    $attribute_id = wc_create_attribute( array(
        'name'         => 'Modelo compatible',
        'slug'         => 'modelo-compatible',
        'type'         => 'select',
        'order_by'     => 'menu_order',
        'has_archives' => false,
    ) );
    if ( is_wp_error( $attribute_id ) ) {
        WP_CLI::warning( 'No se pudo crear el atributo Modelo compatible: ' . $attribute_id->get_error_message() );
    } else {
        register_taxonomy( $attribute_taxonomy, array( 'product' ), array( 'hierarchical' => false, 'label' => 'Modelo compatible' ) );
    }
}

$model_terms = array();
if ( taxonomy_exists( $attribute_taxonomy ) ) {
    foreach ( $catalog as $item ) {
        $model_term = term_exists( $item['model'], $attribute_taxonomy );
        if ( ! $model_term ) {
            $model_term = wp_insert_term( $item['model'], $attribute_taxonomy, array( 'slug' => sanitize_title( $item['model'] ) ) );
        }
        if ( ! is_wp_error( $model_term ) && $model_term ) {
            $model_terms[ $item['model'] ] = (int) ( is_array( $model_term ) ? $model_term['term_id'] : $model_term );
        }
    }
}

$created = 0;
$updated = 0;
$images = 0;

foreach ( $catalog as $item ) {
    $product_id = wc_get_product_id_by_sku( $item['sku'] );
    $product = $product_id ? wc_get_product( $product_id ) : new WC_Product_Simple();
    $is_new = ! $product_id;

    $product->set_name( 'Case ' . $item['model'] );
    $product->set_slug( 'case-' . $item['slug'] );
    $product->set_status( 'publish' );
    $product->set_catalog_visibility( 'visible' );
    $product->set_description( sprintf(
        '<p>Protege tu <strong>%s %s</strong> con un case diseñado para acompañarte todos los días. Su estructura de acabado sobrio ayuda a reducir el impacto de golpes, roces y pequeñas caídas mientras conserva un acceso cómodo a los botones, puertos y cámara.</p><p>El diseño está pensado para mantener un buen agarre sin añadir volumen innecesario. La abertura de cámara está alineada con el modelo compatible y los bordes elevados ayudan a mantener la pantalla y el módulo fotográfico alejados de superficies planas.</p><h3>Características</h3><ul><li>Compatibilidad: %s %s.</li><li>Material protector con acabado mate y cómodo al tacto.</li><li>Acceso preciso a botones, cámara, altavoces y puerto de carga.</li><li>Producto nuevo de demostración, empacado para despacho local.</li></ul><p><strong>Nota:</strong> verifica el modelo exacto de tu teléfono antes de comprar. La imagen, el precio y el inventario corresponden a datos demo del entorno local.</p>',
        $item['brand'],
        $item['model'],
        $item['brand'],
        $item['model']
    ) );
    $product->set_short_description( sprintf(
        'Case protector de acabado mate para %s %s, con buen agarre, acceso preciso a controles y protección diaria.',
        $item['brand'],
        $item['model']
    ) );
    $product->set_sku( $item['sku'] );
    $product->set_regular_price( $item['price'] );
    $product->set_price( $item['price'] );
    $product->set_manage_stock( true );
    $product->set_stock_quantity( 10 );
    $product->set_stock_status( 'instock' );
    $product->set_category_ids( array( $cases_term_id, $brand_terms[ $item['brand'] ] ) );
    if ( ! empty( $model_terms[ $item['model'] ] ) ) {
        $model_attribute = new WC_Product_Attribute();
        $model_attribute->set_id( wc_attribute_taxonomy_id_by_name( $attribute_taxonomy ) );
        $model_attribute->set_name( $attribute_taxonomy );
        $model_attribute->set_options( array( $model_terms[ $item['model'] ] ) );
        $model_attribute->set_position( 0 );
        $model_attribute->set_visible( true );
        $model_attribute->set_variation( false );
        $product->set_attributes( array( $model_attribute ) );
    }

    $image_path = $base_dir . $item['image'];
    if ( file_exists( $image_path ) ) {
        $existing_attachment = get_posts( array(
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => 1,
            'meta_key'       => '_case_tech_source_file',
            'meta_value'     => $item['image'],
            'fields'         => 'ids',
        ) );
        $attachment_id = ! empty( $existing_attachment ) ? (int) $existing_attachment[0] : 0;
        if ( ! $attachment_id ) {
            $tmp_image = wp_tempnam( $image_path );
            copy( $image_path, $tmp_image );
            $file = array(
                'name'     => basename( $image_path ),
                'tmp_name' => $tmp_image,
                'type'     => mime_content_type( $image_path ),
                'error'    => 0,
                'size'     => filesize( $tmp_image ),
            );
            $attachment_id = media_handle_sideload( $file, 0, 'Imagen demo: ' . $item['model'] );
            if ( ! is_wp_error( $attachment_id ) ) {
                update_post_meta( $attachment_id, '_case_tech_source_file', $item['image'] );
                $images++;
            }
            if ( file_exists( $tmp_image ) ) {
                unlink( $tmp_image );
            }
        }
        if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
            $product->set_image_id( $attachment_id );
        }
    } else {
        WP_CLI::warning( 'No se encontró la imagen: ' . $image_path );
    }

    $saved_id = $product->save();
    update_post_meta( $saved_id, '_case_tech_brand', $item['brand'] );
    update_post_meta( $saved_id, '_case_tech_model', $item['model'] );
    update_post_meta( $saved_id, '_case_tech_demo', 'yes' );

    if ( $is_new ) {
        $created++;
    } else {
        $updated++;
    }
    WP_CLI::log( ( $is_new ? 'Creado: ' : 'Actualizado: ' ) . $item['sku'] . ' — ' . $item['model'] );
}

WP_CLI::success( sprintf( 'Catálogo listo: %d creados, %d actualizados, %d imágenes nuevas.', $created, $updated, $images ) );
