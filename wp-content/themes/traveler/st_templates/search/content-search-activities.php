<?php
/**
 * flights search form
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Content search activity
 *
 * Created by ShineTheme
 *
 */
$activity=new STActivity();
$fields=$activity->get_search_fields();
?>

<h4>Search for Cheap Flights</h4>
<form action="http://www.clickmybooking.com/flight/" method="post">
<input type="hidden" name="mode" value="rountrip" />
    <div class="tabbable">
        <ul class="nav nav-pills nav-sm nav-no-br mb10" id="flightChooseTab">
            <li class="active"><a href="#flight-search-1" data-toggle="tab">Round Trip</a>
            </li>
            <li><a href="#flight-search-2" data-toggle="tab">One Way</a>
            </li>
            <li><a href="#flight-search-3" data-toggle="tab">Multi City / Stop Over</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="flight-search-1">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>From</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>To</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                
                
                <div class="input-daterange" data-date-format="M d, D">
                    
                        <div class="col-md-3">
                            <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                <label>Departing</label>
                                <input class="form-control" name="start" type="text" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                <label>Returning</label>
                                <input class="form-control" name="end" type="text" />
                            </div>
                        </div>
                        
                    </div>
                     </div>
                    <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-select-plus">
                                <label>Adults: (12+ YRS)</label>
                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" />1</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />2</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3+</label>
                                </div>
                                <select class="form-control hidden">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option selected="selected">4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                    <option>13</option>
                                    <option>14</option>
                                </select>
                            </div>
                    </div>
                    <div class="col-md-3">
                         <div class="form-group form-group-lg form-group-select-plus">
                                <label>Children: (2-11 YRS)</label>
                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" />1</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />2</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3+</label>
                                </div>
                                <select class="form-control hidden">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option selected="selected">4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                    <option>13</option>
                                    <option>14</option>
                                </select>
                            </div>
                    </div>
                    <div class="col-md-3">
                         <div class="form-group form-group-lg form-group-select-plus">
                                <label>Infants: (0-2 YRS)</label>
                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" />1</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />2</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3+</label>
                                </div>
                                <select class="form-control hidden">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option selected="selected">4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    
                                </select>
                            </div>
                    </div>
                   <div class="col-md-3">
                         <div class="form-group form-group-lg form-group-select-plus">
                                <label>Class:</label>
                               <select class="form-control">
                                    <option>Business</option>
                                    <option selected="selected">Economy</option>
                                    <option>First Class</option>
                                </select>
                            </div>
                    </div>
                </div>
               
                 
                
            </div>
            <div class="tab-pane fade" id="flight-search-2">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>From</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>To</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                            <label>Departing</label>
                            <input class="date-pick form-control" data-date-format="M d, D" type="text" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-select-plus">
                                <label>Adults: (12+ YRS)</label>
                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" />1</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />2</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3+</label>
                                </div>
                                <select class="form-control hidden">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option selected="selected">4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                    <option>13</option>
                                    <option>14</option>
                                </select>
                            </div>
                    </div>
                    <div class="col-md-3">
                         <div class="form-group form-group-lg form-group-select-plus">
                                <label>Children: (2-11 YRS)</label>
                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" />1</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />2</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3+</label>
                                </div>
                                <select class="form-control hidden">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option selected="selected">4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                    <option>13</option>
                                    <option>14</option>
                                </select>
                            </div>
                    </div>
                    <div class="col-md-3">
                         <div class="form-group form-group-lg form-group-select-plus">
                                <label>Infants: (0-2 YRS)</label>
                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" />1</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />2</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3+</label>
                                </div>
                                <select class="form-control hidden">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option selected="selected">4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    
                                </select>
                            </div>
                    </div>
                   <div class="col-md-3">
                         <div class="form-group form-group-lg form-group-select-plus">
                                <label>Class:</label>
                               <select class="form-control">
                                    <option>Business</option>
                                    <option selected="selected">Economy</option>
                                    <option>First Class</option>
                                </select>
                            </div>
                    </div>
                </div>
            </div>
             <div class="tab-pane fade" id="flight-search-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>From</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>To</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                            <label>Departing</label>
                            <input class="date-pick form-control" data-date-format="M d, D" type="text" />
                        </div>
                    </div>
                    
                    </div>
   
<div class="row">
<div class="col-md-12">                 
                    
                
<script language="javascript"> 
function toggle1() {
var ele = document.getElementById("toggleText1");
var text = document.getElementById("displayText1");
if(ele.style.display == "block") {
ele.style.display = "none";
text.innerHTML = "<input type='button'  id='button1'  value='Add City 1'>";
}
else {
ele.style.display = "block";
text.innerHTML = "<input type='button' id='button1' value='Remove City 1'>";
}
} 
</script>


<div id="toggleText1" style="display: none"><hr></hr><br>

<div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>From</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>To</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                            <label>Departing</label>
                            <input class="date-pick form-control" data-date-format="M d, D" type="text" />
                        </div>
                    </div>
                    
                    </div>

<br></div>

<a id="displayText1" href="javascript:toggle1();"><input id="button1" type="button" value="Add City 1"></a>
<br>

<script language="javascript"> 
function toggle2() {
var ele = document.getElementById("toggleText2");
var text = document.getElementById("displayText2");
if(ele.style.display == "block") {
ele.style.display = "none";
text.innerHTML = "<input type='button'  id='button2'  value='Add City 2'>";
}
else {
ele.style.display = "block";
text.innerHTML = "<input type='button' id='button2' value='Remove City 2'>";
}
} 
</script>


<div id="toggleText2" style="display: none"><hr></hr><br>

<div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>From</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>To</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                            <label>Departing</label>
                            <input class="date-pick form-control" data-date-format="M d, D" type="text" />
                        </div>
                    </div>
                    
                    </div>

<br></div>

<a id="displayText2" href="javascript:toggle2();"><input id="button2" type="button" value="Add City 2"></a>
<br>

<script language="javascript"> 
function toggle3() {
var ele = document.getElementById("toggleText3");
var text = document.getElementById("displayText3");
if(ele.style.display == "block") {
ele.style.display = "none";
text.innerHTML = "<input type='button'  id='button3'  value='Add City 3'>";
}
else {
ele.style.display = "block";
text.innerHTML = "<input type='button' id='button3' value='Remove City 3'>";
}
} 
</script>


<div id="toggleText3" style="display: none"><hr></hr><br>

<div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>From</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>To</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                            <label>Departing</label>
                            <input class="date-pick form-control" data-date-format="M d, D" type="text" />
                        </div>
                    </div>
                    
                    </div>

<br></div>

<a id="displayText3" href="javascript:toggle3();"><input id="button3" type="button" value="Add City 3"></a>
<br>

<script language="javascript"> 
function toggle4() {
var ele = document.getElementById("toggleText4");
var text = document.getElementById("displayText4");
if(ele.style.display == "block") {
ele.style.display = "none";
text.innerHTML = "<input type='button'  id='button4'  value='Add City 4'>";
}
else {
ele.style.display = "block";
text.innerHTML = "<input type='button' id='button4' value='Remove City 4'>";
}
} 
</script>


<div id="toggleText4" style="display: none"><hr></hr><br>

<div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>From</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                            <label>To</label>
                            <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                            <label>Departing</label>
                            <input class="date-pick form-control" data-date-format="M d, D" type="text" />
                        </div>
                    </div>
                    
                    </div>

<br></div>

<a id="displayText4" href="javascript:toggle4();"><input id="button4" type="button" value="Add City 4"></a>
<br>









<br>								
                    
                    
                </div>	
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-group-lg form-group-select-plus">
                                <label>Adults: (12+ YRS)</label>
                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" />1</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />2</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3+</label>
                                </div>
                                <select class="form-control hidden">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option selected="selected">4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                    <option>13</option>
                                    <option>14</option>
                                </select>
                            </div>
                    </div>
                    <div class="col-md-3">
                         <div class="form-group form-group-lg form-group-select-plus">
                                <label>Children: (2-11 YRS)</label>
                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" />1</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />2</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3+</label>
                                </div>
                                <select class="form-control hidden">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option selected="selected">4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                    <option>13</option>
                                    <option>14</option>
                                </select>
                            </div>
                    </div>
                    <div class="col-md-3">
                         <div class="form-group form-group-lg form-group-select-plus">
                                <label>Infants: (0-2 YRS)</label>
                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" />1</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />2</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3</label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" />3+</label>
                                </div>
                                <select class="form-control hidden">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option selected="selected">4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    
                                </select>
                            </div>
                    </div>
                   <div class="col-md-3">
                         <div class="form-group form-group-lg form-group-select-plus">
                                <label>Class:</label>
                               <select class="form-control">
                                    <option>Business</option>
                                    <option selected="selected">Economy</option>
                                    <option>First Class</option>
                                </select>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
                 <div class="col-md-12">
<div style="display: inline-flex;padding-top: 10px;">
<input type="checkbox" id="" style="" name="date_flexi">
<label for="date_flexible_check" role="button" aria-disabled="false" tabindex="47" aria-pressed="false">
<div class="sub_label">&nbsp;&nbsp;I want the dates are flexible. [+/- 3 days]</div></label>  
</div> 
</div>
             </div>
    <button class="btn btn-primary btn-lg" type="submit">Search for Flights</button>
</form>
                                             