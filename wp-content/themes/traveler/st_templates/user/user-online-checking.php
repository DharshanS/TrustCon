<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User Online Checking
 *
 * Created by H+Plus Designs - Sri Lanka 
 *
 */
?>
<style type="text/css">
.airline-list { border: 1px solid #ccc; width: 100%; }
.airline-list .airlist-heading { background: #005aa8; color: #fff; padding: 15px 30px; width: 100%; float:left; }
.airline-list .airlist-heading h1 { font-size: 22px; font-weight: bold; margin: 5px 0; }
.airlist-body { float: left; padding:20px 30px; width: 100%; }
.airlist-body a { float: left; text-align: center; text-decoration: none; width: 100%; -o-transition: all 300ms ease-in-out; -webkit-transition: all 300ms ease-in-out; -moz-transition: all 300ms ease-in-out; -ms-transition: all 300ms ease-in-out; transition: all 300ms ease-in-out; text-indent:0; }
/*.airline-list .airlist-body a:hover { -ms-transform: scale(1.1); -webkit-transform: scale(1.1); transform: scale(1.1); }*/
.airlist-body .airline-list-image { border: 1px solid #ccc; float: left; width: 100%; }
.airlist-body .airline-list-image img { width:100%; max-width:140px; height:auto; max-height:134px; }
.airlist-body .airline-list-name { float: left; font-size: 14px; height: 65px; padding: 10px 0; width: 100%; }
</style>
<div class="st-create">
    <h2>Online Checking</h2>
    
<div class="airlist-body">
    <div class="row">
    <!-- retrieving from the xml -->
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.airasia.com/lk/en/check-ins/web-and-mobile.page?icid=iae061hpsba">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/airAsia.gif" src="<?php echo get_home_url();?>/airlineimages/airAsia.gif" alt="Air Asia Flights">
                </div>
                <div class="airline-list-name"> Air Asia </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="https://res.aircanada.com/oci/start?">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/canada.gif" src="<?php echo get_home_url();?>/airlineimages/canada.gif" alt="Air Canada flights">
                </div>
                <div class="airline-list-name"> Air Canada </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.airfrance.fr/FR/en/common/guidevoyageur/e_services/e_services_echeckin_airfrance.htm">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/france.gif" src="<?php echo get_home_url();?>/airlineimages/france.gif" alt="Air France Flights">
                </div>
                <div class="airline-list-name"> Air France </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.britishairways.com/travel/olcilandingpageauthreq/public/en_gb">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/british.gif" src="<?php echo get_home_url();?>/airlineimages/british.gif" alt="British Airways">
                </div>
                <div class="airline-list-name"> British Airways </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.cathaypacific.com/cx/en_LK/manage-booking/check-in/check-in-now.html">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/cathey.gif" src="<?php echo get_home_url();?>/airlineimages/cathey.gif" alt="Cathay Pacific Flights">
                </div>
                <div class="airline-list-name"> Cathay Pacific </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="https://fastcheck.sita.aero/cce-presentation-web-ai/entryUpdate.do">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/airIndia.gif" src="<?php echo get_home_url();?>/airlineimages/airIndia.gif" alt="Air India Flights">
                </div>
                <div class="airline-list-name"> Air India </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="https://fly10.emirates.com/CKIN/OLCI/FlightInfo.aspx">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/emirates.gif" src="<?php echo get_home_url();?>/airlineimages/emirates.gif" alt="Emirates Flights">
                </div>
                <div class="airline-list-name"> Emirates </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.etihad.com/en-lk/before-you-fly/check-in-online">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/ethihad.gif" src="<?php echo get_home_url();?>/airlineimages/ethihad.gif" alt="Etihad Airlines Flights">
                </div>
                <div class="airline-list-name"> Etihad Airways </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.gulfair.com/English/info/Checkin/Pages/WebCheck-in.aspx">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/gulf.gif" src="<?php echo get_home_url();?>/airlineimages/gulf.gif" alt="Gulf Air Flights">
                </div>
                <div class="airline-list-name"> Gulf Air </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.jetairways.com/EN/LK/PlanYourTravel/WebCheck-in.aspx">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/jetAir.gif" src="<?php echo get_home_url();?>/airlineimages/jetAir.gif" alt="Jet Airways Flights">
                </div>
                <div class="airline-list-name"> Jet Airways </div> 
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="https://www.klm.com/travel/in_en/prepare_for_travel/checkin_options/internet_checkin/ici_jffp_app.htm">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/klm.gif" src="<?php echo get_home_url();?>/airlineimages/klm.gif" alt="KLM Royal Dutch Airlines Flights">
                </div>
                <div class="airline-list-name"> KLM Royal Dutch Airlines </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="https://fly.kuwaitairways.com/CKIN/OLCI/FlightInfo.aspx">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/kuwait.gif" src="<?php echo get_home_url();?>/airlineimages/kuwait.gif" alt="Kuwait Airways Flights">
                </div>
                <div class="airline-list-name"> Kuwait Airways </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.lufthansa.com/us/en/My-Bookings">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/lufthansa.gif" src="<?php echo get_home_url();?>/airlineimages/lufthansa.gif" alt="Lufthansa Flights">
                </div>
                <div class="airline-list-name"> Lufthansa </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="https://fastcheck.sita.aero/cce-presentation-web-mh/entryUpdate.do">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/malaysia.gif" src="<?php echo get_home_url();?>/airlineimages/malaysia.gif" alt="Malaysia Airlines Flights">
                </div>
                <div class="airline-list-name"> Malaysia Airlines </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.omanair.com/en/plan-and-book/online-check-in">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/oman.gif" src="<?php echo get_home_url();?>/airlineimages/oman.gif" alt="Oman airways">
                </div>
                <div class="airline-list-name"> Oman Air </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.qatarairways.com/qa/en/check-in-online.page#">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/qatar.gif" src="<?php echo get_home_url();?>/airlineimages/qatar.gif" alt="Qatar Airways">
                </div>
                <div class="airline-list-name"> Qatar Airways </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.singaporeair.com/checkIN-flow.form?execution=e5s1">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/singap.gif" src="<?php echo get_home_url();?>/airlineimages/singap.gif" alt="Singapore Airlines">
                </div>
                <div class="airline-list-name"> Singapore Airlines </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.rj.com/en/get_your_boarding_pass_online.html">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/royalJor.gif" src="<?php echo get_home_url();?>/airlineimages/royalJor.gif" alt="Royal Jordanian">
                </div>
                <div class="airline-list-name"> Royal Jordanian </div> 
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.thaiair.com/ADD_IWCI/Checkin_Process.jsp?random=1378876754585">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/thai.gif" src="<?php echo get_home_url();?>/airlineimages/thai.gif" alt="Thai Airways">
                </div>
                <div class="airline-list-name"> Thai Airways </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.srilankan.com/olci">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/srilankan.gif" src="<?php echo get_home_url();?>/airlineimages/srilankan.gif" alt="SriLankan Airlines">
                </div>
                <div class="airline-list-name"> SriLankan Airlines </div>
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="http://www.united.com/travel/checkin/start.aspx?LangCode=en-US">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/united.gif" src="<?php echo get_home_url();?>/airlineimages/united.gif" alt="United Airlines">
                </div>
                <div class="airline-list-name"> United Airlines </div> 
            </a>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-2">
            <a target="_blank" href="https://book.spicejet.com/SearchWebCheckin.aspx">
                <div class="airline-list-image">
                    <img data-src="<?php echo get_home_url();?>/airlineimages/spice.gif" src="<?php echo get_home_url();?>/airlineimages/spice.gif" alt="Spice Jet Flights">
                </div>
                <div class="airline-list-name"> Spice Jet </div>
            </a>
        </div>								
        <div class="clearfix"></div>
    </div>
</div>
    
    
    
</div>