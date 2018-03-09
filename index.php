<!--
 ███████╗██╗   ██╗███╗   ██╗██╗  ██╗██╗  ██╗ █████╗ ██╗   ██╗███████╗
 ██╔════╝██║   ██║████╗  ██║██║ ██╔╝██║  ██║██╔══██╗██║   ██║██╔════╝
 █████╗  ██║   ██║██╔██╗ ██║█████╔╝ ███████║███████║██║   ██║███████╗
 ██╔══╝  ██║   ██║██║╚██╗██║██╔═██╗ ██╔══██║██╔══██║██║   ██║╚════██║
 ██║     ╚██████╔╝██║ ╚████║██║  ██╗██║  ██║██║  ██║╚██████╔╝███████║
 ╚═╝      ╚═════╝ ╚═╝  ╚═══╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝
 www.funkhaus.us
-->
<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php wp_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/static/bundle.css?ver=<?php echo custom_latest_timestamp(); ?>" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/static/images/favicon.png" />
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/static/images/icon-touch.png"/>

    <?php get_template_part('parts/og-tags'); ?>
    <?php get_template_part('parts/schema'); ?>
    <?php get_template_part('parts/font-loader'); ?>

    <?php wp_head();?>
</head>
<body <?php body_class(); ?>>

    <div id="app"></div>

<?php wp_footer();?>
</body>
</html>
