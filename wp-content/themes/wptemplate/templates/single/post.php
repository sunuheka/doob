<?php get_header(); ?>
<?php the_post(); ?>

	<article class="entry-content">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	</article>

<?php get_footer();
