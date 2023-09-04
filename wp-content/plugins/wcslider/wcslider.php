<?php

/**
 * Plugin Name: BXSlider para wordpress
 * Plugin URI: 
 * Description: añade bxslider a woocommerce
 * Version: 1.0
 * Author: Carlos Santaella
 * Author URI:
 * Text Domain:
 * Domain Path:
 */

// No permitir que se cargue directamente
defined('ABSPATH') or die('No Script kiddies please!');

define('WCSLIDER_PATH', plugin_dir_url(__FILE__));
//  cargar scripts y css
function wcslider_scripts()
{
  wp_enqueue_style('bxslider', WCSLIDER_PATH . '/css/jquery.bxslider.min.css');
  if (wp_script_is('jquery', 'enqueued')) {
    
  } else {
    wp_enqueue_script('jquery');
  }
  wp_enqueue_script('bxsliderjs', WCSLIDER_PATH . '/js/jquery.bxslider.min.js');
}
add_action('wp_enqueue_scripts', 'wcslider_scripts');


// crear el shortcode para mostrar productos
function wcslider_shortcode()
{
  $args = array(
    'posts_per_page' => 10,
    'post_type' => 'product',
    'tax_query' => array(
      array(
        'taxonomy' => 'product_visibility',
        'field' => 'name',
        'terms' => 'featured',
        'operator' => 'IN'
      ),
    )
  );
  $slider_productos = new WP_Query($args);
  echo "<ul class='slider'>";
  while ($slider_productos->have_posts()) : $slider_productos->the_post();
?>

    <li>
      <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail('medium'); ?>
        <?php the_title('<h3>', '</h3>') ?>
      </a>
    </li>

  <?php
  endwhile;
  wp_reset_postdata();
  echo "</ul>";
}
add_shortcode('wcslider', 'wcslider_shortcode');


// agregar metodo bxslider en el footer
function wcslider_ejecutar()
{
  ?>
  <script>
    
    jQuery(document).ready(function() {
      jQuery('.slider').bxSlider({
        auto: true,
        minSlides: 4,
        maxSlides: 4,
        slideWidth: 250,
        slideMargin: 10,
        moveSlides: 1
      });
    });
  </script>
<?php
}
add_action('wp_footer', 'wcslider_ejecutar', 100);
