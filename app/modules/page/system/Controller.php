<?php



class PageController extends Controller



{



	public function indexAction()



	{









        //$model = new PageModel();







        $this->view->title = Lang::translate('INDEX_TITLE');



        $this->setLayout("page_layout");



	}


    public function howtoplayAction(){

    }
    public function phpinfoAction(){

    }




    public function rulesAction()



    {



        //$model = new PageModel();



        $this->view->title = Lang::translate('RULES_TITLE');



    }







    public function termsAction()



    {



        //$model = new PageModel();



        $this->view->title = Lang::translate('TERMS_TITLE');



    }







    public function privacyAction()



    {



        //$model = new PageModel();



        $this->view->title = Lang::translate('TERMS_TITLE');



    }







    public function mainAction()



    {

		  

        $model = new PageModel();







        $act = Request::getUri(0);



        $id = intval(Request::getUri(1));







        if ($act == 'read' && $id > 0) {

			

			 

		    redirect(SITE_URL.'main/readblog?pid='.$id);

		   

			// echo SITE_URL.'main/readblog';

			  

			 

            $news = $model->getNews($id, Lang::$language);



            if (!$news)



                error404();



            $this->view->news = $content;// $news;



            $this->view->title = $title;//$news->name;



        } else {



            Pagination::calculate(get('page'), 10, $model->countNews(Lang::$language));



            $this->view->newsList = $model->getAllNews(Lang::$language, Pagination::$start, Pagination::$end);



            $this->view->title = Lang::translate('MAIN_TITLE');



        }



    }



    public function mapsAction()

    {

        $model = new PageModel();



        $this->view->list = $model->getMaps();

        $this->view->model = $model;

        $this->view->title = Lang::translate('MAIN_TITLE');

    }



    public function serversAction()



    {



        $model = new PageModel();







        $this->view->servers = $model->getServers();



        $this->view->title = Lang::translate('SERVERS_TITLE');



    }







    public function onlineAction()



    {



        $model = new PageModel();







        $this->view->list = $model->getUsersOnline();



        $this->view->title = Lang::translate('ONLINE_TITLE');



    }







    public function authAction()



    {



        $model = new PageModel();








        if (isPost()) {

            $email = post('email');

            $password = md5(post('password'));

            $user = $model->getUserByEP($email, $password);



            if ($user->id)



            {
                $modelProfile = new ProfileModel();
                $updateString = "`looking` = 0";
                $recId = $modelProfile->checkDiscoverRecord($user->id);

                if(!empty($recId->id)) {
                    $modelProfile->updateDiscoverRecord($recId->id, $updateString);
                }

                if(post("remember-me")==1) {
                    $cookie_live_time = 7 * 24 * 60 * 60;
                    setMyCookie('user', $user->id, time() + $cookie_live_time);
                }

                setSession('user', $user->id, false);



                redirect(url($user->id));



            } else {

                  $error="error";

				   setSession('login_error',"invalid");

              //  redirect(url());

			  

			  setMyCookie('login_error', "You have input the incorrect username and password, try again", time()+5);

			  redirect(url('page','index'));



            }



        }



    }



 public function imageverificationAction()



    {



        $model = new PageModel();

	$this->view->title = Lang::translate('captcha');

		 

	}



    public function regAction()



    {



        $model = new PageModel();







        if (isPost()) {



            $regCode = $model->getRegCode(post('code'));







            if ($regCode->id OR true) { // TODO забрати "OR true"



                $data['nickname'] = post('nickname');



                if (checkLenght(post('nickname'), 3, 16) && !$model->getUserByNickname(post('nickname')))



                    $data['nickname'] = post('nickname');



                else



                    $this->view->error = 'Incorrect "Nickname" or exist';







                if (checkEmail(post('email')) && !$model->getUserByEmail(post('email')))



                    $data['email'] = post('email');



                else



                    $this->view->error = 'Incorrect "E-mail" or exist';







                if (checkLenght(post('password'), 6, 20))



                    $data['password'] = md5(post('password'));



                else



                    $this->view->error = 'Incorrect "Password"';







                if (checkLenght(post('password_re'), 6, 20)) {



                    if (post('password') != post('password_re'))



                        $this->view->error = 'Passwords do not match';



                } else



                    $this->view->error = 'Incorrect "Repeat password"';







                if (post('rules') != 'yes')



                    $this->view->error = 'You must agree to Rules';







                if (post('terms') != 'yes')



                    $this->view->error = 'You must accept Terms and Conditions';

				

					

					 if (post('strcheck') != md5(post('captchastring')))



                        $this->view->error = 'Captcha Error';









                //$data['referral'] = $regCode->uid; // TODO ref system



                $data['dateLast'] = time();



                $data['dateReg'] = time();







                if (!$this->view->error) {



                    $uid = $model->insert('users', $data);







                    if ($uid) {



                        setSession('user', $uid, false);



                        //$model->deleteRegCode($regCode->id);

						

						

						



 $to =    $data['email']; 

 

 $message = '

 

Dear, '. $data['nickname'].'<br />

 

Thank you for registering to CSGOBattle.com, please wait for an admin to approve your account (1-2hr), after you will be able to access the site and be ready to play.<br />

 <br />

 Looking forward to seeing you on the site,<br />

<br />

Best regards, <br />

CSGOBattle.com Support Team. 

  

';

 

 

  $subject = 'New Registration'; 

 

  // $headers = 'From: vinu@webeteer.com'  . "\r\n" . 'Reply-To: webmaster@example.com'. "\r\n" . 'Content-type: text/html'. "\r\n" . 'X-Mailer: PHP/' . phpversion(); 



   



 

  

  

  

   $headers="MIME-Version: 1.0" . "\r\n";

   $headers.="Content-type:text/html;charset=utf-8" . "\r\n";

      $headers .= 'From: <info@csgo>' . "\r\n";  

	  

	//  mail($to, $subject, $message, $headers, 'support@webeteer.com'); 



// ri testing

$body=$message;

  //  $to='rijo@webeteer.com';

  $mail             = new PHPMailer();



    $mail->SMTPDebug = 1;



    $mail->IsSMTP();

    $mail->SMTPAuth   = true;                  // enable SMTP authentication

    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier

    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server

 //$mail->Host       =   "74.208.109.155";





    $mail->Port       = 465;                   // set the SMTP port for the GMAIL server



    $mail->Username   = "dummydevoleper@gmail.com";              // MAIL username

    $mail->Password   = "sandeepus";            // MAIL password





    $mail->From       = "info@csgobattle.com";

    $mail->FromName   = "CSGO";



    $mail->Subject    = 'New Registeration CSGO';



    $mail->Body       = $body;                      //HTML Body

    $mail->AltBody    = "CSGO"; // optional, comment out and test

    $mail->WordWrap   = 50; // set word wrap



    $mail->MsgHTML($body);



    $mail->AddAddress($to , "Contact");

    



    $mail->IsHTML(true); // send as HTML



    if(!$mail->Send()) {

        //echo $mail->ErrorInfo;

        return $mail->ErrorInfo;

echo 'notse';

    } else {

echo ' send';

	}



//ri ends

redirect(url($uid));

 

                    } else



                        $this->view->error = 'Error Registration';



                }



            } else



                $this->view->error = 'Incorrect "Registration code"';



        }

			





        $code = Request::getUriOptions()[0];



        if ($code)



            $_POST['code'] = $code;





 

 









        $this->view->title = Lang::translate('REG_TITLE');



    }



 	public function regsAction()



    {



        $model = new PageModel();







        if (isPost()) {



            $regCode = $model->getRegCode(post('code'));

			

	





            if ($regCode->id OR true) { // TODO забрати "OR true"



                $data['nickname'] = post('nickname');



                if (checkLenght(post('nickname'), 3, 16) && !$model->getUserByNickname(post('nickname')))



                    $data['nickname'] = post('nickname');



                else



                    $this->view->error = 'Incorrect "Nickname" or exist';







                if (checkEmail(post('email')) && !$model->getUserByEmail(post('email')))



                    $data['email'] = post('email');



                else



                    $this->view->error = 'Incorrect "E-mail" or exist';







                if (checkLenght(post('password'), 6, 20))



                    $data['password'] = md5(post('password'));



                else



                    $this->view->error = 'Incorrect "Password"';







                if (checkLenght(post('password_re'), 6, 20)) {



                    if (post('password') != post('password_re'))



                        $this->view->error = 'Passwords do not match';



                } else



                    $this->view->error = 'Incorrect "Repeat password"';







                if (post('rules') != 'yes')



                    $this->view->error = 'You must agree to Rules';







                if (post('terms') != 'yes')



                    $this->view->error = 'You must accept Terms and Conditions';







                //$data['referral'] = $regCode->uid; // TODO ref system



                $data['dateLast'] = time();



                $data['dateReg'] = time();







                if (!$this->view->error) {



                    $uid = $model->insert('users', $data);







                    if ($uid) {



                        setSession('user', $uid, false);



                        //$model->deleteRegCode($regCode->id);



                        redirect(url($uid));



                    } else



                        $this->view->error = 'Error Registration';



                }



            } else



                $this->view->error = 'Incorrect "Registration code"';



        }







        $code = Request::getUriOptions()[0];



        if ($code)



            $_POST['code'] = $code;



  

   $this->view->title = Lang::translate('REG_TITLE');

		

		



    }





    public function steamAction()



    {



        $model = new PageModel();







        incFile('modules/page/system/inc/OpenId.inc.php');



        $steamApi = "0426AC32C69FAF916BE374D15CA29B1D"; // новый ключ!!!







        $openid = new LightOpenID(SITE_URL.'page/steam');



        if (!$openid->mode) {



            $openid->identity = 'http://steamcommunity.com/openid/?l=english';



            redirect($openid->authUrl());



        } elseif ($openid->mode == 'cancel') {



            $errorMessage = 'User has canceled authentication!';



        } else {



            if ($openid->validate()) {



                $id = $openid->identity;



                $ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";



                preg_match($ptn, $id, $matches);







                $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$steamApi&steamids=$matches[1]";



                $json_object = file_get_contents($url);



                $json_decoded = json_decode($json_object);







                if ($json_decoded) {



                    $userData = $json_decoded->response->players[0];







                    if ($matches[1] == $userData->steamid && $userData->steamid) {



                        $isUser = $model->getUserBySteam($userData->steamid);







                        if ($isUser->id) {



                            setSession('user', $isUser->id, false);



                            redirect(url($isUser->id));



                        } else



                            $errorMessage = 'Не прикреплен Steam аккаунт';



                    } else



                        $errorMessage = 'Попробуйте еще раз позже';



                } else



                    $errorMessage = 'Попробуйте еще раз позже';







                unset($json_object, $json_decoded);



            } else



                $errorMessage = 'User is not logged in.';



        }







        setMyCookie('error', $errorMessage, time()+5);



        redirect(url());



    }



    



    public function recoveryAction()



    {



        $model = new PageModel();



        $msg = false;



        



        if (isPost()) {



            $post = allPost();



            



            if (isset($post['email'])) {



                if ($model->userExist($post['email'])) {



                    $hash = randomHash();



                    



                    if ($model->createRecoveryCode($post['email'], $hash)) {



                        



                        $message = "Dear,<br/>You requested to recovery Your password at <a href=\"".SITE_URL."\">".SITE_NAME."</a>.<br/>"



                                . "Please visit page by following link:<br/>"



                                . "<a href=\"".SITE_URL."/page/passwordReset/".$hash."\">".SITE_URL."/page/passwordReset/".$hash."</a><br/>"



                                . "Link will be accessible for 24 hours."



                                . "<br/><br/>"



                                . "Thanks for using our service,<br/>"



                                . "Best regards,<br/>Administration.";



                        



                        $headers = "MIME-Version: 1.0\r\n"



                                . "Content-type: text/html; charset=utf-8\r\n";



                        



                        if (mail($post['email'], "Password Recovery", $message, $headers))



                            $msg = "You will receive email at the <".$post['email']."> with link to page, where You could change your password. Thanks for using our service";



                        else



                            $msg = "Sorry, but we can't send email now. Please try later.";



                    } else {



                        $msg = "Sorry, but error occured when system creates recovery email. Maybe You already sent request to recover password. Please check your email or try again later.";



                    }



                    



                } else {



                    $msg = "Sorry, but user with provided email not registered in this system. Please try again.";



                }



            }



        }



        



        $model->deleteOldRecovery();



        $this->view->msg = $msg;



        $this->view->title = Lang::translate("RECOVERY_FORGOT_PASSWORD");



    }



    public function footerAction()



    {



        $model = new PageModel();

		 $this->view->title = Lang::translate('IMAGEBUILDER');

	}



    public function passwordResetAction()



    {



        if (isset(Request::getUri()[0])) {



            $model = new PageModel();



            if ($model->recoveryHashExist(Request::getUri()[0])) {



                $this->view->success = false;



                



                if (isPost()) {



                    $post = allPost();



                    



                    if (isset($post['email']) && isset($post['password']) && isset($post['password2'])) {



                        if ($post['password'] == $post['password2']) {



                            if(checkLenght($post['password'], 6, 20)) {



                                if ($model->recoveryHashExist(Request::getUri()[0], $post['email'])) {



                                    if($model->resetPassword($post['email'], $post['password'])) {



                                        



                                        $this->view->msg = "You have successfully changed password.";



                                        $this->view->success = true;







                                        $message = "Dear,<br/>Your account password at <a href=\"".SITE_URL."\">".SITE_NAME."</a> was changed.<br/>"



                                                . "New password is ".$post['password']."<br/>"



                                                . "Please do not share him!"



                                                . "<br/><br/>"



                                                . "Thanks for using our service,<br/>"



                                                . "Best regards,<br/>Administration.";







                                        $headers = "MIME-Version: 1.0\r\n"



                                                . "Content-type: text/html; charset=utf-8\r\n";







                                        if (mail($post['email'], "Password Reset", $message, $headers))



                                            $this->view->msg .= " Notification about password reset was sent to your email.";







                                    } else {



                                        $this->view->msg = "Something wrong. Please try again later.";



                                    }



                                } else {



                                    $this->view->msg = "Wrong email. Please check entered data";



                                }



                            } else {



                                $this->view->msg = "Allowed password length may be from 6 to 20 characters.";



                            }



                        } else {



                            $this->view->msg = "Passwords aren't similar! Try again";



                        }



                    } else {



                        $this->view->msg = "You must fill all fields! Try again";



                    }



                }



                



                $model->deleteOldRecovery();



                $this->view->langPars = true;



                $this->view->hash = Request::getUri()[0];



                $this->view->title = Lang::translate("PASSWORD_RESET_TITLE");



            } else {



                setMyCookie('error', "Wrong password recovery code.", time()+5);



                redirect(url('page','recovery'));



            }



        } else {



            redirect(url());



        }



    }



function addcommentAction()

{

$model = new PageModel();

	$comment=  post('comments');

	$postid=  post('postid');



	$commentid = $model->postcomment($comment,$postid);





}





}



/* End of file */