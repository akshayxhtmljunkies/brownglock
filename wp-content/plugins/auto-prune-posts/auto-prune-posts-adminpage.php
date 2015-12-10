<?php
/**
 * Created on 31-okt-2010 18:07:18
 * 
 * auto-prune-posts-adminpage.php
 * @author	Ramon Fincken
 */
 
?>

<h3>Auto prune posts</h3>
<?php
if($action_taken)
{
	$plugin->show_message('Settings updated!');
}
?>
<br/>

<form id="form1" name="form1" method="post" action="" onsubmit="return confirm('Are you sure?')">
Delete posts in <?php wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'cat_id_add', 'hierarchical' => true, 'orderby' => 'name', 'show_option_all' => 'All')); ?> after
<input type="text" maxlength="6" name="period_duration_add" style="width: 45px;">
  <select name="period_add">
  <?php
  foreach($plugin->periods as $period)
  {
    echo '<option value="'.$period.'">'.$period.'(s)</option>';
  }
  ?>
  </select>
  
Type:  
<?php 
if(count($plugin->all_types) == 1)
{
	echo 'Post <input type="hidden" name="type" value="'.$plugin->all_types[0].'" />';
}
else
{
?>  
  <select name="type">
  <?php
  foreach($plugin->all_types as $type)
  {
    echo '<option value="'.$type.'">'.$type.'</option>';
  }
  ?>
  </select>
<?php 
}
?> 
  
<br/>
<p class="updated">Note: if a duration has been set for the selected category &amp; post-type combination, it will be over-written by the new settings!</p>
<input type="hidden" name="formaction" value="add" />
<input type="submit" name="submitbutton" value="Add settings" class="button-primary">
<input type="reset" name="submitbutton" value="Reset" class="button"></form>
<br/>


<div style="border: orange 1px solid;">
<h3>Advanced settings, do not change unless you know what you are doing</h3>
<form id="form1" name="form1" method="post" action="" onsubmit="return confirm('Are you sure?')">
Notify admin by email if a post is removed: <input type="text" name="admin_email" value="<?php echo $plugin->conf['settings']['admin_email']; ?>" style="width: 200px;"> Enter email for notification, leave blank to disable.
<br/>
Other post types, comma seperated: <input type="text" name="types" value="<?php echo $plugin->conf['settings']['types']; ?>" style="width: 200px;"> 
Example: <input type="text" value="mytype,myothertype" name="types_ex" style="width: 200px;" readonly="readonly"> Leave blank if you do not have other post types.
<br/>
Delete method:
  <select name="force_delete">
  	<option value="0" <?php if($plugin->conf['settings']['force_delete'] == 0) { echo 'selected="selected"'; } ?>>Sent to trash (default)</option>
  	<option value="1" <?php if($plugin->conf['settings']['force_delete'] == 1) { echo 'selected="selected"'; } ?>>Remove (there is no undo!)</option>
  </select>
  
<br/>

<input type="hidden" name="formaction" value="updatesettings" />
<input type="submit" name="submitbutton" value="Update settings" class="button-primary">
<input type="reset" name="submitbutton" value="Reset" class="button"></form>
</div>
<br/><br/>

<form id="form1" name="form1" method="post" action="" onsubmit="return confirm('Are you sure?')">
<table class="widefat">
   <thead>
   <tr>
      <th class="manage-column" style="width: 200px;">Option</th>
      <th class="manage-column" style="width: 300px;">Value/Setting</th>
      <th class="manage-column">Current Value/Setting</th>
   </tr>
   </thead>
   <tbody>

<?php 
foreach($plugin->conf['config'] as $cat_id => $type)
{
	foreach($type as $the_type => $values)
	{
      	?>
   <tr class="iedit">
      <td valign="top">Category</td>
      <td valign="top">
      <?php
	  if($cat_id == 0)
	  {
	  	echo 'All';
	  }
	  else
	  {
      	$cat = get_category($cat_id);
      	echo $cat->name. ' (id= '.$cat_id.')';
	  }
      ?>
	  </td>
      <td>
      <?php
      wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'category_parent', 'orderby' => 'name', 'selected' => $cat_id, 'hierarchical' => true, 'show_option_all' => 'All'));
      ?>
      <br/>
      Note: you cannot use this dropdown, it is only to show which category is used.
      </td>
   </tr>
   <tr class="iedit">
      <td valign="top">Delete after </td>
      <td>
      
      <input type="text" maxlength="6" style="width: 45px;" name="period_duration[<?php echo $cat_id; ?>][<?php echo $the_type; ?>]" value="<?php echo $values['period']; ?>" />
      <select name="period[<?php echo $cat_id; ?>][<?php echo $the_type; ?>]">
  <?php
  foreach($plugin->periods as $period)
  {
    $select = '';
    if($values['period_duration'] == $period)
    {
    	$select = ' selected="selected"';
    }
    echo '<option value="'.$period.'"'.$select.'>'.$period.'(s)</option>';
  }
  ?>
  </select> 
      </td>
      <td>
      <input type="text" maxlength="6" style="width: 45px;" disabled="disabled" name="period_duration_disabled[<?php echo $cat_id; ?>][<?php echo $the_type; ?>]" value="<?php echo $values['period']; ?>" />
      <select disabled="disabled" name="period_disabled[<?php echo $cat_id; ?>][<?php echo $the_type; ?>]">
  <?php
  foreach($plugin->periods as $period)
  {
    $select = '';
    if($values['period_duration'] == $period)
    {
    	$select = ' selected="selected"';
    }
    echo '<option value="'.$period.'"'.$select.'>'.$period.'(s)</option>';
  }
  ?>
  </select> 
      </td>     
   </tr> 
 <tr class="iedit">
      <td valign="top">Type </td>
      <td>
 <?php 
	echo $the_type;
?> 

      </td>
      <td>
      
 <?php 
		echo $the_type;
?> 
      </td>     
   </tr>    
   <tr class="iedit">
      <td valign="top">Action</td>
      <td valign="top">
		<select name="action[<?php echo $cat_id; ?>][<?php echo $the_type; ?>]">
		<option value="update" selected="selected">Update settings</option>
		<option value="delete">Delete settings</option>
		  </select>	  
	  </td>
      <td valign="top">&nbsp;</td>
   </tr>  
   
   <tr class="iedit">
      <td valign="top" style="height:50px;">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
   </tr>   
   <?php 
	}
}
   ?>
         
   </tbody>
</table>
<input type="hidden" name="formaction" value="update" />
<input type="submit" name="submitbutton" value="Update" class="button-primary">
<input type="reset" name="submitbutton" value="Reset" class="button"></form>
