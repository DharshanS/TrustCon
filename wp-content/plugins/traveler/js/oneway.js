
jQuery(document).ready(function()
{


    jQuery('.ow-shw').click(function(){
       
        jQuery(this).parent().parent().parent().siblings(".edit-visl").slideToggle('slow');
        //jQuery(this).parent().parent().siblings(".well-sm").children(".edit-visl").slideToggle('slow');
    });

    jQuery("input[name='one-way-select']").click(function(){


        var radioValue = JSON.parse(atob(jQuery(this).val()));
        var key=jQuery(this).attr("key");

alert(radioValue);
        set_flight_dts_oneway(radioValue,key,"oneway");
        //jQuery(bookKey).html('Hellow World ....');


    });

});


function set_flight_dts_oneway(radioValue,key,mode)
{

    var jsRs;
    var fly_dts = "";
    var htmlEla = "." + mode + "_" + key;
    //alert('key'+htmlEla);

    jQuery.each(radioValue, function (index, element) {

       // console.log("........."+element);
        var air = element["@attributes"];

        var b_dts=element["0"]["@attributes"];


        jQuery.ajax({
            type: 'get',
            action: 'ajaxUtility',
            data: {action: 'ajaxUtility'},
            url: '/travel/wp-admin/admin-ajax.php?ori=' + air.Origin + '&des=' + air.Destination + '&air=' + air.Carrier,
                success: function (response, status) {
                    alert(response);
                    console.log(jsRs);
                jsRs = JSON.parse(response.split("0")[0]);
                    console.log(jsRs);
                }, async: false
        });


        var now = moment(air.DepartureTime).format("DD MMMM YYYY HH:mm");
        var then = moment(air.ArrivalTime).format('DD MMMM YYYY HH:mm');
        var dif = moment.duration(moment(then).diff(moment(now)));




        var depatureTime = moment(air.DepartureTime).format('hh:mm a');
        var depatureDate = moment(air.DepartureTime).format('ddd DD MMMM YYYY ');
        var arrivalTime = moment(air.ArrivalTime).format('hh:mm a');
        var arrivalDate = moment(air.ArrivalTime).format('ddd DD MMMM YYYY ');



        var time = dif.toString().split("PT")[1];
        var hors = time.split("H")[0];
        var min = time.split("H")[1].split("M")[0].split(",");




        fly_dts = fly_dts + ' <div class="row flt-dtl '+key+'">' +
            '<div class="col-sm-4 col-xs-6 col-lg-4">' +
            '<p>' +
            jsRs.ori + '(' + air.Origin + ')' +
            '<br>' +
            depatureTime +
            '<br>' +
            depatureDate +
            '</p>' +
            '</div>' +
            '<div class="col-sm-4 col-xs-6 col-lg-4">' +
            '<p>' +
            jsRs.des + '(' + air.Destination + ')' +
            '<br>' +
            arrivalTime +
            '<br>' +
            arrivalDate +
            '</p>' +
            '</div>' +
            '<div class="col-sm-4  col-lg-4">' +
            '<p><i class="fa fa-clock-o"></i> ' +
            hors + "hrs " + min + "mins" +
            '</div>' +
            '</div>' +
            '<div class="row vgn">' +
            '<p>' +
            '<img src="../airimages/' + air.Carrier + '.GIF">' +
            air.Carrier +" "+ air.Equipment + '-'+
            '<a>'+ b_dts.CabinClass + b_dts.BookingCode +'</a>-'+air.FlightNumber+
            '</p>' +

            '</div>';

    });


    fly_dts=fly_dts+  '<div class="btm">'+
        '<i class="fa fa-clock-o"></i> '+
        '<strong>TOTAL DURATION</strong> 4hrs 25mins                                        </div>';
    jQuery(htmlEla).html(fly_dts);



}
