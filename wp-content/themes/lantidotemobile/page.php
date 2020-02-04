<?php get_header(); ?>

<h1>VOila</h1>
<section>
  <div class="container">
  <?php while ( have_posts() ) : the_post (); ?>
    <div id=”post-<?php the_ID(); ?>” <?php post_class(); ?>>
      <?php $product = wc_get_product( $post->ID ); ?>
      <?php var_dump($product); ?>
        <?php the_content(); ?>
      </div>
  <?php endwhile; ?>
  </div>
</section>


<?php get_footer ();?>
