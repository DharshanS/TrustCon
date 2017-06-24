/**
 * Created by tharangi on 6/22/2017.
 */
/**
 * Created by tharangi on 6/22/2017.
 */
jQuery(document).ready(function(jQuery) {
    alert("its added ")
    var data = {
        'action': 'my_action',
        'whatever': "Hello Meara"      // We pass php values differently!
    };
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    jQuery.post(ajax_object.ajax_url, data, function(response) {
        alert('Got this from the server: ' + response);
    });
});