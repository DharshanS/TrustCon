

jQuery(document).ready(function () {
    
    var bookingArray;
    jQuery("input[name='selectFlightOut']").click(function () {
        
        var radioValueDec = jQuery("input[name='selectFlightOut']:checked").val();
        //bookingArray[0]=radioValueDec;
        var radioValue = JSON.parse(atob(radioValueDec));
        var elm = "." + jQuery(this).attr("mode");
         var index=elm.split("_");
         alert(index[1]);
        var flightDetails = '';
        jQuery.each(radioValue, function (index, element) {

            flightDetails = flightDetails + '<div class="row flt-dtl">' +
                    '<div class="col-sm-4 col-xs-6 arw">' +
                    "<p>" + element["@attributes"][1] + "<br>" +
                    element["@attributes"]['Origin'] + "<br>" +
                    element["@attributes"][7] + "<br>" +
                    element["@attributes"][3] + "<br>" +
                    element["@attributes"][2] + "</p>" +
                    ' </div>' +
                    '<div class="col-sm-4 col-xs-6">' +
                    "<p>" + element["@attributes"][4] + "<br>" +
                    element["@attributes"]['Destination'] + "<br>" +
                    element["@attributes"][7] + "<br>" +
                    element["@attributes"][5] + "<br>" +
                    element["@attributes"][6] + "</p>" +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<p><i class="fa fa-clock-o"></i> ' +
                    ' 2hrs 10mins</p>' +
                    ' <p></p>' +
                    '</div> </div>';

         //   console.log(element);

        });
        jQuery(elm).html(flightDetails);
         jQuery(".book_out"+index[1]).val(radioValueDec);
    });
    
     jQuery("input[name='selectFlightIn']").click(function () {

        var radioValueDec = jQuery("input[name='selectFlightIn']:checked").val();
               
        var radioValue = JSON.parse(atob(radioValueDec));
       
        var elm = "." + jQuery(this).attr("mode");
         var index=elm.split("_");
         alert(index[1]);
 //bookingArray[1]=radioValueDec;
        var inboundDet = '';
        jQuery.each(radioValue, function (index, element) {

            inboundDet = inboundDet + '<div class="row flt-dtl'+elm+'">' +
                    '<div class="col-sm-4 col-xs-6 arw">' +
                    "<p>" + element["@attributes"][1] + "<br>" +
                    element["@attributes"]['Origin'] + "<br>" +
                    element["@attributes"][7] + "<br>" +
                    element["@attributes"][3] + "<br>" +
                    element["@attributes"][2] + "</p>" +
                    ' </div>' +
                    '<div class="col-sm-4 col-xs-6">' +
                    "<p>" + element["@attributes"][4] + "<br>" +
                    element["@attributes"]['Destination'] + "<br>" +
                    element["@attributes"][7] + "<br>" +
                    element["@attributes"][5] + "<br>" +
                    element["@attributes"][6] + "</p>" +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<p><i class="fa fa-clock-o"></i> ' +
                    ' 2hrs 10mins</p>' +
                    ' <p></p>' +
                    '</div> </div>';

          

        });
          console.log(inboundDet);
        jQuery(elm).html(inboundDet);
        jQuery(".book_in"+index[1]).val(radioValueDec);
    });
    
    
     
    
jQuery("button[name='book']").click(function () {
    
    var key=".book"+jQuery(this).attr("key");
    var outBound = jQuery("input[name='selectFlightOut']:checked").val();
    var inBound = jQuery("input[name='selectFlightIn']:checked").val();
    
    var book={"outbound":outBound,"inbound":inBound};
    var dataset=btoa(book);
        alert(key);book
        
        jQuery(key).val("TEST");
});
});

