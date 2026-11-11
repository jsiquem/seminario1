<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?><main id="main-content" class="page-content"><div class="container"><span class="eyebrow">Case-Tech</span><h1 class="section-title"><?php the_title(); ?></h1><div class="entry-content"><?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?></div></div></main><?php get_footer(); ?>
