<?php
function mm_custom_login_logo() { ?>
	<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url(https://makersmarket.au/wp-content/uploads/2023/01/full-logo-black.png);
			height: 130px;
			width: 300px;
			background-size: 300px;
			background-repeat: no-repeat;
			padding-bottom: 10px;
		}
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'mm_custom_login_logo' );
