<?php
/*

Template Name: Contact

*/

get_header(); ?>

<section id="texte-contact">
  <div class="container">
    <div class="d-flex justify-content-center contactez-nous">
      <div class="col-3 text-center">
        <h1><?php the_field('titre_contactez-nous'); ?></h1>
        <p><?php the_field('texte_contactez-nous'); ?></p>
      </div>
    </div>
    <div class="row d-flex justify-content-center">
      <div class="col-4 telephone">
        <h2><?php the_field('titre_telephone'); ?></h2>
        <a href="tel:<?php the_field('lien_telephone'); ?>"><?php the_field('numero_telephone'); ?></a>
      </div>
      <div class="col-4 clavardage d-flex justify-content-center">
        <div class="col-10 pl-0 pr-5">
          <h2><?php the_field('titre_clavardez'); ?></h2>
          <p><?php the_field('texte_clavardez'); ?></p>
        </div>
      </div>
      <div class="col-3 email">
        <h2><?php the_field('titre_email'); ?></h2>
        <a href="<?php the_field('email'); ?>"><?php the_field('email'); ?></a>
      </div>
    </div>
  </div>
</section>

<section id="formulaire">
  <div class="container d-flex justify-content-center">
    <div class="col-8">
      <p class="offset-2 col-8 text-center"><?php the_field('texte_contact'); ?></p>
      <?php echo do_shortcode('[contact-form-7 id="5" title="Formulaire de contact"]'); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
