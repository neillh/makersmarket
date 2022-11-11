<?php


use GTP\GutenbergPro\Main\gtp__GutenbergPro;

class gutenberg_pro_page {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'gutenberg_pro_settings' ), 10 );
	}

	public function gutenberg_pro_settings() {
		$page_title = 'Gutenberg Pro Dashboard';
		$menu_title = 'Gutenberg Pro';
		$capability = 'manage_options';
		$slug       = 'gutenberg-pro';
		$callback   = array( $this, 'gutenberg_pro_settings_content' );
		$icon_url   = 'dashicons-admin-plugins';
		add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon_url );
	}

	public function gutenberg_pro_settings_content() {

		if ( array_key_exists( 'gutenberg_pro_nonce', $_POST ) && wp_verify_nonce( $_POST['gutenberg_pro_nonce'], 'gutenberg_pro_admin' ) ):

			foreach ( gtp__GutenbergPro::extensions as $slug => $data ):

				$updated_value = array_key_exists( $slug, $_POST ) ? $_POST[ $slug ] === "on" ? "true" : "false" : "false";
				update_option( $slug, $updated_value );

			endforeach;

		endif;

		?>
		<form method="POST" class="gutenberg_pro_admin_form">
			<article class="gutenberg_pro_admin_description">
				<div class="gutenberg_pro_admin_dcontent">

					<div class="gutenberg_pro_admin_dvid"><div class="gutenberg_pro_admin_dvid_wrap">
						<iframe title="Gutenberg Pro (final cut version 2)"
										src="https://player.vimeo.com/video/482265519?dnt=1&amp;app_id=122963" width="800"
										height="450" frameborder="0" allow="autoplay; fullscreen" allowfullscreen=""></iframe>
						</div></div>

					<div class="gutenberg_pro_admin_dtext">
						<header>
							<h1>Welcome to Gutenberg Pro </h1>

							<p>
								<a href="https://docs.pootlepress.com/">Documentation</a> &amp;
								<a href="http://pootlepress.com/support-form">Help &amp; Support</a>
							</p>
						</header>
						<p>
							I love the WordPress Gutenberg Block Editor, but sometimes the core blocks don’t have enough functionality
							that I need. That’s why we built Gutenberg Pro. Gutenberg Pro adds powerful but ‘easy to use’ extra
							options
							to customize Gutenberg Blocks. Whether you are just starting out in WordPress or you are an experienced
							Designer, Gutenberg Pro will take your web designs to the next level. I hope you love it
							<img role="img" class="emoji" alt="🙂" src="https://s.w.org/images/core/emoji/13.0.0/svg/1f642.svg">
						</p>
						<p>Jamie<br><strong>Pootlepress Founder</strong></p>

					</div>

				</div>
			</article>

			<h2>Gutenberg Pro settings</h2>

			<?php foreach ( gtp__GutenbergPro::extensions as $slug => $data ): ?>

				<?php

				$value = get_option( $slug ) === "true" ? "checked" : "";


				?>
				<div class="gutenberg_pro_admin_form_item">
					<input name="<?php echo $slug; ?>" type="checkbox" id="<?php echo $slug; ?>" <?php echo $value ?> />
					<label for="<?php echo $slug; ?>"><?php echo $data['label'] ?></label>
				</div>

			<?php endforeach; ?>

			<div class="gutenberg_pro_admin_button">
				<button name="gutenberg_pro_settings_form" value="update" type="submit">Update Settings</button>
			</div>
			<?php wp_nonce_field( 'gutenberg_pro_admin', 'gutenberg_pro_nonce' ) ?>
		</form>


		<?php
	}

}

new gutenberg_pro_page();
