<?php

$blockClass["text"] = "textBlock"; 
$blockOrder[1][] = "text"; 

class textBlock extends maxBlock 
{

	protected $blockname = "text"; 
	protected $fields = array(
	
  					  "text" =>   array("default" => '' ), 
						"font" => array("default" => "Tahoma", 
											  "css" => "font-family", 
											  "csspart" => 'mb-text'
											  ),
											  
						"font_size" => array("default" => "15px",
											  "css" => "font-size",
											  "csspart" => 'mb-text' ),
					/*	"font_size_unit" => array("default" => "em", 
											  "css" => "font_size_unit",
											  "csspart" => "mb-text",
											), */
						"text_align" => array(  
										"default" => "center",
										 "css" => "text-align",
										 "csspart" => "mb-text",
										 
										 ),
										 																	  
						"font_style" => array("default" => "normal",
											  "css" => "font-style",
											  "csspart" => 'mb-text'),
											  
						"font_weight" => array("default" => "normal", 
											  "css" => "font-weight",
											  "csspart" => 'mb-text'),
						"text_shadow_offset_left" => array("default" => "0px",
											  "css" => "text-shadow-left",
											  "csspart" => 'mb-text',
											  "csspseudo" => "normal,hover"
											  ),
											  
						"text_shadow_offset_top" => array("default" => "0px",
											  "css" => "text-shadow-top",
											  "csspart" => 'mb-text',
											  "csspseudo" => "normal,hover"),
						"text_shadow_width" => array("default" => "0px", 
											  "css" => "text-shadow-width",
											  "csspart" => 'mb-text',
											  "csspseudo" => "normal,hover"),
						"padding_top" => array("default" => "18px",
											   "css" => "padding-top",
											   "csspart" => "mb-text"),
						"padding_right" => array("default" => "0px",
												"css" => "padding-right",
											   "csspart" => "mb-text"),
						"padding_bottom" => array("default" => "0px",
												"css" => "padding-bottom",
											   "csspart" => "mb-text"),
						"padding_left" => array("default" => "0px",
												"css" => "padding-left",
											   "csspart" => "mb-text")
						); 
	

	function __construct()
	{
		parent::__construct();
		$this->fields["text"]["default"] = __("YOUR TEXT","maxbuttons"); 
 
	}

	public function map_fields($map)
	{
		$map = parent::map_fields($map);
		$map["text"]["func"] = "updateAnchorText"; 
		$map["text_shadow_offset_left"]["func"] = "updateTextShadow"; 
		$map["text_shadow_offset_top"]["func"] = "updateTextShadow"; 
		$map["text_shadow_width"]["func"] = "updateTextShadow"; 
		
		return $map; 
	}
	public function parse_css($css,  $mode = 'normal')
	{
		$css = parent::parse_css($css);
 
		
		// allow for font not to be set, but default to theme
		$font_size = $css["mb-text"]["normal"]["font-size"]; 
		if ($font_size == 0 || $font_size == '0px')
			unset($css["mb-text"]["normal"]["font-size"]); 
			
		
		$css["mb-text"]["normal"]["line-height"] = "1em"; 
		$css["mb-text"]["normal"]["box-sizing"] = "border-box";  // default. 	
		$css["mb-text"]["normal"]["display"] = "block"; 
		return $css; 
	}	
	public function parse_button($domObj, $mode = 'normal')
	{
		$data = $this->data[$this->blockname]; 
		$anchor = $domObj->find("a",0); 	
		
	 	if (isset($data["text"]) && $data["text"] != '' || $mode == 'preview') 
		$anchor->innertext = "<span class='mb-text'>" . do_shortcode($data["text"]) . "</span>"; 
		return $domObj; 
		
	}
			
	public function admin_fields() 
	{
		$data = $this->data[$this->blockname]; 
		foreach($this->fields as $field => $options)
		{		
 	 	    $default = (isset($options["default"])) ? $options["default"] : ''; 
			$$field = (isset($data[$field])) ? $data[$field] : $default;
			${$field  . "_default"} = $default; 
		}
		
		global $maxbuttons_font_families; 
		global $maxbuttons_font_sizes; 
		global $maxbuttons_font_styles; 
		global $maxbuttons_font_weights; 
		global $maxbuttons_text_alignments;
 
		
?>
			<div class="mb_tab option-container">
				<div class="title"><?php _e('Text', 'maxbuttons') ?></div>
				<div class="inside">
				

					<div class="option-design">
						<div class="label"><?php _e('Text', 'maxbuttons') ?></div>
						
 
						<div class="input">
							<input type="text" id="text" name="text" value="<?php echo $text ?>" maxlength="100"/>
						</div>
						<div class="default"><?php _e('The actual words that appear on the button.', 'maxbuttons') ?></div>
						<div class="clear"></div>
					</div>
									
					<div class="option-design">
						<div class="label"><?php _e('Font', 'maxbuttons') ?></div>
						<div class="input">
							<select id="font" name="font">
							<?php
							foreach ($maxbuttons_font_families as $name => $value) {
								//$selected = ($maxbutton_text_font_family_value == $value) ? 'selected="selected"' : '';
								echo '<option value="' . $value . '"' . selected($font,$value) . '>' . $name . '</option>';
							}
							?>
							</select>
						</div>
						<div class="default"><?php _e('Default:', 'maxbuttons') ?> <?php echo $font_default ?></div>
						<div class="clear"></div>
					</div>


					
					<div class="option-design">
						<div class="label"><?php _e('Size', 'maxbuttons') ?></div>
						<div class="input">		
							<select id="font_size" name="font_size">
							<?php		
							$font_size = maxUtils::strip_px($font_size); 
							
							foreach ($maxbuttons_font_sizes as $name => $value) {
								//$selected = ($maxbutton_text_font_size_value == $value) ? 'selected="selected"' : '';
								echo '<option value="' . $name . '" ' . selected($font_size, $name, false) . '>' . $value . '</option>';
							} 
							?>
							</select> 
						</div>
						<div class="default"><?php _e('Default:', 'maxbuttons') ?> <?php echo $font_size_default ?></div>
						<div class="clear"></div>
					</div>

 
										
					<div class="option-design">
						<div class="label"><?php _e('Text align', 'maxbuttons') ?></div>
						<div class="input">
							<select id="text_align" name="text_align">
							<?php
							foreach ($maxbuttons_text_alignments as $name => $value) {
								//$selected = ($maxbutton_text_font_style_value == $value) ? 'selected="selected"' : '';
								echo '<option value="' . $value . '" ' . selected($text_align,$value) . '>' . $name . '</option>';
							}
							?>
							</select>
						</div>
						<div class="default"><?php _e('Default:', 'maxbuttons') ?> <?php echo $text_align_default ?></div>
						<div class="clear"></div>
					</div>
					
					<div class="option-design">
						<div class="label"><?php _e('Weight', 'maxbuttons') ?></div>
						<div class="input">
							<select id="font_weight" name="font_weight">
							<?php
							foreach ($maxbuttons_font_weights as $name => $value) {
								//$selected = ($maxbutton_text_font_weight_value == $value) ? 'selected="selected"' : '';
								echo '<option value="' . $value . '" ' . selected($font_weight, $value) . '>' . $name . '</option>';
							}
							?>
							</select>
						</div>
						<div class="default"><?php _e('Default:', 'maxbuttons') ?> <?php echo $font_weight_default ?></div>
						<div class="clear"></div>
					</div>
					
					<div class="option-design">
						<div class="label"><?php _e('Shadow Offset Left', 'maxbuttons') ?></div>
						<div class="input"><input class="tiny-nopad" type="text" id="text_shadow_offset_left" name="text_shadow_offset_left" value="<?php echo maxUtils::strip_px($text_shadow_offset_left) ?>" />px</div>
						<div class="default"><?php _e('Default:', 'maxbuttons') ?> <?php echo $text_shadow_offset_left_default ?></div>
						<div class="clear"></div>
					</div>
					
					<div class="option-design">
						<div class="label"><?php _e('Shadow Offset Top', 'maxbuttons') ?></div>
						<div class="input"><input class="tiny-nopad" type="text" id="text_shadow_offset_top" name="text_shadow_offset_top" value="<?php echo maxUtils::strip_px($text_shadow_offset_top) ?>" />px</div>
						<div class="default"><?php _e('Default:', 'maxbuttons') ?> <?php echo $text_shadow_offset_top_default ?></div>
						<div class="clear"></div>
					</div>
					
					<div class="option-design">
						<div class="label"><?php _e('Shadow Width', 'maxbuttons') ?></div>
						<div class="input"><input class="tiny-nopad" type="text" id="text_shadow_width" name="text_shadow_width" value="<?php echo maxUtils::strip_px($text_shadow_width) ?>" />px</div>
						<div class="default"><?php _e('Default:', 'maxbuttons') ?> <?php echo $text_shadow_width_default ?></div>
						<div class="clear"></div>
					</div>
					
					<div class="spacer"></div>
					
					<div class="option-design">
						<div class="label"><label><?php _e('Padding', 'maxbuttons') ?></label></div>
						<div class="input">
							<table>
								<tr>
									<td>
										<div class="cell-label"><?php _e('Top', 'maxbuttons') ?></div>
										<div class="input"><input class="tiny" type="text" id="padding_top" name="padding_top" 
										value="<?php echo maxUtils::strip_px($padding_top) ?>" />px</div>
										<div class="default-other"><?php _e('Default:', 'maxbuttons') ?> <?php echo $padding_top_default ?></div>
										<div class="clear"></div>
									</td>
									<td>
										<div class="cell-label"><?php _e('Bottom', 'maxbuttons') ?></div>
										<div class="input"><input class="tiny" type="text" id="padding_bottom" name="padding_bottom" value="<?php echo maxUtils::strip_px($padding_bottom) ?>" />px</div>
										<div class="default-other"><?php _e('Default:', 'maxbuttons') ?> <?php echo $padding_bottom_default ?></div>
										<div class="clear"></div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="cell-label"><?php _e('Left', 'maxbuttons') ?></div>
										<div class="input"><input class="tiny" type="text" id="padding_left" name="padding_left" value="<?php echo maxUtils::strip_px($padding_left) ?>" />px</div>
										<div class="default-other"><?php _e('Default:', 'maxbuttons') ?> <?php echo $padding_left_default ?></div>
										<div class="clear"></div>
									</td>
									<td>
										<div class="cell-label"><?php _e('Right', 'maxbuttons') ?></div>
										<div class="input"><input class="tiny" type="text" id="padding_right" name="padding_right" value="<?php echo maxUtils::strip_px($padding_right) ?>" />px</div>
										<div class="default-other"><?php _e('Default:', 'maxbuttons') ?> <?php echo $padding_right_default ?></div>
										<div class="clear"></div>
									</td>
								</tr>
							</table>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
<?php } // admin fields  
	} // class 
	
?>
