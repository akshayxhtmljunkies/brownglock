<?php if( true === $gr_connected ) { ?>
	<h3 class="spu-title"><?php _e( 'GetResponse Lists' ,'spup' ); ?></h3>
	<p><?php _e( 'The table below shows your GetResponse lists data. If you applied changes to your GetResponse lists, please use the following button to renew your cached data.', 'spup' ); ?></p>

	<p>
		<a href="<?php print wp_nonce_url(admin_url('edit.php?post_type=spucpt&page=spu_integrations'), 'spu_gr_renew', 'spu_nonce');?>" class="button "><?php _e( 'Renew GetResponse lists', 'spup' ); ?></a>
	</p>
	<table class="wp-list-table widefat">
		<thead>
		<tr>
			<th class="spu-hide-smallscreens" scope="col"><?php _e( 'List ID', 'spup' ); ?></th>
			<th class="spu-hide-smallscreens" scope="col"><?php _e( 'List Name', 'spup' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		if($gr_lists) {
			foreach($gr_lists as $list) {
				?>

				<tr valign="top">
					<td><?php echo esc_html( $list->id ); ?></td>
					<td><?php echo esc_html( $list->name ); ?></td>
				</tr>
			<?php
			}
		} else { ?>
			<tr>
				<td colspan="2">
					<p><?php _e( 'No lists were found in your GetResponse account.', 'spup' ); ?></p>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>

<?php } ?>