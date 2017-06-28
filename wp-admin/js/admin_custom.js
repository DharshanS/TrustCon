/**
 * Created by tharangi on 6/22/2017.
 */
/**
 * Created by tharangi on 6/22/2017.
 */
jQuery(document).ready(function(jQuery) {

        var data = {
            'action': 'my_action',
            'whatever': 1234
        };

        var html_res;
        jQuery.ajax({
            type: 'post',
            data: data,
            url: ajaxurl,
            success: function (response, status) {
                var result = JSON.parse(atob(response));
console.log(result);
                jQuery.each(result, function (index, element) {

                    html_res=html_res+'<tr>'+
                        '<td>'+element.ID+'</td>'+
                        '<td>'+element.bank_Name+'</td>'+
                        '<td>'+element.path+'</td>'+
                        '<td class="amount_td"><input type="text" value="'+element.charge+'" class="bank_charge_txt"/></td>'+
                    '<td><button type="button" amount="'+element.charge+'" bank="'+element.bank_Name+'"id="'+element.ID+'" class="btn btn-primary bank_button">update</button></td></tr>'
                        ;

                });

            }, async: false
        });



    jQuery('.charge_body').html(html_res);






    jQuery(document).on('click', '.bank_button', function(){
        var amount=jQuery(this).parent().siblings('.amount_td').children('.bank_charge_txt').val();
        alert(amount);
      var bank=jQuery(this).attr('bank');
        var data = {
            'action': 'admin_custom_service',
            'bank': bank,
            'amount': amount
        };
        jQuery.ajax({
            type: 'post',
            data: data,
            url: ajaxurl,
            success: function (response, status) {

             alert(response);

            }, async: false
        });

    });

});