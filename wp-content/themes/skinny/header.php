<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Skinny
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8015261447860780" crossorigin="anonymous"></script>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'skinny_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'skinny_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner">
		<?php Skinny\display_site_header(); ?>
	</header><!-- #masthead -->

	<?php do_action( 'skinny_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
