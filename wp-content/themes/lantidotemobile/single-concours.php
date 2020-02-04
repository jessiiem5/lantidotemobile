<?php get_header(); ?>

<section id="single-concours">
  <div class="container">
    <?php $image = get_field('image'); ?>
    <img src="<?= $image['url']; ?>" alt="<?= $image['title']; ?>">
    <div class="row p-0">
      <div class="col-8">
        <h4 class="mt-3"><?php the_title(); ?></h4>
        <h5>Détails du concours</h5>
        <p class="mb-4"><?php the_field('details'); ?></p>
        <h5 class="mt-5 mb-3">Modalités</h5>
        <p><?php the_field('modalites'); ?></p>
      </div>
      <div class="col-4">
        <?= do_shortcode( get_field('shortcode') ); ?>
      </div>
    </div>
  </div>
</section>


<?php get_footer(); ?>
