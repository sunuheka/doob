<?php get_header(); ?>

	<?php the_field( '404_content', 'option' ); ?>

	<?php get_template_part( 'templates/parts/searchform' ); ?>

<?php get_footer();
