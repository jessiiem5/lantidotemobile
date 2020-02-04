<?php

// Ajout des menus
function menus()
{
    register_nav_menus(array(
      'menu-principal' => 'Menu principal',
      'menu-offre' => 'Menu offre',
      'menu-offres-gratuite' => 'Menu offre gratuite',
      'menu-support' => 'Menu support',
      'menu-footer-page' => 'Menu footer page',
      'menu-category' => 'Menu category',
    ));
}
add_action('init', 'menus');

function wpm_custom_post_type() {

	// On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
	$labels = array(
		// Le nom au pluriel
		'name'                => _x( 'Concours', 'Post Type General Name'),
		// Le nom au singulier
		'singular_name'       => _x( 'Concours', 'Post Type Singular Name'),
		// Le libellé affiché dans le menu
		'menu_name'           => __( 'Concours'),
		// Les différents libellés de l'administration
		'all_items'           => __( 'Touts les concours'),
		'view_item'           => __( 'Voir les concours'),
		'add_new_item'        => __( 'Ajouter une nouveau concour'),
		'add_new'             => __( 'Ajouter'),
		'edit_item'           => __( 'Editer les concours'),
		'update_item'         => __( 'Modifier les concours'),
		'search_items'        => __( 'Rechercher un concour'),
		'not_found'           => __( 'Non trouvé'),
		'not_found_in_trash'  => __( 'Non trouvé dans la corbeille'),
	);

	// On peut définir ici d'autres options pour notre custom post type

	$args = array(
		'label'               => __( 'concours'),
		'description'         => __( 'Tous sur services'),
		'labels'              => $labels,
		// On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		/*
		* Différentes options supplémentaires
		*/
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => true,
	);

  register_post_type( 'concours', $args );

}

add_action( 'init', 'wpm_custom_post_type', 0 );

// STYLES
add_action('wp_enqueue_scripts', 'refri4saison_styles');
function refri4saison_styles()
{
    wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css', 1);
    wp_enqueue_style('bootstrap');

    wp_register_style('style', get_template_directory_uri() . '/style.min.css', 1);
    wp_enqueue_style('style');

    wp_register_style('aos', get_template_directory_uri() . '/css/aos.css', 1);
    wp_enqueue_style('aos');

    wp_register_style('slick', get_template_directory_uri() . '/css/slick.css', 1);
    wp_enqueue_style('slick');

    wp_register_style('slick-theme', get_template_directory_uri() . '/css/slick-theme.css', 1);
    wp_enqueue_style('slick-theme');
}

// SCRIPTS
add_action('wp_enqueue_scripts', 'refri4saison_scripts');

function refri4saison_scripts()
{
    wp_deregister_script('jquery');
    wp_register_script('jquery', get_template_directory_uri() . '/js/jquery-3.4.1.min.js', array(), null, false);

    wp_register_script('scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.1', true);
    wp_enqueue_script('scripts');

    wp_register_script('jquery-ui', get_template_directory_uri() . '/js/jquery-ui.js', array('jquery'), '1.1', true);
    wp_enqueue_script('jquery-ui');


    wp_register_script('aos', get_template_directory_uri() . '/js/aos.js', array('jquery'), '1.1', true);
    wp_enqueue_script('aos');

    wp_register_script('isotope', get_template_directory_uri() . '/js/isotope.js', array('jquery'), '1.1', true);
    wp_enqueue_script('isotope');

    wp_register_script('slick', get_template_directory_uri() . '/js/slick.js', array('jquery'), '1.1', true);
    wp_enqueue_script('slick');
}

add_theme_support('post-thumbnails');


if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' 	=> 'ACF du header',
        'menu_title'	=> 'Options Header',
        'menu_slug'	=> 'header',
    ));

    acf_add_options_page(array(
        'page_title' 	=> 'ACF du footer',
        'menu_title'	=> 'Options Footer',
        'menu_slug'	=> 'footer',
    ));
}

function my_acf_init()
{
    acf_update_setting('google_api_key', 'AIzaSyDv8qXpWROaGrAApHe5reWS2ohI_rzmW7s');
}

// EMPECHE LA MISE A JOUR DU PLUGIN DE CONCOURS QUI À ÉTÉ MODIFIÉ MANUELLEMENT
function stop_plugin_update( $value ) {
   if( isset( $value->response['duplicator/duplicator.php'] ) ){
     unset( $value->response['giveaways-contests/contest-cat.php'] );
   }

 return $value;
}
add_filter( 'site_transient_update_plugins', 'stop_plugin_update' );

add_action('acf/init', 'my_acf_init');
