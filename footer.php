<footer>
  <h1>MODIF ADAM</h1>
  <div class="container">
    <div id="infolettre" class="col-12 offset-0 offset-md-1 col-md-10 text-center">
      <h5><?php the_field('titre_infolettre','option'); ?></h5>
      <div class="texte">
        <p><?php the_field('texte_infolettre','option'); ?></p>
      </div>
    </div>
    <div class="row avantages d-flex justify-content-center">
      <div class="col-12 col-md-10 col-lg-5 d-block d-sm-flex align-items-start">
        <?php $icone_etoile = get_field('icone_etoile','option'); ?>
        <img src="<?= $icone_etoile['url']; ?>" alt="<?= $icone_etoile['title']; ?>">
        <div class="texte">
          <h5><?php the_field('titre_avantages','option'); ?></h5>
          <p class="mb-5"><?php the_field('texte_avantages','option'); ?></p>
          <a class="btn-plus" href="<?php the_permalink(); ?>"><span>Voir le concours</span></a>
        </div>
      </div>
      <div class="col-12 col-md-10 offset-lg-1 col-12 col-lg-5 d-block d-sm-flex align-items-start">
        <?php $icone_papier = get_field('icone_papier','option'); ?>
        <img src="<?= $icone_papier['url']; ?>" alt="<?= $icone_papier['title']; ?>">
        <div class="texte">
          <h5><?php the_field('titre_idees','option'); ?></h5>
          <p class="mb-5"><?php the_field('texte_idees','option'); ?></p>
          <a class="btn-fleche" href="<?php the_permalink(); ?>"><span>Voir le concours</span></a>
        </div>
      </div>
    </div>
  </div>

  <div id="menu-footer" class="container-fluid pl-5 pr-5">
    <div class="row d-flex justify-content-center text-center text-lg-left">
      <div class="col-12 col-sm-8 col-md-6 col-xl-3">
        <?php $logo_footer = get_field('logo_footer','option'); ?>
        <img class="col-12 col-lg-6 logo p-0" src="<?= $logo_footer['url']; ?>" alt="<?= $logo_footer['title']; ?>">
        <p><?php the_field('texte_vente','option'); ?></p>
      </div>

      <div class="offset-11 offset-xl-0 espace-footer"></div>

      <div class="col-12 col-md-6 col-lg-3 col-xl-2 d-flex justify-content-center justify-content-xl-end mb-5 mb-xl-0">
        <div>
          <h6><?php the_field('titre_offres','option'); ?></h6>
          <?php wp_nav_menu( array( 'theme_location' => 'menu-offre' ) ); ?>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3 col-xl-2 d-flex justify-content-center mb-5 mb-xl-0">
        <div>
          <h6><?php the_field('titre_offres_gratuites','option'); ?></h6>
          <?php wp_nav_menu( array( 'theme_location' => 'menu-offres-gratuite' ) ); ?>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3 col-xl-2 d-flex justify-content-center mb-5 mb-xl-0">
        <div>
          <h6><?php the_field('titre_support','option'); ?></h6>
          <?php wp_nav_menu( array( 'theme_location' => 'menu-support' ) ); ?>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3 col-xl-2 d-flex justify-content-center mb-5 mb-xl-0">
        <div>
          <h6><?php the_field('titre_lantidote_mobile','option'); ?></h6>
          <?php wp_nav_menu( array( 'theme_location' => 'menu-footer-page' ) ); ?>
          <div class="d-flex menu-social justify-content-center justify-content-lg-start">
            <a href="<?php the_field('lien_facebook','option'); ?>" target="blank"><i class="fab fa-facebook-f"></i></a>
            <a href="<?php the_field('lien_instagram','option'); ?>" target="blank"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="background">

  </div>
  <div class="waves">
    <script>
    var w = window.innerWidth;;
      document.getElementsByClassName('waves')[0].innerHTML = "<svg width=\"100%\" height=\"200px\" fill=\"none\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\"> <path fill=\"#000\" d=\" M0 67 C 273,183 822,-40   " + w + ",106 V 359 H 0 V 67 Z\"> <animate repeatCount=\"indefinite\" fill=\"#000\" attributeName=\"d\" dur=\"15s\"   attributeType=\"XML\" values=\"   M0 77 C 473,283 822,-40 " + w + ",116 V 359 H 0 V 67 Z; M0 77 C 473,-40 1222,283 " + w +",136 V 359 H 0 V 67 Z;   M0 77 C 973,260 1722,-53 " + w + ",120 V 359 H 0 V 67 Z; M0 77 C 473,283 822,-40 " + w + ",116 V 359 H 0 V 67 Z\"></animate></path></svg>";
      $( window ).resize(function() {
        var w = window.innerWidth;;
          document.getElementsByClassName('waves')[0].innerHTML = "<svg width=\"100%\" height=\"200px\" fill=\"none\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\"> <path fill=\"#000\" d=\" M0 67 C 273,183 822,-40   " + w + ",106 V 359 H 0 V 67 Z\"> <animate repeatCount=\"indefinite\" fill=\"#000\" attributeName=\"d\" dur=\"15s\"   attributeType=\"XML\" values=\"   M0 77 C 473,283 822,-40 " + w + ",116 V 359 H 0 V 67 Z; M0 77 C 473,-40 1222,283 " + w +",136 V 359 H 0 V 67 Z;   M0 77 C 973,260 1722,-53 " + w + ",120 V 359 H 0 V 67 Z; M0 77 C 473,283 822,-40 " + w + ",116 V 359 H 0 V 67 Z\"></animate></path></svg>";
      });
    </script>

    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
