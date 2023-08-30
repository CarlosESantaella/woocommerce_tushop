<?php 

  // remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);


  remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
  add_Action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 1);

  add_filter('loop_shop_per_page', 'productos_por_pagina', 20);
  function productos_por_pagina($columnas){
    $columnas = 2;
    return $columnas;
  }

  //Cambiar a pesos mexicanos (MXN)
  add_filter('woocommerce_currency_symbol', 'carolinaspa_mxn', 10, 2);

  function carolinaspa_mxn($simbolo, $moneda){
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
    echo "Derechos Reservados &copy; ".get_bloginfo('name')." ".get_the_date("Y");
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

  //mostrar 4 categorias en el homepage

  function carolinaspa_categorias($args)
  {
    $args['limit'] = 4;
    $args['columns'] = 4;
    return $args;
  }
  add_filter('storefront_product_categories_args', 'carolinaspa_categorias', 100);