<html>
	<head>
		<title><?php _e( 'Form Preview', 'ultimate-form-builder-lite' ); ?></title>
		<?php wp_head(); ?>
		<style>
			body:before{display:none !important;}
			body:after{display:none !important;}
			body{background:#F1F1F1 !important;}
		</style>

	</head>
	<body>
		<div class="ufbl-preview-title-wrap">
			<div class="ufbl-preview-title"><?php _e( 'Preview Mode', 'ultimate-form-builder-lite' ); ?></div>
		</div>
		<div class="ufbl-preview-note"><?php _e( 'This is just the basic preview and it may look different when used in frontend as per your theme\'s styles.', 'ultimate-form-builder-lite' ); ?></div>
		<div class="ufbl-form-preview-wrap">
			<?php
			$form_id = sanitize_text_field( $_GET['ufbl_form_id'] );
			echo do_shortcode( '[ufbl form_id="' . $form_id . '"]' );
			?>
		</div>

	</body>

</html>

