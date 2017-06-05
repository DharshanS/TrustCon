<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("../../../../wp-load.php");

include(get_home_path()."mpdf60/mpdf.php");


require_once(get_home_path().'PHPMailer/PHPMailerAutoload.php');
require_once(PLUG_DIR."/core/MailSettings.php");
require_once(PLUG_DIR.'/utility/FlightUtility.php');
session_start();
set_time_limit(0);
date_default_timezone_set('Asia/Colombo');
function email_send($resReq,$tripId)
{
	$mpdf=new mPDF('c');
	$mailsetters=$GLOBALS['mailsetters'];
	$mail=get_emailer();
	$mail2=get_emailer();
	$pnr= $resReq['uniRecLocCode'];
	error_log(print_r($mail,true));
	$adminmail=$mailsetters['adminmail'];
	$booking_reason=$_SESSION['booking_reason'];
	$letterhead_name="";
	$letterhead_company="";
	$letterhead_address="";
	$noofpassenger="";

	$fareInfo=unserialize($_SESSION['fareInfo']);
	$air=unserialize($_SESSION['air']);
	$outBound=$air[0];
	$inbound="";

	if(isset($air[1]))
	{
		$inbound=$air[1];
	}


	$mailto = $resReq['email'];
	$mailsubject = '';

	$mailtoname = $resReq['f_name'][0] . ' ' . $resReq['l_name'][0];
	$title = $resReq['title'][0];
	$passport_no = $resReq['passport_no'][0];

	$mail->addAddress("cdharshans@gmail.com", $mailtoname);
	$mail->addReplyTo($mailsetters['from']);
	$mail->isHTML(true);

	$pdfhtml = "";

	$mailhtml = '<img src="http://www.clickmybooking.com/images/newsletter-logo.png" alt="Logo">';
	$mailhtml .= '<br>';
	$mailhtml .= '<br>';


if(isset($air[0])&& isset($pnr))
{

	$mailhtml .=get_mail_header($pnr,$tripId);
	$mailhtml .=get_flight_details_table($air);
} else
{
			$mailhtml .= 'We are sorry, we do not find any Itenary for your search. Please check your input and search again';
}
		$mailhtml .= '<em>*All times are local to airport</em>';
		$mailhtml .= '<br><br>';
	$mailhtml.=get_passanger_details($resReq);
	$mailhtml.get_mail_footer();


// pdf attachment
	$mpdf->WriteHTML($mailhtml);
	$sout = $mpdf->Output('', 'S');
	$mail->AddStringAttachment($sout, $mailsubject . ".pdf");

	$mail->Subject = $mailsubject;
	$mail->Body = $mailhtml;
	if ($booking_reason == 'Embassy Visa Purpose') {

	} else {

		error_log("Mail sending .......................".$mail->send());

	}

//	$mail2->addAddress($adminmail,"Dharshan");
//	$mail2->addReplyTo($mailsetters['from']);
//	$mail2->isHTML(true);
//	$mail2->AddStringAttachment($resReq, $mailsubject . ".pdf");
//	$mail2->Subject = $mailsubject;
//	$mail2->Body = $mailhtml;
//	error_log("Mail sending ....................2...");
//	$mail2->send();


}



function get_flight_details_table($items)
{

	$flyUtil= new	FlightUtility();
	$outBound=$items[0];
	$mailhtml= '<table cellpadding="10" cellspacing="0" border="0" bordercolor="#ccc" >
            <tr>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Flight</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Aircraft</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Departure</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Arrival</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Duration</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Class Of Service</th>
            </tr>';
	foreach ($outBound as $air) {
		$item = $air['@attributes'];
		error_log("fly details--->".print_r($item,true));
		$classdts="";
			if(isset($item[0]['@attributes'])){
				$classdts=$item[0]['@attributes'];
			}

		$duration =$flyUtil->getTimeDiff($item['ArrivalTime'], $item['DepartureTime']);
		$mailhtml .= '<tr>
			<td style="border-bottom: 1px dotted #c4c4c4;">' . $item['Carrier'] . ' ' . $item['FlightNumber'] . ' <br /><img src="http://clickmybooking.com/airimages/' . $item['Carrier'] . '.GIF' . '" /><br />' .  $flyUtil->getAirLineName($item['Carrier']) . '</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">Aircraft ' .  $item['Equipment'] . '</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">' .$flyUtil->getCityName($item['Origin']). ' (' .$item['Origin'] . ') ' . date("l, d M", strtotime($item['DepartureTime'])) . '<br />' . date("Y h:i", strtotime($item['DepartureTime'])) . '<br />' .$flyUtil->getAirportName($item['Origin'] ). '</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">' . $flyUtil->getCityName($item['Destination']) . ' (' . $item['Destination'] . ') ' . date("l, d M", strtotime($item['ArrivalTime'])) . '<br />' . date("Y h:i", strtotime($item['ArrivalTime'])) . ' <br />' . $flyUtil->getAirportName($item['Destination']) . '</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">' . $duration . '</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">' . $classdts['CabinClass'] . ' ' . $classdts['BookingCode'] . '</td>
		</tr>';
	}
	$mailhtml .= '</table>';

	return $mailhtml;
}

function get_mail_header($pnr,$tripId)
{

	$mailsubject = 'Clickmybooking - Regarding Booking Of Air Ticket';
	$mailhtml = '<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><img src="http://www.clickmybooking.com/images/newsletter-logo.png" alt="Logo"></td>
                <td valign="top"><strong>Customer Care:</strong><br>
				<strong>Call :</strong> 0812 22 77 77<br>
				<strong>Email :</strong> support@clickmybooking.com</td>
            </tr>';
	$mailhtml .= '</table>';
	$mailhtml .= 'Travel Itinerary (reservation copy)';
	$mailhtml .= '<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong>MyTrip ID :</strong>' . $tripId . '<br>';
	$mailhtml .= '<strong>Airline PNR :</strong> ' . $pnr . '';
	$mailhtml .= '     </td>
                <td valign="top"><strong>Booking Date :</strong> ' . date("jS M, Y") . '</td>
            </tr>';
	$mailhtml .= '</table>';
	$mailhtml .= '<br>';
	$mailhtml .= '<strong>Itinerary Details:</strong>';
	$mailhtml .= '<br><br>';

	return $mailhtml;
}

function get_mail_footer()
{
	$mailhtml= '<br>';
	$mailhtml .= '<strong>Additional Information:</strong>';
	$mailhtml .= '<br>';
	$mailhtml .= '&nbsp;&nbsp;&gt;This document can not be used as a travel document or an E-ticket under any circumstance.';
	$mailhtml .= '<br>';
	$mailhtml .= '&nbsp;&nbsp;&gt;The fare is subjected to change unless it is being ticketed.';
	$mailhtml .= '<br>';
	$mailhtml .= '&nbsp;&nbsp;&gt;You may have to submit details of your passport before issuing of tickets to certain destinations.';
	$mailhtml .= '<br>';
	$mailhtml .= '&nbsp;&nbsp;&gt;Our agents might contact you directly for verification purposes.';
	$mailhtml .= '<br>';
	$mailhtml .= '&nbsp;&nbsp;&gt;Authorities should be immediately if the name appearing in this document is different to that mentioned in the passport.';
	$mailhtml .= '<br>';
	$mailhtml .= '&nbsp;&nbsp;&gt;You are required to comprehend and settle the total flight fare before you could proceed to the issuing of the ticket.';
	$mailhtml .= '<br>';
	$mailhtml .= '&nbsp;&nbsp;&gt;Any alteration done to the special fares offered are subjected to a fee of penalty and cannot be refunded.';
	$mailhtml .= '<br>';
	$mailhtml .= '&nbsp;&nbsp;&gt;Please use the MyTrip ID when communicating with us.';
	$mailhtml .= '<br>';
	$mailhtml .= '&nbsp;&nbsp;&gt;Please use \'MYSKY ID\' to contact us.';
	$mailhtml .= '<br><br>';
	$mailhtml .= '<strong>OFFICE:</strong> No 07, D S Senanyake Street, Kandy<br>
<strong>Email:</strong> booking@clickmybooking.com<br>
<strong>Contact Number:</strong> 081-2227771';
//## XXXX Added for SMS integration
//		$myPhone = $S['country'][0] . $S['phone'][0];
////smsTemplate($myPhone , $mailtoname , $PNR , 'confirm');
//## XXXX Added for SMS integration


	// else
	$mailhtml .= '<br>';
	$mailhtml .= '<br>';
	$mailhtml .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:20px 0; border-top: 3px solid #4EDBFF ; color: #0000A6; ">
	<tr>
		<td align="center" valign="top">Head Office: 76/A, New Town, Digana, Rajawella, Kandy.<br>
		Tel: +94 812 203050, +94 812 227777 | Fax: +94 812 220103 | Mob: +94 777 776535<br>
		City Office: 07, D.S. Senanayake Veediya, Kandy - Sri Lanka.<br>
		08, Bus Stand, Commercial Building, Bandarawela - Sri Lanka. Tel: +94 57 2230555/ 131<br>
		E-mail: meera 1958@yahoo.co.in | www.meeratravels.lk</td>
	</tr>
</table>
';

	return $mailhtml;
}


function get_passanger_details($resReq)
{
	$mailhtml = '<strong>Passenger Details:</strong>';
	$mailhtml .= '<br>';
	$mailhtml .= '<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong><u>Name</u></strong></td>
                <td style=""><strong><u>Baggage Allowed </u></strong></td>
            </tr>';
	foreach($resReq['f_name'] as $k=>$v)
	{

		$mailhtml .= '<tr>'.
			'<td><em>' . $v . '</em><br>' . $resReq['title'][$k] . ' ' . $resReq['f_name'][$k] . ' ' . $resReq['l_name'][$k] . '</u></td>'.
//			if ($v == 'CHD') $v = 'CNN';
//			$mailhtml .= '     <td>' . $baggageallow[$v] . '</td>
			' </tr>';
	}
	$mailhtml .= '</table>';
	return $mailhtml;
}
