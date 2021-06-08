<?php if ( is_search() ) : ?>
	<?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'wptemplate' ); ?>
<?php else : ?>
	<?php _e( 'No posts was found', 'wptemplate' ); ?>
<?php endif;
