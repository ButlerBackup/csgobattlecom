<?php



class AdminController extends Controller

{

    public function indexAction()

    {

        $model = new AdminModel();



        $this->view->countVerifyUsers = $model->countVerifyUsers();

        $this->view->title = Lang::translate('INDEX_TITLE');

    }



    /*---------- News ----------*/



    public function newsAction()

    {

        $model = new AdminModel();



        $this->view->list = $model->getNews();

        $this->view->title = Lang::translate('NEWS_TITLE');

    }



    public function lang_newsAction()

    {

        $model = new AdminModel();

        $form = Call::form('Lang_news');



        $news = $model->getNewsByID(Request::getUri()[0]);



        if (!$news->id)

            error404();



        if (isPost()) {

            $dataPost = array(

                'name' => post('name'),

                'lang' => 'en',

                'text' => post('text')

            ); // allPost()

            $lnid = post('lnid', 'int');



            if ($form->isValid($dataPost)) {

                $data = $form->data;

                $data['nid'] = $news->id;

                $data['uid'] = Request::getParam('user')->id;

                $data['time'] = time();



                if ($lnid) {

                    $model->update('news_lang', $data, "`id` = '$lnid'");

                    setNotice(Lang::translate('LANG_NEWS_EDITED'));

                } else {

                    $id = $model->insert('news_lang', $data);

                    $lnid = $id;

                    if ($id)

                        setNotice(Lang::translate('LANG_NEWS_ADDED'));

                }



                $dataImg['path'] = 'public/news/';

                $dataImg['new_name'] = $lnid;

                $dataImg['resize'] = 2;

                $dataImg['mkdir'] = true;

                $dataImg['min_width'] = 600;

                $dataImg['min_height'] = 400;



                if ($_FILES['image']['name']) {

                    $f = File::LoadImg($_FILES['image'], $dataImg);

                }

            } else {

                setNotice(Lang::translate('SOME_ERROR'));

            }

            //redirect(url('admin', 'lang_news', $news->id));

        }



        $this->view->list = $model->getLangNewsList($news->id);

        $this->view->news = $news;

        $this->view->title = $news->name;

    }



    public function act_lang_newsAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new AdminModel();



        $lang = $model->getLangNewsByID(post('lnid', 'int'));



        if ($lang->id) {

            $act = post('act');



            if ($act == 'delete') {

                $model->delete('news_lang', "`id` = '$lang->id'");

                $response['target_h']['#n_'.$lang->id] = '-';

                $response['target_h']['#status'] = Lang::translate('LANG_NEWS_DELETED');

            } elseif ($act == 'edit') {

                $response['target_v']['#lnid'] = $lang->id;

                $response['target_v']['#name'] = reFilter($lang->name);

                $response['target_v']['#lang'] = reFilter($lang->lang);

                $response['target_v']['#text'] = reFilter($lang->text);

                $response['target_h']['#preview'] = bb($lang->text);

            }

        } else {

            $response['target_h']['#status'] = Lang::translate('LANG_NEWS_NOT_FOUND');

        }



        $response['error'] = 0;

        echo json_encode($response);

        exit;

    }



    public function add_newsAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new AdminModel();



        $nid = post('__nid', 'int');

        $name = post('__name');



        if ($name) {

            if ($nid) {

                $news = $model->getNewsByID($nid);



                if ($news) {

                    $data['name'] = $name;

                    $model->update('news', $data, "`id` = '$nid'");

                    $response['target_v']['#nid'] = 0;

                    $response['target_v']['#name'] = '';

                    $response['target_h']['#status'] = Lang::translate('NEWS_ADD_NEWS_EDITED');

                } else

                    $response['target_h']['#status'] = Lang::translate('NEWS_ADD_NEWS_NOT_FOUND');

            } else {

                $data['uid'] = Request::getParam('user')->id;

                $data['name'] = $name;

                $data['time'] = time();

                $id = $model->insert('news', $data);



                if ($id) {

                    $response['target_v']['#nid'] = 0;

                    $response['target_v']['#name'] = '';



                    $response['target_p']['#news_list'] = '<div id="n_'.$id.'">'

                        .'<a href="'.url('admin', 'lang_news', $id).'">'.$name.'</a>'

                        .' (<span class="c_red">'.Lang::translate('NEWS_HIDDEN').'</span>)';

                    if (Request::getRole() == 'admin') {

                        $response['target_p']['#news_list'] .= ' - <a onclick="'.ajaxLoad(url('admin', 'act_news'), 'act_news', 'act:approve|id:'.$id).'">'.Lang::translate('NEWS_APPROVE_0').'</a>'

                            .' | <a onclick="'.ajaxLoad(url('admin', 'act_news'), 'act_news', 'act:delete|id:'.$id).'">'.Lang::translate('NEWS_DELETE').'</a>';

                    }

                    $response['target_p']['#news_list'] .= '</div>';

                    $response['target_h']['#status'] = Lang::translate('NEWS_ADD_NEWS_ADDED');

                } else

                    $response['target_h']['#status'] = Lang::translate('NEWS_ADD_NEWS_NOT_ADDED');

            }

        } else

            $response['target_h']['#status'] = Lang::translate('NEWS_ADD_NEWS_EMPTY_NAME');



        $response['error'] = 0;

        echo json_encode($response);

        exit;

    }



    public function act_newsAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new AdminModel();



        $news = $model->getNewsByID(post('id', 'int'));

        if ($news->id) {

            $act = post('act');



            if ($act == 'delete') {

                $model->delete('news', "`id` = '$news->id'");

                $model->delete('news_lang', "`nid` = '$news->id'");

                $response['target_h']['#n_'.$news->id] = '-';

                $response['target_h']['#status'] = Lang::translate('NEWS_ACT_NEWS_DELETED');

            } elseif ($act == 'approve') {

                if ($news->status == 1)

                    $data['status'] = 0;

                else

                    $data['status'] = 1;

                $data['uid'] = Request::getParam('user')->id;

                $data['time'] = time();



                $model->update('news', $data, "`id` = '$news->id'");

                $response['target_h']['#n_'.$news->id] = '<a href="'.url('admin', 'lang_news', $news->id).'">'.$news->name.'</a>';



                if ($data['status'] == 1)

                    $response['target_h']['#n_'.$news->id] .= ' (<span class="c_green">'.Lang::translate('NEWS_SHOWN').'</span>)';

                else

                    $response['target_h']['#n_'.$news->id] .= ' (<span class="c_red">'.Lang::translate('NEWS_HIDDEN').'</span>)';



                if (Request::getRole() == 'admin') {

                    $response['target_h']['#n_'.$news->id] .= ' - <a onclick="'.ajaxLoad(url('admin', 'act_news'), 'act_news', 'act:approve|id:'.$news->id).'">'.Lang::translate('NEWS_APPROVE_0').'</a>'

                        .' | <a onclick="'.ajaxLoad(url('admin', 'act_news'), 'act_news', 'act:delete|id:'.$news->id).'">'.Lang::translate('NEWS_DELETE').'</a>';

                }



                $response['target_h']['#status'] = Lang::translate('NEWS_ACT_NEWS_APPROVE_'.$data['status']);

            }

        } else {

            $response['target_h']['#status'] = Lang::translate('NEWS_ADD_NEWS_NOT_FOUND');

        }



        $response['error'] = 0;

        echo json_encode($response);

        exit;

    }



    /*---------- Verify ----------*/



    public function verify_usersAction()

    {

        $model = new AdminModel();



        $this->view->list = $model->getVerifyUsers();
		
		 
		

        $this->view->title = Lang::translate('INDEX_TITLE');

    }



    public function verify_users_submitAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new AdminModel();



        $data['role'] = 'user';

        $id = post('id', 'int');



        $model->setVerifyUser($id, $data);



        $response['error'] = 0;

        $response['target_h']['#verify' . $id] = Lang::translate('VERIFY_USERS_SUBMITTED');
		
		
		
		/*-------------USER DETAILS------------*/
		$userdetails = $model->getUserByID($id);
		
		$to =  $userdetails->email ;
		$user_nikname =  $userdetails->nickname ; 
		 
		
		/*-------------USER DETAILS------------*/
 
		$message = '
		
		Dear, '. $user_nikname.'<br />
		
		<br>
		You are now verified to use CSGOBattle.com. You may join ladders and challenge other players for skins. Please follow all of our rules to ensure the best possible experience. Welcome to CSGOBattle!<br>
		<br>
		Best regards, <br>
		CSGOBattle.com Support Team 
		
		';
		
		
		$subject = 'User Verification'; 
		$headers = "MIME-Version: 1.0\r\n"
		. "Content-type: text/html; charset=utf-8\r\n";
		
		//mail($to, $subject, $message, $headers);
		
		//mail new s
		$body=$message;
  //  $to='rijo@webeteer.com';
  $mail             = new PHPMailer();

    $mail->SMTPDebug = 1;

    $mail->IsSMTP();
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
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

    } else {

	}
		
		//mail new ends
	  
	  
        echo json_encode($response);

        exit;

    }



    public function verify_users_rejectAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new AdminModel();



        $data['role'] = 'claim';

        $id = post('id', 'int');



        //$model->setVerifyUser($id, $data);

        $model->delete('users', "`id` = '$id'");



        $response['error'] = 0;

        $response['target_h']['#verify' . $id] = Lang::translate('VERIFY_USERS_REJECTED');

 
        echo json_encode($response);

        exit;

    }



    public function disputesAction()

    {

        $model = new AdminModel();



        $this->view->disputes = $model->getDisputes();

        $this->view->title = "DISPUTES_TITLE";

    }



    public function serversAction()

    {

        $model = new AdminModel();



        if(Request::getUri()[0]) {

            switch(Request::getUri()[0]) {

                case "add":

                    $response['error'] = 0;



                    if(isPost()) {

                        $post = allPost();



                        //if () {}



                        if(!empty($post['__name']) && !empty($post['__addr']) && !empty($post['__pic'])) {

                            $data['name'] = $post['__name'];

                            $data['addr'] = $post['__addr'];

                            $data['pic'] = $post['__pic'];



                            $response['error'] = !$model->addServer($data);

                        } else {

                            $response['error'] = Lang::translate("SERVERS_ADD_EMPTY_FIELDS");

                        }

                    } else {

                        $response['error'] = Lang::translate("SERVERS_ADD_EMPTY_POST");

                    }



                    echo json_encode($response);

                    exit;

                case "delete":

                    $response['error'] = 0;



                    if(!empty(Request::getUri()[1])) {

                        $response['error'] = !$model->deleteServer(Request::getUri()[1]);

                    } else {

                        $response['error'] = Lang::translate("SERVERS_DELETE_EMPTY_REQUEST");

                    }



                    echo json_encode($response);

                    exit;

                case "edit":

                    $response['error'] = 0;



                    if(!empty(Request::getUri()[1])) {

                        $server = $model->getServer(Request::getUri()[1]);



                        if($server) {

                            $response['id'] = $server->id;

                            $response['name'] = $server->name;

                            $response['addr'] = $server->addr;

                            $response['pic'] = $server->pic;

                        } else

                            $response['error'] = Lang::translate("SERVERS_EDIT_WRONG_SERVER");

                    } else {

                        $response['error'] = Lang::translate("SERVERS_EDIT_EMPTY_REQUEST");

                    }



                    echo json_encode($response);

                    exit;

                case "save":

                    $response['error'] = 0;



                    if(!empty(Request::getUri()[1]) && isPost()) {

                        $post = allPost();



                        if(!empty($post['__name']) && !empty($post['__addr']) && !empty($post['__pic'])) {

                            $data['name'] = $post['__name'];

                            $data['addr'] = $post['__addr'];

                            $data['pic'] = $post['__pic'];



                            $response['error'] = !$model->editServer(Request::getUri()[1], $data);

                        } else {

                            $response['error'] = Lang::translate("SERVERS_SAVE_EMPTY_FIELDS");

                        }

                    } else {

                        $response['error'] = Lang::translate("SERVERS_SAVE_EMPTY_POST");

                    }



                    echo json_encode($response);

                    exit;

                case "get":

                    $response['error'] = 0;

                    $response['target_h']['#servers'] = "";



                    $servers = $model->getServers();



                    if(count($servers) > 0) {

                        foreach($servers as $server){

                            $response['target_h']['#servers'] .= '<div>'

                                . '<div>#'.$server->id.'. '.$server->name.'</div>'

                                . '<div class="font-sm c_green">'.$server->addr.'</div>'

                                . '<div class="font-sm c_red">'.$server->pic.'</div>'

                                . '<div><textarea class="serv-code"><a href="'.$server->addr.'"><img src="'.$server->pic.'" alt="'.$server->name.'"></a></textarea></div>'



                                . '<div>'

                                    . '<button onclick="editServer('.$server->id.');">'.Lang::translate('SERVERS_EDIT').'</button>'

                                    . '<button onclick="delServer('.$server->id.');">'.Lang::translate('SERVERS_DELETE').'</button>'

                                . '</div>'

                            . '</div>';

                        }

                    } else {

                        $response['target_h']['#servers'] = Lang::translate("SERVERS_NO_SERVER");

                    }



                    echo json_encode($response);

                    exit;

                default:

                    echo json_encode(array("error" => Lang::translate("SERVERS_WRONG_REQUEST")));

                    exit;

            }

        }



        $this->view->title = Lang::translate("SERVERS_TITLE");

    }



    /*---------- Users ----------*/



    public function usersAction()

    {

        $model = new AdminModel();



        $this->view->title = Lang::translate('USERS_TITLE');

    }



    public function search_usersAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new AdminModel();

        $response['target_h']['#listing'] = '';

        $response['error'] = 0;



        $uid = post('__uid', 'int');

        $nickname = post('__nickname');

        $steamid = post('__steamid', 'int');

        $role = post('__role');

        $page = post('page');



        $count = $model->countSearchUsers($uid, $nickname, $steamid, $role);



        Pagination::calculate($page, 10, $count);



        $result = $model->searchUsers($uid, $nickname, $steamid, $role, Pagination::$start, Pagination::$end);



        while ($list = mysqli_fetch_object($result))

        {

            if ($list->role == 'ban' && ($list->banRange == '0' OR ($list->banRange + $list->banDate) > time())) {

                if ($list->banRange == '0')

                    $banTime = '('.Lang::translate('USERS_BAN_FOREVER').')';

                else

                    $banTime = '('.Lang::translate('USERS_BAN_TO').' '.printTime($list->banRange + $list->banDate, "H:i / m.d.Y").')';



                $banned = '<div class="usersBanned">'.Lang::translate('USERS_BANNED').' '.$banTime.'</div>';

                $banned .= '<div class="usersReason">'.$list->banComment.'</div>';

            } else

                $banned = '';



            if ($list->role == 'moder' OR $list->role == 'admin')

                $role = ' <span class="usersRole">('.$list->role.')</span>';

            else

                $role = '';



            if ($list->role == 'user' OR $list->role == 'claim')

                $btn = '<dib class="usersBtn btn" onclick="'.ajaxLoad(url('admin','ban'), 'process', 'id:'.$list->id.'|height:\'+winH()+\'|width:\'+winW()+\'', 'openPopup').'">'.Lang::translate('USERS_BAN').'</dib>';

            else

                $btn = '';



            $response['target_h']['#listing'] .= '<div class="usersRow">'

                .'<div class="usersImage"><a href="'.url($list->id).'" target="_blank"><img src="'.getAvatar($list->id, 'm').'"></a></div>'

                .'<div class="usersInfo">'

                .'<div class="usersName"><a href="'.url($list->id).'">'.$list->nickname.'</a><span class="level-icon">'.$list->level.'</span> <span>ID:'.$list->id.'</span>'.$role.'</div>'

                .'<div class="usersRes"></div>'

                .'<div class="usersAction">'

                .'<div class="usersActionEl">'

                .$banned

                .$btn

                .'</div>'

                .'</div>'

                .'</div>'

                .'</div>';

        }



        $ajaxPag['href'] = '#';

        $ajaxPag['url'] = url('admin','search_users');

        $ajaxPag['permit'] = 'process';

        $ajaxPag['fields'] = '#uid!|#nickname!|#steamid!|#role!|';

        $response['target_h']['#listing'] .= '<div class="pagin">'.Pagination::ajaxPagination(2, 'span', $ajaxPag).'</div>';



        echo json_encode($response);

        exit;

    }



    public function banAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new AdminModel();



        $user = $model->getUserByID(post('id', 'int'));



        if ($user->id && ($user->role == 'user' OR $user->role == 'claim')) {

            $popup['winH'] = post('height', 'int');

            $popup['winW'] = post('width', 'int');

            $popup['popupH'] = 450;

            $popup['popupW'] = 450;



            $html = '<div>'

                .'<div class="usersImage"><a href="'.url($user->id).'" target="_blank"><img src="'.getAvatar($user->id, 'm').'"></a></div>'

                .'<div class="usersInfo">'

                .'<div class="usersName"><a href="'.url($user->id).'">'.$user->nickname.'</a><span class="level-icon">'.$user->level.'</span> <span>ID:'.$user->id.'</span></div>'

                .'</div>'

                .'<div class="usersBanBody">'

                .'<div id="status"></div>'

                .'<div>'

                .'<span class="inputText">'.Lang::translate('USERS_RANGE').':</span> '

                .'<select id="banRange">'

                .'<option value="1800">'.Lang::translate('BAN_HALF_HOUR').'</option>'

                .'<option value="3600">'.Lang::translate('BAN_HOUR').'</option>'

                .'<option value="43200">'.Lang::translate('BAN_TWELVE_HOUR').'</option>'

                .'<option value="86400">'.Lang::translate('BAN_DAY').'</option>'

                .'<option value="518400">'.Lang::translate('BAN_SIX_DAYS').'</option>'

                .'<option value="1036800">'.Lang::translate('BAN_TWELVE_DAYS').'</option>'

                .'<option value="2592000">'.Lang::translate('BAN_MONTH').'</option>'

                .'<option value="0">'.Lang::translate('BAN_FULL').'</option>'

                .'</select>'

                .'</div>'

                .'<div>'

                .'<div>'.Lang::translate('USERS_COMMENT').':</div>'

                .'<textarea id="banComment" style="height: 230px; width: 100%;"></textarea>'

                .'</div>'

                .'<div>'

                .'<div class="btn" onclick="'.ajaxLoad(url('admin','set_ban'), 'process', '#banRange!|#banComment!|id:'.$user->id).'">'.Lang::translate('USERS_PROCESS').'</div>'

                .'</div>'

                .'</div>'

                .'</div>';



            $response['target_h']['#popup'] = PopupInc::SimplePopup($html, $popup);

        }



        $response['error'] = 0;

        echo json_encode($response);

        exit;

    }



    public function set_banAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new AdminModel();



        $id = post('id', 'int');



        $user = $model->getUserByID(post('id', 'int'));



        if ($user->id && $user->role == 'user') {



            $data['role'] = 'ban';

            $data['banDate'] = time();

            $data['banRange'] = post('__banRange', 'int');

            $data['banComment'] = post('__banComment');



            if (!$data['banRange'])

                $data['banRange'] = "0";



            $model->update('users', $data, "`id` = '$user->id' LIMIT 1");

            $response['target_h']['#status'] = Lang::translate('SET_BAN_BANNED');

        }



        $response['error'] = 0;



        echo json_encode($response);

        exit;

    }



    public function changeRoleAction()

    {

        $response['error'] = 0;

        if (Request::getUri()[0]) {

            if (isPost()) {

                if (Request::getRole() == 'moder' && post('__role'.Request::getUri()[0]) != 'user' ) {

                    $response['error'] = "ERROR_SET_ROLE_PERMISSION";

                } else {

                    $model = new AdminModel();

                    if (!$model->changeRole(Request::getUri()[0], post('__role'.Request::getUri()[0])))

                        $response['error'] = "ERROR_SET_ROLE";

                }

            }

        }



        echo json_encode($response);

        exit;

    }



    /*---------- Guests ----------*/



    public function guestsAction()

    {

        $model = new AdminModel();



        $this->view->online24h = $model->countGuests("`time` > '".(time()-24*3600)."'");

        $this->view->google = $model->getGuests('browser', 'google');

        $this->view->bing = $model->getGuests('browser', 'bing');

        $this->view->list = $model->getGuestsOnline();

        $this->view->title = Lang::translate('GUESTS_TITLE');

    }



    /*---------- Userstat ----------*/



    public function userstatAction()

    {

        $model = new AdminModel();



        $this->view->count = $model->countUsers();

        $this->view->count24h = $model->countUsers("`dateLast` > '".(time()-24*3600)."'");

        $this->view->list = $model->getUsersOnline();

        $this->view->title = Lang::translate('USERSTAT_TITLE');

    }

}

/* End of file */