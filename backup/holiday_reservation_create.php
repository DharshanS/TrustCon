<?php
session_start();
set_time_limit(0);

if(!isset($_SESSION['hotel_entries'])){
echo 'Something gone wrong';
}
require_once("travelportsettings.php");
require_once("tconnect.php");
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

require_once('PHPMailer/PHPMailerAutoload.php'); 
require_once("mailsettings.php");
$adminmail=$mailsetters['adminmail'];

$mail = new PHPMailer;
$mail->isSMTP(); 
$mail->Host = $mailsetters['host']; 
$mail->SMTPAuth = $mailsetters['smtpauth'];    
$mail->SMTPDebug  =$mailsetters['smtpdebug'];
$mail->Port =$mailsetters['port'];   
$mail->Username = $mailsetters['username'];                
$mail->Password = $mailsetters['password'];                         
$mail->SMTPSecure = $mailsetters['smtpsecure'];                            
$mail->From = $mailsetters['from'];
$mail->FromName = $mailsetters['fromname'];

$mail2 = new PHPMailer;
$mail2->isSMTP(); 
$mail2->Host = $mailsetters['host']; 
$mail2->SMTPAuth = $mailsetters['smtpauth'];    
$mail2->SMTPDebug  =$mailsetters['smtpdebug'];
$mail2->Port =$mailsetters['port'];   
$mail2->Username = $mailsetters['username'];                
$mail2->Password = $mailsetters['password'];                         
$mail2->SMTPSecure = $mailsetters['smtpsecure'];                            
$mail2->From = $mailsetters['from'];
$mail2->FromName = $mailsetters['fromname'];



## Get data

foreach($_REQUEST as $k=>$v) 
{ 
	$$k = $v; 
}

$userid=0;
if(isset($_REQUEST['r'])){
$u=$_REQUEST['r'];	
$u=base64_decode($u);
$u=unserialize($u);	
$userid=$u[0];
}

$amount=$_SESSION['holidaybooking']['totalamount'];
$airtransport=$_SESSION['holidaybooking']['atprice'];

$mytripid=tripIdGenerator();

## ----Added - 29/10/15
$_SESSION['Mytripid']=$mytripid;	

// $city = $mysqli->real_escape_string($city);
	/*$SQL  =  'INSERT INTO booking SET name = "'.$name.'" , dob = "'.$dob.'" , adult = '.$adult.' , checkin = "'.$checkin.'" , '.
	         ' checkout = "'.$checkout.'" , email = "'.$email.'" , price = '.$amount.' , booking_dt = "'.date('Y-m-d').'" ,   '.
			 ' mobile = "'.$phone.'" , booking_id = "'.$mytripid.'"  , room_id = '.$roomid.' , tour_ids = "'.$tourids.'" ,  '.
			 ' child = '.$child.' , infant = '.$infant.',`noofrooms`= '.$noofrooms.' ';*/
			 
$SQL  =  'INSERT INTO booking SET userid="'.$userid.'", name = "'.$name.'" , dob = "'.$dob.'" , adult = '.$adult.' , checkin = "'.$checkin.'" , '.
	         ' checkout = "'.$checkout.'" , email = "'.$email.'" , price = '.$amount.' , booking_dt = "'.date('Y-m-d').'" ,   '.
			 ' mobile = "'.$phone.'" , booking_id = "'.$mytripid.'"  , room_id = '.$roomid.' , tour_ids = "'.$tourids.'" ,  '.
			 ' child = '.$child.' , infant = '.$infant.',`noofrooms`= '.$noofrooms.',`cwb`= "'.$cwb.'",`airtransport`= "'.$airtransport.'",`tour_details`= "'.addslashes($tour_details).'"';
			 

$result = $mysqli->query($SQL);
$insert_id=$mysqli->insert_id;


$_SESSION['post_id'] = $insert_id;


## Room Booked Update
    $sql  =  'UPDATE room SET available = "1"  WHERE  id = '.$roomid.' '; 
	$result = $mysqli->query($sql);
	
// get hotel details
$sql  =  'SELECT `hotel_id`,`description` FROM room WHERE  id = '.$roomid.' '; 
$result = $mysqli->query($sql);
$row = $result->fetch_array(MYSQLI_BOTH);
$hotel_id=$row['hotel_id'];
$room_description=$row['description'];
$SQL="SELECT * FROM `hotel` WHERE `id`='".$hotel_id."'";
$result = $mysqli->query($SQL);
$row = $result->fetch_array(MYSQLI_BOTH);

$hotel=$row['hotel'];
$description=$row['description'];
$address=$row['address'];
$category=$row['category'];
$photo=$row['photo'];

$S=$_SESSION['hotel_entries'];

$detailsstring='';
if($noofrooms>1){
	$detailsstring.='('.$noofrooms.' rooms)';
}else $detailsstring.='('.$noofrooms.' room)';
$detailsstring.='<br>Adult: '.$adult.'<br>Child: '.$child.'<br>Infant: '.$infant.'<br>';
if($cwb=='y'){
	$detailsstring.='CWB: Yes<br>';
}else{
	$detailsstring.='CWB: No<br>';
}
if($airtransport){
	$detailsstring.='Need Airport Transportation: Yes';
}else{
	$detailsstring.='Need Airport Transportation: No';
}
$tourdata=$_SESSION['holidaybooking']['tourdata'];
$transport_type_arr=array();
foreach($tourdata as $td){
 $transport_type_arr[$td['tourid']]=$td['transport_type'];	
}
$tourdetailsarr=array();
if($tourids!='')
 $touridsarray=explode(",",$tourids);
if(count($touridsarray)){
$tourids=implode(",",$touridsarray);
$SQL="SELECT * FROM  `tour` WHERE `id` IN(".$tourids.")";
$result = $mysqli->query($SQL);
while($row = $result->fetch_array(MYSQLI_BOTH)){
	$data=array(
	  'tour'=>$row['tour'],
	  'photo'=>$row['photo'],
	  'price'=>$row['price'],
	  'transport_type'=>isset($transport_type_arr[$row['id']])&& $transport_type_arr[$row['id']]!=''?$transport_type_arr[$row['id']]:'-'
	);
	$tourdetailsarr[]=$data;
}
}

$mysqli->close();

// sendmail to client
$mailto= $email;
$mailsubject='';
$mailhtml='';
$mailtoname= $name;

$mail->addAddress($mailto, $mailtoname); 
$mail->addReplyTo($mailsetters['from']);
$mail->isHTML(true);  


$mailsubject='Clickmybooking - Holiday Booking';

$mailhtml='<img src="http://www.clickmybooking.com/images/newsletter-logo.png" alt="Logo">';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Dear '.$name.',<br><br>';
$mailhtml.='Thanking for your inquiry with us dated '.date("jS M, Y").',';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Following is the details of your bookings:';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='<table cellpadding="10" cellspacing="0" border="0" bordercolor="#ccc" width="100%" >
            <tr>
                <td style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;" width="50%"><strong>Trip ID</strong></td>
                <td style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;"><strong>'.$mytripid.'</strong></td>
            </tr>
            <tr>
                <td>Hotel : LKR</td>
                <td>'.$_SESSION['holidaybooking']['hotelamount'].'</td>
            </tr>
            <tr>
                <td>Tours : LKR</td>
                <td>'.$_SESSION['holidaybooking']['touramount'].'</td>
            </tr>
			<tr>
                <td>Air Transportation : LKR</td>
                <td>'.$_SESSION['holidaybooking']['airtransportprice'].'</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #2eade2;"><strong>Total Amount : LKR</strong></td>
                <td style="border-bottom: 1px solid #2eade2;" ><strong>'.$_SESSION['holidaybooking']['totalamount'].'</strong></td>
            </tr>
			<tr>
                <td>
				<img style="width:50%;" src="http://www.clickmybooking.com/wp-content/plugins/hotelbooking/uploads/hotels/'.rawurlencode(urldecode($photo)).'"
				</td>
                <td valign="top">'.$hotel.'<br>'.$address.'<br>Room: '.$room_description.''.$detailsstring.'</td>
            </tr>';
			foreach($tourdetailsarr as $td){
$mailhtml.='<tr>
                <td>
				<img style="width:50%;" src="http://www.clickmybooking.com/wp-content/plugins/hotelbooking/uploads/tours/'.rawurlencode(urldecode($td['photo'])).'"
				</td>
                <td valign="top">'.$td['tour'].'<br>LKR '.$td['price'].'<br>Transport type: '.strtoupper($td['transport_type']).'</td>
            </tr>';				
			}
$mailhtml.='</table>';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='We are pleased to provide further information on request and trust that you would contact on us to fulfill your order, which will receive our prompt and keen attention.';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Thanking you,';
$mailhtml.='<br>';
$mailhtml.='<a href="http://www.clickmybooking.com">www.clickmybooking.com</a>';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:20px 0; border-top: 3px solid #4EDBFF ; color: #0000A6; ">
	<tr>
		<td align="center" valign="top">Head Office: 76/A, New Town, Digana, Rajawella, Kandy.<br>
		Tel: +94 812 203050, +94 812 227777 | Fax: +94 812 220103 | Mob: +94 777 776535<br>
		City Office: 07, D.S. Senanayake Veediya, Kandy - Sri Lanka.<br>
		08, Bus Stand, Commercial Building, Bandarawela - Sri Lanka. Tel: +94 57 2230555/ 131<br>
		E-mail: meera 1958@yahoo.co.in | www.meeratravels.lk</td>
	</tr>
</table>
';	

if(isset($_SESSION['holidaybooking'])){
$_SESSION['holidaybooking']=array();
unset($_SESSION['holidaybooking']);	
}

$mail->Subject = $mailsubject;
$mail->Body    = $mailhtml;
$mail->send();

$mail2->addAddress($adminmail); 
$mail2->addReplyTo($mailsetters['from']);
$mail2->isHTML(true);
$mail2->Subject = $mailsubject;
$mail2->Body    = $mailhtml;
$mail2->send();  


//$meta_key
// meta_value

function tripIdGenerator($length = 6){
$ret='C';
$pw='';
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$len = strlen($chars);
for ($i=0;$i<20;$i++)
        $pw .= substr($chars, rand(0, $len-1), 1);
// the finished password
$pw = str_shuffle($pw);
$pw=md5($pw);
$len = strlen($pw);
for ($i=0;$i<$length;$i++)
 $ret .= substr($pw, rand(0, $len-1), 1);
return strtoupper($ret);	
}

?>