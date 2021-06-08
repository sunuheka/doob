<footer id="footer">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<div class="logo_name">DOOB</div>
				<div class="footer-title">Creativity Above</div>
				<div class="footer_menu">
				<?php
						wp_nav_menu([
							'theme_location' => 'primary',
							'depth' => 1,
							'container' => false,
						]);
						?>
				</div>
				<div class="reserved">@2019- Doob. All Right Reserved</div>
				<div class="social_icons">
					<span class="s_icon fb"><i class="icon-facebook"></i></span>
					<span class="s_icon tw"><i class="icon-twitter"></i></span>
					<span class="s_icon db"><i class="icon-dribble"></i></span>
				</div>
			</div>
		</div>
	</div>
</footer>