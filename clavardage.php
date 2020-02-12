<?php
/*

Template Name: Clavardage

*/

get_header(); ?>

<section id="support">
  <div class="container">

    <div class="col-12 text-center mb-5">
      <h1><?php the_field('titre_page_support'); ?></h1>
      <span class="sous-titre">
        <?php the_field('texte_page_support'); ?>
      </span>
    </div>

    <div class="row d-flex justify-content-center">
      <div class="col-12 col-sm-6 col-xl-4 clavardez p-2">
        <a href="#">
        <div class="support text-center">
          <div class="content w-100">
            <?php $image_clavardez = get_field('image_clavardez'); ?>
            <div class="image">
              <img src="<?= $image_clavardez['url']; ?>" alt="<?= $image_clavardez['title']; ?>">
            </div>
            <h4><?php the_field('titre_clavardez'); ?></h4>
            <p class="offset-2 col-8"><?php the_field('texte_clavardez') ?></p>
          </div>
        </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-xl-4 faq p-2">
        <div class="support text-center">
          <div class="content w-100">
            <?php $image_faq = get_field('image_faq'); ?>
            <div class="image">
              <img src="<?= $image_faq['url']; ?>" alt="<?= $image_faq['title']; ?>">
            </div>
            <h4><?php the_field('titre_faq'); ?></h4>
            <p class="offset-2 col-8"><?php the_field('texte_faq') ?></p>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-4 contact p-2">
        <div class="support text-center">
          <div class="content w-100">
            <?php $image_contact = get_field('image_contact'); ?>
            <div class="image">
              <img src="<?= $image_contact['url']; ?>" alt="<?= $image_contact['title']; ?>">
            </div>
            <h4><?php the_field('titre_contact'); ?></h4>
            <p class="offset-2 col-8"><?php the_field('texte_contact') ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
