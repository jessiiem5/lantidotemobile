<?php
/*
Template Name: Listing-concours
*/
get_header(); ?>

<?php $posts = get_posts( array(
  'post_type'         => 'concours',
  'posts_per_page'    => 50,
  'orderby'           => 'date',
  'order'             => 'desc',
  'suppress_filters' => 0 ));
?>
<section id="archive-concours">
  <div class="container">
    <div class="text-center">
      <h2>Les concours Antidote Mobile</h2>
      <p class="sous-titre">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
    </div>
    <?php if( $posts ): ?>
    <div class="row">
    <?php $cpt = 1; ?>
      <?php foreach( $posts as $post ): ?>
        <?php setup_postdata( $post ); ?>
        <?php $image = get_field('image') ?>
          <?php if ($cpt == 1): ?>
            <div class="col-12 p-3 d-block d-lg-flex main">
              <div class="col-12 col-lg-8 p-0">
                <img src="<?= $image['url']; ?>" alt="<?= $image['title']; ?>">
              </div>
              <div class="col-12 col-lg-4 texte">
                <h4><?php the_field('titre_introductif'); ?></h4>
                <p><i class="fas fa-map-marker-alt"></i> <?php the_field('lieux'); ?></p>
                <p><?php the_field('texte_introductif'); ?></p>
                <a class="btn-plus" href="<?php the_permalink(); ?>"><span>Voir le concours</span></a>
              </div>
              </div>
          <?php else: ?>
            <div class="col-12 col-lg-6 p-3 mt-4 min">
              <img src="<?= $image['url']; ?>" alt="<?= $image['title']; ?>">
              <div class="texte">
                <h5 class="col-12 col-md-6 p-0"><?php the_field('titre_introductif'); ?></h5>
                <div class="offset-12"></div>
                <div class="d-flex justify-content-between align-items-center">
                  <p><i class="fas fa-map-marker-alt"></i> <?php the_field('lieux'); ?></p>
                  <a class="btn-plus" href="<?php the_permalink(); ?>"><span>Voir le concours</span></a>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <?php $cpt++; ?>
        <?php endforeach; ?>
      <?php wp_reset_postdata(); ?>
      </div>
    <?php endif; ?>
  </div>
</section>


<?php get_footer(); ?>
