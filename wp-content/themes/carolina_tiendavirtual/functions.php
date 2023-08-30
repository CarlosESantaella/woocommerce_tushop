<?php 

  // remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);


  remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
  add_Action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 1);

  add_filter('loop_shop_per_page', 'productos_por_pagina', 20);
  function productos_por_pagina($columnas){
    $columnas = 2;
    return $columnas;
  }

?>