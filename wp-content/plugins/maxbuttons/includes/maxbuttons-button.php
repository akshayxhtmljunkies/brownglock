<?php
defined('ABSPATH') or die('No direct access permitted');

include_once 'arrays.php';
 

$button = MB()->getClass("button"); //new maxButton();
$button_id = 0; 


if ($_POST) {
	if (! check_admin_referer("button-edit","maxbuttons_button"))
	{
		exit("Request not valid"); 
	}	 
	 
	$button_id = intval($_POST["button_id"]); 

	if ($button_id > 0) 
		$button->set($button_id);
	$return = $button->save($_POST); 
	if (is_int($return) && $button_id <= 0) 
		$button_id = $return;
 
 	if ($button_id === 0) 
 	{
 		error_log(__("Maxbuttons Error: Button id should never be zero","maxbuttons")); 
 
 	}
 	
	$button->set($button_id);	
	 wp_redirect(admin_url('admin.php?page=maxbuttons-controller&action=button&id=' . $button_id));
	 exit();
}
	
if (isset($_GET['id']) && $_GET['id'] != '') { 
	$button = MB()->getClass('button'); // reset
	$button_id = intval($_GET["id"]); 
	if ($button_id == 0) 
	{
		$error = __("Maxbuttons button id is zero. Your data is not saved correctly! Please check your database.","maxbuttons");
		MB()->add_notice('error', $error); 
	}
		// returns bool
		$return = $button->set($button_id);
	if ($return === false)
	{
		$error = __("MaxButtons could not find this button in the database. It might not be possible to save this button! Please check your database or contact support! ", "maxbuttons");
		MB()->add_notice('error', $error); 
	}
}

 
$admin = MB()->getClass('admin'); 
$page_title = __("Button editor","maxbuttons"); 
$action = "<a class='page-title-action add-new-h2' href='" . admin_url() . "admin.php?page=maxbuttons-controller&action=edit'>" . __('Add New', 'maxbuttons') . "</a>";
$admin->get_header(array("title" => $page_title, "title_action" => $action) );
 ?>
	
		<form id="new-button-form" action="<?php echo admin_url('admin.php?page=maxbuttons-controller&action=button&noheader=true'); ?>" method="post">
			<input type="hidden" name="button_id" value="<?php echo $button_id ?>"> 
			<?php wp_nonce_field("button-edit","maxbuttons_button") ?>
			
			<div class="form-actions">				
				<a class="button-primary button-save"><?php _e('Save', 'maxbuttons') ?></a>
				<a id="button-copy" class="button" href="<?php admin_url() ?>admin.php?page=maxbuttons-controller&action=copy&id=<?php echo $button_id ?>"><?php _e('Copy', 'maxbuttons') ?></a>
				<a id="button-trash" class="button" href="<?php admin_url() ?>admin.php?page=maxbuttons-controller&action=trash&id=<?php echo $button_id ?>"><?php _e('Move to Trash', 'maxbuttons') ?></a>
				<a  class="button" href="#delete-button" rel="leanModal"><?php _e("Delete","maxbuttons"); ?> </a>
			</div>
			
			<div class="max-modal" id="delete-button">
				<div class="content-area"> 
					<div class="modal_header">
						<h3><?php _e("Removing button","maxbuttons"); ?></h3>
						<div class="modal_close tb-close-icon"></div>
					</div>
						<p class="content"><?php _e("You are about to permanently remove this button. Are you sure?", "maxbuttons"); ?></p>
					<div class='controls'>
						<div class='yesno'>
							<a href="<?php admin_url() ?>admin.php?page=maxbuttons-controller&action=delete&id=<?php echo $button_id ?>" type="button" name="" class="button-primary big"><?php _e("Yes","maxbuttons"); ?></a>
						&nbsp;&nbsp;
						<input type="button" name="#" onClick="javascript:$('.modal_close').click();" class="button-primary" value="<?Php _e("No", "maxbuttons"); ?>">
						</div>
					</div>
				</div>		

			</div>
			
			<?php do_action("mb_display_notices"); ?> 
			
			<?php if ($button_id > 0): ?>
			<div class="mb-message shortcode">
				<?php $button_name = $button->getName();

				 ?>
				<?php _e('To use this button, place the following shortcode anywhere you want it to appear in your site content:', 'maxbuttons') ?>
				<strong>[maxbutton id="<?php echo $button_id ?>"]</strong>   
				<span class='shortcode-expand closed'><?php _e("See more examples","maxbuttons"); ?>
					<span class="dashicons-before dashicons-arrow-down"></span>
				</span> 
				
				<div class="expanded">
					<p class="example"> 
						<strong><?php _e("Add a button by using the button name","maxbuttons"); ?></strong>
						&nbsp; [maxbutton name="<?php echo $button_name; ?>"] 
					</p> 
					<p class="example">
					<strong><?php _e("Add the same button with different link","maxbuttons");  ?></strong>
						&nbsp; [maxbutton id="<?php echo $button_id ?>" url="http://yoururl"]
					</p>
					 
					<p class="example"><strong><?php _e("Use the same button but change the text","maxbuttons"); ?> </strong>
						&nbsp; [maxbutton id="<?php echo $button_id ?>" text="yourtext"]
					</p>
					<p class="example"><strong><?php _e("All possible shortcode options","maxbuttons"); ?></strong>
						&nbsp; [maxbutton id="<?php echo $button_id ?>" text="yourtext" url="http://yoururl" window="new" nofollow="true"] 
					</p>
					
					<h4><?php _e("Some tips","maxbuttons"); ?></h4>
					<p><?php _e("If you use this button on a static page, on multiple pages, or upload your theme to another WordPress installation choose an unique name and use ", 
						"maxbuttons"); ?>  <strong>[maxbutton name='my-buy-button' url='http://yoururl']</strong>.

		 
					 <?php _e("By using this syntax when you edit and save your button it will be changed everywhere it is used on your site. If you delete the button and create a new one with the same name the new button will be used on your site.","maxbuttons"); ?>
				 	</p>
					
				</div>
			</div>
			<?php endif; ?>
			
			<?php #### STARTING FIELDS; 
			
				
			$button->admin_fields();
			
			?> 

			<div class="form-actions">				
				<a class="button-primary button-save"><?php _e('Save', 'maxbuttons') ?></a>
			</div>
		</form>

		<div class="output">
			<div class="header"><?php _e('Preview', 'maxbuttons') ?></div>
			<div class="inner">
 
				<p><?php _e('The top is the normal button, the bottom one is the hover.', 'maxbuttons') ?></p>
				<div class="result">

					<?php $button->display(array("mode" => 'editor', "load_css" => "element"));  ?> 
 
					<p>&nbsp;</p>
 
					<?php $button->display(array("mode" => 'editor', "preview_part" => ":hover", "load_css" => "element")); ?> 
					
					<?php $button->display_field_map(); ?> 
				</div>
				
				<input type='hidden' id='colorpicker_current' value=''>
				
				<span class="colorpicker-box" id="button_preview_box">
					
					<span></span>
				</span>
				<input  type="hidden" id="button_preview" value='' />
				<input style="display: none;" type="text" id="button_output" name="button_output" value="" />
				<div class="note"><?php _e('Change this color to see your button on a different background.', 'maxbuttons') ?></div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
<?php $admin->get_footer(); ?> 
