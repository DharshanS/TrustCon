
  <?php
  //$_REQUEST['r']= $_SESSION['post_id'];
  $price_details = $_SESSION['price_details'];
  
  
  ?>
   <div class="container pay_options" >
        <form action="../paynow" method="post" id="frmoption">
        <div class="row clsheadopt">
            <div class="col-lg-12 pay-option-header"><h3>PAYMENT OPTION</h3></div>
            <div class="col-lg-12 pay-option-header-two"><h5>TOTAL TO BE PAID IS LKR <?php echo $_SESSION['totalAmountPay']?></h5></div>
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                
            </div>
            <div class="col-lg-12 paycard-type">
                <span class="clsdebit">CREDIT/DEBIT CARD </span>
            </div>

        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>                
           
            <div class="col-lg-2 imgcls">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/visa.png">
            </div> 
            <div class="col-lg-8 cardcls">
                <span>We Accept All VISA Cards Issued by Sri Lankan Banks </span>
            </div> 
            <div class="col-lg-1 cardcls">
                <input type="radio" name="cardType" id="visacard" >
            </div>
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/listicon.png"> 
            </div>
            <div class="col-lg-2 imgcls">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/master.png"/>
            </div>
            <div class="col-lg-8 cardcls">
               <span>We Accept All MASTER Cards Issued by Sri Lankan Banks </span>
            </div>
            <div class="col-lg-1 cardcls">
              <input type="radio" name="cardType" id="mastercard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/listicon.png"> 
            </div>
            <div class="col-lg-2 imgcls">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/amarican.png"/>
            </div>
            <div class="col-lg-8 cardcls">
               <span>We Accept All AMERICAN EXPRESS Cards Issued by Sri Lankan Banks </span>
            </div>
             <div class="col-lg-1 cardcls">
              <input type="radio" name="cardType" id="amaricancard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                 <img class="img-responsive" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2 imgcls">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/standerd.png"/>
            </div>
            <div class="col-lg-8 cardcls1">
               <span>Standard Chartered Bank </span>
            </div>
             <div class="col-lg-1 cardcls">
               <input type="radio" name="cardType" id="standerdcard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/listicon.png"> 
            </div>
            <div class="col-lg-2 imgcls imgclsimg">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/hnb.png"/>
            </div>
            <div class="col-lg-8 cardcls2">
               <span>Hatton National Bank</span>
            </div>
             <div class="col-lg-1 cardcls">
                <input type="radio" name="cardType" id="hnbcard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                 <img class="img-responsive" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2 imgcls imgclsimg">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/commercial.png"/>
            </div>
            <div class="col-lg-8 cardcls2">
               <span>Commercial Bank</span>
            </div>
             <div class="col-lg-1 cardcls">
                <input type="radio" name="cardType" id="commercialcard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                 <img class="img-responsive" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2 imgcls">
                <img class="img-responsive"  src="<?php echo IMG_PATH ?>/seylan.png"/>
            </div>
            <div class="col-lg-8 cardcls2">
               <span>Seylan Bank </span>
            </div>
             <div class="col-lg-1 cardcls">
               <input type="radio" name="cardType" id="seylancard" >
            </div>
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                 <img class="img-responsive" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2 imgcls imgclsimg">
                <img class="img-responsive"  src="<?php echo IMG_PATH ?>/sampath.png"/>
            </div>
            <div class="col-lg-8 cardcls2">
               <span>Sampath Bank Cards</span>
            </div>
            <div class="col-lg-1 cardcls">
              <input type="radio" name="cardType" id="sampathcard" >
            </div>
            
        </div>
        <div class="row totmain">
           

                <div class="col-lg-6"><label class="totcls">TOTAL AMOUNT</label></div>
            <div class="col-lg-2"><input type="text" value="348.00" placeholder="charge"></div>
                <div class="col-lg-4"><label class="totcls1"> <?php echo $_SESSION['totalAmountPay']?></label></div>
            
        </div>
        <div class="row conform-book-btns">
            <div class="col-lg-4">
            </div>
            <div class="col-lg-3 backbtncls" >
                <div class="backbtn img-responsive"></div>
                
            </div>
            <div class="col-lg-5 imgclsimg">
                <div class="mkpayment img-responsive">


                    <button type="submit" class="mkpayment make-pay-bt">

                    </button>



                </div>

            </div>
            </div>

            <div class="col-lg-12 pay-botom-label">
                We use the highest secure payment gateway to process your transaction.
            </div>    

    </form>
    </div>
