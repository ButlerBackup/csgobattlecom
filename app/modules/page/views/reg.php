<h1>{L:REG_TITLE}</h1>

<div>
    Before you register, you must know that your account will be getting processed by an admin, in which case it might, or might not be accepted. Therefore, once you have registered, in your profile add your “STEAM ID” so that we can access your information and see your eligibility. Once you have been verified, an admin will admit you to the site, where then you might add your Steam Trade URL and proceed to use our website. If you do not add your Steam ID to your profile, your account WILL be rejected and you will have to re-register again for another screening.
</div><br>



<?php

if ($this->error)
    echo '<div>'.$this->error.'</div>';


/*

<div class="formRow">

    <div class="formRowTitle">

        <?php echo Lang::translate('REG_CODE'); ?>:

    </div>

    <div class="formRowField">

        <input type="text" name="code" value="<?php echo post('code'); ?>">

    </div>

</div>

*/

?>

<form method="post" action="<?php echo url('page','reg'); ?>">

    <div class="formRow">

        <div class="formRowTitle">

            <?php echo Lang::translate('REG_NICKNAME'); ?>:

        </div>

        <div class="formRowField">

            <input type="text" name="nickname" value="<?php echo post('nickname'); ?>">

        </div>

    </div>



    <div class="formRow">

        <div class="formRowTitle">

            <?php echo Lang::translate('REG_EMAIL'); ?>:

        </div>

        <div class="formRowField">

            <input type="email" name="email" value="<?php echo post('email'); ?>">

        </div>

    </div>



    <div class="formRow">

        <div class="formRowTitle">

            <?php echo Lang::translate('REG_PASSWORD'); ?>:

        </div>

        <div class="formRowField">

            <input type="password" name="password" value="">

        </div>

    </div>



    <div class="formRow">

        <div class="formRowTitle">

            <?php echo Lang::translate('REG_PASSWORD_REPEAT'); ?>:

        </div>

        <div class="formRowField">

            <input type="password" name="password_re" value="">

        </div>

    </div>

<div class="formRow">

       <div class="formRowTitle">

            Please Enter Captcha

        </div>

        <div class="formRowField">
        <div class="capimg">
        <?php
		$random=rand(1,6);
		 $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$org = '';
 for ($i = 0; $i < 5; $i++) {
      $string[$i]= $characters[rand(0, strlen($characters) - 1)];
	  $org.=$string[$i];
 }
 $totallet=$random*5;
 $p=0;
 for($i=0;$i< $totallet;$i++)
 { 
	 if($i%$random==0)
	 {
		 $rotate=rand(0,20);
		 if($i%2==0)
		 {
			 $rotate='-'.$rotate.'deg';
		 }
		 else
		 {
			  $rotate='+'.$rotate.'deg';
		 }
		 ?>
		<span class="hidesr str<?php echo $random;  ?>" style="transform: rotate(<?php echo $rotate;?>);display:inline"><?php echo $string[$p];$p++; ?></span> 
	 <?php
	 }
	 else
	 { 
	 while( in_array( ($newrand = rand(1,6)), array($random) ) );
	 ?>
		<span class="hidesr str<?php echo $newrand;  ?>"><?php echo $characters[rand(0, strlen($characters) - 1)]; ?></span>  
	<?php }
 }
		
		 ?>
         </div>
         <input type="hidden" name="strcheck" value="<?php echo md5($org); ?>"/>
         


            <input type="text" name="captchastring" id="captchastring" value="">

        </div>

    </div>

    <div class="formRow">

        <div class="formRowTitle"></div>

        <div class="formRowField">

            <input type="checkbox" name="rules" value="yes">{L:REG_RULES_0}<a href="{URL:page/rules}" target="_blank">{L:REG_RULES_1}</a>

        </div>

    </div>



    <div class="formRow">

        <div class="formRowTitle"></div>

        <div class="formRowField">

            <input type="checkbox" name="terms" value="yes">{L:REG_TERMS_0}<a href="{URL:page/terms}" target="_blank">{L:REG_TERMS_1}</a>

        </div>

    </div>
    
     
    
     



    <div class="formRow">

        <div class="formRowTitle"></div>

        <div class="formRowField">

            <input type="submit" value="<?php echo Lang::translate('REG_SUBMIT'); ?>">

        </div>

    </div>

</form>

<style>
.hidesr
{
    display: none;
    padding: 2px;
    color: red;
    font-size: 18px;
    font-weight: bold;
    float: left;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
</style>