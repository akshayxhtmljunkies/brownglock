(function() {
    
    tinymce.create('tinymce.plugins.facIcon', {
        init : function(ed, url) {
            ed.addButton('fac_icon', {
               title : 'FontAwesome Constructor',
               image : url+'/ico.png',
               onclick : function() {
                    jQuery('.fac-constructor-apply').unbind('click');
                    jQuery('.fac-constructor-apply').bind('click', function(event) {
                        var data = 'action=getShortcode&nonce=' + ajax_fac.ajax_nonce;
                        data = data + "&" + jQuery('#fac-constructor-params').serialize();
                        
                        jQuery('.fac-constructor-spinner').show();
                        jQuery.colorbox.resize();
                        jQuery.ajax({
                            url: ajax_fac.ajax_url,
                            type: 'POST' ,
                            data: data,
                            dataType: 'json',
                            cache: false,
                            success: function(data) {
                                if (data['shortcode']) {
                                    ed.execCommand('mceInsertContent', false, data['shortcode']);
                                }
                                jQuery('.fac-constructor-spinner').hide();
                                jQuery.colorbox.resize();
                                jQuery.colorbox.close();                                
                            },
                            error: function (request, status, error) {
                               jQuery('.fac-constructor-spinner').hide();
                            }
                        });                               
                    });
                    jQuery("#fac-constructor-box").click();
               }
            }); 
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
               longname : "FontAwesome Constructor",
               author : 'Alexey Golubnichenko',
               authorurl : 'https://github.com/AGolubnichenko',
               version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('agp_fac_icon', tinymce.plugins.facIcon);
})();