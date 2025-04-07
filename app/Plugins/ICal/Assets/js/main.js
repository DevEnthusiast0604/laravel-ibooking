(function($){
    'use strict';

    var iCalAction = {
        init: function(){
            this.initIcalField();
        },

        initIcalField: function(){
            $(document).on('click', '.gmz-field-ical button', function(){
                var parent = $(this).closest('.gmz-field-ical');
                $('input', parent).select();
                document.execCommand('copy');
                GmzOption.toast({
                    status: true,
                    message: $(this).data('text')
                });
            });
        },
    };
    iCalAction.init();
})(jQuery);