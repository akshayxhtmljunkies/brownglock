/*
 * Settings of the sticky menu
 */

jQuery(document).ready(function($){
    var wpAdminBar = $('#wpadminbar');
    if (wpAdminBar.length) {
        $("#site-navigation").sticky({topSpacing:wpAdminBar.height()});
    } else {
        $("#site-navigation").sticky({topSpacing:0});
    }
});