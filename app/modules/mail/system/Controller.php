<?php
class MailController extends Controller
{
    
	public function indexAction()
	{
        $model = new MailModel();

        Pagination::calculate(get('page', 'int'), 10, $model->countDialog(Request::getParam('user')->id));
        $this->view->list = $model->selectDialog(Request::getParam('user')->id, Pagination::$start, Pagination::$end);
        $this->view->title = Lang::translate('INDEX_TITLE');
	}

    public function mailAction()
    {
        $model = new MailModel();
        $receiverID = Request::getUriOptions(0);

        $receiver = $model->getUserByID($receiverID);
        if (!$receiver->id OR $receiver->id == Request::getParam('user')->id)
            redirect(url('profile'));

        if ($receiver->id < Request::getParam('user')->id) {
            $uid1 = $receiver->id;
            $uid2 = Request::getParam('user')->id;
            $pos = 2;
        } else {
            $uid1 = Request::getParam('user')->id;
            $uid2 = $receiver->id;
            $pos = 1;
        }

        $dialog = $model->getDialogByUsers($uid1, $uid2);
        if (!$dialog->id) {
            $data['uid1'] = $uid1;
            $data['uid2'] = $uid2;
            $did = $model->addDialog($data);
        } else {
            $did = $dialog->id;
        }

        $hash = md5($uid1 . SALT . $uid2);
        $arr['did'] = $did;
        $arr['uid1'] = $uid1;
        $arr['uid2'] = $uid2;
        $arr['pos'] = $pos;

        //Create session hash
        setSession($hash, $arr);

        $this->view->list = $model->getMessages($did);
        $this->view->name = $receiver->nickname;
        $this->view->receiver = $receiver->id;
        $this->view->hash = $hash;
        $this->view->did = $did;
        $this->view->rightList = $model->selectDialog(Request::getParam('user')->id, 0, 7);
        $this->view->title = $receiver->nickname;
    }

    public function clearAction()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))
            error404();

        $model = new MailModel();
        $id = post('id', 'int');

        $dialog = $model->getDialogByID($id);

        if ($dialog->id && ($dialog->uid1 == Request::getParam('user')->id OR $dialog->uid2 == Request::getParam('user')->id)) {
            //$query = "UPDATE `dialog` SET `` = '' , `time` = '".time()."' WHERE `id` = '".$dialog['did']."';";
            //$model->multiQuery($query);
        }

        $response['error'] = 0;
        $response['target_h']['#dialog'] = '';

        echo json_encode($response);
        exit;
    }

    public function sendAction()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))
            error404();

        $model = new MailModel();
        $hash = post('__hash');
        $dialog = getSession($hash);

        $response['error'] = 0;

        if (!$dialog['did']) {
            $response['error'] = 'There is no dialogue';
            echo json_encode($response);
            exit;
        }


        $dialogCon = $model->getDialogByID($dialog['did']);
        if ($dialogCon->uid1 == Request::getParam('user')->id)
            $userId = $dialogCon->uid2;
        elseif ($dialogCon->uid2 == Request::getParam('user')->id)
            $userId = $dialogCon->uid1;

        $friendStatus = $model->friendsStatus(Request::getParam('user')->id, $userId);

        if ($friendStatus['ban'] == 1) {
            $response['target_a']['#dialog'] = '<div>'.Lang::translate('SEND_BAN').'</div>';

            echo json_encode($response);
            exit;
        }

        $message['did'] = $dialog['did'];
        $message['uid'] = Request::getParam('user')->id;
        $message['name'] = Request::getParam('user')->nickname;
        $message['message'] = post('__msg');
        $message['time'] = time();

        if (!empty($message['message'])) {
            if ($dialog['pos'] == 1)
                $pos = 2;
            else
                $pos = 1;

            $query = $model->getInsertQuery('messages', $message);
            $query .= "UPDATE `dialog` SET `countMsg$pos` = `countMsg$pos` +1 , `time` = '".time()."' WHERE `id` = '".$dialog['did']."';";
            $model->multiQuery($query);
        }

        echo json_encode($response);
        exit;
    }

    public function getAction()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))
            error404();

        $model = new MailModel();
        $hash = post('__hash');
        $dialog = getSession($hash);
        $messages = '';

        if (!$dialog['did']) {
            $response['error'] = 'There is no dialogue';
            echo json_encode($response);
            exit;
        }

        $data['countMsg'.$dialog['pos']] = '0';
        $model->update('dialog', $data, "`id` = '".$dialog['did']."'");

        $lastMessageID = getSession('mail_last_message' . $dialog['did'], false);
        $mailList = $model->getMessages($dialog['did'], 'ASC', $lastMessageID);

        if ($mailList) {
            foreach ($mailList as $value) {
                //$objVal = (object)$value;

                $messages .= '<div class="chat_message">'
                    .'<div class="chat_img"><a href="'.url($value['uid']).'" target="_blank"><img src="'.getAvatar($value['uid'], 's').'"></a></div>'
                    .'<div class="chat_text">'
                    .'<div><span class="chat_nickname">'.$value['name'].'</span> <span class="chat_time">'.printTime($value['time']).'</span></div>'
                    .'<div>'.$value['message'].'</div>'
                    .'</div>'
                    .'</div>';
                setSession('mail_last_message' . $dialog['did'], $value['id']);
            }
        }
        unset($chatList);

        $response['error'] = 0;
        $response['target_a']['#dialog'] = $messages;

        echo json_encode($response);
        exit;
    }

}
/* End of file */