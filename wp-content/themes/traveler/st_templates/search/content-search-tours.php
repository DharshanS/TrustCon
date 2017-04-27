<?php
/**
 * Flights status search form
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Content search tours
 *
 * Created by ShineTheme
 *
 */
$tours=new STTour();
$fields=$tours->get_search_fields();
?>

<h4>Check For Flight Status</h4>
     <form action="">
	                                        <div class="row">
											  <div class="col-md-6">
                                                    <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                                                        <label>Leaving From</label>
                                                        <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                                                    </div>
													</div>
													  <div class="col-md-6">
													 <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                                                        <label>Going To</label>
                                                        <input class="typeahead form-control" placeholder="City or Airport" type="text" />
                                                    </div>
													</div>
											</div>
                                                   
                                                        <div class="row">
                                                            <div class="col-md-6">
															 <div class="input-daterange" data-date-format="M d, D">
                                                                <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                                    <label>Departure Date</label>
                                                                    <input class="form-control" name="start" type="text" />
                                                                </div>
                                                            </div>
															</div>
                                                            <div class="col-md-6">
                                                               <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-plane input-icon"></i>
                                                        <label>Flight Number</label>
                                                        <input class="typeahead form-control" placeholder="Flight Number" type="text" />
                                                    </div>
                                                            </div>
                                                           
                                                           
                                                        
                                                    </div>
                                                    <button class="btn btn-primary btn-lg" type="submit">Search Now</button>
                                                </form>
