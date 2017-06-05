jQuery(document).ready(function(){

    // jQuery('.cls-booking-arvl').click(function(){
    //     alert('testing textbox click');
    // });
    // jQuery('.test123typeahead').typeahead({
    //
    //         hint: true,
    //         highlight: true,
    //         minLength: 3,
    //         limit: 8
    //     }, {
    //         source: function(q, cb) {
    //             return jQuery.ajax({
    //                 dataType: 'json',
    //                 type: 'get',
    //                 data: {action: 'getCity',q:q},
    //                 url: '/travel/wp-admin/admin-ajax.php?',
    //                 chache: false,
    //
    //                 success: function(data) {     //alert(data);
    //                     var result = [];
    //                     jQuery.each(data, function(index, val) {
    //                         result.push({
    //                             value: val
    //                         });
    //                     });
    //                     cb(result);
    //                 }
    //             });
    //         }
    //     });
    //
    jQuery('.cls-booking-arvl').typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
        limit: 8
    }, {

        source: function(q, cb) {
            return jQuery.ajax({
                dataType: 'json',
                type: 'get',
                data: {action: 'getCity',q:q},
                url: '/travel/wp-admin/admin-ajax.php?',
                chache: false,

                success: function(data) { //alert(data);
                    var result = [];
                    jQuery.each(data, function(index, val) {

                        result.push({
                            value: val
                        });
                    });
                    cb(result);
                }
            });
        }
    });


});

