<?php
class SettingsController extends Controller
{
    
	public function indexAction()
	{
        $model = new SettingsModel();
        $form = Call::form('Index');

        $countrysList = $model->getCountryList();

        if (isPost())
        {
            if (($form->isValid(allPost())) AND( (isset($form->data["email"])) OR (isset($form->data["password"])) AND (isset($form->data["password1"])) ))
            {
                if (Request::getParam('user')->password == md5($form->data['password']))
                {
                    $data=[];
                    if($form->data['password1']!=''){
                    $data['password'] = md5($form->data['password1']);}
                    if(isset($form->data['email'])){
                    $data['email'] = $form->data["email"];}
                    if($form->data['news']==1){
                    $data['newsletter'] = $form->data["news"];}
                    $model->setSettings(Request::getParam('user')->id, $data);
                    redirect(url('settings'));
                }
            }/*elseif((Request::getParam('user')->password == md5($form->data['password']))&&(isset($form->data['email']))){       }*/
            else
            {
                $this->view->error = printError($form->error, 'INDEX_ERROR_');
            }
        }

        $this->view->countrysList = $countrysList;
        $this->view->title = Lang::translate('INDEX_TITLE');
	}

    public function generalAction()
    {
        $model = new SettingsModel();

        if (isPost())
        {
            $data['realname'] = post('realname');
            $data['country'] = post('country');
            $data['city'] = post('city');
            $data['sex'] = post('sex', 'int');
            $data['mm'] = post('mm', 'int');
            $data['dd'] = post('dd', 'int');
            $data['yyyy'] = post('yyyy', 'int');
            $data['about'] = post('about');
            $tradelink = urldecode(post('tradelink'));
            
            if (preg_match('/'.preg_quote('/?partner=','/').'(.*)'.preg_quote('&amp;token=', '/').'/Us', $tradelink, $match)) {
                $partner = $match[1];
            }

            if (preg_match('/'.preg_quote('&amp;token=','/').'(.*)'.preg_quote('<<<eof', '/').'/Us',$tradelink.'<<<eof',$match)) {
                $token = $match[1];
            }
            
            if ($partner && $token) {
                $data['partner'] = $partner;
                $data['token'] = $token;
            }

            $model->setSettings(Request::getParam('user')->id, $data);

            $path = 'public/users/'.Request::getParam('user')->id.'/';
            remkdir($path);

            File::LoadImage($_FILES['file'], $path, 'avatar', 'jpg', null, 0, 2, 184, 184);
            File::LoadImage($_FILES['file'], $path, 'avatar_m', 'jpg', null, 0, 2, 64, 64);
            File::LoadImage($_FILES['file'], $path, 'avatar_s', 'jpg', null, 0, 2, 32, 32);

            redirect(url('settings','general'));
        }

        $this->view->countrysList = $model->getCountryList();
        $this->view->title = Lang::translate('GENERAL_TITLE');
    }

    public function favoritesAction()
    {
        $this->view->title = Lang::translate('FAVORITES_TITLE');
    }

    public function favorites_saveAction()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))
            error404();

        $model = new SettingsModel();

        $save['iPlayer'] = post('__iPlayer');
        $save['iTeam'] = post('__iTeam');
        $save['iGame'] = post('__iGame');
        $save['iRole'] = post('__iRole');
        $save['iMusic'] = post('__iMusic');
        $save['iFood'] = post('__iFood');
        $save['iSport'] = post('__iSport');

        $model->setSettings(Request::getParam('user')->id, $save);

        $status = '<div class="">'.Lang::translate('FAVORITES_SAVED').'</div>';

        $response['error'] = 0;
        $response['target_h']['#status'] = $status;

        echo json_encode($response);
        exit;
    }

    public function rigAction()
    {
        $this->view->title = Lang::translate('RIG_TITLE');
    }

    public function rig_saveAction()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))
            error404();

        $model = new SettingsModel();

        $save['videoCard'] = post('__videoCard');
        $save['soundCard'] = post('__soundCard');
        $save['cpu'] = post('__cpu');
        $save['ram'] = post('__ram');
        $save['hardDrive'] = post('__hardDrive');
        $save['os'] = post('__os');
        $save['headset'] = post('__headset');
        $save['mouse'] = post('__mouse');
        $save['mousepad'] = post('__mousepad');
        $save['keyboard'] = post('__keyboard');
        $save['monitor'] = post('__monitor');
        $save['iCase'] = post('__iCase');

        $model->setSettings(Request::getParam('user')->id, $save);

        $status = '<div class="">'.Lang::translate('RIG_SAVED').'</div>';

        $response['error'] = 0;
        $response['target_h']['#status'] = $status;

        echo json_encode($response);
        exit;
    }

    public function socialAction()
    {
        $this->view->title = Lang::translate('SOCIAL_TITLE');
    }

    public function social_saveAction()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))
            error404();

        $model = new SettingsModel();

        $save['facebook'] = post('__facebook');
        $save['steam'] = post('__steam');
        $save['twitch'] = post('__twitch');
        $save['twitter'] = post('__twitter');
        $save['youtube'] = post('__youtube');

        $model->setSettings(Request::getParam('user')->id, $save);

        $status = '<div class="">'.Lang::translate('SOCIAL_SAVED').'</div>';

        $response['error'] = 0;
        $response['target_h']['#status'] = $status;

        echo json_encode($response);
        exit;
    }

}
/* End of file */