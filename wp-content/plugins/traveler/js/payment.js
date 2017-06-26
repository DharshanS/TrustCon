
jQuery(document).ready(function(jQuery) {

    jQuery("input[name='cardType']").click(function(){

        var bank=jQuery(this).attr('id');
        var amount;
  
        jQuery.ajax({
            type: 'post',
            action: 'bank_service',
            data: {action: 'bank_service',bank:bank},
            url: '/travel/wp-admin/admin-ajax.php',
            success: function (response, status) {
              amount=response;
            }, async: false
        });

        jQuery(".bank-service-charge-label").text(amount);

        var total=parseInt(jQuery(".bank-net-label").text())+parseInt(amount);
     
        jQuery(".bank-net-label").text(total);


    });



});


