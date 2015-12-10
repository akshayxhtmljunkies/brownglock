(function ($) {
    $(function () {
        $('.asap-tab').click(function () {
            var attr_id = $(this).attr('id');
            var id = attr_id.replace('asap-tab-', '');
            $('.asap-tab').removeClass('asap-active-tab');
            $(this).addClass('asap-active-tab');
            $('.asap-section').hide();
            $('#asap-section-' + id).show();
        });



        $('.asap-bitly-check').click(function () {
            if ($(this).is(':checked')) {
                $('.asap-bitly-ref').show();
            }
            else
            {
                $('.asap-bitly-ref').hide();
            }
        });


    });//document.ready close
}(jQuery));