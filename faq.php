<?php
/*

Template Name: FAQ

*/

get_header(); ?>

<section id="search">
  <div class="container d-flex justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <?php if( have_rows('categorie') ): ?>
        <div class="d-flex input">
          <input type="search" id="myInput" placeholder="Search for names.." title="Type in a name">
          <button id="rechercher" onclick="myFunction()" type="submit">Rechercher</button>
        </div>
        <ul id="list-search">
        <?php while ( have_rows('categorie') ) : the_row(); ?>
            <?php if( have_rows('questions_reponse') ): ?>
              <?php while ( have_rows('questions_reponse') ) : the_row(); ?>
                <li class="accordion">
                  <p class="question"><?php the_sub_field('question') ?></p>
                  <div class="reponse">
                    <?php the_sub_field('reponse') ?>
                  </div>
                </li>
              <?php endwhile; ?>
            <?php endif; ?>
          <?php endwhile; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</section>

<section id="tabs">
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-3">
        <?php if( have_rows('categorie') ): ?>
          <div class="cats d-block d-lg-none">
            <p>Choisissez une catégorie!</p>
          </div>
          <ul class="categorie">
          <?php while ( have_rows('categorie') ) : the_row(); ?>
              <li><a href="#<?php the_sub_field('slug_categorie'); ?>"><?php the_sub_field('nom_categorie') ?></a></li>
            <?php endwhile; ?>
          </ul>
        <?php endif; ?>
      </div>
      <div class="col-12 col-lg-9">
        <?php if( have_rows('categorie') ): ?>
          <?php while ( have_rows('categorie') ) : the_row(); ?>
            <div id="<?php the_sub_field('slug_categorie'); ?>" class="categorie">
              <h5><?php the_sub_field('phrases_associee') ?></h5>
                <?php if( have_rows('questions_reponse') ): ?>
                  <div class="accordion">
                    <?php while ( have_rows('questions_reponse') ) : the_row(); ?>
                    <p class="question"><?php the_sub_field('question') ?></p>
                    <div class="reponse">
                      <?php the_sub_field('reponse') ?>
                    </div>
                    <?php endwhile; ?>
                  </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>


<script>
function myFunction() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("list-search");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        p = li[i].getElementsByTagName("p")[0];
        txtValue = p.textContent || p.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "block";
        } else {
            li[i].style.display = "none";
        }
    }
}
</script>


<?php get_footer(); ?>
