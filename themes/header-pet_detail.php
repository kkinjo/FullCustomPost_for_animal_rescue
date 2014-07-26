<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title><?php wp_title('::', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
	<link href='http://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>
<body id="top" <?php body_class(); ?>>
	<header id="header" class="clearfix">

		<div class="wrapper clearfix">

			<!-- logo -->
			<h1 id="logo"><a href="<?php echo home_url(); ?>">ワン'Sパートナーの会</a></h1>

			<!-- Sub Navi -->
			<nav id="sub-navi">
				<?php wp_nav_menu( array('theme_location' => 'header-sub-navi' ) ); ?>
			</nav>

			<!-- Searchform -->
			<div id="search-form">
				<?php get_search_form(); ?>
			</div>

		</div>
		<!-- /wrapper -->

	<nav id="navi">
		<?php wp_nav_menu( array( 'theme_location' => 'header-navi' ) ); ?>
	</nav>
	<script>
		var navi = jQuery("#navi");
		jQuery(navi).find("li:first").addClass("first");
		jQuery(navi).find("li:last").addClass("last");
	</script>
	<!-- /Navi -->

	</header>
	<!-- /header -->

<?php if(!is_page_template('top.php')) : ?>

	<div id="container" class="container_12 clearfix">

		<?php if(!is_front_page()): ?>

			<div class="grid_12 clearfix"><BR>

				<!-- breadcrumb -->
				<?php //breadcrumb(); /* パンくずリスト */ ?>
				<!-- /breadcrumb -->

				<!-- headline -->
				<hgroup id="page-title" class="clearfix">
					<h2>保護ワン・ニャン 情報</h2><h3>Pet Information</h3>
				</hgroup>

				<!-- / headline -->
			</div>

		<?php endif; ?>

<?php endif; ?>

<!-- /header.php -->