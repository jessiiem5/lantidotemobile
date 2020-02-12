<!DOCTYPE html>
<?php $current_page = $_SERVER['REQUEST_URI']; ?>
<?php $current = str_replace('/', '', $current_page); ?>
<html <?php if ($current == 'foire-aux-questions'): ?> itemscope itemtype="https://schema.org/FAQPage" <?php endif; ?>   <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link href="<?php the_field('favicon', 'option'); ?>" rel="shortcut icon">


    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php $template_uri = get_template_directory_uri(); ?>

    <!-- Place favicon.ico in the root directory -->

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.typekit.net/sop3fkk.css">


    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <header class="z-6">
  			<div class="header ">
  				<div class="h-100 flex-row">
  					<div class="h-100 d-flex align-items-center ">
  						<div class="h-100 logo z-7">
  							<a class="h-100" href="<?= get_home_url(); ?>">
  								<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
  								<?php $logoHeader = get_field('logo', 'option'); ?>
  								<img class="logo" src="<?= $logoHeader; ?>">
  							</a>
  						</div>
  						<?php wp_nav_menu(
          array(
                                  'theme_location' => 'menu-principal',
                                  'container' => false,
                                  'menu_class' => 'h-100 align-items-center d-none d-lg-flex mb-0'
                              )
                          );?>
                          <div class="d-none d-lg-flex ml-auto">
              <a href="#" class="lrm-login ml-auto lrm-hide-if-logged-in">Se connecter</a>
              <?php $current_user = wp_get_current_user();?>
              <a href="<?php echo get_page_link(255); ?>" class=" lrm-show-if-logged-in"><? echo $current_user->user_login ?><img src="<?php echo get_template_directory_uri();?>/img/iconfinder_00-ELASTOFONT-STORE-READY_user-circle_2703062.svg" /></a>
  						</div><div class="hamburger left d-block d-lg-none">

  							<span></span>
  						</div>
  						<div class="menu-mobile z-6 d-lg-none d-flex">
  							<?php wp_nav_menu(array( 'theme_location' => 'menu-principal' )); ?>

  						</div>


  			</div>
  		</div>


  	</header>
<header>

</header>
