<?php get_header(); ?>


<section>
  <div class="container">
  <?php while ( have_posts() ) : the_post (); ?>
    <div id=”post-<?php the_ID(); ?>” <?php post_class(); ?>>
      <?php $product = wc_get_product( $post->ID ); ?>
      <?php var_dump($product); ?>
<<<<<<< HEAD
      <p>absdhjaskd</p>
        <?php the_content(); ?>
=======
        <?php the_content(); echo 'Hello'; ?>
>>>>>>> 75f3749bb5b3375cc04b23cf6fa5b540753fb167
      </div>
  <?php endwhile; ?>
  </div>
</section>


<?php get_footer ();?>
