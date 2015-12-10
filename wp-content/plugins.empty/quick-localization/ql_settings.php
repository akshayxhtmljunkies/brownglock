<?php



function ql_settings_page () {
	global $QLC;

	global $ql_options;

	if ( isset ( $_POST [ 'qlsnonce' ] ) && wp_verify_nonce ( $_POST [ 'qlsnonce' ], 'qls' ) ) {
		if ( isset ( $_POST [ 'ql_save' ] ) ) {
			//
			$ql_options [ "only_unknown" ] = $_POST [ "ql_settings_only_unknown" ] ? "yes" : "no" ;
			if ( is_multisite () && is_super_admin () ) {
				$ql_options [ "only_superadmins" ] = $_POST [ "ql_settings_only_superadmins" ] ? "yes" : "no" ;
			} // end of if ( is_multisite () && is_super_admin () )
			$ql_options [ "default_order_by" ] = $_POST [ "ql_settings_default_order_by" ];
			//
			$ql_options [ "warn_on_duplicates" ] = $_POST [ "ql_settings_warn_on_duplicates" ] ? "yes" : "no" ;
			//
			$ql_options [ "footer_textarea" ] = $_POST [ "ql_settings_footer_textarea" ] ? "yes" : "no" ;
			$ql_options [ "collect_draft_translations_fe" ] = $_POST [ "ql_settings_collect_draft_translations_fe" ] ? "yes" : "no" ;
			$ql_options [ "collect_draft_translations_be" ] = $_POST [ "ql_settings_collect_draft_translations_be" ] ? "yes" : "no" ;
			$ql_options [ "collect_draft_translations_gettext" ] = "yes"; // $_POST [ "ql_settings_collect_draft_translations_gettext" ] ? "yes" : "no" ;
			$ql_options [ "collect_draft_translations_ngettext" ] = $_POST [ "ql_settings_collect_draft_translations_ngettext" ] ? "yes" : "no" ;
			$ql_options [ "collect_draft_translations_gettext_with_context" ] = $_POST [ "ql_settings_collect_draft_translations_gettext_with_context" ] ? "yes" : "no" ;
			$ql_options [ "collect_draft_translations_ngettext_with_context" ] = $_POST [ "ql_settings_collect_draft_translations_ngettext_with_context" ] ? "yes" : "no" ;
			update_option ( 'ql_options', $ql_options );
			//
			$ql_collect_draft_translations_white = explode ( "\n", stripslashes ( $_POST [ "ql_settings_collect_draft_translations_white" ] ) );
			$collect_draft_translations_white = array ();
			foreach ( $ql_collect_draft_translations_white as $row ) {
				$row = trim ( $row );
				if ( $row ) {
					$collect_draft_translations_white [] = $row;
				}
			}
			update_option ( 'ql_collect_draft_translations_white', $collect_draft_translations_white );
			//
			$ql_collect_draft_translations_black = explode ( "\n", stripslashes ( $_POST [ "ql_settings_collect_draft_translations_black" ] ) );
			$collect_draft_translations_black = array ();
			foreach ( $ql_collect_draft_translations_black as $row ) {
				$row = trim ( $row );
				if ( $row ) {
					$collect_draft_translations_black [] = $row;
				}
			}
			update_option ( 'ql_collect_draft_translations_black', $collect_draft_translations_black );

			echo '<div id="message" class="updated fade"><p>' . __ ( "Settings saved.", "QL" ) . '</p></div>';
		}
		elseif ( isset ( $_POST [ 'ql_reset' ] ) ) {
			$ql_new = $QLC -> reinstall ();
			$ql_options = get_option ( 'ql_options' );
			echo '<div id="message" class="updated fade"><p>' . __ ( "You are done reinstalling!", "QL" ) . '</p></div>';
		}
	}
?>	
	<div class="wrapper">
	<h2><?php _e ( "Quick Localisation", "QL" ); ?> - <?php _e ( "Settings", "QL" ); ?> (<?php echo sprintf ( __ ( "DB version: %s", "QL" ), QL_VERSION ); ?>)</h2>
	<form method='post'>
	<?php wp_nonce_field ( "qls", "qlsnonce" ); ?>

	<p><h4><?php _e ( "General", "QL" ); ?></h4></p>
	<p><input type="checkbox" value="1" name="ql_settings_only_unknown" id="ql_settings_only_unknown" <?php echo "yes" == $ql_options [ "only_unknown" ] ? 'checked="yes"' : ''; ?>/><label for="ql_settings_only_unknown"> <?php _e ( 'Only handle translations where Wordpress has the same values for old and new entries (if not checked, all translations will be handled). N.B. this will also apply to saving the drafts (see <a href="#collecting">below</a>).', "QL" ); ?></label></p>
<?php if ( is_multisite () && is_super_admin () ) { ?>
	<p><input type="checkbox" value="1" name="ql_settings_only_superadmins" id="ql_settings_only_superadmins" <?php echo "yes" == $ql_options [ "only_superadmins" ] ? 'checked="yes"' : ''; ?>/><label for="ql_settings_only_superadmins"> <?php _e ( "Show Quick Localisation admin sections only to super admins.", "QL" ); ?></label></p>
<?php } // end of if ( is_multisite () && is_super_admin () ) ?>
	<p><input type="submit" class="button-primary" value="<?php _e ( "Save", "QL" ); ?>" name="ql_save"></p>

	<p><h4><?php _e ( "Editing", "QL" ); ?></h4></p>
	<p><input type="checkbox" value="1" name="ql_settings_warn_on_duplicates" id="ql_settings_warn_on_duplicates" <?php echo "yes" == $ql_options [ "warn_on_duplicates" ] ? 'checked="yes"' : ''; ?>/><label for="ql_settings_warn_on_duplicates"> <?php _e ( 'Warn on duplicates on <a href="admin.php?page=ql-home">Edit</a> page.', "QL" ); ?></label></p>
	<p><input type="submit" class="button-primary" value="<?php _e ( "Save", "QL" ); ?>" name="ql_save"></p>

	<p><h4><?php _e ( "Sorting", "QL" ); ?></h4></p>
	<p><label for="ql_settings_default_order_by"> <?php _e ( 'Default order (on <a href="admin.php?page=ql-home">Edit</a> and <a href="admin.php?page=ql-export">Export</a> pages):', "QL" ); ?></label></p>
	<p>&nbsp; <input type="radio" value="id" name="ql_settings_default_order_by" id="ql_settings_default_order_by_id" <?php echo "id" == $ql_options [ "default_order_by" ] ? 'checked="checked"' : ''; ?>/><label for="ql_settings_default_order_by_id"> <?php _e ( "By addition time", "QL" ); ?></label>
	<br />&nbsp; <input type="radio" value="old" name="ql_settings_default_order_by" id="ql_settings_default_order_by_old" <?php echo "old" == $ql_options [ "default_order_by" ] ? 'checked="checked"' : ''; ?>/><label for="ql_settings_default_order_by_old"> <?php _e ( "By old values", "QL" ); ?></label>
	<br />&nbsp; <input type="radio" value="new" name="ql_settings_default_order_by" id="ql_settings_default_order_by_new" <?php echo "new" == $ql_options [ "default_order_by" ] ? 'checked="checked"' : ''; ?>/><label for="ql_settings_default_order_by_new"> <?php _e ( "By new values", "QL" ); ?></label>
	</p>
	<p><input type="submit" class="button-primary" value="<?php _e ( "Save", "QL" ); ?>" name="ql_save"></p>

	<p><a name="collecting"></a><h4><?php _e ( "Collecting", "QL" ); ?></h4></p>
	<p><input type="checkbox" value="1" name="ql_settings_collect_draft_translations_fe" id="ql_settings_collect_draft_translations_fe" <?php echo "yes" == $ql_options [ "collect_draft_translations_fe" ] ? 'checked="yes"' : ''; ?>/><label for="ql_settings_collect_draft_translations_fe"> <?php _e ( "Save drafts of translations used on the front-end (the actual site).", "QL" ); ?></label></p>
	<p><input type="checkbox" value="1" name="ql_settings_collect_draft_translations_be" id="ql_settings_collect_draft_translations_be" <?php echo "yes" == $ql_options [ "collect_draft_translations_be" ] ? 'checked="yes"' : ''; ?>/><label for="ql_settings_collect_draft_translations_be"> <?php _e ( "Save drafts of translations used on the back-end (control panel, login, sign-up pages, etc).", "QL" ); ?></label></p>
	<p><?php _e ( "IMPORTANT: collecting drafts will create additional load each time any page is requested. It is highly recommended to enable this option shortly, go through the pages you want to collect draft translations, then disable this feature.", "QL" ); ?></p>
	<p><?php _e ( 'Once collected, drafts will be available via <a href="admin.php?page=ql-home">Edit</a> page. Draft entries will be marked by adding dashes on both sides to translation domains <code>-DOMAIN-</code>. You will need to remove dashes making it <code>DOMAIN</code> for the draft to be used live.', "QL" ); ?></p>
	<p><label for="ql_settings_collect_draft_translations_white"><?php _e ( "Save only these domains (one per line; all domains will be saved if empty):", "QL" ); ?></label><br />
	<textarea name="ql_settings_collect_draft_translations_white" id="ql_settings_collect_draft_translations_white"><?php echo esc_textarea ( implode ( "\n", get_option ( "ql_collect_draft_translations_white", array () ) ) ); ?></textarea></p>
	<p><label for="ql_settings_collect_draft_translations_black"><?php _e ( "Do not save these domains (one per line):", "QL" ); ?></label><br />
	<textarea name="ql_settings_collect_draft_translations_black" id="ql_settings_collect_draft_translations_black"><?php echo esc_textarea ( implode ( "\n", get_option ( "ql_collect_draft_translations_black", array ( "default" ) ) ) ); ?></textarea><br />
	<?php _e ( "N.B. <code>default</code> domain usually has 600+ translations. You don't want to load that many of them, do you?", "QL" ); ?></p>
	<p><input type="checkbox" value="1" name="ql_settings_collect_draft_translations_gettext" id="ql_settings_collect_draft_translations_gettext" <?php echo "yes" == $ql_options [ "collect_draft_translations_gettext" ] ? 'checked="yes"' : ''; ?> disabled/><label for="ql_settings_collect_draft_translations_gettext"> <?php _e ( "Save drafts of translations called by functions <code>__</code>, <code>_e</code>, and <code>translate</code> (filter <code>gettext</code>).", "QL" ); ?></label></p>
	<p><input type="checkbox" value="1" name="ql_settings_collect_draft_translations_ngettext" id="ql_settings_collect_draft_translations_ngettext" <?php echo "yes" == $ql_options [ "collect_draft_translations_ngettext" ] ? 'checked="yes"' : ''; ?>/><label for="ql_settings_collect_draft_translations_ngettext"> <?php _e ( "Save drafts of translations called by function <code>_n</code> (filter <code>ngettext</code>).", "QL" ); ?></label></p>
	<p><input type="checkbox" value="1" name="ql_settings_collect_draft_translations_gettext_with_context" id="ql_settings_collect_draft_translations_gettext_with_context" <?php echo "yes" == $ql_options [ "collect_draft_translations_gettext_with_context" ] ? 'checked="yes"' : ''; ?>/><label for="ql_settings_collect_draft_translations_gettext_with_context"> <?php _e ( "Save drafts of translations called by functions <code>_x</code>, <code>_ex</code>, and <code>translate_with_gettext_context</code> (filter <code>gettext_with_context</code>).", "QL" ); ?></label></p>
	<p><input type="checkbox" value="1" name="ql_settings_collect_draft_translations_ngettext_with_context" id="ql_settings_collect_draft_translations_ngettext_with_context" <?php echo "yes" == $ql_options [ "collect_draft_translations_ngettext_with_context" ] ? 'checked="yes"' : ''; ?>/><label for="ql_settings_collect_draft_translations_ngettext_with_context"> <?php _e ( "Save drafts of translations called by functions <code>_nx</code> (filter <code>ngettext_with_context</code>).", "QL" ); ?></label></p>
	<?php // _e ( 'N.B. Drafts of translations called by function <code>_nx</code> (filter <code>ngettext_with_context</code>) will be saved if both options for filters <label for="ql_settings_collect_draft_translations_ngettext"><code>ngettext</code></label> and <label for="ql_settings_collect_draft_translations_gettext_with_context"><code>gettext_with_context</code></label> above are enabled.', "QL" ); ?></p>
	<p><input type="hidden" name="ql_settings_update"/></p>
	<p><input type="submit" class="button-primary" value="<?php _e ( "Save", "QL" ); ?>" name="ql_save"></p>

	<p><h4><?php _e ( "Debugging (sort of)", "QL" ); ?></h4></p>
	<p><input type="checkbox" value="1" name="ql_settings_footer_textarea" id="ql_settings_footer_textarea" <?php echo "yes" == $ql_options [ "footer_textarea" ] ? 'checked="yes"' : ''; ?>/><label for="ql_settings_footer_textarea"> <?php _e ( "Gather all translations called via <code>*gettext*</code> filters and show them only to the site admins on every page in a black box in the footer area.", "QL" ); ?></label></p>
	<p><input type="submit" class="button-primary" value="<?php _e ( "Save", "QL" ); ?>" name="ql_save"></p>

	<p>&nbsp;</p>
	<p><h4><?php _e ( "Reinstall", "QPM" ); ?></h4></p>
	<p style='font-size:12px;'><?php _e ( 'If you want to reinstall Quick Localisation, use the button below. N.B. it will ERASE all saved Quick Localisation settings and translation. Please <a href="admin.php?page=ql-export">Export</a> if you want to keep a back-up.', "QL" ); ?></p>
	<p><input type="submit" onclick="return confirm('<?php _e ( "All settings including translations will be lost! ARE YOU SURE?", "QL" ); ?>')" class="button" value="<?php _e ( "Reset all settings", "QL" ); ?>" name="ql_reset"></p>
	</form>

	<p>&nbsp;</p>
	<p><h4><?php _e ( "Name.ly/Plugins", "QPM" ); ?></h4></p>
	<p>This plugin is proundly presented to you by <a href="http://name.ly/plugins/" target="_blank"><i>Name.ly/Plugins</i></a>.</p>
	<p><i>Name.ly</i> offers WordPress blogs and many other services allowing to consolidate multiple sites, pages and profiles.</p>
	<p>All on catchy domain names, like many.at, brief.ly, sincere.ly, links2.me, thatis.me, of-cour.se, ... and hundreds more.</p>
	<p><i>Name.ly/PRO</i> platform allows domain name owners to run similar sites under their own brand.</p>
	<p><a href="http://namely.pro/" target="_blank"><i>Name.ly/PRO</i></a> is most known for being first WordPress driven product allowing reselling emails and sub-domains.</p>

	</div>

<?php

}



?>