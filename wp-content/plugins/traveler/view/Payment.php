
  <?php
  //$_REQUEST['r']= $_SESSION['post_id'];
  $price_details = $_SESSION['price_details'];
  
  
  ?>
   <div class="container pay_options" >
        <form action="../paynow" method="post" id="frmoption">


        <div class="row">
            <div class="col-lg-12 pay-option-header"><p>
                    <label class="pay-head">PAYMENT OPTIONS(CREDIT/DEBIT CARD)</label><br>
                        <label class="pay-amount"><?php echo $_SESSION['totalAmountPay']?></label>
                </p></div>
           </div>


        <div class="row payfirst">
            <div class="col-lg-1">
                <img class="line-icon" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>                
           
            <div class="col-lg-2">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>cards/visa.png">
            </div> 
            <div class="col-lg-8 ">
                <span>We accept all VISA cards issued by Sri Lankan banks </span>
            </div> 
            <div class="col-lg-1 cardcls">
                <input type="radio" name="cardType" id="visacard" >
            </div>
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                <img class="line-icon" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2 ">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/cards/master.png"/>
            </div>
            <div class="col-lg-8 ">
               <span>We accept all MASTER cards issued by Sri Lankan banks </span>
            </div>
            <div class="col-lg-1 cardcls">
              <input type="radio" name="cardType" id="mastercard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                <img class="line-icon" src="<?php echo IMG_PATH ?>/listicon.png"> 
            </div>
            <div class="col-lg-2 ">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/cards/ame.png"/>
            </div>
            <div class="col-lg-8 ">
               <span>We accept all AMERICAN EXPRESS cards issued by Sri Lankan Banks </span>
            </div>
             <div class="col-lg-1 cardcls">
              <input type="radio" name="cardType" id="amaricancard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                 <img class="line-icon" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2 ">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/cards/stb.png"/>
            </div>
            <div class="col-lg-8 ">
               <span>Standard Chartered Bank </span>
            </div>
             <div class="col-lg-1 cardcls">
               <input type="radio" name="cardType" id="standerdcard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                <img class="line-icon" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2  ">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/cards/hnb.png"/>
            </div>
            <div class="col-lg-8 ">
               <span>Hatton National Bank</span>
            </div>
             <div class="col-lg-1 cardcls">
                <input type="radio" name="cardType" id="hnbcard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                 <img class="line-icon" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2  ">
                <img class="img-responsive" src="<?php echo IMG_PATH ?>/cards/comm.png"/>
            </div>
            <div class="col-lg-8 ">
               <span>Commercial Bank</span>
            </div>
             <div class="col-lg-1 cardcls">
                <input type="radio" name="cardType" id="commercialcard" >
            </div>
            
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                 <img class="line-icon" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2 ">
                <img class="img-responsive"  src="<?php echo IMG_PATH ?>/cards/slb.png"/>
            </div>
            <div class="col-lg-8 ">
               <span>Seylan Bank </span>
            </div>
             <div class="col-lg-1 cardcls">
               <input type="radio" name="cardType" id="seylancard" >
            </div>
        </div>
        <div class="row payfirst">
            <div class="col-lg-1">
                 <img class="line-icon" src="<?php echo IMG_PATH ?>/listicon.png">
            </div>
            <div class="col-lg-2 ">
                <img class="img-responsive"  src="<?php echo IMG_PATH ?>/cards/sampath.png"/>
            </div>
            <div class="col-lg-8 ">
               <span>Sampath Bank Cards</span>
            </div>
            <div class="col-lg-1 cardcls">
              <input type="radio" name="cardType" id="sampath" >
            </div>
            
        </div>
        <div class="row tot-main">
           

                <div class="col-lg-5"><label class="totcls">TOTAL AMOUNT</label></div>
            <div class="col-lg-2" class="bank-service-charge-div"><label class="bank-service-charge-label"></label>
            </label></div>
                <div class="col-lg-2"><label class="totcls1 bank-net-label "> <?php echo $_SESSION['totalAmountPay']?></label></div>
            <div class="col-lg-1">=</div>
            <div class="col-lg-2"><label class="bank-net-label"></label></div>
            
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
