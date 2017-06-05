<?php
$l=$_POST['l'];
$html='';
for($i=0;$i<$l;$i++){
    $html.='<div class="multi-option"><div class="col-sm-4 fl"><input type="text" name="from_city[]" placeholder="From" class="lt_typeahead cls-multi-booking-dtn"/></div><div class="col-sm-4 fl"><input type="text" name="to_city[]" placeholder="To" class="lt_typeahead cls-multi-booking-arvl"/></div><div class="col-sm-4 fl"><div class="date_picker"><input type="text" class="date-pick"  data-date-format="M d, D" data-date-start-date="0d" name="depart_date[]" placeholder="Departing" /></div><span class="cls" onclick="rmv(this);">X</span></div></div>';
}
?>
    <script>
        jQuery('input.date-pick, .input-daterange, .date-pick-inline').datepicker({
            autoclose: true,
            todayHighlight: true
        });
        jQuery('.lt_typeahead').typeahead(
            {
                hint: true,
                highlight: true,
                minLength: 3,
                limit: 8
            },
            {
                source: function(q, cb) {
                    return jQuery.ajax({
                        dataType: 'json',
                        type: 'get',
                        url: 'http://www.clickmybooking.com/getcity_airport.php?q=' + q ,
                        //url: 'http://localhost/clickmybooking/getcity_airport.php?q=' + q ,
                        cache: false,
                        success: function(data) {
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
    </script>
<?php
echo $html;
?>