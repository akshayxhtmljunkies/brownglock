<?php
if ( !is_admin() )
{
    _e( 'Direct access not allowed.', 'calculated-fields-form' );
    exit;
}

check_admin_referer( 'session_id_'.session_id(), '_cpcff_nonce' );

$_GET['lu'] = intval(@$_GET['lu']);

if( isset( $_GET['ld'] ) && is_array( $_GET['ld'] ) )
{
	foreach( $_GET['ld'] as $key => $ld ) $_GET['ld'][ $key ] = intval( $ld );
}
else
{	
	$_GET['ld'] = intval(@$_GET['ld']);
}

if (!defined('CP_CALCULATEDFIELDSF_ID'))
    define ('CP_CALCULATEDFIELDSF_ID',intval($_GET["cal"]));

global $wpdb;

$message = "";

if ( !empty( $_GET['lu'] ) )
{
    $wpdb->query( $wpdb->prepare( 'UPDATE `'.CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME.'` SET paid=%d WHERE id=%d', $_GET["status"], $_GET['lu'] ) );
	/**
	 * Action called when a submission is updated, the submission ID is passed as parameter
	 */
	do_action( 'cpcff_update_submission', $_GET['lu'] );
	
    $message = __("Item updated", 'calculated-fields-form' );
}
else if ( !empty( $_GET['ld'] ) )
{
	if( is_array( $_GET['ld'] ) ) 
	{	
		foreach( $_GET['ld'] as $ld )
		{
			$wpdb->query( $wpdb->prepare( 'DELETE FROM `'.CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME.'` WHERE id=%d', $ld ) );
			/**
			 * Action called when a submission is deleted, the submission ID is passed as parameter
			 */
			do_action( 'cpcff_delete_submission', $ld );
		}	
	}	
	else
	{	
		$wpdb->query( $wpdb->prepare( 'DELETE FROM `'.CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME.'` WHERE id=%d', $_GET['ld'] ) );
		/**
		 * Action called when a submission is deleted, the submission ID is passed as parameter
		 */
		do_action( 'cpcff_delete_submission', $_GET['ld'] );
	}
    $message = __("Item(s) deleted", 'calculated-fields-form' );
}

$form_list_opts = '';
$form_list = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CALCULATEDFIELDSF_FORMS_TABLE." ORDER BY id" );
foreach( $form_list as $form )
{
	$selected_opt = '';
	if( $form->id == CP_CALCULATEDFIELDSF_ID ){ $myform = $form; $selected_opt = 'SELECTED'; }
	$form_list_opts .= '<option value="'.esc_attr( $form->id ).'" '.$selected_opt.'>'.$form->id.' - '.$form->form_name.'</option>';
}

$current_page = intval($_GET["p"]);
if (!$current_page) $current_page = 1;
$records_per_page = 50;                                                                                  

$cond = '';
if ($_GET["search"] != '') $cond .= " AND (data like '%".esc_sql($_GET["search"])."%' OR paypal_post LIKE '%".esc_sql($_GET["search"])."%')";
if ($_GET["dfrom"] != '') $cond .= " AND (`time` >= '".esc_sql($_GET["dfrom"])."')";
if ($_GET["dto"] != '') $cond .= " AND (`time` <= '".esc_sql($_GET["dto"])." 23:59:59')";

$events_query = "SELECT * FROM ".CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME." WHERE formid=".CP_CALCULATEDFIELDSF_ID.$cond." ORDER BY `time` DESC";
/**
 * Allows modify the query of messages, passing the query as parameter
 * returns the new query
 */
$events_query = apply_filters( 'cpcff_messages_query', $events_query );
$events = $wpdb->get_results( $events_query );

$total_pages = ceil(count($events) / $records_per_page);
if ($message) echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".$message."</strong></p></div>";
?>
<script type="text/javascript">
 function cp_moreInfo( e )
 {
	try{
		var $ = jQuery,
			e = $( e ),
			t = e.text();
		e.text( ( t.indexOf( '+' ) != -1 ) ? t.replace( '+', '-' ) : t.replace( '-', '+' ) )
		 .closest( 'td' )
		 .find( 'div.paypal_data' )
		 .toggle();
		 
	}catch( err ){}	
 }
 function cp_checkAllItems( e )
 {    
    try{
		var $  = jQuery;
		$( e ).closest( 'table' ).find( 'input[type="checkbox"]' ).prop( 'checked', $( e ).prop( 'checked' ) );
	}catch( err ){}
 } 
 function cp_deleteAll( )
 {    
    try{
		var $  = jQuery,
			ld = [];
			
		$( '.cp_item:checked' ).each( function(){ ld.push( 'ld[]='+this.value ); } );
		if( ld.length )
		{
			if (confirm('<?php _e( 'Are you sure that you want to delete these items?', 'calculated-fields-form' ); ?>'))
			{        
				document.location = 'options-general.php?page=cp_calculated_fields_form&cal=<?php echo CP_CALCULATEDFIELDSF_ID; ?>&list=1&'+ld.join( '&' )+'&r='+Math.random()+'&_cpcff_nonce=<?php echo wp_create_nonce( 'session_id_'.session_id() ); ?>';
			}
		}
	}catch( err ){}
 } 
 function cp_updateMessageItem(id,status)
 {    
    document.location = 'options-general.php?page=cp_calculated_fields_form&cal=<?php echo CP_CALCULATEDFIELDSF_ID; ?>&list=1&status='+status+'&lu='+id+'&r='+Math.random( )+'&_cpcff_nonce=<?php echo wp_create_nonce( 'session_id_'.session_id() ); ?>';   
 } 
 function cp_deleteMessageItem(id)
 {
    if (confirm('<?php _e( 'Are you sure that you want to delete this item?', 'calculated-fields-form' ); ?>'))
    {        
        document.location = 'options-general.php?page=cp_calculated_fields_form&cal=<?php echo CP_CALCULATEDFIELDSF_ID; ?>&list=1&ld='+id+'&r='+Math.random()+'&_cpcff_nonce=<?php echo wp_create_nonce( 'session_id_'.session_id() ); ?>';
    }
 }
 function do_dexapp_print()
 {
      w=window.open();
      w.document.write("<style>table{border:2px solid black;width:100%;}th{border-bottom:2px solid black;text-align:left}td{padding-left:10px;border-bottom:1px solid black;}</style>"+document.getElementById('dex_printable_contents').innerHTML);
      w.document.close();
	  w.focus();
      w.print();
      w.close();    
 }
</script>
<div class="wrap">
<h1><?php _e( 'Calculated Fields Form - Message List', 'calculated-fields-form' ); ?></h1>

<input type="button" name="backbtn" value="<?php esc_attr_e( 'Back to items list...', 'calculated-fields-form' ); ?>" onclick="document.location='admin.php?page=cp_calculated_fields_form';">


<div id="normal-sortables" class="meta-box-sortables">
 <hr />
 <h3><?php _e( 'This message list is from', 'calculated-fields-form' ); ?>: <?php echo $myform->form_name; ?></h3>
</div>


<form action="admin.php" method="get">
 <input type="hidden" name="page" value="cp_calculated_fields_form" />
 <input type="hidden" name="list" value="1" />
 <div style="display:inline-block; white-space:nowrap; margin-right:20px;">
	<?php _e( 'Search for', 'calculated-fields-form' ); ?>: <input type="text" name="search" value="<?php echo esc_attr($_GET["search"]); ?>" />
 </div> 
 <div style="display:inline-block; white-space:nowrap; margin-right:20px;">
	<?php _e( 'From', 'calculated-fields-form' ); ?>: <input type="text" id="dfrom" name="dfrom" value="<?php echo esc_attr($_GET["dfrom"]); ?>" />
 </div>
 <div style="display:inline-block; white-space:nowrap; margin-right:20px;">  
	<?php _e( 'To', 'calculated-fields-form' ); ?>: <input type="text" id="dto" name="dto" value="<?php echo esc_attr($_GET["dto"]); ?>" />
 </div>
 <div style="display:inline-block; white-space:nowrap; margin-right:20px;"> 
	<?php _e( 'In', 'calculated-fields-form' ); ?>: <select id="cal" name="cal"><?php echo $form_list_opts; ?></select>
 </div>	
 <?php
	/**
	 * Additional filtering options, allows to add new fields for filtering the results
	 */
	do_action( 'cpcff_messages_filters' );
 ?> 
 <nobr><span class="submit"><input type="submit" name="ds" value="<?php esc_attr_e( 'Filter', 'calculated-fields-form' ); ?>" /></span> &nbsp; &nbsp; &nbsp; 
 <span class="submit"><input type="submit" name="cp_calculatedfieldsf_csv" value="<?php esc_attr_e( 'Export to CSV', 'calculated-fields-form' ); ?>" /></span></nobr>
 <input type="hidden" name="_cpcff_nonce" value="<?php echo wp_create_nonce( 'session_id_'.session_id() ); ?>" />
</form>

<br />
                             
<?php
echo paginate_links(  array(
    'base'         => 'admin.php?page=cp_calculated_fields_form&cal='.CP_CALCULATEDFIELDSF_ID.'&list=1%_%&dfrom='.urlencode($_GET["dfrom"]).'&dto='.urlencode($_GET["dto"]).'&search='.urlencode($_GET["search"]),
    'format'       => '&p=%#%',
    'total'        => $total_pages,
    'current'      => $current_page,
    'show_all'     => False,
    'end_size'     => 1,
    'mid_size'     => 2,
    'prev_next'    => True,
    'prev_text'    => __('&laquo; Previous'),
    'next_text'    => __('Next &raquo;'),
    'type'         => 'plain',
    'add_args'     => False
    ) );

?>

<div id="dex_printable_contents">
<table class="wp-list-table widefat fixed pages" cellspacing="0">
	<thead>
	<tr>
      <th width="30px" style="font-weight:bold;"><input type="checkbox" onclick="cp_checkAllItems( this )" style="margin-left:8px;"></th>  
      <th style="padding-left:7px;font-weight:bold;"><?php _e( 'Submission ID', 'calculated-fields-form' ); ?></th>  
	  <th style="padding-left:7px;font-weight:bold;"><?php _e( 'Date', 'calculated-fields-form' ); ?></th>
	  <th style="padding-left:7px;font-weight:bold;"><?php _e( 'Email', 'calculated-fields-form' ); ?></th>
	  <th style="padding-left:7px;font-weight:bold;"><?php _e( 'Message', 'calculated-fields-form' ); ?></th>
	  <th style="padding-left:7px;font-weight:bold;"><?php _e( 'Payment Info', 'calculated-fields-form' ); ?></th>
	  <?php 
		/**
		 * Action called to include new headers in the table of messages
		 */
		do_action( 'cpcff_messages_list_header' );
	  ?>
	  <th style="padding-left:7px;font-weight:bold;"><?php _e( 'Options', 'calculated-fields-form' ); ?></th>	
	</tr>
	</thead>
	<tbody id="the-list">
	 <?php for ($i=($current_page-1)*$records_per_page; $i<$current_page*$records_per_page; $i++) if (isset($events[$i])) { ?>
	  <tr class='<?php if (!($i%2)) { ?>alternate <?php } ?>author-self status-draft format-default iedit' valign="top">
        <td width="30px"><input type="checkbox" value="<?php echo $events[$i]->id; ?>" class="cp_item" style="margin-left:8px;"></td>
        <td><?php echo $events[$i]->id; ?></td>
		<td><?php echo substr($events[$i]->time,0,16); ?></td>
		<td><?php echo $events[$i]->notifyto; ?></td>
        <td>
			<?php 
				echo str_replace( array( '\"', "\'", "\n" ), array( '"', "'", "<br />" ), $events[$i]->data ); 
				
				// Add links
				$paypal_post = @unserialize( $events[ $i ]->paypal_post );
				if( $paypal_post !== false )
				{				
					foreach( $paypal_post as $_key => $_value )
					{
						if( strpos( $_key, '_url' ) )
						{
							if( is_array( $_value ) )
							{
								foreach( $_value as $_url )
								{
									print '<p><a href="'.esc_attr( $_url ).'" target="_blank">'.$_url.'</a></p>';
								}
							}	
						}	
					}
				}
			?>
		</td>
		<td>
		    <?php 
				if ($events[$i]->paid) {
					
					if( $paypal_post !== false && !empty( $paypal_post[ 'paypal_data' ] ) )
					{	
						echo '<span style="color:#00aa00;font-weight:bold"><a href="javascript:void(0);" onclick="cp_moreInfo(this);">'.__("Paid", 'calculated-fields-form' ).'[+]</a></span><div class="paypal_data" style="display:none;">'.$paypal_post[ 'paypal_data' ].'</div>';
					}
					else
					{
						echo '<span style="color:#00aa00;font-weight:bold">'.__("Paid", 'calculated-fields-form' ).'</span>';
					}
				}    
				else 
					echo '<span style="color:#ff0000;font-weight:bold">'.__("Not Paid", 'calculated-fields-form' ).'</span>'; 
		    ?>
		    
		</td>
		<?php 
			/**
			 * Action called to add related data to the message
			 * The row is passed as parameter
			 */
			do_action( 'cpcff_message_row_data', $events[ $i ] );
		?>
		<td>
		  <?php if ($events[$i]->paid) { ?>
   	        <input type="button" name="calmanage_<?php echo $events[$i]->id; ?>" value="<?php esc_attr_e( 'Change to NOT PAID', 'calculated-fields-form' ); ?>" onclick="cp_updateMessageItem(<?php echo $events[$i]->id; ?>,0);" />                             
 		  <?php } else { ?>
 		    <input type="button" name="calmanage_<?php echo $events[$i]->id; ?>" value="<?php esc_attr_e( 'Change to PAID', 'calculated-fields-form' ); ?>" onclick="cp_updateMessageItem(<?php echo $events[$i]->id; ?>,1);" />                             
 		  <?php } ?>
		  &nbsp;
		  <input type="button" name="caldelete_<?php echo $events[$i]->id; ?>" value="<?php esc_attr_e( 'Delete', 'calculated-fields-form' ); ?>" onclick="cp_deleteMessageItem(<?php echo $events[$i]->id; ?>);" />                             
		</td>
      </tr>
     <?php } ?>
	</tbody>
</table>
</div>
<p class="submit"><input type="button" name="pbutton" value="<?php esc_attr_e( 'Delete all checked', 'calculated-fields-form' ); ?>" onclick="cp_deleteAll();" /> <input type="button" name="pbutton" value="<?php esc_attr_e( 'Print', 'calculated-fields-form' ); ?>" onclick="do_dexapp_print();" /></p>
</div>

<script type="text/javascript">
 var $j = jQuery.noConflict();
 $j(function() {
 	$j("#dfrom").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 	$j("#dto").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 });
 
</script>