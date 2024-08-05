<?php
/*
Plugin Name: Lista Proyectos Plugin
Description: Shortcode para listar proyectos con estilos personalizados y filtrado por nombre.
Version: 1.2
Author: Daniel Diaz Tag Marketing
Author URI: http://daniels35.com/
*/

// Función para normalizar texto
function normalizar_texto($texto) {
    $texto = strtolower($texto);
    $originales = 'áéíóúñ';
    $modificadas = 'aeioun';
    $texto = strtr($texto, $originales, $modificadas);
    return $texto;
}

// Función principal del shortcode
function lista_proyectos_shortcode() {
    ob_start();

    // Obtener los parámetros de consulta 'nombre'
    $nombres_filtro = array();
    if (isset($_GET['nombre1'])) {
        $nombres_filtro[] = normalizar_texto(sanitize_text_field($_GET['nombre1']));
    }
    if (isset($_GET['nombre2'])) {
        $nombres_filtro[] = normalizar_texto(sanitize_text_field($_GET['nombre2']));
    }

    // Consulta para obtener los productos
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );

    $products = new WP_Query($args);

    if ($products->have_posts()) :
        echo '<div class="filter-bar">';
        echo '<input type="text" id="search-bar" placeholder="Buscar productos...">';
        echo '</div>';
        echo '<div class="custom-product-list">';

        $background_colors = array("#E4002B", "#ACA39A", "#05202B");
        $color_index = 0;

        while ($products->have_posts()) : $products->the_post();
            $product_id = get_the_ID();
            $product_title = get_the_title();
            $product_image = get_the_post_thumbnail_url($product_id, 'full');
            $logo_proyecto_blanco = get_field('logo_proyecto_blanco', $product_id);
            $descripcion_corta = get_the_excerpt();
            $ciudad = get_field('ciudad', $product_id);
            $sector = get_field('sector', $product_id);
            $precio = get_field('precio', $product_id);
            $product_category = get_the_terms($product_id, 'product_cat'); // Categoría del producto
            $product_link = get_permalink(); // Obtiene el enlace del producto

            // Normalizar el título del producto
            $product_title_normalized = normalizar_texto($product_title);

            // Filtrar por nombres normalizados
            $mostrar_producto = empty($nombres_filtro);
            foreach ($nombres_filtro as $filtro) {
                if (strpos($product_title_normalized, $filtro) !== false) {
                    $mostrar_producto = true;
                    break;
                }
            }

            if ($mostrar_producto) {
                echo '<div class="custom-product" style="background-color: ' . $background_colors[$color_index] . ';" data-ciudad="' . $ciudad . '" data-categoria="' . $product_category[0]->slug . '" onclick="location.href=\'' . $product_link . '\'">';
                echo '<div class="product-image"><img src="' . $product_image . '" alt="' . $product_title . '"></div>';

                if ($logo_proyecto_blanco) {
                    echo '<div class="logo-proyecto"><img src="' . $logo_proyecto_blanco . '" alt="Logo Proyecto Blanco"></div>';
                }

                echo '<div class="product-content">';
                
                echo '<div class="product-description">' . $descripcion_corta . '</div>';

                echo '<div class="product-details">';
                echo '<div class="product-city"><i class="fa fa-globe"></i> ' . $ciudad . '</div>';
                echo '<div class="product-sector"><i class="fa fa-map-marker" aria-hidden="true"></i> ' . $sector . '</div>';
                echo '</div>';

                echo '<div class="product-price"><i class="fa fa-dollar-sign"></i> Desde: ' . $precio . '</div>';
                echo '<a href="' . get_permalink() . '" class="ver-proyecto">Ver Proyecto</a>';
                echo '</div>';
                echo '</div>';

                $color_index = ($color_index + 1) % 3; // Alternar entre los colores
            }
        endwhile;

        echo '</div>';
        wp_reset_postdata();
    endif;

    return ob_get_clean();
}
add_shortcode('lista_proyectos', 'lista_proyectos_shortcode');
function proyecto_ejecutado_shortcode() {
    ob_start();

    // Consulta para obtener los productos
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );
    $products = new WP_Query($args);

    if ($products->have_posts()) :
        echo '<div class="executed-projects-wrapper">';
        echo '<div class="executed-projects-container">';
        echo '<button class="executed-projects-prev">&lt;</button>';
        echo '<div class="executed-projects">';

        $background_colors = array("#E4002B", "#ACA39A", "#05202B");
        $color_index = 0;

        while ($products->have_posts()) : $products->the_post();
            $product_id = get_the_ID();
            $product_title = get_the_title();
            $product_image = get_the_post_thumbnail_url($product_id, 'full');
            $logo_proyecto_blanco = get_field('logo_proyecto_blanco', $product_id);
            $descripcion_corta = get_the_excerpt();
            $ciudad = get_field('ciudad', $product_id);
            $sector = get_field('sector', $product_id);
            $precio = get_field('precio', $product_id);
            $product_category = get_the_terms($product_id, 'product_cat'); // Categoría del producto
            $product_link = get_permalink(); // Obtiene el enlace del producto

            echo '<div class="custom-project" style="background-color: ' . $background_colors[$color_index] . ';" data-ciudad="' . $ciudad . '" data-categoria="' . $product_category[0]->slug . '" onclick="location.href=\'' . $product_link . '\'">';
            echo '<div class="product-image"><img src="' . $product_image . '" alt="' . $product_title . '"></div>';

            if ($logo_proyecto_blanco) {
                echo '<div class="logo-proyecto"><img src="' . $logo_proyecto_blanco . '" alt="Logo Proyecto Blanco"></div>';
            }

            echo '<div class="product-content">';
            
            echo '<div class="product-description">' . $descripcion_corta . '</div>';

            echo '<div class="product-details">';
            echo '<div class="product-city"><i class="fa fa-globe"></i> ' . $ciudad . '</div>';
            echo '<div class="product-sector"><i class="fa fa-map-marker" aria-hidden="true"></i> ' . $sector . '</div>';
            echo '</div>';

            echo '<div class="product-price"><i class="fa fa-dollar-sign"></i> Desde: ' . $precio . '</div>';
            echo '<a href="' . get_permalink() . '" class="ver-proyecto">Ver Proyecto</a>';
            echo '</div>';
            echo '</div>';

            $color_index = ($color_index + 1) % 3; // Alternar entre los colores
        endwhile;

        echo '</div>';
        echo '<button class="executed-projects-next">&gt;</button>';
        echo '</div>';
        wp_reset_postdata();
    endif;

    return ob_get_clean();
}
add_shortcode('proyecto_ejecutado', 'proyecto_ejecutado_shortcode');


function lista_proyectos_enqueue_styles() {
    wp_enqueue_style('lista-proyectos-styles', plugin_dir_url(__FILE__) . 'styles.css');
    wp_enqueue_script('lista-proyectos-scripts', plugin_dir_url(__FILE__) . 'scripts.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'lista_proyectos_enqueue_styles');
?>
