<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package CMSSuperHeroes
 * @subpackage Recruitment
 * @since Recruitment 1.0.9
 */
global $opt_meta_options;
$post_layout = recruitment_get_opt('post_layout', 'layout1');
if (isset($opt_meta_options['post_layout_custom']) && $opt_meta_options['post_layout_custom'] != 'themeoption') {
	$post_layout = $opt_meta_options['post_layout_custom'];
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="initial-scale=1, width=device-width" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>
</head>

<body id="cms-theme" <?php body_class('post-' . $post_layout); ?>>
	<?php wp_body_open(); ?>
	<?php recruitment_get_page_loading(); ?>
	<div id="page" class="hfeed site">
		<header id="masthead" class="site-header">
			<?php recruitment_header(); ?>
		</header><!-- #masthead -->
		<?php recruitment_page_title(); ?><!-- #page-title -->
		<div id="cms-content" class="site-content">