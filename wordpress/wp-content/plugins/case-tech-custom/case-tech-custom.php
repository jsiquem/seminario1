<?php
/**
 * Plugin Name: Case-Tech Custom
 * Description: Funcionalidades propias del catálogo Case-Tech para WooCommerce.
 * Version: 0.2.0
 * Author: Case-Tech
 * Text Domain: case-tech-custom
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function case_tech_guatemala_states( $states ) {
    $states['GT'] = array(
        'AV' => 'Alta Verapaz',
        'BV' => 'Baja Verapaz',
        'CM' => 'Chimaltenango',
        'CQ' => 'Chiquimula',
        'PR' => 'El Progreso',
        'ES' => 'Escuintla',
        'GU' => 'Guatemala',
        'HU' => 'Huehuetenango',
        'IZ' => 'Izabal',
        'JA' => 'Jalapa',
        'JU' => 'Jutiapa',
        'PE' => 'Petén',
        'QZ' => 'Quetzaltenango',
        'QC' => 'Quiché',
        'RE' => 'Retalhuleu',
        'SA' => 'Sacatepéquez',
        'SM' => 'San Marcos',
        'SR' => 'Santa Rosa',
        'SO' => 'Sololá',
        'SU' => 'Suchitepéquez',
        'TO' => 'Totonicapán',
        'ZA' => 'Zacapa',
    );

    return $states;
}
add_filter( 'woocommerce_states', 'case_tech_guatemala_states' );

function case_tech_only_guatemala( $countries ) {
    return array( 'GT' => 'Guatemala' );
}
add_filter( 'woocommerce_countries_allowed_countries', 'case_tech_only_guatemala' );
add_filter( 'woocommerce_countries_shipping_countries', 'case_tech_only_guatemala' );

function case_tech_custom_shop_page_id() {
    $shop_id = absint( get_option( 'woocommerce_shop_page_id' ) );
    if ( ! $shop_id && function_exists( 'wc_get_page_id' ) ) {
        $shop_id = absint( wc_get_page_id( 'shop' ) );
    }
    if ( ! $shop_id ) {
        $shop_page = get_page_by_path( 'shop', OBJECT, 'page' );
        $shop_id   = $shop_page ? absint( $shop_page->ID ) : 0;
    }
    if ( ! $shop_id ) {
        $shop_page = get_page_by_title( 'Shop', OBJECT, 'page' );
        $shop_id   = $shop_page ? absint( $shop_page->ID ) : 0;
    }

    return $shop_id;
}

function case_tech_custom_catalog_form_url() {
    $shop_id = case_tech_custom_shop_page_id();
    return $shop_id
        ? add_query_arg( array( 'page_id' => $shop_id ), home_url( '/' ) )
        : home_url( '/?post_type=product' );
}

function case_tech_catalog_is_archive( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return false;
    }

    $shop_id = case_tech_custom_shop_page_id();
    if ( $shop_id && $query->is_page( $shop_id ) ) {
        return true;
    }

    return $query->is_post_type_archive( 'product' ) || $query->is_tax( 'product_cat' );
}

function case_tech_catalog_filter_query( $query ) {
    if ( ! case_tech_catalog_is_archive( $query ) ) {
        return;
    }

    $tax_query = (array) $query->get( 'tax_query' );

    if ( ! empty( $_GET['case_brand'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => sanitize_title( wp_unslash( $_GET['case_brand'] ) ),
        );
    }

    if ( ! empty( $_GET['case_model'] ) && taxonomy_exists( 'pa_modelo-compatible' ) ) {
        $tax_query[] = array(
            'taxonomy' => 'pa_modelo-compatible',
            'field'    => 'slug',
            'terms'    => sanitize_title( wp_unslash( $_GET['case_model'] ) ),
        );
    }

    if ( ! empty( $_GET['case_stock'] ) && 'available' === sanitize_key( wp_unslash( $_GET['case_stock'] ) ) ) {
        $meta_query = (array) $query->get( 'meta_query' );
        $meta_query[] = array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '=',
        );
        $query->set( 'meta_query', $meta_query );
    }

    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
        $query->set( 'tax_query', $tax_query );
    } elseif ( ! empty( $tax_query ) ) {
        $query->set( 'tax_query', $tax_query );
    }
}
add_action( 'pre_get_posts', 'case_tech_catalog_filter_query' );

function case_tech_catalog_filters() {
    $shop_id = case_tech_custom_shop_page_id();
    $is_shop_page = $shop_id && is_page( $shop_id );
    if ( ! $is_shop_page && ( ! function_exists( 'is_shop' ) || ( ! is_shop() && ! is_product_taxonomy() ) ) ) {
        return;
    }

    $brand_root = get_term_by( 'slug', 'cases', 'product_cat' );
    $brands = get_terms(
        array(
            'taxonomy'   => 'product_cat',
            'parent'     => $brand_root ? (int) $brand_root->term_id : 0,
            'hide_empty' => true,
        )
    );
    $models = array();

    if ( is_wp_error( $brands ) ) {
        $brands = array();
    }
    if ( is_wp_error( $models ) ) {
        $models = array();
    }

    $current_brand = isset( $_GET['case_brand'] ) ? sanitize_title( wp_unslash( $_GET['case_brand'] ) ) : '';
    $current_model = isset( $_GET['case_model'] ) ? sanitize_title( wp_unslash( $_GET['case_model'] ) ) : '';
    $current_stock = isset( $_GET['case_stock'] ) ? sanitize_key( wp_unslash( $_GET['case_stock'] ) ) : '';

    if ( is_product_category() ) {
        $queried = get_queried_object();
        if ( $queried && ! empty( $queried->slug ) && 'cases' !== $queried->slug ) {
            $current_brand = $queried->slug;
        }
    }

    if ( taxonomy_exists( 'pa_modelo-compatible' ) ) {
        $model_args = array(
            'taxonomy'   => 'pa_modelo-compatible',
            'hide_empty' => true,
        );

        if ( $current_brand ) {
            $brand_term = get_term_by( 'slug', $current_brand, 'product_cat' );
            if ( $brand_term && ! is_wp_error( $brand_term ) ) {
                $product_ids = get_objects_in_term( (int) $brand_term->term_id, 'product_cat' );
                $model_args['object_ids'] = is_wp_error( $product_ids ) ? array() : $product_ids;
            } else {
                $model_args['object_ids'] = array( 0 );
            }
        }

        $models = get_terms( $model_args );
    }

    $model_brands = array();
    foreach ( $brands as $brand ) {
        $brand_product_ids = get_objects_in_term( (int) $brand->term_id, 'product_cat' );
        if ( is_wp_error( $brand_product_ids ) || empty( $brand_product_ids ) ) {
            continue;
        }
        $brand_models = get_terms(
            array(
                'taxonomy'   => 'pa_modelo-compatible',
                'object_ids' => $brand_product_ids,
                'hide_empty' => false,
            )
        );
        if ( is_wp_error( $brand_models ) ) {
            continue;
        }
        foreach ( $brand_models as $brand_model ) {
            $model_brands[ $brand_model->slug ][] = $brand->slug;
        }
    }
    ?>
    <section class="case-catalog-filters" aria-labelledby="case-filter-title">
        <div class="case-filter-heading">
            <span class="eyebrow">Encuentra tu modelo</span>
            <h2 id="case-filter-title">Filtra por compatibilidad</h2>
        </div>
        <form class="case-filter-form" method="get" action="<?php echo esc_url( case_tech_custom_catalog_form_url() ); ?>">
            <label>
                <span>Marca</span>
                <select name="case_brand">
                    <option value="">Todas las marcas</option>
                    <?php foreach ( $brands as $brand ) : ?>
                        <option value="<?php echo esc_attr( $brand->slug ); ?>" <?php selected( $current_brand, $brand->slug ); ?>><?php echo esc_html( $brand->name ); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Modelo compatible</span>
                <select name="case_model">
                    <option value="">Todos los modelos</option>
                    <?php foreach ( $models as $model ) : ?>
                        <option value="<?php echo esc_attr( $model->slug ); ?>" data-brands="<?php echo esc_attr( implode( ',', array_unique( $model_brands[ $model->slug ] ?? array() ) ) ); ?>" <?php selected( $current_model, $model->slug ); ?>><?php echo esc_html( $model->name ); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Disponibilidad</span>
                <select name="case_stock">
                    <option value="">Todos</option>
                    <option value="available" <?php selected( $current_stock, 'available' ); ?>>En existencia</option>
                </select>
            </label>
            <button class="button button-primary" type="submit">Aplicar filtros</button>
            <?php if ( $current_brand || $current_model || $current_stock ) : ?>
                <a class="case-filter-reset" href="<?php echo esc_url( case_tech_custom_catalog_form_url() ); ?>">Limpiar</a>
            <?php endif; ?>
        </form>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('.case-filter-form');
            if (!form) return;
            var brand = form.querySelector('[name="case_brand"]');
            var model = form.querySelector('[name="case_model"]');
            if (!brand || !model) return;
            var options = Array.prototype.slice.call(model.options);
            function syncModels() {
                var selectedBrand = brand.value;
                options.forEach(function (option) {
                    if (!option.value) return;
                    var brands = (option.getAttribute('data-brands') || '').split(',');
                    var visible = !selectedBrand || brands.indexOf(selectedBrand) !== -1;
                    option.hidden = !visible;
                    if (!visible && option.selected) model.value = '';
                });
            }
            brand.addEventListener('change', syncModels);
            syncModels();
        });
        </script>
    </section>
    <?php
}
add_action( 'woocommerce_before_shop_loop', 'case_tech_catalog_filters', 8 );

function case_tech_product_model_label( $product ) {
    if ( ! $product instanceof WC_Product ) {
        return '';
    }

    $models = wc_get_product_terms( $product->get_id(), 'pa_modelo-compatible', array( 'fields' => 'names' ) );
    if ( ! empty( $models ) && ! is_wp_error( $models ) ) {
        return implode( ', ', $models );
    }

    $model = get_post_meta( $product->get_id(), '_case_tech_model', true );
    $brand = get_post_meta( $product->get_id(), '_case_tech_brand', true );

    return trim( $brand . ' ' . $model );
}

function case_tech_product_sales_summary() {
    global $product;

    if ( ! $product instanceof WC_Product ) {
        return;
    }

    $model_label = case_tech_product_model_label( $product );
    $stock_label = $product->is_in_stock() ? 'En existencia' : 'Agotado';
    $stock_class = $product->is_in_stock() ? 'is-available' : 'is-unavailable';
    ?>
    <section class="case-product-summary" aria-label="Información de compra">
        <?php if ( $model_label ) : ?>
            <div class="case-product-compatibility"><span>Compatible con</span><strong><?php echo esc_html( $model_label ); ?></strong></div>
        <?php endif; ?>
        <div class="case-product-facts">
            <span class="case-stock <?php echo esc_attr( $stock_class ); ?>"><?php echo esc_html( $stock_label ); ?></span>
            <span>Envío nacional: <strong>Q25.00</strong></span>
            <span>Pago: <strong>Transferencia bancaria</strong></span>
        </div>
    </section>
    <?php
}
add_action( 'woocommerce_single_product_summary', 'case_tech_product_sales_summary', 25 );

function case_tech_product_compatibility_notice() {
    global $product;

    if ( ! $product instanceof WC_Product ) {
        return;
    }
    ?>
    <div class="case-compatibility-notice" role="note">
        <strong>Antes de comprar:</strong> verifica el modelo exacto de tu teléfono en Ajustes del dispositivo para confirmar la compatibilidad.
    </div>
    <?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'case_tech_product_compatibility_notice', 5 );

function case_tech_product_trust_strip() {
    ?>
    <div class="case-product-trust" aria-label="Beneficios de compra">
        <span>✓ Producto demo nuevo</span>
        <span>✓ Envío a Guatemala</span>
        <span>✓ Compra administrada por WooCommerce</span>
    </div>
    <?php
}
add_action( 'woocommerce_after_add_to_cart_form', 'case_tech_product_trust_strip', 10 );

function case_tech_checkout_notice() {
    if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() ) {
        return;
    }
    ?>
    <div class="case-checkout-notice" role="note">
        <strong>Compra demo para Guatemala:</strong> envío nacional de Q25.00 y pago mediante transferencia bancaria directa. Los datos bancarios de esta instalación son ficticios.
    </div>
    <?php
}
add_action( 'woocommerce_before_checkout_form', 'case_tech_checkout_notice', 8 );
