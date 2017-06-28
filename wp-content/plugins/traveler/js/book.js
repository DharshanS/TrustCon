/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function () {
    jQuery(".embasy_class").hide();
    jQuery(".btnpaynow").click(function () {



        makeReservationAjax(collectReservationDetails(), "btnpaynow");


    });

    jQuery(".btnembassy").on("click", function() {
      // alert('clcik');
        jQuery(".embasy_class").slideToggle("slow");

    });

    jQuery(".btncontinue").on("click", function() {

        pay_embassy_purpose();
    });


});
function makeReservationAjax(request, mode) {


    alert('makeReservation called' + request.email);
    jQuery.ajax({
        type: 'post',
        data: {action: 'paymentPage', reservationRequest: request, paymode: mode},
       url: '/travel/wp-admin/admin-ajax.php',
        success: function (h) {


            jQuery(location).attr('href', '../travel/Payment');

        }
    });
}

function pay_embassy_purpose() {

    alert('method called');
   
    jQuery.ajax({
        type: 'post',
        data: {action: 'paymentPage'},
        url: '/travel/wp-admin/admin-ajax.php',
        success: function (h) {


            jQuery(location).attr('href', '../travel/Payment');

        }
    });
}
function collectReservationDetails() {
    alert('collectReservationDetails');
    var k = 0;
    var first_name = [];
    var titile_array = [];
    var last_name = [];
    var dob_arry = [];
    var age_array = [];
    var sex_array = [];
    var countryCode="0094";
    var mobNumber="0770885997";

    var passport_exp_date = [];
    var passport_country = [];
    var birth_country = [];


    var k = 0;
    jQuery("select[name='traveler_titile[]']").each(function () {
        alert(jQuery.trim(jQuery(this).val()));
        if (jQuery.trim(jQuery(this).val()).length < 1) {
            jQuery("#traveler_titile_err" + k).text('Please select a titile !');
            return;

        }
        titile_array.push(jQuery.trim(jQuery(this).val()));
        k = k + 1;
    });
    var k = 0;

    jQuery("input[name='first_name[]']").each(function () {
        jQuery('#first_name_err' + k).text('');
        if (jQuery.trim(jQuery(this).val()).length < 2) {

            jQuery("#first_name_err" + k).text('Missing Minimum two chracters !');
            return;
        }

        last_name.push(jQuery.trim(jQuery(this).val()));

        k = k + 1;
    });


    var k = 0;
    jQuery("input[name='last_name[]']").each(function () {

        jQuery('#last_name_err' + k).text('');
        if (jQuery.trim(jQuery(this).val()).length < 2) {
            jQuery('#last_name_err' + k).text('Missing Minimum two chracters !');
            return;
        }
        first_name.push(jQuery.trim(jQuery(this).val()));
        k = k + 1;
    });


    var k = 0;
    jQuery("select[name='sex[]']").each(function () {
        sex_array.push(jQuery.trim(jQuery(this).val()));
        k = k + 1;
    });


    var k = 0;


    var k = 0;
    jQuery("input[name='dob[]']").each(function () {

        jQuery('#doberror' + k).text('');
        if (jQuery(this).val() === '') {
            jQuery('#doberror' + k).text('DOB Missing');

            return;
        }
        var passType = jQuery(this).attr('typePass');
        var dob = new Date(jQuery(this).val());
        var currentDate = new Date;
        var diffdate = currentDate.getTime() - dob.getTime();
        var years = 24 * 60 * 60 * 1000 * 360;
        var age = diffdate / years;


        if (passType === 'ADT') {
            if (age > 11) {
            } else {
                jQuery('#doberror' + k).text('adult age should be more than 11 years');
                return;
            }

        }
        else if (passType === 'CNN') {
            if (age <= 11 & age > 2) {
            } else {
                jQuery('#doberror' + k).text('child age should be between 2 to 11 years');
                return;
            }
        }
        else if (passType === 'INF') {
            if (age <= 2) {
            } else {
                jQuery('#doberror' + k).text('infant age should be bellow 2 years');
                return;
            }
        }
        age_array.push(age);
        dob_arry.push(jQuery(this).val());
        k = k + 1;
    });
    var bookRequest = {
        email: emailValidate(),

        f_name: first_name,
        l_name: last_name,
        titile: titile_array,
        dob: dob_arry,
        age: age_array,
        sex: sex_array,
        countryCode: countryCode,
        mobNumber: mobNumber,
        passExDate: passport_exp_date,
        passCountry: passport_country,
        birthCountry: birth_country
    };
    return bookRequest;
}
function isValidEmailAddress(emailAddress) {

    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

    return pattern.test(emailAddress);

};
function MobValidate() {
    var mobile = document.getElementById("mob").value;
    var pattern = /^\d{9,10}$/;
    if (pattern.test(mobile))
        return true;
    else
        return false;
}
function emailValidate() {
    jQuery('#emailerror').text('');

    var eml = jQuery('#email_address').val();

    var send_offers = jQuery('#send_offers').is(":checked");

    if (send_offers == true)send_offers = 1;

    else send_offers = 0;

    if (isValidEmailAddress(eml) == false || eml == '') {

        jQuery('#emailerror').text('Error, enter a valid email address! ( Email is must )!');
        jQuery('#email_address').focus();
        return;

    }
    return eml;
}
    