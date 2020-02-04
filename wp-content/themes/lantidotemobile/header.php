<!DOCTYPE html>
<?php $current_page = $_SERVER['REQUEST_URI']; ?>
<?php $current = str_replace('/', '', $current_page); ?>
<html <?php if ($current == 'foire-aux-questions'): ?> itemscope itemtype="https://schema.org/FAQPage" <?php endif; ?>   <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Lantidote mobile</title>

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

<header>

</header>
