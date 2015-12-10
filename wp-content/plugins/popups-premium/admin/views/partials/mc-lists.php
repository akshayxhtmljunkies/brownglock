<?php if( true === $mc_connected ) { ?>
	<h3 class="spu-title"><?php _e( 'MailChimp Lists' ,'spup' ); ?></h3>
	<p><?php _e( 'The table below shows your MailChimp lists data. If you applied changes to your MailChimp lists, please use the following button to renew your cached data.', 'spup' ); ?></p>

	<p>
		<a href="<?php print wp_nonce_url(admin_url('edit.php?post_type=spucpt&page=spu_integrations'), 'spu_mc_renew', 'spu_nonce');?>" class="button "><?php _e( 'Renew MailChimp lists', 'spup' ); ?></a>
	</p>
	<table class="wp-list-table widefat">
		<thead>
		<tr>
			<th class="spu-hide-smallscreens" scope="col"><?php _e( 'List ID', 'spup' ); ?></th>
			<th scope="col"><?php _e( 'List Name', 'spup' ); ?></th>
			<th scope="col"><?php _e( 'Groupings', 'spup' ); ?></th>
			<th scope="col"><?php _e( 'Subscribers', 'spup' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		if($mc_lists) {
			foreach($mc_lists as $list) { ?>

				<tr valign="top">
					<td><?php echo esc_html( $list->id ); ?></td>
					<td><?php echo esc_html( $list->name ); ?></td>

					<td>
						<?php
						if( ! empty( $list->interest_groupings ) ) {
							foreach($list->interest_groupings as $grouping) { ?>
								<strong><?php echo esc_html( $grouping->name ); ?></strong>

								<?php if( ! empty( $grouping->groups ) ) { ?>
									<ul class="ul-square">
										<?php foreach( $grouping->groups as $group ) { ?>
											<li><?php echo esc_html( $group->name ); ?></li>
										<?php } ?>
									</ul>
								<?php } ?>
							<?php }
						} else {
							?>-<?php
						} ?>

					</td>
					<td><?php echo esc_html( $list->subscriber_count ); ?></td>
				</tr>
			<?php
			}
		} else { ?>
			<tr>
				<td colspan="5">
					<p><?php _e( 'No lists were found in your MailChimp account.', 'spup' ); ?></p>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>

<?php } ?>