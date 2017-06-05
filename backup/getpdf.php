<?php
$html = '';
$pdfdata=isset($_POST['pdfdata'])?$_POST['pdfdata']:'';
if($pdfdata!=''){
$pdfdata=base64_decode($pdfdata);
}
include("mpdf60/mpdf.php");
$mpdf=new mPDF('c'); 

$mpdf->WriteHTML($pdfdata);
$mpdf->Output('Letter Of Confirmation.pdf','D'); exit;
?>
