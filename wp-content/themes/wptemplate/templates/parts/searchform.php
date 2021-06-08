<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="sr-only"><?php _e( 'Search for:', 'wptemplate' ); ?></label>
	<input type="search" value="<?php echo get_search_query(); ?>" name="s" class="search-field" placeholder="<?php _e( 'Search', 'wptemplate' ); ?>" required>
	<button type="submit" class="search-submit btn btn-default"><?php _e( 'Search', 'wptemplate' ); ?></button>
</form>
