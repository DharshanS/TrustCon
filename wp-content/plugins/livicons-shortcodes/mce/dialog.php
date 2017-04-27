<!DOCTYPE html>
<html>
<?php
$path = dirname( __FILE__ );
$path = substr( $path , 0 , strpos( $path , "wp-content" ) );
require_once( $path . '/wp-blog-header.php' );
$pl_url = plugins_url();
$inc_url = includes_url();
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
  <meta charset="utf-8">
  
  <title>Add LivIcon</title>
  
  <link href="<?php echo $pl_url . '/livicons-shortcodes/css/jquery.minicolors.css'?>" rel="stylesheet">
  <link href="<?php echo $pl_url . '/livicons-shortcodes/css/bootstrap.min.css'?>" rel="stylesheet">
  <link href="<?php echo $pl_url . '/livicons-shortcodes/mce/dialog.css'?>" rel="stylesheet">
  <link href="<?php echo $pl_url . '/livicons-shortcodes/result/customlivicons.css'?>" rel="stylesheet">

  <script src="<?php echo $inc_url . 'js/json2.js'?>"></script>
  <script src="<?php echo $inc_url . 'js/jquery/jquery.js'?>"></script>
  <script src="<?php echo $inc_url . 'js/tinymce/tiny_mce_popup.js'?>"></script>
  <script src="<?php echo $pl_url . '/livicons-shortcodes/js/bootstrap.min.js'?>"></script>
  <script src="<?php echo $pl_url . '/livicons-shortcodes/js/raphael-min.js'?>"></script>
  <script src="<?php echo $pl_url . '/livicons-shortcodes/result/customlivicons.js'?>"></script>
  <script src="<?php echo $pl_url . '/livicons-shortcodes/js/jquery.inputCtl.min.js'?>"></script>
  <script src="<?php echo $pl_url . '/livicons-shortcodes/js/jquery.minicolors.js'?>"></script>
</head>
   
<body>
<?php
$iconlist_options = get_option('lisc_iconslist');
$choosenLivicons = explode(',', $iconlist_options['chosen_livicons']);
$options = get_option('lisc_options');
$globalDefaults = array(
      'htmltag' => $options['defhtmltag'],
      'size' => $options['defsize'],
      'animated' => $options['defanimated'],
      'loop' => $options['deflooped'],
      'eventtype' => $options['defeventtype'],
      'onparent' => $options['defonparent'],
      'activeclass' => $options['activeclass']
    );
    if ($options['deforiginalcolor'] == 'true') {
      $globalDefaults['color'] = 'original';
      $originalColor = 'original';
    } else {
      $globalDefaults['color'] = $options['defcolor'];
      $originalColor = 'notoriginal';
    };
    if ($options['defchangecoloronhover'] == 'true') {
      $globalDefaults['hovercolor'] = $options['defhovercolor'];
      $hoverChange = 'true';
    } else {
      $globalDefaults['hovercolor'] = 'false';
      $hoverChange = 'false';
    };

?>
<form id="lisc_livicons" method="" action="">
  <div id="lisc_container" class="container">
    
    <div class="row"> <!-- Rendered choosen icons -->
      <div class="span12">
        <p><small><em>If you see 'wrong' icons in this list after changing the global settings please reload this frame and/or clear your browser cache.</em></small></p>
        <div id="livicons_list">
          <?php
          foreach ($choosenLivicons as $value) {
            echo ('<div class="icon" title="'.$value.'"><input type="radio" name="choosen_icon" value="' .$value. '" id="livicon_' .$value. '"><label for="livicon_' .$value. '"><div class="livicon" data-n="' .$value. '"></div></label></div>');
          }?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="span12">
        <div class="btn-group" data-toggle="buttons-radio">
          <label class="btn">
            <input type="radio" name="defaults" value="global" checked="checked"> Use global defaults
          </label>
          <label class="btn">
            <input type="radio" name="defaults" value="custom"> Customize an icon
          </label>
        </div>
      </div>
    </div>
    
    <hr>
    
    <div id="custom_options">
      <div class="row">
        <div class="span3">
          <p>HTML tag for a container:</p>
        </div>
        <div class="span3">
          <div class="btn-group" data-toggle="buttons-radio">
            <label class="btn">
              <input type="radio" name="htmltag" value="span" <?php checked('span', $globalDefaults['htmltag']); ?>> &lt;span>
            </label>
            <label class="btn">
              <input type="radio" name="htmltag" value="div" <?php checked('div', $globalDefaults['htmltag']); ?>> &lt;div> 
            </label>
            <label class="btn">
              <input type="radio" name="htmltag" value="i" <?php checked('i', $globalDefaults['htmltag']); ?>> &lt;i>
            </label>
          </div>
        </div>
        <div class="span3">
          <p>Icon's size (pixels):</p> 
        </div>
        <div class="span3">
          <div class="input-append" id="iconsizewrap">
            <input class="input-mini" type="text" maxlength="5" id="iconsize" name="iconsize" value="<?php echo $globalDefaults['size']; ?>">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="span3">
          <p>Icon's color:</p> 
        </div>
        <div class="span3">
          <div class="btn-group" data-toggle="buttons-radio">
            <label class="btn" style="padding:3px 12px;z-index:5;">
              <input type="radio" name="iconcolor" value="notoriginal" <?php checked('notoriginal', $originalColor); ?>><input class="input-mini minicolors" type="text" maxlength="7" id="iconcolorvalue" name="iconcolorvalue" value="<?php echo $globalDefaults['color']; ?>">
            </label>
            <label class="btn">
              <input type="radio" name="iconcolor" value="original" <?php checked('original', $originalColor); ?>> Original
            </label>
          </div>
        </div>
        <div class="span3">
          <p>Change color on hover:</p> 
        </div>
        <div class="span3">
          <div class="btn-group" data-toggle="buttons-radio">
            <label class="btn" style="padding:3px 12px;z-index:5;">
              <input type="radio" name="hoverchange" value="true" <?php checked('true', $hoverChange); ?>><input class="input-mini minicolors" type="text" maxlength="7" id="hovercolor" name="hovercolor" value="<?php if ($globalDefaults['hovercolor']=='false'){echo '';} else {echo $globalDefaults['hovercolor'];};?>">
            </label>
            <label class="btn">
              <input type="radio" name="hoverchange" value="false" <?php checked('false', $hoverChange); ?>> No
            </label>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="span3">
          <p>Icon is animated:</p> 
        </div>
        <div class="span3">
          <div class="btn-group" data-toggle="buttons-radio">
            <label class="btn">
              <input type="radio" name="animated" value="true" <?php checked('true', $globalDefaults['animated']); ?>> Yes
            </label>
            <label class="btn">
              <input type="radio" name="animated" value="false" <?php checked('false', $globalDefaults['animated']); ?>> No
            </label>
          </div>
        </div>
        <div class="span3">
          <p>Animation is looped <strong>(be carefull!)</strong>:</p> 
        </div>
        <div class="span3" id="loopwrap">
          <div class="btn-group" data-toggle="buttons-radio">
            <label class="btn">
              <input type="radio" name="looped" value="true" <?php checked('true', $globalDefaults['loop']); ?>> Yes
            </label>
            <label class="btn">
              <input type="radio" name="looped" value="false" <?php checked('false', $globalDefaults['loop']); ?>> No
            </label>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="span3">
          <p>Event's type:</p> 
        </div>
        <div class="span3">
          <div class="btn-group" data-toggle="buttons-radio">
            <label class="btn">
              <input type="radio" name="eventtype" value="hover" <?php checked('hover', $globalDefaults['eventtype']); ?>> Hover
            </label>
            <label class="btn">
              <input type="radio" name="eventtype" value="click" <?php checked('click', $globalDefaults['eventtype']); ?>> Click
            </label>
          </div>
        </div>
        <div class="span3">
          <p>Trigger events 'on parent' element:</p> 
        </div>
        <div class="span3">
          <div class="btn-group" data-toggle="buttons-radio">
            <label class="btn">
              <input type="radio" name="onparent" value="true" <?php checked('true', $globalDefaults['onparent']); ?>>Yes
            </label>
            <label class="btn">
              <input type="radio" name="onparent" value="false" <?php checked('false', $globalDefaults['onparent']); ?>>No
            </label>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="span3">
          <p>Animation's duration (ms):</p> 
        </div>
        <div class="span3">
          <div class="input-append" id="durationwrap">
            <input class="input-mini" type="text" maxlength="5" id="duration" name="duration" value="None Icon">
          </div>
        </div>
        <div class="span3">
          <p>Animation's iterations (times):</p> 
        </div>
        <div class="span3">
          <div class="input-append" id="iterationwrap">
            <input class="input-mini" type="text" maxlength="3" id="iteration" name="iteration" value="None Icon">
          </div>
        </div>
      </div>
      <hr>
    </div>
    
    <div class="row" id="optional_params">
      <div class="span3">
        <p><strong>Optional parameters:</strong></p> 
      </div>
    </div>

    <div class="row">
      <div class="span3">
        <p>Add LivIcon's ID:</p> 
      </div>
      <div class="span3">
        <input class="span3" type="text" id="iconid" name="iconid">
      </div>
      <div class="span3">
        <p>Inline styles for this LivIcon:</p> 
      </div>
      <div class="span3">
        <div>
          <input class="span3" type="text" id="addstyles" name="addstyles" placeholder="For ex. top:16px; left:16px;">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="span3">
        <p>Adjust the LivIcon's height:</p> 
      </div>
      <div class="span3">
        <div class="input-append">
          <input class="input-mini" type="text" maxlength="4" id="iconheight" name="iconheight" value="<?php echo $globalDefaults['size']; ?>">
        </div>
      </div>
    
      <div class="span3">
        <p>Background color (suitable for <em>metro-bg, circle-bg, rounded-bg</em> classes):</p>
      </div>
      <div class="span3">
        <div class="btn-group" data-toggle="buttons-radio">
            <label class="btn" style="padding:3px 12px;z-index:5;">
              <input type="radio" name="bgcolor" value="true"><input class="input-mini minicolors" type="text" maxlength="7" id="bgcolorvalue" name="bgcolorvalue" value="#000000">
            </label>
            <label class="btn">
              <input type="radio" name="bgcolor" value="false" checked> None
            </label>
          </div>
      </div>

    </div>
  
    <div class="row">
      <div class="span3">
        <p>Wrap with a link:</p> 
      </div>
      <div class="span3">
        <input class="span3" type="text" id="addlink" name="addlink" placeholder="http://...">
        <label class="checkbox inline" id="linktargetwrap">
          <input type="checkbox" id="linktarget" name="linktarget"> Open link in a new window / tab
        </label>
      </div>
      <div class="span3">
        <p>Additional class(es) for this LivIcon:</p>
      </div>
      <div class="span3">
        <input class="span3" type="text" id="addclass" name="addclass">
        <label class="checkbox inline" id="activeclasswrap">
          <input type="checkbox" id="activeclass" name="activeclass"> Add 'active' class for this LivIcon
        </label>
      </div>
    </div>
  
    <hr>
  
    <div class="row">
      <div class="span1">
        <input class="btn" type="button" id="preview" name="preview" value="Preview">
      </div>
      <div class="span2">
        <div id="previewbox"></div>
      </div>
      <div class="span1">
        <input class="btn" type="button" id="gethtml" name="gethtml" value="HTML">
      </div>
      <div class="span5">
        <textarea class="span5" rows="6" id="htmlresult" ></textarea>
      </div>
      <div class="span2">
        <input class="btn btn-primary" type="button" id="insert" name="insert" value="Insert shortcode">
      </div>
      <div class="span1">
        <input class="btn" type="button" id="cancel" name="cancel" value="Cancel">
      </div>
    </div>
  </div>
  
</form>  

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($){
  $('#custom_options').hide();
  $('.btn-group label.btn input[type=radio]').hide();

  //define defaults values
  var defhtmltag = <?php echo json_encode($globalDefaults['htmltag']); ?>,
      defsize = <?php echo json_encode($globalDefaults['size']); ?>,
      defcolor = <?php echo json_encode($globalDefaults['color']); ?>,
      defhovercolor = <?php echo json_encode($globalDefaults['hovercolor']); ?>,
      defanimated = <?php echo json_encode($globalDefaults['animated']); ?>,
      deflooped = <?php echo json_encode($globalDefaults['loop']); ?>,
      defeventtype = <?php echo json_encode($globalDefaults['eventtype']); ?>,
      defonparent = <?php echo json_encode($globalDefaults['onparent']); ?>,
      defactiveclass = <?php echo json_encode($globalDefaults['activeclass']); ?>,
      cur_icon_duration,
      cur_icon_iteration;

  //retriving duration and iterations for choosen icon
  $('.livicon').click(function(){
    cur_icon_duration = $(this).dataofLivicon('d');
    cur_icon_iteration = $(this).dataofLivicon('i');
    $('#duration').val(cur_icon_duration);
    $('#iteration').val(cur_icon_iteration)
  });

  //show/hide custom options
  $('input:radio[name=defaults]').change(function(){
    if ($(this).is(':checked') && $(this).val()==='custom') {
      $('#custom_options').slideDown();
    } else if ($(this).is(':checked') && $(this).val()==='global') {
      $('#custom_options').slideUp();
    };
  });

  //disable some parameters for morph icons
  $('input:radio[name=choosen_icon]').change(function(){
    var name = $('input:checked + label > .livicon').data('n');
    if (name.match(/morph/)) {
      $('input:radio[name=looped]').prop('disabled',true);
      $('input[name=duration]').prop('disabled',true);
      $('input[name=iteration]').prop('disabled',true);
      $('#loopwrap label.btn').addClass('disabled');
      $('#durationwrap .btn').prop('disabled',true);
      $('#iterationwrap .btn').prop('disabled',true);
    } else {
      $('input:radio[name=looped]').prop('disabled',false);
      $('input[name=duration]').prop('disabled',false);
      $('input[name=iteration]').prop('disabled',false);
      $('#loopwrap label.btn').removeClass('disabled');
      $('#durationwrap .btn').prop('disabled',false);
      $('#iterationwrap .btn').prop('disabled',false);
    };
  });

//Proceed insert shortcode to TinyMCE
  $('#insert').click(function(){
    
    if (!$('input:radio[name=choosen_icon]').is(":checked")) {
      alert('Please choose a LivIcon first!');
    } else {
      var shortcode = '';
      //if global settings
      if ($('input:radio[name=defaults]:checked').val()==='global') {
        $('input:checked + label > .livicon').each(function(){
          var icon_name = 'name="' + $(this).data('n') + '"',
              icon_htmltag = ' htmltag="' + defhtmltag +'"',
              icon_size = ' size="' + defsize +'"',
              icon_duration = ' duration="' + cur_icon_duration +'"',
              icon_iteration = ' iteration="' + cur_icon_iteration +'"',
              icon_color = ' color="' + defcolor +'"',
              icon_hovercolor = ' hovercolor="' + defhovercolor +'"',
              icon_animate = ' animate="' + defanimated +'"',
              icon_looped = ' loop="' + deflooped +'"',
              icon_eventtype = ' eventtype="' + defeventtype +'"',
              icon_onparent = ' onparent="' + defonparent +'"';
          
          if ($('#iconid').val()) {
            var icon_id = ' id="' + $('#iconid').val() +'"';
          } else {
            var icon_id = '';
          };

          if ($('input[name=activeclass]').is(':checked')) {
            var icon_active = ' addactiveclass="' + defactiveclass + '"';
          } else {
            var icon_active = '';
          };

          if ($('#iconheight').val() == $('#iconsize').val()) {
            var icon_height = '';
          } else if ($('#iconheight').val()) {
            var icon_height = 'height:' + $('#iconheight').val() +'px;';
          } else {
            var icon_height = '';
          };
          
          if ($('#addstyles').val()) {
            var icon_addstyles = $('#addstyles').val();
          } else {
            var icon_addstyles = '';
          };
          if ($('input:radio[name=bgcolor]:checked').val()=='false') {
              var icon_bgcolor = '';
            } else {
              var icon_bgcolor = 'background:' + $('#bgcolorvalue').val() +';';
            };
          if (icon_height === '' && icon_addstyles === '' && icon_bgcolor === '') {
            var icon_styles = '';
          } else {
            var icon_styles = ' styles="' + icon_height + icon_bgcolor + icon_addstyles + '"';
          };

          if ($('#addclass').val()) {
            var icon_addclass = ' addclass="' + $('#addclass').val() +'"';
          } else {
            var icon_addclass = '';
          };

          if ($('#addlink').val()) {
            var icon_addlink = ' link="' + $('#addlink').val() +'"';
          } else {
            var icon_addlink = '';
          };

          if ($('#addlink').val()) {
            if ($('input[name=linktarget]').is(':checked')) {
              var icon_linktarget = ' target="_blank"';
            } else {
              var icon_linktarget = '';
            };
          } else {
            var icon_linktarget = '';
          };

          if (icon_name.match(/morph/)) {
            shortcode = '[liviconmorph ';
            var icon_options = icon_name + icon_htmltag + icon_id + icon_active + icon_size + icon_color + icon_hovercolor + icon_animate + icon_eventtype + icon_onparent + icon_styles + icon_addclass + icon_addlink + icon_linktarget;
            shortcode += icon_options;
            shortcode += '] [/liviconmorph]';
          } else {
            shortcode = '[livicon ';
            var icon_options = icon_name + icon_htmltag + icon_id + icon_active + icon_size + icon_color + icon_hovercolor + icon_animate + icon_looped + icon_eventtype + icon_onparent + icon_duration + icon_iteration + icon_styles + icon_addclass + icon_addlink + icon_linktarget;
            shortcode += icon_options;
            shortcode += ']';
          };
        });//end input checked

      } else { //if custom settings

        $('input:checked + label > .livicon').each(function(){
          
          var icon_name = 'name="' + $(this).data('n') + '"',
              icon_htmltag = ' htmltag="' + $('input:radio[name=htmltag]:checked').val() +'"',
              icon_size = ' size="' + $('#iconsize').val() +'"',
              icon_duration = ' duration="' + $('#duration').val() +'"',
              icon_iteration = ' iteration="' + $('#iteration').val() +'"';

          if ($('#iconid').val()) {
            var icon_id = ' id="' + $('#iconid').val() +'"';
          } else {
            var icon_id = '';
          };

          if ($('input[name=activeclass]').is(':checked')) {
            var icon_active = ' addactiveclass="' + defactiveclass + '"';
          } else {
            var icon_active = '';
          };

          if ($('#iconheight').val() == $('#iconsize').val()) {
            var icon_height = '';
          } else if ($('#iconheight').val()) {
            var icon_height = 'height:' + $('#iconheight').val() +'px;';
          } else {
            var icon_height = '';
          };

          if ($('#addstyles').val()) {
            var icon_addstyles = $('#addstyles').val();
          } else {
            var icon_addstyles = '';
          };
          if ($('input:radio[name=bgcolor]:checked').val()=='false') {
              var icon_bgcolor = '';
            } else {
              var icon_bgcolor = 'background:' + $('#bgcolorvalue').val() +';';
            };
          if (icon_height === '' && icon_addstyles === '' && icon_bgcolor === '') {
            var icon_styles = '';
          } else {
            var icon_styles = ' styles="' + icon_height + icon_bgcolor + icon_addstyles + '"';
          };

          if ($('#addclass').val()) {
            var icon_addclass = ' addclass="' + $('#addclass').val() +'"';
          } else {
            var icon_addclass = '';
          };

          if ($('#addlink').val()) {
            var icon_addlink = ' link="' + $('#addlink').val() +'"';
          } else {
            var icon_addlink = '';
          };

          if ($('#addlink').val()) {
            if ($('input[name=linktarget]').is(':checked')) {
              var icon_linktarget = ' target="_blank"';
            } else {
              var icon_linktarget = '';
            };
          } else {
            var icon_linktarget = '';
          };

          if ($('input:radio[name=iconcolor]:checked').val()=='original') {
            var icon_color = ' color="original"';
          } else {
            var icon_color = ' color="' + $('#iconcolorvalue').val() +'"';
          };
        
          if ($('input:radio[name=hoverchange]:checked').val()=='false') {
            var icon_hovercolor = ' hovercolor="false"';
          } else {
            var icon_hovercolor = ' hovercolor="' + $('#hovercolor').val() +'"';
          };
          
          if ($('input:radio[name=animated]:checked').val()=='true') {
            var icon_animate = ' animate="true"';
          } else {
            var icon_animate = ' animate="false"';
          };
          
          if ($('input:radio[name=looped]:checked').val()=='true') {
            var icon_looped = ' loop="true"';
          } else {
            var icon_looped = ' loop="false"';
          };

          if ($('input:radio[name=eventtype]:checked').val()=='click') {
            var icon_eventtype = ' eventtype="click"';
          } else {
            var icon_eventtype = ' eventtype="hover"';
          };

          if ($('input:radio[name=onparent]:checked').val()=='true') {
            var icon_onparent = ' onparent="true"';
          } else {
            var icon_onparent = ' onparent="false"';
          };

          if (icon_name.match(/morph/)) {
            shortcode = '[liviconmorph ';
            var icon_options = icon_name + icon_htmltag + icon_id + icon_active + icon_size + icon_color + icon_hovercolor + icon_animate + icon_eventtype + icon_onparent + icon_styles + icon_addclass + icon_addlink + icon_linktarget;
            shortcode += icon_options;
            shortcode += '] [/liviconmorph]';
          } else {
            shortcode = '[livicon ';
            var icon_options = icon_name + icon_htmltag + icon_id + icon_active + icon_size + icon_color + icon_hovercolor + icon_animate + icon_looped + icon_eventtype + icon_onparent + icon_duration + icon_iteration + icon_styles + icon_addclass + icon_addlink + icon_linktarget;
            shortcode += icon_options;
            shortcode += ']';
          };
        });//end input checked
      };
      
      tinyMCEPopup.editor.execCommand('mceInsertContent', false,  shortcode);
      tinyMCEPopup.close();
    };//end error check
  });// end insert

  //getting HTML code of LivIcon
  $('#gethtml').click(function(){
    if (!$('input:radio[name=choosen_icon]').is(":checked")) {
      alert('Please choose a LivIcon first!');
    } else {
      var htmlcoderesult = getHtmlCode();
      $('#htmlresult').text(htmlcoderesult);
    };//end error check
  });// end gethtml

  //Cancel shortcode
  $("#cancel").click(function(){
    tinyMCEPopup.close();
  });//end cancel

  //Changing preview
  $('#preview').click(function(){
    if (!$('input:radio[name=choosen_icon]').is(":checked")) {
      alert('Please choose a LivIcon first!');
    } else {
      var htmlcoderesult = getHtmlCode();
      $('#previewbox').empty().append(htmlcoderesult);
      $('#previewbox .livicon').addLivicon();
    };//end error check
  });// end preview

  //Block of controls
  $('#iconsize').inputCtl({minVal: 1, step: 1});
  $('#iconheight').inputCtl({minVal: 1, step: 1});
  $('#duration').inputCtl({minVal: 100, step: 10});
  $('#iteration').inputCtl({minVal: 1, step: 1});
  $('#iconcolorvalue').minicolors({swatchPosition:'left'});
  $('#hovercolor').minicolors({swatchPosition:'left'});
  $('#bgcolorvalue').minicolors({swatchPosition:'left'});
  $('.btn-group label.btn input[type=radio]').hide().filter(':checked').parent('.btn').addClass('active');
  $('#iconsize').change(function(){
    var newheight = $(this).val();
    $('#iconheight').val(newheight);
  });
  $('#iconsizewrap .button').click(function(){
    var newheight = $('#iconsize').val();
    $('#iconheight').val(newheight);
  });

  //function for getting HTML result. Used in 'Preview' and 'HTML'
  function getHtmlCode(){
    var htmlcode;
    //if global settings
    if ($('input:radio[name=defaults]:checked').val()==='global') {
      $('input:checked + label > .livicon').each(function(){
        var icon_name = ' data-name="' + $(this).data('n') + '"',
            icon_htmltag = defhtmltag,
            icon_size = ' data-size="' + defsize +'"',
            icon_duration = ' data-duration="' + cur_icon_duration +'"',
            icon_iteration = ' data-iteration="' + cur_icon_iteration +'"',
            icon_color = ' data-color="' + defcolor +'"',
            icon_hovercolor = ' data-hovercolor="' + defhovercolor +'"',
            icon_animate = ' data-animate="' + defanimated +'"',
            icon_looped = ' data-loop="' + deflooped +'"',
            icon_eventtype = ' data-eventtype="' + defeventtype +'"',
            icon_onparent = ' data-onparent="' + defonparent +'"';
        
        if ($('#iconid').val()) {
          var icon_id = ' id="' + $('#iconid').val() +'"';
        } else {
          var icon_id = '';
        };

        if ($('input[name=activeclass]').is(':checked')) {
          var icon_active = ' ' + defactiveclass;
        } else {
          var icon_active = '';
        };

        if ($('#iconheight').val() == $('#iconsize').val()) {
            var icon_height = '';
          } else if ($('#iconheight').val()) {
            var icon_height = 'height:' + $('#iconheight').val() +'px;';
          } else {
            var icon_height = '';
          };
        
        if ($('#addstyles').val()) {
          var icon_addstyles = $('#addstyles').val();
        } else {
          var icon_addstyles = '';
        };
        if ($('input:radio[name=bgcolor]:checked').val()=='false') {
            var icon_bgcolor = '';
          } else {
            var icon_bgcolor = 'background:' + $('#bgcolorvalue').val() +';';
          };
        if (icon_height === '' && icon_addstyles === '' && icon_bgcolor === '') {
          var icon_styles = '';
        } else {
          var icon_styles = ' style="' + icon_height + icon_bgcolor + icon_addstyles + '"';
        };

        if ($('#addclass').val()) {
          var icon_addclass = ' ' + $('#addclass').val();
        } else {
          var icon_addclass = '';
        };

        if ($('#addlink').val()) {
          var icon_addlink = $('#addlink').val() + '"';
        } else {
          var icon_addlink = '';
        };

        if ($('#addlink').val()) {
          if ($('input[name=linktarget]').is(':checked')) {
            var icon_linktarget = ' target="_blank"';
          } else {
            var icon_linktarget = '';
          };
        } else {
          var icon_linktarget = '';
        };

        if ($('#addlink').val()) {
          if (icon_name.match(/morph/)) {
            htmlcode = '<a href="' + icon_addlink + icon_linktarget + '><' + icon_htmltag + icon_id + ' class="livicon' + icon_addclass + icon_active + '"' + icon_name + icon_size + icon_color + icon_hovercolor + icon_animate + icon_eventtype + icon_onparent + icon_styles + '></' + icon_htmltag +'></a>';
          } else {
            htmlcode = '<a href="' + icon_addlink + icon_linktarget + '><' + icon_htmltag + icon_id + ' class="livicon' + icon_addclass + icon_active + '"' + icon_name + icon_size + icon_color + icon_hovercolor + icon_animate + icon_looped + icon_eventtype + icon_onparent + icon_duration + icon_iteration + icon_styles + '></' + icon_htmltag +'></a>';
          };
        } else {
          if (icon_name.match(/morph/)) {
            htmlcode = '<' + icon_htmltag + icon_id + ' class="livicon' + icon_addclass + icon_active + '"' + icon_name + icon_size + icon_color + icon_hovercolor + icon_animate + icon_eventtype + icon_onparent + icon_styles + '></' + icon_htmltag +'>';
          
          } else {
            htmlcode = '<' + icon_htmltag + icon_id + ' class="livicon' + icon_addclass + icon_active + '"' + icon_name + icon_size + icon_color + icon_hovercolor + icon_animate + icon_looped + icon_eventtype + icon_onparent + icon_duration + icon_iteration + icon_styles + '></' + icon_htmltag +'>';
          };
        };
        
      });//end each
    } else { //if custom settings
      $('input:checked + label > .livicon').each(function(){
        var icon_name = ' data-name="' + $(this).data('n') + '"',
            icon_htmltag = $('input:radio[name=htmltag]:checked').val(),
            icon_size = ' data-size="' + $('#iconsize').val() +'"',
            icon_duration = ' data-duration="' + $('#duration').val() +'"',
            icon_iteration = ' data-iteration="' + $('#iteration').val() +'"';

        if ($('#iconid').val()) {
          var icon_id = ' id="' + $('#iconid').val() +'"';
        } else {
          var icon_id = '';
        };

        if ($('input[name=activeclass]').is(':checked')) {
          var icon_active = ' ' + defactiveclass;
        } else {
          var icon_active = '';
        };

        if ($('#iconheight').val() == $('#iconsize').val()) {
          var icon_height = '';
        } else if ($('#iconheight').val()) {
          var icon_height = 'height:' + $('#iconheight').val() +'px;';
        } else {
          var icon_height = '';
        };
        
        if ($('#addstyles').val()) {
          var icon_addstyles = $('#addstyles').val();
        } else {
          var icon_addstyles = '';
        };
        if ($('input:radio[name=bgcolor]:checked').val()=='false') {
            var icon_bgcolor = '';
          } else {
            var icon_bgcolor = 'background:' + $('#bgcolorvalue').val() +';';
          };
        if (icon_height === '' && icon_addstyles === '' && icon_bgcolor === '') {
          var icon_styles = '';
        } else {
          var icon_styles = ' style="' + icon_height + icon_bgcolor + icon_addstyles + '"';
        };

        if ($('#addclass').val()) {
          var icon_addclass = ' ' + $('#addclass').val();
        } else {
          var icon_addclass = '';
        };

        if ($('#addlink').val()) {
          var icon_addlink = $('#addlink').val() + '"';
        } else {
          var icon_addlink = '';
        };

        if ($('#addlink').val()) {
          if ($('input[name=linktarget]').is(':checked')) {
            var icon_linktarget = ' target="_blank"';
          } else {
            var icon_linktarget = '';
          };
        } else {
          var icon_linktarget = '';
        };

        if ($('input:radio[name=iconcolor]:checked').val()=='original') {
          var icon_color = ' data-color="original"';
        } else {
          var icon_color = ' data-color="' + $('#iconcolorvalue').val() +'"';
        };
      
        if ($('input:radio[name=hoverchange]:checked').val()=='false') {
          var icon_hovercolor = ' data-hovercolor="false"';
        } else {
          var icon_hovercolor = ' data-hovercolor="' + $('#hovercolor').val() +'"';
        };
        
        if ($('input:radio[name=animated]:checked').val()=='true') {
          var icon_animate = ' data-animate="true"';
        } else {
          var icon_animate = ' data-animate="false"';
        };
        
        if ($('input:radio[name=looped]:checked').val()=='true') {
          var icon_looped = ' data-loop="true"';
        } else {
          var icon_looped = ' data-loop="false"';
        };

        if ($('input:radio[name=eventtype]:checked').val()=='click') {
          var icon_eventtype = ' data-eventtype="click"';
        } else {
          var icon_eventtype = ' data-eventtype="hover"';
        };

        if ($('input:radio[name=onparent]:checked').val()=='true') {
          var icon_onparent = ' data-onparent="true"';
        } else {
          var icon_onparent = ' data-onparent="false"';
        };

        if ($('#addlink').val()) {
          if (icon_name.match(/morph/)) {
            htmlcode = '<a href="' + icon_addlink + icon_linktarget + '><' + icon_htmltag + icon_id + ' class="livicon' + icon_addclass + icon_active + '"' + icon_name + icon_size + icon_color + icon_hovercolor + icon_animate + icon_eventtype + icon_onparent + icon_styles + '></' + icon_htmltag +'></a>';
          } else {
            htmlcode = '<a href="' + icon_addlink + icon_linktarget + '><' + icon_htmltag + icon_id + ' class="livicon' + icon_addclass + icon_active + '"' + icon_name + icon_size + icon_color + icon_hovercolor + icon_animate + icon_looped + icon_eventtype + icon_onparent + icon_duration + icon_iteration + icon_styles + '></' + icon_htmltag +'></a>';
          };
        } else {
          if (icon_name.match(/morph/)) {
            htmlcode = '<' + icon_htmltag + icon_id + ' class="livicon' + icon_addclass + icon_active + '"' + icon_name + icon_size + icon_color + icon_hovercolor + icon_animate + icon_eventtype + icon_onparent + icon_styles + '></' + icon_htmltag +'>';
          
          } else {
            htmlcode = '<' + icon_htmltag + icon_id + ' class="livicon' + icon_addclass + icon_active + '"' + icon_name + icon_size + icon_color + icon_hovercolor + icon_animate + icon_looped + icon_eventtype + icon_onparent + icon_duration + icon_iteration + icon_styles + '></' + icon_htmltag +'>';
          };
        };
        
      });//end each
    };//end if ... else ...
    return htmlcode;
  };//end getHtmlCode

});
//]]>
</script>
<script>
//<![CDATA[
  !function ($) {
    $('.icon').tooltip({'placement':'bottom'});
  }(jQuery)
//]]>
</script>
</body>
</html>
