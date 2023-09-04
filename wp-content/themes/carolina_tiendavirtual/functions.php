<?php
function carolinaspa_cambiar_agregar_carrito(){
  return 'Contratar Servicio';
}
add_filter('woocommerce_product_add_to_cart_text', 'carolinaspa_cambiar_agregar_carrito');
add_filter('woocommerce_product_single_add_to_cart_text', 'carolinaspa_cambiar_agregar_carrito');

function carolinaspa_admin_estilos(){
  wp_enqueue_style('admin-estilos', get_stylesheet_directory_uri(). '/login/login.css');
}
add_action('login_enqueue_scripts', 'carolinaspa_admin_estilos');

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
  if (is_front_page()) {

    $imagen = '<div class="destacada">';

    $imagen .= '<img src="' . get_stylesheet_directory_uri() . '/img/cupon.jpg' . '" />';
    $imagen .= '</div>';
    echo $imagen;
  }
}
add_action('storefront_page_before', 'carolinaspa_descuento', 5);


// Crear nueva sección en el home
add_action('storefront_page_before', 'carolinaspa_spacasa_homepage', 30);
function carolinaspa_spacasa_homepage()
{
  if (is_front_page()) {
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
  echo "<p class='subtitulo'>" . get_field('subtitulo', $post->ID) . "</p>";
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
  if ($video) {
    echo "<video controls loop>";
    echo "<source src='" . $video . "'>";

    echo "</video>";
  } else {
    echo "No hay video disponible";
  }
}

// Boton para vaciar el carrito
add_action('woocommerce_cart_actions', 'carolinaspa_limpiar_carrito');
function carolinaspa_limpiar_carrito()
{
  echo "<a class='button' href='?vaciar-carrito=true' >" . __('Vaciar Carrito', 'woocommerce') . "</a>";
}

// Vaciar el carrito
add_action('init', 'carolinaspa_vaciar_carrito');
function carolinaspa_vaciar_carrito()
{
  if (isset($_GET['vaciar-carrito'])) {
    global $woocommerce;
    $woocommerce->cart->empty_cart();
  }
}

//Imprimir banner en el carrito
add_action('woocommerce_check_cart_items', 'carolinaspa_imprimir_banner_carrito', 10);
function carolinaspa_imprimir_banner_carrito()
{
  global $post;
  $imagen = get_field('imagen', $post->ID);
  if ($imagen) {
    echo "<div class='cupon-carrito'>";

    echo "<img src='" . $imagen . "'>";
    echo "</div>";
  }
}

// Eliminar un campo del checkout
add_filter('woocommerce_checkout_fields', 'carolinaspa_remover_telefono_checkout', 20, 1);
function carolinaspa_remover_telefono_checkout($campos)
{
  unset($campos['billing']['billing_phone']);
  // $campos['billing']['billing_email']['class'] = array('form-row-wide');
  return $campos;
}

//agregar campos en checkout
add_filter('woocommerce_checkout_fields', 'carolinaspa_rfc', 40, 1);
function carolinaspa_rfc($campos)
{

  $campos['billing']['factura'] = array(
    'css' => array('form-row-wide'),
    'label' => 'Requiere factura?',
    'type' => 'checkbox',
    'id' => 'factura'
  );

  $campos['billing']['rfc'] = array(
    'label' => 'RFC',
    'css' => array('form-row-wide'),
    'required' => 0,
    'priority' => 120
  );

  $campos['order']['escuchaste_nosotros'] = array(
    'type' => 'select',
    'css' => array('form-row-wide'),
    'label' => '¿Como te enteraste de nosotros?',
    'options' => array(
      'default' => 'Seleccione...',
      'tv' => 'TV',
      'radio' => 'Radio',
      'periodico' => 'Periodico',
      'internet' => 'Facebook'
    )
  );

  return $campos;
}

/** Ocultar / mostrar en checkout el rfc */
function carolinaspa_mostrar_rfc()
{
  if (is_checkout()) { ?>
    <script>
      jQuery(document).ready(function() {
        jQuery('input[type="checkbox"]#factura').on('change', function() {
          jQuery('#rfc_field').slideToggle();
        })
      })
    </script>

  <?php }
}
add_action('wp_footer', 'carolinaspa_mostrar_rfc');

/** insertar campos perzonalizados en base de datos en checkout */

add_action('woocommerce_checkout_update_order_meta', 'carolinaspa_insertar_campos_personalizados', 10);
function carolinaspa_insertar_campos_personalizados($orden_id)
{
  if (!empty($_POST['rfc'])) {
    update_post_meta($orden_id, 'RFC', sanitize_text_field($_POST['rfc']));
  }
  if (!empty($_POST['factura'])) {
    update_post_meta($orden_id, 'factura', sanitize_text_field($_POST['factura']));
  }
  if (!empty($_POST['escuchaste_nosotros'])) {
    update_post_meta($orden_id, 'escuchaste_nosotros', sanitize_text_field($_POST['escuchaste_nosotros']));
  }
}

/** agregar columnas personalizadas a las ordenes */
add_filter('manage_edit-shop_order_columns', 'carolinaspa_columnas_ordenes');
function carolinaspa_columnas_ordenes($columnas)
{
  echo '<pre>';
  print_r($columnas);
  echo '</pre>';
  $columnas['factura'] = __('Factura', 'woocommerce');
  $columnas['rfc'] = __('RFC', 'woocommerce');
  $columnas['escuchaste_nosotros'] = __('Escuchaste nosotros', 'woocommerce');
  return $columnas;
}

/** mostrar constenido dentro de las columnas */
add_action('manage_shop_order_posts_custom_column', 'carolinaspa_columnas_informacion');
function carolinaspa_columnas_informacion($columnas)
{
  global $post, $woocommerce, $order;


  //obtiene los valores de la orden (se pasa el id de la orden)
  if (empty($order) || ($order->id ?? '') != ($post->ID ?? '')) {
    $order = new WC_Order($post->ID);
  }
  if ($columnas == 'factura') {
    $factura =  get_post_meta($post->ID, 'factura', true);

    if ($factura) {
      echo 'Si';
    }
  }

  if ($columnas == 'rfc') {
    echo get_post_meta($post->ID, 'rfc', true);
  }
  if ($columnas == 'escuchaste_nosotros') {
    echo get_post_meta($post->ID, 'escuchaste_nosotros', true);
  }
}

//mostrando los campos personalizados en pedidos
add_action('woocommerce_admin_order_data_after_billing_address', 'carolinaspa_mostrar_informacion_ordenes');
function carolinaspa_mostrar_informacion_ordenes($pedido)
{
  global $post;
  $factura = get_post_meta($pedido->ID, 'factura', true);
  if ($factura) {
    echo '<p><strong>' . __('Factura', 'woocommerce') . ':</strong>Si</p>';
    echo '<p><strong>' . __('RFC', 'woocommerce') . ':</strong>' . get_post_meta($pedido->id, 'rfc', true) . '</p>';
  }
  echo '<p><strong>' . __('Escuchaste Nosotros', 'woocommerce') . ':</strong>' . get_post_meta($pedido->id, 'escuchaste_nosotros', true) . '</p>';
}

/** mostrar iconos de caracteristicas de la tienda */
function carolinaspa_mostrar_iconos()
{
  if (is_front_page()) :

  ?>
    </main>
    </div>
    </div>

    <div class="iconos-inicio">
      <div class="col-full">
        <div class="columns-4">
          <?php the_field('icono_1'); ?>
          <?php the_field('descripcion_icono_1') ?>
        </div>
        <div class="columns-4">
          <?php the_field('icono_2'); ?>
          <?php the_field('descripcion_icono_2') ?>
        </div>
        <div class="columns-4">
          <?php the_field('icono_3'); ?>
          <?php the_field('descripcion_icono_3') ?>
        </div>
      </div>
    </div>

    <div class="col-full">
      <div class="content-area">
        <main class="site-main">


        <?php
      endif;
    }
    add_action('storefront_page_after', 'carolinaspa_mostrar_iconos', 15);

    // mostrar placeholder de imagenes
    add_filter('woocommerce_placeholder_img_src', 'carolinaspa_no_imagen_destacada');
    function carolinaspa_no_imagen_destacada($imagen_url)
    {
      $imagen_url = get_stylesheet_directory_uri() . '/img/no-imagen.png';
      return $imagen_url;
    }

    // imprimir entradas de blog en el 
    function carolinaspa_entradas_block()
    {
      if (is_front_page()) {
        $args = array(
          'post_type' => 'post',
          'posts_per_page' => 3,
          'orderBy' => 'date',
          'order' => 'DESC'
        );
        $entradas = new WP_Query($args);
        ?>
          <div class="entradas-blog">
            <h2 class="secion-title">Últimas entradas de blog</h2>
            <ul>
              <?php while ($entradas->have_posts()) : $entradas->the_post(); ?>
                <li>
                  <?php the_post_thumbnail('shop_catalog'); ?>
                  <?php the_title('<h3>', '</h3>'); ?>
                  <div class="contenido-entrada">
                    <header class="encabezado-entrada">
                      <p>Por: <?php the_author(); ?> | <?php the_time(get_option('date_format')); ?></p>
                    </header>
                    <?php
                    $contenido = wp_trim_words(get_the_content(), 20, '..');
                    echo $contenido;
                    ?>
                    <footer class="footer-entrada">
                      <a href="<?php the_permalink(); ?>" class="enlace-entrada button">Ver más</a>
                    </footer>
                  </div>
                </li>

              <?php endwhile;
              wp_reset_postdata(); ?>
            </ul>
          </div>
        <?php
      }
    }
    add_action('storefront_page_after', 'carolinaspa_entradas_block', 80);

    // productos relacionados
    function carolinaspa_productos_relacionados()
    {
      global $post;
      $productos_relacionados = get_field('productos_relacionados', $post->ID);

      if ($productos_relacionados) :
        ?>

          <div class="productos_relacionados">
            <h2 class="section-title">Productos Relacionados</h2>
            <?php
            $ids = implode(', ', $productos_relacionados);
            echo do_shortcode('[products ids="' . $ids . '"]');
            ?>

          </div>


      <?php
      endif;
    }
    add_action('storefront_post_content_after', 'carolinaspa_productos_relacionados');



    function carolinaspa_slider()
    {
      if (is_front_page()) {
        echo do_shortcode('[wcslider]');
      }
    }
    add_action('storefront_page_before', 'carolinaspa_slider', 5);

    function carolinaspa_redireccionar_home()
    {
      return home_url();
    }
    add_filter('login_headerurl', 'carolinaspa_redireccionar_home');