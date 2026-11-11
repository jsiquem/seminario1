<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?><main id="main-content" class="page-content"><div class="container"><?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?><article <?php post_class(); ?>><span class="eyebrow">Case-Tech</span><h1 class="section-title"><?php the_title(); ?></h1><div class="entry-content"><?php the_content(); ?></div></article><?php endwhile; else : ?><h1>No encontramos contenido.</h1><?php endif; ?></div></main><?php get_footer(); ?>
