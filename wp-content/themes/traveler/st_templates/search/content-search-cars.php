<?php
/**
 * flights + hotel Search form
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Content search cars
 *
 * Created by ShineTheme
 *
 */
$cars=new STCars();
if($st_style_search=="style_1"){
    $fields=$cars->get_search_fields();
}else{
    $fields=$cars->get_search_fields_box();
}

?>

    <h4>Search for Flight + Hotels</h4>
    <form action="http://www.clickmybooking.com/flight-hotel/">
                                                    <div class="tabbable">
                                                        <ul class="nav nav-pills nav-sm nav-no-br mb10" id="flightChooseTab">
                                                            <li class="active"><a href="#flight-search-1-1" data-toggle="tab">Round Trip</a>
                                                            </li>
                                                            <li><a href="#flight-search-2-2" data-toggle="tab">One Way</a>
                                                            </li>
															
                                                        </ul>
                                                        <div class="tab-content">
                                                           
							 <div class="tab-pane fade in active" id="flight-search-1-1">
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
	<div class="col-md-6">													
	<div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                                                        <label>Where are you going?</label>
                                                        <input class="typeahead form-control" placeholder="Hotel Destination" type="text" />
                                                    </div>
													</div>
                                                    <div class="input-daterange" data-date-format="M d, D">
                                                        
                                                            <div class="col-md-3">
                                                                <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                                    <label>Check-in</label>
                                                                    <input class="form-control" name="start" type="text" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                                    <label>Check-out</label>
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

                                                                </div>
													</div>								
                                 <div class="tab-pane fade" id="flight-search-2-2">
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
                                                                        
                                                                        
                                                                    </div>
																	
                                                                </div>
																 
																
                                                            
	<div class="row">	 
	<div class="col-md-6">													
	<div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                                                        <label>Where are you going?</label>
                                                        <input class="typeahead form-control" placeholder="Hotel Destination" type="text" />
                                                    </div>
													</div>
                                                    <div class="input-daterange" data-date-format="M d, D">
                                                        
                                                            <div class="col-md-3">
                                                                <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                                    <label>Check-in</label>
                                                                    <input class="form-control" name="start" type="text" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                                    <label>Check-out</label>
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

                                                                </div>
													</div>

                                                        </div>
                                                    </div>
													
                                                    <button class="btn btn-primary btn-lg" type="submit">Search for Flights + Hotels</button>
                                                </form>