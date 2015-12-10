<?php if( true === $ccontact ) { ?>
	<h3 class="spu-title"><?php _e( 'Constant Contact Lists' ,'spup' ); ?></h3>
	<p><?php _e( 'The table below shows your Constant Contact lists data. If you applied changes to your Constant Contact lists, please use the following button to renew your cached data.', 'spup' ); ?></p>

	<p>
		<a href="<?php print wp_nonce_url(admin_url('edit.php?post_type=spucpt&page=spu_integrations'), 'spu_ccontact_renew', 'spu_nonce');?>" class="button "><?php _e( 'Renew Constant Contact lists', 'spup' ); ?></a>
	</p>
	<table class="wp-list-table widefat">
		<thead>
		<tr>
			<th class="spu-hide-smallscreens" scope="col"><?php _e( 'List ID', 'spup' ); ?></th>
			<th scope="col"><?php _e( 'List Name', 'spup' ); ?></th>
			<th scope="col"><?php _e( 'Subscribers', 'spup' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		if($ccontact_lists) {
			foreach($ccontact_lists as $list) { ?>

				<tr valign="top">
					<td><?php echo esc_html( $list->id ); ?></td>
					<td><?php echo esc_html( $list->name ); ?></td>
					<td><?php echo esc_html( $list->contact_count ); ?></td>
				</tr>
			<?php
			}
		} else { ?>
			<tr>
				<td colspan="5">
					<p><?php _e( 'No lists were found in your Constant Contact account.', 'spup' ); ?></p>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>

<?php } ?>