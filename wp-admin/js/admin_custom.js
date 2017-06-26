/**
 * Created by tharangi on 6/22/2017.
 */
/**
 * Created by tharangi on 6/22/2017.
 */
jQuery(document).ready(function(jQuery) {
    alert("its added ")
    var data = {
        'action': 'admin_service_charge_setup',
        'whatever': "Hello Meara"      // We pass php values differently!
    };
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations


    jQuery('.add-charge').click(function() {
        var data = {
            'action': 'my_action',
            'whatever': 1234
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        // jQuery.post(ajaxurl, data, function(response) {
        //    alert(response);
        // });
        var html_res;
        jQuery.ajax({
            type: 'post',
            data: data,
            url: ajaxurl,
            success: function (response, status) {
                var result = JSON.parse(atob(response));

                jQuery.each(result, function (index, element) {
                    html_res=html_res+'<tr>'+
                        // '<td>'+element.ID+'</td>'+
                        // '<td>'+element.bank+'</td>'+
                        // '<td>'+element.image+'</td>'+
                        // '<td>'+element.path+'</td>'+
                        '<td>  <button type="button" class="btn btn-primary test">Edit-loop</button></td>'

                        '</tr>';
                });

            }, async: false
        });



            jQuery('.charge_body').html(html_res);


    });


    jQuery(".edit-charge").click(function(){
        alert('Its awsome.....');
    });

    jQuery(".test").click(function(){
        alert('Its awsome.....');
    });



});