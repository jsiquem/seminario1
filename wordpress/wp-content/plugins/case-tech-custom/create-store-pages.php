<?php
/**
 * Crea o actualiza las páginas comerciales administrables de Case-Tech.
 * Ejecutar dentro de wpcli:
 * wp eval-file /var/www/html/wp-content/plugins/case-tech-custom/create-store-pages.php
 */

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'wp_insert_post' ) ) {
    exit;
}

$pages = array(
    array(
        'slug'    => 'contacto',
        'title'   => 'Contacto',
        'content' => '<p>¿Tienes dudas sobre la compatibilidad de tu teléfono? Escríbenos antes de comprar y te ayudaremos a elegir el modelo correcto.</p><div class="content-callout"><strong>Atención demo</strong><br>WhatsApp: +502 0000-0000<br>Correo: tienda@case-tech.test</div><h2>Horario de atención</h2><p>Lunes a viernes, de 8:00 a 17:00 horas. Estos datos son ficticios y corresponden únicamente al entorno local de demostración.</p><h2>Antes de escribirnos</h2><p>Ten a la mano la marca y el modelo exacto de tu teléfono. Esa información nos ayuda a confirmar la compatibilidad del producto.</p>',
    ),
    array(
        'slug'    => 'preguntas-frecuentes',
        'title'   => 'Preguntas frecuentes',
        'content' => '<h2>¿Cómo sé si un case es compatible?</h2><p>Busca la marca y el modelo exacto de tu teléfono. Revisa también el bloque “Compatible con” en la ficha del producto antes de agregarlo al carrito.</p><h2>¿Realizan instalación?</h2><p>No. En esta primera etapa vendemos cases, fundas y micas como productos independientes, sin servicio de instalación.</p><h2>¿A dónde realizan envíos?</h2><p>La cobertura demo contempla toda Guatemala mediante una tarifa plana de Q25.00.</p><h2>¿Cómo puedo pagar?</h2><p>El método inicial es transferencia bancaria directa. Las instrucciones mostradas en este entorno son ficticias y deben reemplazarse antes de producción.</p><h2>¿Puedo cambiar un producto?</h2><p>Consulta las condiciones de cambios antes de comprar. El producto debe conservarse nuevo, sin uso y con su empaque cuando aplique.</p>',
    ),
    array(
        'slug'    => 'envios',
        'title'   => 'Envíos y pagos',
        'content' => '<div class="content-callout"><strong>Envío nacional demo: Q25.00</strong><br>Una tarifa plana para toda Guatemala.</div><h2>Preparación del pedido</h2><p>Los pedidos se preparan después de confirmar el pago. El tiempo real de entrega deberá definirse con el servicio de mensajería elegido antes de publicar la tienda.</p><h2>Forma de pago</h2><p>Durante las pruebas utilizamos transferencia bancaria directa con datos ficticios. No realices pagos con la información de este entorno local.</p><h2>Seguimiento</h2><p>Cuando la operación esté conectada a un servicio real, se añadirá la confirmación de despacho y el número de seguimiento al correo del pedido.</p>',
    ),
    array(
        'slug'    => 'politicas',
        'title'   => 'Políticas de Case-Tech',
        'content' => '<h2>Cambios y devoluciones</h2><p>Antes de publicar, la administración debe definir el plazo, las condiciones y el procedimiento para cambios o devoluciones. Esta página funciona como estructura inicial de contenido.</p><h2>Privacidad</h2><p>La tienda deberá informar qué datos se recopilan, para qué se utilizan, cuánto tiempo se conservan y cómo puede solicitarse su modificación o eliminación.</p><h2>Términos de compra</h2><p>Los precios, disponibilidad, cobertura y condiciones deberán confirmarse antes de cada compra. Los datos actuales son ficticios y solo sirven para pruebas locales.</p>',
    ),
);

foreach ( $pages as $page ) {
    $existing = get_page_by_path( $page['slug'], OBJECT, 'page' );
    $postarr = array(
        'post_title'   => $page['title'],
        'post_name'    => $page['slug'],
        'post_content' => $page['content'],
        'post_status'  => 'publish',
        'post_type'    => 'page',
    );

    if ( $existing ) {
        if ( 'yes' === get_post_meta( $existing->ID, '_case_tech_managed_page', true ) ) {
            $postarr['ID'] = $existing->ID;
            $page_id = wp_update_post( wp_slash( $postarr ), true );
            WP_CLI::log( 'Actualizada: ' . $page['slug'] );
        } else {
            $page_id = $existing->ID;
            WP_CLI::log( 'Conservada sin sobrescribir: ' . $page['slug'] );
        }
    } else {
        $page_id = wp_insert_post( wp_slash( $postarr ), true );
        WP_CLI::log( 'Creada: ' . $page['slug'] );
    }

    if ( ! is_wp_error( $page_id ) && $page_id ) {
        update_post_meta( $page_id, '_case_tech_managed_page', 'yes' );
    }
}

WP_CLI::success( 'Páginas comerciales listas.' );
