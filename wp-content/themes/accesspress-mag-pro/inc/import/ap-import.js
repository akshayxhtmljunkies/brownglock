jQuery(function($){
    $('#ap_import').on('click',function(){
        $import_true = confirm('Are you sure to import dummy content ? It will overwrite the existing data.');
        if($import_true == false) return;
        var $this = $(this)
        $this.addClass('ap_loading');
        $('.import_message').html('<br />Data is being imported please be patient, while the awesomeness is being created. It may take few minutes. :)  ');
        var data = {
            'action': 'my_action'       
        };

        $.post(ajaxurl, data, function(response) {
            $('.import_message').html('<br />'+ response);
            $this.removeClass('ap_loading');
            if(response != "Import error! Please uninstall Wordpress importer plugin and try again")
            location.reload(); 
        });
    });
});