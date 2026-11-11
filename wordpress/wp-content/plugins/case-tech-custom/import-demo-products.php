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
    array( 'brand' => 'Apple',   'model' => 'iPhone 15',      'slug' => 'iphone-15',      'sku' => 'CASE-APPLE-15',       'price' => '169.00', 'image' => 'demo-iphone-15-case.jpg', 'colors' => array( 'Negro', 'Transparente' ) , 'featured' => true ),
    array( 'brand' => 'Samsung', 'model' => 'Galaxy A15',    'slug' => 'galaxy-a15',     'sku' => 'CASE-SAMSUNG-A15',    'price' => '129.00', 'image' => 'demo-samsung-a15-case.jpg' , 'featured' => true ),
    array( 'brand' => 'Samsung', 'model' => 'Galaxy A55',    'slug' => 'galaxy-a55',     'sku' => 'CASE-SAMSUNG-A55',    'price' => '149.00', 'image' => 'demo-samsung-a55-case.jpg' ),
    array( 'brand' => 'Samsung', 'model' => 'Galaxy S24',    'slug' => 'galaxy-s24',     'sku' => 'CASE-SAMSUNG-S24',    'price' => '169.00', 'image' => 'demo-samsung-s24-case.jpg' ),
    array( 'brand' => 'Huawei',  'model' => 'Y9a',           'slug' => 'huawei-y9a',     'sku' => 'CASE-HUAWEI-Y9A',     'price' => '129.00', 'image' => 'demo-huawei-y9a-case.jpg' ),
    array( 'brand' => 'Huawei',  'model' => 'nova 11i',      'slug' => 'huawei-nova-11i', 'sku' => 'CASE-HUAWEI-NOVA11I', 'price' => '139.00', 'image' => 'demo-huawei-nova-11i-case.jpg' , 'featured' => true ),
    array( 'brand' => 'Huawei',  'model' => 'P30 lite',      'slug' => 'huawei-p30-lite', 'sku' => 'CASE-HUAWEI-P30LITE',  'price' => '119.00', 'image' => 'demo-huawei-p30-lite-case.jpg' , 'stock' => 3 ),
    array( 'brand' => 'Xiaomi',  'model' => 'Redmi 13C',     'slug' => 'redmi-13c',      'sku' => 'CASE-XIAOMI-13C',     'price' => '119.00', 'image' => 'demo-xiaomi-redmi-13c-case.jpg' ),
    array( 'brand' => 'Xiaomi',  'model' => 'Redmi Note 13', 'slug' => 'redmi-note-13',  'sku' => 'CASE-XIAOMI-NOTE13',  'price' => '139.00', 'image' => 'demo-xiaomi-redmi-note-13-case.jpg' , 'featured' => true ),
    array( 'brand' => 'Xiaomi',  'model' => 'POCO X6',        'slug' => 'poco-x6',        'sku' => 'CASE-XIAOMI-POCOX6',  'price' => '149.00', 'image' => 'demo-xiaomi-poco-x6-case.jpg' ),

    // Cases de modelos nuevos: usan la foto demo del modelo más parecido de la marca (reemplazable).
    array( 'brand' => 'Apple',   'model' => 'iPhone 12',     'slug' => 'iphone-12',      'sku' => 'CASE-APPLE-12',        'price' => '139.00', 'image' => 'demo-iphone-11-case.jpg' ),
    array( 'brand' => 'Apple',   'model' => 'iPhone 14',     'slug' => 'iphone-14',      'sku' => 'CASE-APPLE-14',        'price' => '159.00', 'image' => 'demo-iphone-13-case.jpg' ),
    array( 'brand' => 'Samsung', 'model' => 'Galaxy A25',    'slug' => 'galaxy-a25',     'sku' => 'CASE-SAMSUNG-A25',     'price' => '129.00', 'image' => 'demo-samsung-a15-case.jpg' ),
    array( 'brand' => 'Samsung', 'model' => 'Galaxy S23',    'slug' => 'galaxy-s23',     'sku' => 'CASE-SAMSUNG-S23',     'price' => '159.00', 'image' => 'demo-samsung-s24-case.jpg' ),
    array( 'brand' => 'Huawei',  'model' => 'nova 12i',      'slug' => 'huawei-nova-12i', 'sku' => 'CASE-HUAWEI-NOVA12I', 'price' => '139.00', 'image' => 'demo-huawei-nova-11i-case.jpg' ),
    array( 'brand' => 'Huawei',  'model' => 'nova Y70',      'slug' => 'huawei-nova-y70', 'sku' => 'CASE-HUAWEI-NOVAY70', 'price' => '119.00', 'image' => 'demo-huawei-y9a-case.jpg' ),
    array( 'brand' => 'Xiaomi',  'model' => 'Redmi 12',      'slug' => 'redmi-12',       'sku' => 'CASE-XIAOMI-12',       'price' => '119.00', 'image' => 'demo-xiaomi-redmi-13c-case.jpg' ),
    array( 'brand' => 'Xiaomi',  'model' => 'Redmi Note 12', 'slug' => 'redmi-note-12',  'sku' => 'CASE-XIAOMI-NOTE12',   'price' => '129.00', 'image' => 'demo-xiaomi-redmi-note-13-case.jpg' ),

    // Fundas y micas para modelos que ya tienen case: activan el filtro por tipo y las ventas cruzadas.
    array( 'type' => 'Funda', 'brand' => 'Apple',   'model' => 'iPhone 13',     'slug' => 'iphone-13',       'sku' => 'FUNDA-APPLE-13',       'price' => '169.00', 'image' => 'demo-funda-correa.jpg' ),
    array( 'type' => 'Mica',  'brand' => 'Apple',   'model' => 'iPhone 15',     'slug' => 'iphone-15',       'sku' => 'MICA-APPLE-15',        'price' => '69.00',  'image' => 'demo-mica.jpg' ),
    array( 'type' => 'Funda', 'brand' => 'Samsung', 'model' => 'Galaxy A55',    'slug' => 'galaxy-a55',      'sku' => 'FUNDA-SAMSUNG-A55',    'price' => '159.00', 'image' => 'demo-funda.jpg' ),
    array( 'type' => 'Mica',  'brand' => 'Samsung', 'model' => 'Galaxy A15',    'slug' => 'galaxy-a15',      'sku' => 'MICA-SAMSUNG-A15',     'price' => '59.00',  'image' => 'demo-mica-2.jpg' ),
    array( 'type' => 'Funda', 'brand' => 'Huawei',  'model' => 'P30 lite',      'slug' => 'huawei-p30-lite', 'sku' => 'FUNDA-HUAWEI-P30LITE', 'price' => '149.00', 'image' => 'demo-funda-correa.jpg' ),
    array( 'type' => 'Mica',  'brand' => 'Huawei',  'model' => 'nova 11i',      'slug' => 'huawei-nova-11i', 'sku' => 'MICA-HUAWEI-NOVA11I',  'price' => '59.00',  'image' => 'demo-mica.jpg' ),
    array( 'type' => 'Funda', 'brand' => 'Xiaomi',  'model' => 'Redmi Note 13', 'slug' => 'redmi-note-13',   'sku' => 'FUNDA-XIAOMI-NOTE13',  'price' => '149.00', 'image' => 'demo-funda.jpg' ),
    array( 'type' => 'Mica',  'brand' => 'Xiaomi',  'model' => 'POCO X6',       'slug' => 'poco-x6',         'sku' => 'MICA-XIAOMI-POCOX6',   'price' => '69.00',  'image' => 'demo-mica-2.jpg' ),
);

// Textos por tipo: %1$s = marca, %2$s = modelo.
$type_texts = array(
    'Case'  => array(
        '<p>Protege tu <strong>%1$s %2$s</strong> con un case diseñado para acompañarte todos los días. Su estructura de acabado sobrio ayuda a reducir el impacto de golpes, roces y pequeñas caídas mientras conserva un acceso cómodo a los botones, puertos y cámara.</p><p>El diseño está pensado para mantener un buen agarre sin añadir volumen innecesario. La abertura de cámara está alineada con el modelo compatible y los bordes elevados ayudan a mantener la pantalla y el módulo fotográfico alejados de superficies planas.</p><h3>Características</h3><ul><li>Compatibilidad: %1$s %2$s.</li><li>Material protector con acabado mate y cómodo al tacto.</li><li>Acceso preciso a botones, cámara, altavoces y puerto de carga.</li><li>Producto nuevo de demostración, empacado para despacho local.</li></ul><p><strong>Nota:</strong> verifica el modelo exacto de tu teléfono antes de comprar. La imagen, el precio y el inventario corresponden a datos demo del entorno local.</p>',
        'Case protector de acabado mate para %1$s %2$s, con buen agarre, acceso preciso a controles y protección diaria.',
    ),
    'Funda' => array(
        '<p>Funda tipo cartera para <strong>%1$s %2$s</strong>: cubre el frente y la parte trasera del teléfono y guarda tus tarjetas en el mismo lugar.</p><p>La tapa protege la pantalla dentro del bolso o la mochila y se cierra con broche magnético. El soporte interno sujeta el teléfono sin retirarlo para usar la cámara o cargarlo.</p><h3>Características</h3><ul><li>Compatibilidad: %1$s %2$s.</li><li>Tres ranuras para tarjetas y un bolsillo para billetes.</li><li>Cierre magnético y tapa que funciona como soporte horizontal.</li><li>Producto nuevo de demostración, empacado para despacho local.</li></ul><p><strong>Nota:</strong> verifica el modelo exacto de tu teléfono antes de comprar. La imagen es ilustrativa y el precio y el inventario corresponden a datos demo del entorno local.</p>',
        'Funda tipo cartera para %1$s %2$s, con ranuras para tarjetas, cierre magnético y protección frontal.',
    ),
    'Mica'  => array(
        '<p>Mica de vidrio templado para <strong>%1$s %2$s</strong>, cortada a la medida de la pantalla para protegerla de rayones y golpes en los bordes.</p><p>Se entrega como repuesto sin instalación: incluye toallita de limpieza y guía para colocarla en casa sin burbujas.</p><h3>Características</h3><ul><li>Compatibilidad: %1$s %2$s.</li><li>Vidrio templado de 9H con bordes redondeados.</li><li>Conserva la sensibilidad táctil y la nitidez de la pantalla.</li><li>Incluye kit de limpieza; no incluye instalación.</li></ul><p><strong>Nota:</strong> verifica el modelo exacto de tu teléfono antes de comprar. La imagen es ilustrativa y el precio y el inventario corresponden a datos demo del entorno local.</p>',
        'Mica de vidrio templado para %1$s %2$s, con kit de limpieza para colocarla en casa.',
    ),
);

// Ficha técnica por tipo: se muestra en la pestaña "Información adicional" (datos demo).
$type_specs = array(
    'Case'  => array(
        'Material'        => 'TPU flexible con acabado mate',
        'Interior'        => 'Microfibra suave',
        'Grosor'          => '1.5 mm',
        'Protección'      => 'Bordes elevados sobre pantalla y cámara',
        'Botones'         => 'Cubiertos, con buena respuesta al presionar',
    ),
    'Funda' => array(
        'Material'        => 'Cuero sintético (PU) con costura visible',
        'Interior'        => 'Carcasa de TPU que sujeta el teléfono',
        'Tarjetas'        => '3 ranuras y 1 bolsillo para billetes',
        'Cierre'          => 'Magnético',
        'Soporte'         => 'La tapa se dobla como soporte horizontal',
        'Color'           => 'Negro',
    ),
    'Mica'  => array(
        'Material'        => 'Vidrio templado',
        'Dureza'          => '9H',
        'Grosor'          => '0.33 mm',
        'Bordes'          => '2.5D redondeados, con marco negro',
        'Incluye'         => 'Toallita húmeda, paño seco y stickers quitapolvo',
        'Instalación'     => 'No incluida (se coloca en casa)',
    ),
);

// Color de cada render de case; el case variable (iPhone 15) toma el color de la variación.
$image_colors = array(
    'demo-iphone-11-case.jpg'            => 'Negro',
    'demo-iphone-13-case.jpg'            => 'Naranja',
    'demo-samsung-a15-case.jpg'          => 'Gris grafito',
    'demo-samsung-a55-case.jpg'          => 'Azul',
    'demo-samsung-s24-case.jpg'          => 'Gris grafito',
    'demo-huawei-y9a-case.jpg'           => 'Verde azulado',
    'demo-huawei-nova-11i-case.jpg'      => 'Azul marino',
    'demo-huawei-p30-lite-case.jpg'      => 'Negro',
    'demo-xiaomi-redmi-13c-case.jpg'     => 'Rojo',
    'demo-xiaomi-redmi-note-13-case.jpg' => 'Verde',
    'demo-xiaomi-poco-x6-case.jpg'       => 'Negro con detalles naranja',
);

// Segunda foto de la galería por tipo; sin entrada, se usa un acercamiento de la foto principal.
$type_gallery = array(
    'Funda' => 'demo-funda-abierta.jpg',
    'Mica'  => 'demo-mica-limpieza.jpg',
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

$type_taxonomy = 'pa_tipo-producto';
if ( ! taxonomy_exists( $type_taxonomy ) ) {
    $type_attribute_id = wc_create_attribute( array(
        'name'         => 'Tipo de producto',
        'slug'         => 'tipo-producto',
        'type'         => 'select',
        'order_by'     => 'menu_order',
        'has_archives' => false,
    ) );
    if ( is_wp_error( $type_attribute_id ) ) {
        WP_CLI::warning( 'No se pudo crear el atributo Tipo de producto: ' . $type_attribute_id->get_error_message() );
    } else {
        register_taxonomy( $type_taxonomy, array( 'product' ), array( 'hierarchical' => false, 'label' => 'Tipo de producto' ) );
    }
}

$type_terms = array();
if ( taxonomy_exists( $type_taxonomy ) ) {
    foreach ( array_keys( $type_texts ) as $type_name ) {
        $type_terms[ $type_name ] = case_tech_demo_term_id( $type_name, $type_taxonomy );
    }
}

$color_taxonomy = 'pa_color';
if ( ! taxonomy_exists( $color_taxonomy ) ) {
    $color_attribute_id = wc_create_attribute( array(
        'name'         => 'Color',
        'slug'         => 'color',
        'type'         => 'select',
        'order_by'     => 'menu_order',
        'has_archives' => false,
    ) );
    if ( is_wp_error( $color_attribute_id ) ) {
        WP_CLI::warning( 'No se pudo crear el atributo Color: ' . $color_attribute_id->get_error_message() );
    } else {
        register_taxonomy( $color_taxonomy, array( 'product' ), array( 'hierarchical' => false, 'label' => 'Color' ) );
    }
}

/**
 * Devuelve el adjunto creado a partir de un archivo del tema; lo reutiliza si ya existe.
 * $crop = true sube un acercamiento al centro de la imagen (vista de detalle para la galería).
 */
function case_tech_demo_attachment( $image_path, $source_key, $title, $crop = false ) {
    $existing = get_posts( array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'meta_key'       => '_case_tech_source_file',
        'meta_value'     => $source_key,
        'fields'         => 'ids',
    ) );
    if ( $existing ) {
        return (int) $existing[0];
    }

    $tmp_image = wp_tempnam( $image_path );
    if ( $crop ) {
        $editor = wp_get_image_editor( $image_path );
        if ( is_wp_error( $editor ) ) {
            WP_CLI::warning( 'No se pudo recortar ' . basename( $image_path ) . ': ' . $editor->get_error_message() );
            return 0;
        }
        $size = $editor->get_size();
        $w    = (int) ( $size['width'] * 0.6 );
        $h    = (int) ( $size['height'] * 0.6 );
        $editor->crop( (int) ( ( $size['width'] - $w ) / 2 ), (int) ( ( $size['height'] - $h ) / 2 ), $w, $h, $size['width'], $size['height'] );
        $saved = $editor->save( $tmp_image, 'image/jpeg' );
        if ( is_wp_error( $saved ) ) {
            WP_CLI::warning( 'No se pudo guardar el detalle de ' . basename( $image_path ) );
            return 0;
        }
        if ( $saved['path'] !== $tmp_image ) {
            unlink( $tmp_image ); // El editor cambia la extensión .tmp por .jpg.
        }
        $tmp_image = $saved['path'];
    } else {
        copy( $image_path, $tmp_image );
    }

    $file = array(
        'name'     => ( $crop ? 'detalle-' : '' ) . basename( $image_path ),
        'tmp_name' => $tmp_image,
        'type'     => 'image/jpeg',
        'error'    => 0,
        'size'     => filesize( $tmp_image ),
    );
    $attachment_id = media_handle_sideload( $file, 0, $title );
    if ( file_exists( $tmp_image ) ) {
        unlink( $tmp_image );
    }
    if ( is_wp_error( $attachment_id ) ) {
        WP_CLI::warning( 'No se pudo subir ' . $file['name'] . ': ' . $attachment_id->get_error_message() );
        return 0;
    }
    update_post_meta( $attachment_id, '_case_tech_source_file', $source_key );
    $GLOBALS['case_tech_new_images']++;
    return (int) $attachment_id;
}

/** Devuelve el ID de un término, creándolo si no existe; 0 si falla. */
function case_tech_demo_term_id( $name, $taxonomy ) {
    $term = term_exists( $name, $taxonomy );
    if ( ! $term ) {
        $term = wp_insert_term( $name, $taxonomy, array( 'slug' => sanitize_title( $name ) ) );
    }
    return ( $term && ! is_wp_error( $term ) ) ? (int) ( is_array( $term ) ? $term['term_id'] : $term ) : 0;
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
$GLOBALS['case_tech_new_images'] = 0;
$ids_by_model = array();

foreach ( $catalog as $item ) {
    $product_id   = wc_get_product_id_by_sku( $item['sku'] );
    $is_variable  = ! empty( $item['colors'] ) && taxonomy_exists( $color_taxonomy );
    // Instanciar por clase (no wc_get_product) permite convertir un simple existente en variable.
    $product      = $is_variable ? new WC_Product_Variable( $product_id ) : new WC_Product_Simple( $product_id );
    $is_new       = ! $product_id;

    $type = isset( $item['type'] ) ? $item['type'] : 'Case';
    $product->set_name( $type . ' ' . $item['model'] );
    $product->set_slug( sanitize_title( $type ) . '-' . $item['slug'] );
    $product->set_status( 'publish' );
    $product->set_catalog_visibility( 'visible' );
    $product->set_featured( ! empty( $item['featured'] ) );
    $product->set_description( sprintf( $type_texts[ $type ][0], $item['brand'], $item['model'] ) );
    $product->set_short_description( sprintf( $type_texts[ $type ][1], $item['brand'], $item['model'] ) );
    $product->set_sku( $item['sku'] );
    if ( $is_variable ) {
        // Precio e inventario viven en cada variación.
        $product->set_manage_stock( false );
    } else {
        $product->set_regular_price( $item['price'] );
        $product->set_price( $item['price'] );
        $product->set_manage_stock( true );
        $product->set_stock_quantity( isset( $item['stock'] ) ? $item['stock'] : 10 );
        $product->set_stock_status( 'instock' );
    }
    $product->set_category_ids( array( $cases_term_id, $brand_terms[ $item['brand'] ] ) );
    $product_attributes = array();
    if ( ! empty( $model_terms[ $item['model'] ] ) ) {
        $model_attribute = new WC_Product_Attribute();
        $model_attribute->set_id( wc_attribute_taxonomy_id_by_name( $attribute_taxonomy ) );
        $model_attribute->set_name( $attribute_taxonomy );
        $model_attribute->set_options( array( $model_terms[ $item['model'] ] ) );
        $model_attribute->set_position( 0 );
        $model_attribute->set_visible( true );
        $model_attribute->set_variation( false );
        $product_attributes[] = $model_attribute;
    }
    if ( ! empty( $type_terms[ $type ] ) ) {
        $type_attribute = new WC_Product_Attribute();
        $type_attribute->set_id( wc_attribute_taxonomy_id_by_name( $type_taxonomy ) );
        $type_attribute->set_name( $type_taxonomy );
        $type_attribute->set_options( array( $type_terms[ $type ] ) );
        $type_attribute->set_position( 1 );
        $type_attribute->set_visible( true );
        $type_attribute->set_variation( false );
        $product_attributes[] = $type_attribute;
    }
    $color_terms = array();
    if ( $is_variable ) {
        foreach ( $item['colors'] as $color ) {
            $color_term_id = case_tech_demo_term_id( $color, $color_taxonomy );
            if ( $color_term_id ) {
                $color_terms[ $color_term_id ] = get_term( $color_term_id, $color_taxonomy )->slug;
            }
        }
        $color_attribute = new WC_Product_Attribute();
        $color_attribute->set_id( wc_attribute_taxonomy_id_by_name( $color_taxonomy ) );
        $color_attribute->set_name( $color_taxonomy );
        $color_attribute->set_options( array_keys( $color_terms ) );
        $color_attribute->set_position( 2 );
        $color_attribute->set_visible( true );
        $color_attribute->set_variation( true );
        $product_attributes[] = $color_attribute;
    }
    $specs = $type_specs[ $type ];
    if ( ! $is_variable && isset( $image_colors[ $item['image'] ] ) ) {
        $specs['Color'] = $image_colors[ $item['image'] ];
    }
    if ( $is_variable ) {
        $specs['Material'] = 'TPU flexible: negro mate o transparente';
    }
    $position = 3;
    foreach ( $specs as $label => $value ) {
        // Atributo local (sin taxonomía): solo informativo, no crea filtros.
        $spec_attribute = new WC_Product_Attribute();
        $spec_attribute->set_name( $label );
        $spec_attribute->set_options( array( $value ) );
        $spec_attribute->set_position( $position++ );
        $spec_attribute->set_visible( true );
        $spec_attribute->set_variation( false );
        $product_attributes[] = $spec_attribute;
    }
    if ( $product_attributes ) {
        $product->set_attributes( $product_attributes );
    }

    $image_path = $base_dir . $item['image'];
    if ( file_exists( $image_path ) ) {
        $attachment_id = case_tech_demo_attachment( $image_path, $item['image'], 'Imagen demo: ' . $item['model'] );
        if ( $attachment_id ) {
            $product->set_image_id( $attachment_id );
        }
        if ( isset( $type_gallery[ $type ] ) && file_exists( $base_dir . $type_gallery[ $type ] ) ) {
            $detail_id = case_tech_demo_attachment( $base_dir . $type_gallery[ $type ], $type_gallery[ $type ], 'Galería demo: ' . $type );
        } else {
            $detail_id = case_tech_demo_attachment( $image_path, 'detalle-' . $item['image'], 'Detalle demo: ' . $item['model'], true );
        }
        if ( $detail_id ) {
            $product->set_gallery_image_ids( array( $detail_id ) );
        }
    } else {
        WP_CLI::warning( 'No se encontró la imagen: ' . $image_path );
    }

    $saved_id = $product->save();
    if ( $is_variable ) {
        wp_set_object_terms( $saved_id, 'variable', 'product_type' );
        foreach ( $color_terms as $color_slug ) {
            $variation_sku = $item['sku'] . '-' . strtoupper( $color_slug );
            $variation_id  = wc_get_product_id_by_sku( $variation_sku );
            $variation     = new WC_Product_Variation( $variation_id );
            $variation->set_parent_id( $saved_id );
            $variation->set_sku( $variation_sku );
            $variation->set_attributes( array( $color_taxonomy => $color_slug ) );
            $variation->set_regular_price( $item['price'] );
            $variation->set_manage_stock( true );
            $variation->set_stock_quantity( 5 );
            $variation->set_stock_status( 'instock' );
            $variation->set_status( 'publish' );
            $variation->save();
        }
        WC_Product_Variable::sync( $saved_id );
        wc_delete_product_transients( $saved_id );
    }
    update_post_meta( $saved_id, '_case_tech_brand', $item['brand'] );
    update_post_meta( $saved_id, '_case_tech_model', $item['model'] );
    update_post_meta( $saved_id, '_case_tech_demo', 'yes' );
    $ids_by_model[ $item['model'] ][] = $saved_id;

    if ( $is_new ) {
        $created++;
    } else {
        $updated++;
    }
    WP_CLI::log( ( $is_new ? 'Creado: ' : 'Actualizado: ' ) . $item['sku'] . ' — ' . $item['model'] );
}

// Ventas cruzadas: case, funda y mica del mismo modelo se recomiendan entre sí en el carrito.
foreach ( $ids_by_model as $model_ids ) {
    foreach ( $model_ids as $model_product_id ) {
        $model_product = wc_get_product( $model_product_id );
        $model_product->set_cross_sell_ids( array_values( array_diff( $model_ids, array( $model_product_id ) ) ) );
        $model_product->save();
    }
}

// Créditos de fotos externas (ver themes/case-tech/CREDITOS-IMAGENES.md): van en la leyenda, que muestra el lightbox.
$image_credits = array(
    'demo-funda.jpg'         => 'Foto: Budget Bizar (Pexels)',
    'demo-funda-correa.jpg'  => 'Foto: Santeri Viinamäki, CC BY-SA 4.0 (Wikimedia Commons)',
    'demo-funda-abierta.jpg' => 'Foto: Acabashi, CC BY-SA 4.0 (Wikimedia Commons)',
    'demo-mica.jpg'          => 'Foto: Rann Vijay (Pexels)',
    'demo-mica-2.jpg'        => 'Foto: Rann Vijay (Pexels)',
    'demo-mica-limpieza.jpg' => 'Foto: Towfiqu barbhuiya (Pexels)',
);
foreach ( $image_credits as $source_key => $credit ) {
    foreach ( array( $source_key, 'detalle-' . $source_key ) as $key ) {
        $ids = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'meta_key' => '_case_tech_source_file', 'meta_value' => $key, 'fields' => 'ids' ) );
        if ( $ids ) {
            wp_update_post( array( 'ID' => $ids[0], 'post_excerpt' => $credit ) );
        }
    }
}

WP_CLI::success( sprintf( 'Catálogo listo: %d creados, %d actualizados, %d imágenes nuevas.', $created, $updated, $GLOBALS['case_tech_new_images'] ) );
