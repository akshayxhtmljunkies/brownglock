<?php if( true === $aweber ) { ?>
	<h3 class="spu-title"><?php _e( 'Aweber Lists' ,'spup' ); ?></h3>
	<p><?php _e( 'The table below shows your Aweber lists data. If you applied changes to your Aweber lists, please use the following button to renew your cached data.', 'spup' ); ?></p>

	<p>
		<a href="<?php print wp_nonce_url(admin_url('edit.php?post_type=spucpt&page=spu_integrations'), 'spu_aweber_renew', 'spu_nonce');?>" class="button "><?php _e( 'Renew Aweber lists', 'spup' ); ?></a>
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
		if($aweber_lists) {
			foreach($aweber_lists as $list) { ?>

				<tr valign="top">
					<td><?php echo esc_html( $list->id ); ?></td>
					<td><?php echo esc_html( $list->name ); ?></td>
					<td><?php echo esc_html( $list->total_subscribed_subscribers ); ?></td>
				</tr>
			<?php
			}
		} else { ?>
			<tr>
				<td colspan="5">
					<p><?php _e( 'No lists were found in your Aweber account.', 'spup' ); ?></p>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>

<?php } ?>