<?php

class ChatController extends Controller

{

    public function indexAction()

    {

        $model = new ChatModel();

        setSession('chat_ses', 0);



        $this->view->list = $model->getChatMessages('chat');

        $this->view->title = Lang::translate('INDEX_TITLE');
		
		$this->view->onlines= $model->getUserOnline();

          /*  $countUser = 0;



            while ($list = mysqli_fetch_object($listUserOnline)) {

                $userList .= '<li><a href="' . url($list->id) . '" target="_blank"><span>' . $list->nickname . '</span><span class="level-icon">' . $list->level . '</span></a></li>';

                $countUser++;

            } */

    }



    public function sendAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new ChatModel();



        $message['uid'] = Request::getParam('user')->id;

        $message['uName'] = filter(Request::getParam('user')->nickname);

        $message['message'] = post('__msg');

        $message['time'] = time();



        if (!empty($message['message']))

            $model->insert('chat', $message);



        $response['error'] = 0;



        echo json_encode($response);

        exit;

    }



    public function getAction()

    {

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))

            error404();



        $model = new ChatModel();

        $dialog = '';

        $userList = '';



        $lastMessageID = getSession('chat_lmid', false);

        $chatList = $model->getChatMessages('chat', 'ASC', $lastMessageID);



        if ($chatList) {

            foreach ($chatList as $value) {

                $msg = ' ' . $value['message'];

                if (strpos($msg, Request::getParam('user')->nickname) !== false)

                    $color = ' chat_your_msg';

                else

                    $color = false;



                $dialog .= '<div class="chat_message'.$color.'">'

                        .'<div class="chat_img"><a href="'.url($value['uid']).'" target="_blank"><img src="'.getAvatar($value['uid'], 's').'"></a></div>'

                        .'<div class="chat_text">'

                            .'<div><span class="chat_nickname" onclick="chatNickname(\''.$value['uName'].'\');">'.$value['uName'].'</span> <span class="chat_time">'.printTime($value['time']).'</span></div>'

                            .'<div>'.$value['message'].'</div>'

                        .'</div>'

                    .'</div>';

                setSession('chat_lmid', $value['id']);

            }

        }

        unset($chatList);



        /*

        if (time()%5 == 0 OR getSession('chat_ses') == 0) {

            $listUserOnline = $model->getUserOnline();

            $countUser = 0;



            while ($list = mysqli_fetch_object($listUserOnline)) {

                $userList .= '<li><a href="' . url($list->id) . '" target="_blank"><span>' . $list->nickname . '</span><span class="level-icon">' . $list->level . '</span></a></li>';

                $countUser++;

            }



            $response['userList'] = $userList;

            $response['countUser'] = $countUser;

        }

        */



        $response['error'] = 0;

        if ($dialog)

            $response['target_a']['#dialog'] = $dialog;



        setSession('chat_ses', 1);

        echo json_encode($response);

        exit;

    }
  public function useronlinerAction()
    {
		 $model = new ChatModel();
		$onlines= $model->getUserOnline();
		$cur_user_id = getSession('user', false);
		$newarc= '<div class="online1"><div class="hd">Who is Online</div></div>
<ul class="ulist">'; 
foreach($onlines as $user)
{ 

if($cur_user_id != $user['id']) {
  
$newarc.='<li class="listu">'.$user['nickname'].'<span class="addfriends"  style="cursor:pointer;">add friend</span></li>';
 } 

}

$newarc.='</ul>';
echo $newarc;
		exit;
	}
	
	public function updateuseronlineAction()
    {
	  $model = new ChatModel();
	  $cur_user_id = getSession('user', false);
	  $time = time();
	  $query .= "UPDATE `users` SET `dateLast` =  '".time()."'  WHERE `id` = '".$cur_user_id."';";
      $model->multiQuery($query);
	  exit;
	}
	
}

/* End of file */