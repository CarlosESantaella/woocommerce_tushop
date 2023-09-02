<?php

// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);


remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_Action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 1);

add_filter('loop_shop_per_page', 'productos_por_pagina', 20);
function productos_por_pagina($productos)
{
  $productos = 7;
  return $productos;
}

//columnas por página en tienda
add_filter('loop_shop_columns', 'carolinaspa_columnas', 20);
function carolinaspa_columnas($columnas)
{
  return 4;
}

//Cambiar a pesos mexicanos (MXN)
add_filter('woocommerce_currency_symbol', 'carolinaspa_mxn', 10, 2);

function carolinaspa_mxn($simbolo, $moneda)
{
  $simbolo = 'MXN $';

  return $simbolo;
}

//modificar los creditos del footer
function carolinaspa_creditos()
{
  remove_action('storefront_footer', 'storefront_credit', 20);
  add_action('storefront_after_footer', 'carolinaspa_nuevo_footer', 20);
}
add_action('init', 'carolinaspa_creditos');

function carolinaspa_nuevo_footer()
{
  echo "<div class='reservados'>";
  echo "Derechos Reservados &copy; " . get_bloginfo('name') . " " . get_the_date("Y");
  echo "</div>";
}

// Agregar imagen al homepage

function carolinaspa_descuento()
{
  $imagen = '<div class="destacada">';

  $imagen .= '<img src="' . get_stylesheet_directory_uri() . '/img/cupon.jpg' . '" />';
  $imagen .= '</div>';
  echo $imagen;
}
add_action('storefront_page_before', 'carolinaspa_descuento', 5);


// Crear nueva sección en el home
add_action('storefront_page_before', 'carolinaspa_spacasa_homepage', 30);
function carolinaspa_spacasa_homepage()
{
  echo "<div class='spa-en-casa'>";
  echo "<div class='imagen-categoria'>";
  $imagen = get_term_meta(20, 'thumbnail_id', true);
  $imagen_categoria = wp_get_attachment_image_src($imagen, 'full');

  if ($imagen_categoria) {
    echo "<div class='imagen-destacada' style='background-image: url(" . $imagen_categoria[0] . ")'></div>";
    echo "<h1>Spa en casa</h1>";
    echo "</div>";
  }
  echo "<div class='productos'>";

  echo do_shortcode('[product_category columns="3" category="spa-en-casa"]');

  echo "</div>";
  echo "</div>";
}

//mostrar 4 categorias en el homepage

function carolinaspa_categorias($args)
{
  $args['limit'] = 4;
  $args['columns'] = 4;
  return $args;
}
add_filter('storefront_product_categories_args', 'carolinaspa_categorias', 100);

//cambiar texto a filtro
add_filter('woocommerce_catalog_orderby', 'carolinaspa_cambiar_sort', 40);

function carolinaspa_cambiar_sort($filtro)
{
  $filtro['date'] = __('Nuevos productos primero', 'woocommerce');
  return $filtro;
}

// remover tabs
add_filter('woocommerce_product_tabs', 'carolinaspa_remover_tabs', 11);
function carolinaspa_remover_tabs($tabs)
{
  // unset($tabs['description']);
  return $tabs;
}

// mostrar descuento en cantidad

// add_filter('woocommerce_get_price_html', 'carolinaspa_cantidad_ahorrada', 10, 2);

// function carolinaspa_cantidad_ahorrada($precio, $producto)
// {
//   if($producto->sale_price){
//     $ahorro = wc_price($producto->regular_price - $producto->sale_price);
//     return $precio.sprintf(__('<br><span class="ahorro">Ahorro %s </span>', 'woocommerce'), $ahorro);
//   }
//   return $precio;
// }
// add_filter('woocommerce_get_price_html', 'carolinaspa_cantidad_ahorrada_porcentaje', 10, 2);

// function carolinaspa_cantidad_ahorrada_porcentaje($precio, $producto)
// {
//   if ($producto->sale_price) {
//     $porcentaje = round((($producto->regular_price - $producto->sale_price) / $producto->regular_price) * 100);
//     return $precio . sprintf(__('<br><span class="ahorro">Ahorro %s &#37; </span>', 'woocommerce'), $porcentaje);
//   }
//   return $precio;
// }

//Muestra el ahorro en porcentaje o en cantidad fija
add_filter('woocommerce_get_price_html', 'carolinaspa_mostrar_ahorro', 10, 2);

function carolinaspa_mostrar_ahorro($precio, $producto)
{
  $precio_regular = $producto->get_regular_price();
  if ($producto->sale_price) {
    if ($precio_regular <= 100) {
      $porcentaje = round((($producto->regular_price - $producto->sale_price) / $producto->regular_price) * 100);
      return $precio . sprintf(__('<br><span class="ahorro">Ahorro %s &#37; </span>', 'woocommerce'), $porcentaje);
    } else {
      $ahorro = wc_price($producto->regular_price - $producto->sale_price);
      return $precio . sprintf(__('<br><span class="ahorro">Ahorro %s </span>', 'woocommerce'), $ahorro);
    }
  }
  return $precio;
}

// cambiar tab descripción por el título del producto
add_filter('woocommerce_product_tabs', 'carolinaspa_titulo_tab_descripcion', 10, 1);

function carolinaspa_titulo_tab_descripcion($tabs)
{
  global $post;
  if (isset($tabs['description']['title'])) {
    $tabs['description']['title'] = $post->post_title;
  }
  return $tabs;
}

add_filter('woocommerce_product_description_heading', 'carolinaspa_titulo_contenido_tab', 10, 1);

function carolinaspa_titulo_contenido_tab($titulo)
{
  global $post;
  return $post->post_title;
}

// Imprimir subtitulo con advanced custom field
add_action('woocommerce_single_product_summary', 'carolinaspa_imprimir_subtitulo', 6);
function carolinaspa_imprimir_subtitulo()
{
  global $post;
  echo "<p class='subtitulo'>".get_field('subtitulo', $post->ID)."</p>";
}

// Nuevo tab para video con acf
add_filter('woocommerce_product_tabs', 'carolinaspa_agregar_tab_video', 11, 1);
function carolinaspa_agregar_tab_video($tabs)
{
  // $tabs['video'] = 'Video';
  // echo '<pre>';
  // print_r($tabs);
  // echo '</pre>';
  $tabs['video'] = [
    'title' => 'Video',
    'priority' => 5,
    'callback' => 'mi_gran_video'
  ];
  return $tabs;
}
function mi_gran_video()
{
  global $post;
  $video = get_field('video', $post->ID);
  if($video){
    echo "<video controls loop>";
    echo "<source src='".$video."'>";

    echo "</video>";
  }else{
    echo "No hay video disponible";
  }
}