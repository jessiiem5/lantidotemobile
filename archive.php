<?php
/*
Template Name: Archives
*/
get_header(); ?>


<?php $posts = get_posts( array(
  'post_type'         => 'post',
  'posts_per_page'    => 50,
  'orderby'           => 'date',
  'order'             => 'desc',
  'suppress_filters' => 0 ));
?>
<section id="blog">
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-9 order-2 order-lg-1">
        <?php if( $posts ): ?>
          <div class="row">
          <?php $cpt = 1; ?>
            <?php foreach( $posts as $post ): ?>
              <?php setup_postdata( $post ); ?>
              <?php $image = get_field('image'); ?>
              <?php if ($cpt == 1): ?>
                <div class="col-12 big p-2">
                  <div class="image">
                    <img src="<?= $image['url']; ?>" alt="<?= $image['title']; ?>">
                    <span>Idées de sorties</span>
                  </div>
                  <h1><?php the_title('') ?></h1>
                </div>
              <?php else: ?>
                <div class="col-12 col-md-6 col-xl-4 petit p-2">
                  <div class="image">
                    <img src="<?= $image['url']; ?>" alt="<?= $image['title']; ?>">
                    <span>Idées de sorties</span>
                  </div>
                  <h1><?php the_title('') ?></h1>
                </div>
                <?php endif; ?>
              <?php $cpt++; ?>
            <?php endforeach; ?>
          <?php wp_reset_postdata(); ?>
          </div>
      <?php endif; ?>
      </div>

      <div class="col-12 col-lg-3 order-1 order-lg-2 pt-2 pl-2 pr-2 pb-0">
        <div class="menu-category">
          <h4>Catégories</h4>
          <?php wp_nav_menu( array( 'theme_location' => 'menu-category' ) ); ?>
        </div>

        <?php $posts = get_posts( array(
          'post_type'         => 'post',
          'posts_per_page'    => 50,
          'orderby'           => 'date',
          'order'             => 'desc',
          'suppress_filters' => 0 ));
        ?>

        <?php wp_reset_postdata(); ?>
        <div class="menu-populaire mt-5">
          <h4>Articles populaires</h4>
          <?php if( $posts ): ?>
            <ul>
              <?php foreach( $posts as $post ): ?>
                <?php setup_postdata( $post ); ?>
                <?php $categories = get_the_category($post->ID);
                if(isset($categories->name))
                {
                  $category = $categories->name;
                }

                              ?>
                  <?php foreach( $categories as $category ) :?>
                  <?php $slug = $category->slug;  ?>
                  <?php endforeach; ?>
                  <?php if ($slug =="populaires"): ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                  <?php endif; ?>
            <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>


<?php get_footer(); ?>
