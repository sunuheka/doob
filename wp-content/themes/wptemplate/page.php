<?php get_header(); ?>
<?php the_post(); ?>

	<!--<div class="entry-content">
		<?php the_content(); ?>
	</div>-->

	<?php if ( have_rows( 'page_components' ) && ! post_password_required() ) : ?>
		<?php while ( have_rows( 'page_components' ) ) : the_row(); ?>
			<?php get_template_part( sprintf( 'components/%1$s/%1$s', str_replace( '_', '-', get_row_layout() ) ) ); ?>
		<?php endwhile; ?>
	<?php endif; ?>

<?php get_footer();
