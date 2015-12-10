<?php
global $pagenow;

?>

<?php // Only run in post/page creation and edit screens - also don't load when button is disabled ?>
<?php if (get_option('maxbuttons_noshowtinymce') == 1) return; ?> 
<?php if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) { ?>
 
	<?php
	 $button = new maxButton();
	 $mbadmin = MaxButtonsAdmin::getInstance(); 
	 
	 $published_buttons = $mbadmin->getButtons(); 
	?>
	<script type="text/javascript">
		function insertButtonShortcode(button_id) {
			if (button_id == "") {
				alert("<?php _e('Please select a button.', 'maxbuttons') ?>");
				return;
			}
			
			// Send shortcode to the editor
			window.send_to_editor('[maxbutton id="' + button_id + '"]');
		}
	</script>
	
	<div id="select-maxbutton-container" style="display:none" >
		<div class="wrap">
			<h2 style="line-height: 32px; padding-left: 40px; background: url(<?php echo MB()->get_plugin_url() . 'images/mb-peach-32.png' ?>) no-repeat;">
				<?php _e('Insert Button into Editor', 'maxbuttons') ?>
				
			</h2>
			
			
			<p><?php _e('Select a button from the list below to place the button shortcode in the editor.', 'maxbuttons') ?></p>
			<div id="mb_media_buttons">
				<div class='loading'><?php _e("Loading your buttons","maxbuttons"); ?></div>
				
			</div>
 
 
			<a class="button-secondary" style="margin-left: 10px; margin-top: 10px;" onclick="tb_remove();"><?php _e('Cancel', 'maxbuttons') ?></a>
		</div>
	</div>
<?php } ?>
