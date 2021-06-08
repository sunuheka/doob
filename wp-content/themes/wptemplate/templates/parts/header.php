<header class="site-header">
	<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
		<img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/logo.png'; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
	</a>
	<div class="container">
		<div class="row">
			<div class="col-sm-9">
				<div class="d-flex justify-content-between">				
					<div id="menu">
					<a href="javascript:void(0);" class="menuBtn">
						<span class="lines"></span>
					</a>
						<?php
						wp_nav_menu([
							'theme_location' => 'primary',
							'depth' => 1,
							'container' => false,
						]);
						?>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="d-flex justify-content-between align-items-center">
					<div class="language">
						<ul>
							<li><span class="lang">En</span> <i class="angle-down" aria-hidden="true"></i>
								<div class="triangle"></div>
								<ul>
								<li><i class="sl-flag flag-de"><div id="germany"></div></i> <span class="active">Deutsch</span></li>
								<li><i class="sl-flag flag-usa"><div id="germany"></div></i> <span>Englisch</span></li>
								</ul>
							</li>
						</ul>						
					</div>
					<button class="btn btn-contact has_boxshadow">
						Contact Us
						<span class="arrow-right">
                        &#8594;
                    	</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</header>


