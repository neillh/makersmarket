<style>
	.wbk-btn-2 {}

	.woobuilder-lightbox {
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background: #fff;
		border: 1px solid #aaa;
		padding: 2.5em;
		display: none;
		z-index: 99999;
		max-width: 80vw;
		max-height: 80vh;
		overflow: auto;
		box-shadow: 0 0 0 9999px rgba(0,0,0,0.7);
	}

	.woobuilder-lightbox:target {
		display: block;
	}

	.woobuilder-lightbox h3 {
		margin-top: 0;
		margin-bottom: 2rem;
	}

	.woobuilder-lightbox .button {
		vertical-align: middle;
	}

	.woobuilder-templates {
		display: flex;
		flex-wrap: wrap;
		margin-right: -.7em;
	}

	.woobuilder-templates > a.button.button-primary { /*Needs higher specificity*/
		margin: .7em .7em 0 0;
		flex: 1;
	}

	.woobuilder-options {
		display: flex;
		justify-content: space-between;
		margin: 2.5em 0;
	}
	.woobuilder-option {
		padding: 1em 0;
		flex: 0 0 30%;
		display: flex;
		flex-direction: column;
	}
	.woobuilder-option > :first-child,
	.woobuilder-option-2x > :first-child{
		margin-top: 0;
	}
	.woobuilder-option-2x {
		padding: calc( 1em - 1px ) 1em;
		background: rgba( 0, 0, 0, 0.2 );
		flex: 0 0 65%;
		box-sizing: border-box;
	}

	.woobuilder-option > :last-child {
		margin-top: auto;
		text-align: center;
	}
	.woobuilder-lb-footer {
		margin: 2.5em 0 0;
		text-align: right;
	}
</style>
<div id="woobuilder-enable-dialog" class="woobuilder-lightbox">
	<h3>How you like to use WooBuilder Blocks for this product?</h3>

	<div class="woobuilder-options">

		<div class="woobuilder-option">
			<h4>Start from scratch</h4>
			<p>Start building your bespoke product page design from a blank canvas.</p>
			<a class="button button-primary" href="<?php echo add_query_arg( 'toggle-woobuilder', 1 ); ?>">
				Start from scratch</a>
		</div>

		<?php
		if ( 'auto-draft' != get_post_status() ): ?>

		<div class="woobuilder-option">
			<h4>Start from a prebuilt template</h4>
			<p>Choose one of our beautiful templates to start with, then build on top of it.</p>
			<a class="button button-primary" href="<?php echo add_query_arg( 'toggle-woobuilder', '__presets' ); ?>">
				Pick a template</a>
		</div>

		<div class="woobuilder-option">
			<h4>Start from your template</h4>
			<?php
			$templates = WooBuilder_Blocks::templates();
			if ( $templates ): ?>

			<p>Pick your custom template below.</p>
			<div class="woobuilder-templates">
				<?php foreach ( $templates as $tid => $tpl ) {
					$t_uri  = add_query_arg( 'toggle-woobuilder', $tid );
					$t_name = $tpl['title'];
					echo "<a class='button button-primary' href='$t_uri'>$t_name</a>";
				} ?>
			</div>

			<?php else: ?>

			<p>You don't have any custom templates, Did you know you can save any product as a template for reuse?</p>

			<a class="button button-primary" href="#woobuilder-how-to-use-templates">Show me how!</a>

			<?php endif; ?>
		</div>

		<?php else: ?>
		<div class="woobuilder-option woobuilder-option-2x">
			<h4>Save the product to start from templates</h4>
			<p>Save the product first to see template options here. You can save as a draft with just a title if you want to make other changes later.</p>
			<a class="button button-primary" href="#">Yes, I'll save the product first</a>
		</div>

		<?php endif; ?>
	</div>

	<footer class="woobuilder-lb-footer">Or <a href="#">Continue using default editor</a></footer>
</div>

<div id="woobuilder-how-to-use-templates" class="woobuilder-lightbox">
	<h3>Using WooBuilder Blocks templates</h3>
	<iframe width="560" height="315" src="https://www.youtube.com/embed/Blb8oC0RqE8?start=30"
					frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen></iframe>
	<footer class="woobuilder-lb-footer">
		<a href="#woobuilder-enable-dialog" class="button button-primary" id="toggle-woobuilder">
			<?php _e( 'Use WooBuilder Blocks', $this->token ); ?></a>
		<a class="button" href="#">
			<?php _e( 'Close', $this->token ); ?>
		</a>
	</footer>
</div>

<?php
