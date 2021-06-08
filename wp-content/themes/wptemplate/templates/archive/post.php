<?php get_header(); ?>

	<?php if ( get_option( 'page_for_posts' ) ) : ?>
		<?php
			$post = get_post( get_option( 'page_for_posts' ) );
			setup_postdata( $post );
		?>

		<h2><?php the_title(); ?></h2>

		<?php wp_reset_postdata(); ?>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php the_title(); ?>

		<?php endwhile; ?>

	<?php else : ?>

		<?php get_template_part( 'templates/list/no-posts' ); ?>

	<?php endif; ?>

<?php get_footer();
