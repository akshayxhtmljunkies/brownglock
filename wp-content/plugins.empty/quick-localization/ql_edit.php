<?php



define ( "QLC_BULK_EDIT_MAX_ITEMS", 50 );



function ql_select_option ( $value, $display, $selected ) {
	return "<option value='" . $value . "'" . ($selected ? " selected='selected' " : '') . '>' . $display . '</option>';
}

function ql_print_main ( $domain = '', $order_by = '', $pageset = null ) {
	global $QLC;

	$ql_options = get_option ( 'ql_options' );
	$warn_on_duplicates = "yes" == $ql_options [ 'warn_on_duplicates' ];

	$ql_all = $QLC -> get_from_db ( $domain, $order_by );
	$ql_all_ok = $ql_all && is_array ( $ql_all );

	if ( $ql_all_ok && ! $pageset ) {
		$allow_bulk_edit = count ( $ql_all ) <= QLC_BULK_EDIT_MAX_ITEMS;
		$input_disabled = $allow_bulk_edit ? '' : ' disabled';
	} else {
		$allow_bulk_edit = true;
		$input_disabled = '';
	} // end of if ( $ql_all_ok )

	if ( ! $allow_bulk_edit ) {
		echo "<p>" . sprintf ( __ ( 'N.B. Bulk edit is only possible for up to %d items. Please use individual paged view first by clicking on the numbered links above or below.', "QL" ), QLC_BULK_EDIT_MAX_ITEMS ) . "</p>";
	}

?>
<table width="70%" id="ql">
	<thead><tr><td></td><td><strong><?php _e ( "Old", "QL" ); ?></strong></td><td><strong><?php _e ( "New", "QL" ); ?></strong></td><td><strong><?php _e ( "Domain", "QL" ); ?></strong></td><td><strong><?php _e ( "Action(s)", "QL" ); ?></strong></td></tr></thead>
<?php
	$x = 1;
	if ( $ql_all_ok ) {
		// work-around for PHP 5.2 not handling the "new" property correctly
		$newfield = "new";
		foreach ( $ql_all as $index => $row ) {
			if ( $pageset ) {
				if ( $index < ($pageset-1)*QLC_BULK_EDIT_MAX_ITEMS || $index >= $pageset*QLC_BULK_EDIT_MAX_ITEMS ) {
					continue;
				}
			}
			$is_draft_domain = $QLC -> is_draft_domain ( $row->domain );
			$undrafted_domain = $is_draft_domain ? $QLC -> undraft_domain ( $row -> domain ) : $row -> domain;
			$draft_domain_message = $is_draft_domain && $allow_bulk_edit ? "<br /><a onclick='document.getElementById(\"ql_domain_$x\").value=\"{$undrafted_domain}\";if(\"\"==document.getElementById(\"ql_new_$x\").value){document.getElementById(\"ql_new_$x\").value=document.getElementById(\"ql_old_$x\").value;}'>" . __ ( "Undraft", "QL" ) . "</a>" : "" ;
			if ( $warn_on_duplicates ) {
				$did = $QLC -> find_id ( $row->old, $row->domain );
				$duplicate_message = ! $did || $did == $row -> id ? '' : __ ( "Warning:", "QL" ) . ' <a href="admin.php?page=ql-home' . ( null === $domain ? '' : '&domain=' . urlencode ( $row->domain ) ) . '#' . $did . '">' . __ ( "Found a duplicate!", "QL" ) . '</a><br />';
				if ( $is_draft_domain ) {
					$uid = $QLC -> find_id ( $row->old, $undrafted_domain );
					$undrafted_duplicate_message = $uid ? __ ( "Warning:", "QL" ) . ' <a href="admin.php?page=ql-home' . ( null === $domain ? '' : '&domain=' . urlencode ( $undrafted_domain ) ) . '#' . $uid . '">' . __ ( "Found an undrafted duplicate!", "QL" ) . '</a><br />' : '';
				}
			}
			$ps = QLC_BULK_EDIT_MAX_ITEMS > 0 ? ceil ( ( $index + 1 ) / QLC_BULK_EDIT_MAX_ITEMS ) : null;
			$empty_message = $allow_bulk_edit ? "<a onclick='ql_mark2delete($x);'>" . __ ( "Empty", "QL" ) . "</a> " . __ ( "(mark to delete)", "QL" ) : ( QLC_BULK_EDIT_MAX_ITEMS > 0 ? sprintf ( __ ( 'Switch to <a href="admin.php?page=ql-home' . ( $domain ? "&domain=" . urlencode ( $domain ) : "" ) . ( $order_by ? "&order_by=" . urlencode ( $order_by ) : "" ) . ( $ps ? "&pageset=" . $ps : "" ) . '#%d">page %d</a> to edit', "QL" ), $row->id, ceil ( ( $index + 1 ) / QLC_BULK_EDIT_MAX_ITEMS ) ) : "" );
			$oldi = esc_textarea ( $row -> old );
			$newi = esc_textarea ( $row -> $newfield );
			$domaini = esc_attr ( $row -> domain );
			echo "
			<tr>
			<td><input type='hidden' value='{$row->id}' name='ql[$x][id]' id='ql_id_$x' /></td>
			<td><a name='{$row->id}'></a><textarea style='width:90%;' size='50' name='ql[$x][old]' id='ql_old_$x'" . $input_disabled . ">{$oldi}</textarea></td>
			<td><textarea style='width:90%;' size='50' name='ql[$x][new]' id='ql_new_$x'" . $input_disabled . ">{$newi}</textarea></td>
			<td><input type='text' style='width:90%;' size='10' value='{$domaini}' name='ql[$x][domain]' id='ql_domain_$x'" . $input_disabled . "/></td>
			<td>{$duplicate_message}{$undrafted_duplicate_message}{$empty_message}{$draft_domain_message}</td>
			</tr>";
			$x++;
		}
	}
?>
	<tr id="row_penultimate">
<?php
		echo "
		<td><input type='hidden' value='' name='ql[$x][id]' id='ql_id_$x' /></td>
		<td><textarea style='width:90%;' name='ql[$x][old]' id='ql_old_$x' ></textarea></td>
		<td><textarea style='width:90%;' name='ql[$x][new]' id='ql_new_$x' ></textarea></td>
		<td><input type='text' value='$domain' style='width:90%;' name='ql[$x][domain]' id='ql_domain_$x' /></td>
		<td><a onclick='ql_mark2delete($x);'>" . __ ( "Empty", "QL" ) . "</a> " . __ ( "(mark to delete)", "QL" ) . "</td>";
		$x++;
?>
	</tr>
	<tr id="row_last">
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
<script type="text/javascript">
var increment=<?php echo $x; ?>;
function ql_mark2delete(id){
	document.getElementById("ql_old_"+id).value="";
	document.getElementById("ql_new_"+id).value="";
	document.getElementById("ql_domain_"+id).value="";
}
function ql_one_more_row(){
	jQuery("#row_last").before("<tr><td><input type='hidden' value='' name='ql["+increment+"][id]' id='ql_id_"+increment+"' /></td><td><textarea style='width:90%;' size='50' value='' name='ql["+increment+"][old]' id='ql_old_"+increment+"' ></textarea></td><td><textarea style='width:90%;' size='50' value='' name='ql["+increment+"][new]' id='ql_new_"+increment+"' ></textarea></td><td><input type='text' size='10' style='width:90%;' value='<?php echo $domain; ?>' name='ql["+increment+"][domain]' id='ql_domain_"+increment+"' /></td><td><a onclick='ql_mark2delete("+increment+");'>Empty</a> (mark to delete)</td></tr>");
	increment++;
}
</script>
<?php
}



function ql_edit_page () {
	global $ql_options;
	global $QLC;
	
	$domain = isset ( $_GET [ 'domain' ] ) ? $_GET [ 'domain' ] : null;
	$domain_url = null === $domain ? "" : "&domain=" . urlencode ( $domain );
	$order_by = isset ( $_GET [ 'order_by' ] ) ? $_GET [ 'order_by' ] : $ql_options [ "default_order_by" ];
	$order_by = $order_by ? $order_by : "id";
	$order_by_url = null === $order_by ? "" : "&order_by=" . urlencode ( $order_by );
	$pageset = isset ( $_GET [ 'pageset' ] ) ? (int) $_GET [ 'pageset' ] : null;
	$pageset_url = null === $pageset ? "" : "&pageset=" . $pageset;

	if ( wp_verify_nonce ( $_POST [ 'qlenonce' ], 'qle' ) ) {
		if ( isset ( $_POST [ 'ql_save' ] ) ) {
			$qls = $_POST [ 'ql' ];
			$qlna = 0;
			$qlnd = 0;
			$qlns = 0;
			$qlnu = 0;
			foreach ( $qls as $qli ) {
				if ( ! empty ( $qli [ 'id' ] ) ) {
					if ( empty ( $qli [ 'old' ] ) ) {
						$QLC -> delete ( $qli [ 'id' ] );
						$qlnd++;
					} else {
						$QLC -> update ( $qli [ 'id' ], stripslashes ( $qli [ 'old' ] ), stripslashes ( $qli [ 'new' ] ), stripslashes ( $qli [ 'domain' ] ) );
						$qlnu++;
					}
				} else {
					if ( empty ( $qli [ 'old' ] ) ) {
						$qlns++;
					} else {
						$QLC -> add ( stripslashes ( $qli [ 'old' ] ), stripslashes ( $qli [ 'new' ] ), stripslashes ( $qli [ 'domain' ] ) );
						$qlna++;
					}
				}
			}
			echo '<div id="message" class="updated fade"><p>' . sprintf ( __ ( "Added: %d; Deleted: %d; Skipped: %d, Updated: %d.", "QL" ), $qlna, $qlnd, $qlns, $qlnu ) . '</p></div>';
		} elseif ( isset ( $_POST [ 'ql_erase' ] ) ) {
			$QLC -> delete_from_db ( $domain );
			echo '<div id="message" class="updated fade"><p>' . __ ( 'Cleared!', "QL" ) . '</p></div>';
		}
	}
?>

<div class="wrapper">

<h2><?php _e ( "Quick Localisation by <i>Name.ly</i>", "QL" ); ?></h2>

<form method="post">
<h3><?php _e ( "Edit", "QL" ); ?></h3>
<p><?php _e ( "Filter by domain:", "QL" ); ?> 
<?php 
	$row_count = 0;
	$list_of_saved_domains = $QLC -> get_list_of_saved_domains ();
	$filter_line = "";
	$all_count = 0;
	foreach ( $list_of_saved_domains as $row ) {
		$filter_line .= " | ";
		$filter_line .= $domain === $row->domain ? ( $row->domain ? $row->domain : "empty" ) : '<a href="admin.php?page=ql-home&domain=' . urlencode ( $row->domain ) . $order_by_url . $pageset_url . '">' . ( $row->domain ? $row->domain : "empty" ) . '</a>';
		$filter_line .= " (" . $row->count . ")";
		$all_count += $row->count;
		if ( $domain === $row->domain ) {
			$row_count = $row->count;
		}
	}
	if ( ! $row_count && ! $domain ) {
		$row_count = $all_count;
	}

	$filter_line = ( null === $domain ? __ ( 'All', "QL" ) : '<a href="admin.php?page=ql-home' . $order_by_url . '">' . __ ( 'All', "QL" ) . '</a>' ) . " (" . $all_count . ")" . $filter_line;
	echo $filter_line;
	// Pages
	$number_of_pages = QLC_BULK_EDIT_MAX_ITEMS > 0 ? ceil ( $row_count / QLC_BULK_EDIT_MAX_ITEMS ) : 1 ;
	if ( $number_of_pages > 1 ) {
		echo "<br />";
		_e ( "Pages:", "QL" );
		echo " " . ( ! $pageset ? __ ( 'All', "QL" ) : '<a href="admin.php?page=ql-home' . $domain_url . $order_by_url . '">' . __ ( 'All', "QL" ) . '</a>' );
		for ( $index = 1; $index <= $number_of_pages; $index++ ) {
			echo " | " . ( $pageset == $index ? $index : '<a href="admin.php?page=ql-home' . $domain_url . $order_by_url . '&pageset=' . $index . '">' . $index . '</a>' );
		}
	}
	// Sorting
	echo "<br />";
	_e ( "Sort by:", "QL" );
	echo " " . ( "id" == $order_by || ! $order_by ? __ ( 'Addition time', "QL" ) : '<a href="admin.php?page=ql-home' . $domain_url . '&order_by=id' . $pageset_url . '">' . __ ( 'Addition time', "QL" ) . '</a>' );
	echo " | " . ( "old" == $order_by ? __ ( 'Old', "QL" ) : '<a href="admin.php?page=ql-home' . $domain_url . '&order_by=old' . $pageset_url . '">' . __ ( 'Old', "QL" ) . '</a>' );
	echo " | " . ( "new" == $order_by ? __ ( 'New', "QL" ) : '<a href="admin.php?page=ql-home' . $domain_url . '&order_by=new' . $pageset_url . '">' . __ ( 'New', "QL" ) . '</a>' );
?> 
</p>
<?php ql_print_main ( $domain, $order_by, $pageset ); ?>
<p><a href="#" onclick="ql_one_more_row(); return false;"><?php _e ( "Add another one", "QL" ); ?></a></p>

<?php wp_nonce_field ( "qle", "qlenonce" ); ?>
<p><input type="submit" class="button-primary" value="<?php _e ( "Save", "QL" ); ?>" name="ql_save"></p>
<p></p>
<p><input type="submit" onclick="return confirm('<?php _e ( "ARE YOU SURE?", "QL" ); ?>')" class="button" value="<?php _e ( "Remove all entries above", "QL" ); ?>" name="ql_erase"></p>
</form>

<hr style="width:96%;" size="1" />
<h3><?php _e ( "Instructions", "QL" ); ?></h3>
<p style='font-size:12px;'><?php _e ( "Quick Localisation comes handy when you need to patch or substitute some [missing] translations in one of the Wordpress plugins or themes.", "QL" ); ?></p>
<p style='font-size:12px;'><?php _e ( "Simply provide old values to look for and to replace with the new values. This plugin then hooks on WP translation framework <code>gettext</code> and replaces the desired strings.", "QL" ); ?></p>
<p style='font-size:12px;'><?php _e ( 'To erase some entry, simply save the old value as an empty one. For more advanced mastering, please use <a href="admin.php?page=ql-import">Import</a> (where you can erase all previously saved records), <a href="admin.php?page=ql-export">Export</a> (where you can back it up), and more advanced <a href="admin.php?page=ql-settings">Settings</a> (where you can grab translations currently used).', "QL" ); ?></p>
<hr style="width:96%;" size="1" />

</div>
<?php
}



?>