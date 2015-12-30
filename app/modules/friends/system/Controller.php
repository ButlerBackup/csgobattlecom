<?php
class FriendsController extends Controller
{    
    public function indexAction()
    {
        $model = new FriendsModel();


        Pagination::calculate(get('page', 'int'), 15, $model->countFriends(Request::getParam('user')->id, false, 1, 0, false));

        $this->view->friends = $model->getFriends(Request::getParam('user')->id, false, 1, 0, false, Pagination::$start, Pagination::$end);
        $this->view->title = Lang::translate('INDEX_TITLE');

    }
    
    public function onlineAction()
    {
        $model = new FriendsModel();
        
        Pagination::calculate(get('page', 'int'), 15, $model->countFriends(Request::getParam('user')->id, false, 1, 0, true));
        
        $this->view->online = $model->getFriends(Request::getParam('user')->id, false, 1, 0, true, Pagination::$start, Pagination::$end);
        $this->view->title = Lang::translate('ONLINE_TITLE');
    }
    
    public function incomingAction()
    {
        $model = new FriendsModel();
        
        Pagination::calculate(get('page', 'int'), 15, $model->countFriends(Request::getParam('user')->id, 'in', 0, 0, false));
        
        $this->view->incoming = $model->getFriends(Request::getParam('user')->id, 'in', 0, 0, false, Pagination::$start, Pagination::$end);
        $this->view->title = Lang::translate('INCOMING_TITLE');
    }
    
    public function outgoingAction()
    {
        $model = new FriendsModel();
        
        Pagination::calculate(get('page', 'int'), 15, $model->countFriends(Request::getParam('user')->id, 'out', 0, 0, false));
        
        $this->view->outgoing = $model->getFriends(Request::getParam('user')->id, 'out', 0, 0, false, Pagination::$start, Pagination::$end);
        $this->view->title = Lang::translate('OUTGOING_TITLE');
    }
    
    public function blacklistAction()
    {
        $model = new FriendsModel();
        
        Pagination::calculate(get('page', 'int'), 15, $model->countFriends(Request::getParam('user')->id, 'out', 0, 1, false));
        
        $this->view->blacklist = $model->getFriends(Request::getParam('user')->id, 'out', 0, 1, false, Pagination::$start, Pagination::$end);
        $this->view->title = Lang::translate('BLACKLIST_TITLE');
    }
    
    public function sendRequestAction()
    {
        $response['error'] = 'Wrong data provided to process adding to friends list.';

        if (isPost()) {
            $post = allPost();
            $model = new FriendsModel();
            
            if (isset($post['pid'])) {
                if ($model->userExist($post['pid'])) {
                    $status = $model->friendsStatus(Request::getParam('user')->id, $post['pid']);
                    if (empty($status)) {
                        if ($model->processFriend('sendFriendsRequest', Request::getParam('user')->id, $post['pid'])) {
                            $response['error'] = 0;
                            $response['target_h']['#request'] = 'Your request was sent.';
                        } else
                            $response['error'] = 'Error occurs while adding selected user to your friends list. Please try later.';
                    } else {
                        if (!$status['status'] && $status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You banned this user.';
                            else
                                $response['error'] = 'You is banned by this user.';
                        } elseif (!$status['status'] && !$status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You have already sent request.';
                            else
                                $response['error'] = 'User has already sent request to you. Please refresh page.';
                        } elseif ($status['status'] && !$status['ban'])
                            $response['error'] = 'You and this user are friends.';
                    }
                }
            }
        }

        echo json_encode($response);
        exit;
    }
    
    public function deleteFriendAction()
    {
        $response['error'] = 'Wrong data provided to process deleting user from friends list.';

        if (isPost()) {
            $post = allPost();
            $model = new FriendsModel();
            
            if (isset($post['pid'])) {
                if ($model->userExist($post['pid'])) {
                    $status = $model->friendsStatus(Request::getParam('user')->id, $post['pid']);
                    if (empty($status)) {
                        $response['error'] = "Information about your relationships with this user not found.";
                    } else {
                        if (!$status['status'] && $status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You banned this user.';
                            else
                                $response['error'] = 'You is banned by this user.';
                        } elseif (!$status['status'] && !$status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You have already sent request to add this user to friends.';
                            else
                                $response['error'] = 'User has already sent request to add you to his friends list. You can\'t do requested operation.';
                        } elseif ($status['status'] && !$status['ban'])
                            if ($model->processFriend('deleteFriend', Request::getParam('user')->id, $post['pid'])) {
                                $response['error'] = 0;
                                $response['target_h']['#request'] = 'You deleted this user from your friends list.';
                            } else
                                $response['error'] = 'Error occurs while deleting selected user from your friends list. Please try later.';
                    }
                }
            }
        }

        echo json_encode($response);
        exit;
    }
    
    public function cancelRequestAction()
    {
        $response['error'] = 'Wrong data provided to process canceling friendship request.';

        if (isPost()) {
            $post = allPost();
            $model = new FriendsModel();
            
            if (isset($post['pid'])) {
                if ($model->userExist($post['pid'])) {
                    $status = $model->friendsStatus(Request::getParam('user')->id, $post['pid']);
                    if (empty($status)) {
                        $response['error'] = "Information about your relationships with this user not found.";
                    } else {
                        if (!$status['status'] && $status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You banned this user.';
                            else
                                $response['error'] = 'You is banned by this user.';
                        } elseif (!$status['status'] && !$status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id) {
                                if ($model->processFriend('cancelRequest', Request::getParam('user')->id, $post['pid'])) {
                                    $response['error'] = 0;
                                    $response['target_h']['#request'] = 'You canceled your friendship request.';
                                } else
                                    $response['error'] = 'Error occurs while canceling your friendship request. Please try later.';
                            } else
                                $response['error'] = 'User has sent request to add you to his friends list. You can\'t do requested operation.';
                        } elseif ($status['status'] && !$status['ban'])
                            $response['error'] = 'You and this user are friends.';
                    }
                }
            }
        }

        echo json_encode($response);
        exit;
    }
    
    public function acceptRequestAction()
    {
        $response['error'] = 'Wrong data provided to process accepting friendship request.';

        if (isPost()) {
            $post = allPost();
            $model = new FriendsModel();
            
            if (isset($post['pid'])) {
                if ($model->userExist($post['pid'])) {
                    $status = $model->friendsStatus(Request::getParam('user')->id, $post['pid']);
                    if (empty($status)) {
                        $response['error'] = "Information about your relationships with this user not found.";
                    } else {
                        if (!$status['status'] && $status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You banned this user.';
                            else
                                $response['error'] = 'You is banned by this user.';
                        } elseif (!$status['status'] && !$status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You have sent request. You can\'t do requested operation.';
                            else {
                                if ($model->processFriend('acceptRequest', Request::getParam('user')->id, $post['pid'])) {
                                    $response['error'] = 0;
                                    $response['target_h']['#request'] = 'You and this user are friend now!';
                                } else
                                    $response['error'] = 'Error occurs while accepting friendship request from this user. Please try later.';
                            }
                        } elseif ($status['status'] && !$status['ban'])
                            $response['error'] = 'You and this user are friends.';
                    }
                }
            }
        }

        echo json_encode($response);
        exit;
    }
    
    public function declineRequestAction()
    {
        $response['error'] = 'Wrong data provided to process declination friendship request.';

        if (isPost()) {
            $post = allPost();
            $model = new FriendsModel();
            
            if (isset($post['pid'])) {
                if ($model->userExist($post['pid'])) {
                    $status = $model->friendsStatus(Request::getParam('user')->id, $post['pid']);
                    if (empty($status)) {
                        $response['error'] = "Information about your relationships with this user not found.";
                    } else {
                        if (!$status['status'] && $status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You banned this user.';
                            else
                                $response['error'] = 'You is banned by this user.';
                        } elseif (!$status['status'] && !$status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You have sent request. You can\'t do requested operation.';
                            else {
                                if ($model->processFriend('declineRequest', Request::getParam('user')->id, $post['pid'])) {
                                    $response['error'] = 0;
                                    $response['target_h']['#request'] = 'You have declined friendship request from this user.';
                                } else
                                    $response['error'] = 'Error occurs while declination this user friendship request. Please try later.';
                            }
                        } elseif ($status['status'] && !$status['ban'])
                            $response['error'] = 'You and this user are friends.';
                    }
                }
            }
        }

        echo json_encode($response);
        exit;
    }
    
    public function banRequestAction()
    {
        $response['error'] = 'Wrong data provided to process ban request.';

        if (isPost()) {
            $post = allPost();
            $model = new FriendsModel();
            
            if (isset($post['pid'])) {
                if ($model->userExist($post['pid'])) {
                    $status = $model->friendsStatus(Request::getParam('user')->id, $post['pid']);
                    if (empty($status)) {
                        if ($model->processFriend('banRequest', Request::getParam('user')->id, $post['pid'])) {
                            $response['error'] = 0;
                            $response['target_h']['#request'] = 'You have banned this user.';
                        } else
                            $response['error'] = 'Error occurs while processing ban request. Please try later.';
                    } else {
                        if (!$status['status'] && $status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                $response['error'] = 'You already banned this user.';
                            else
                                $response['error'] = 'You is banned by this user.';
                        } else {
                            if($model->processFriend('deleteFriend', Request::getParam('user')->id, $post['pid'])) {
                                if ($model->processFriend('banRequest', Request::getParam('user')->id, $post['pid'])) {
                                    $response['error'] = 0;
                                    $response['target_h']['#request'] = 'You have banned this user.';
                                } else
                                    $response['error'] = 'Error occurs while processing ban request. Please try later.';
                            } else
                                $response['error'] = 'Error occurs while processing ban request. Please try later.';
                        }
                    }
                }
            }
        }

        echo json_encode($response);
        exit;
    }
    
    public function cancelBanAction()
    {
        $response['error'] = 'Wrong data provided to process ban canceling request.';

        if (isPost()) {
            $post = allPost();
            $model = new FriendsModel();
            
            if (isset($post['pid'])) {
                if ($model->userExist($post['pid'])) {
                    $status = $model->friendsStatus(Request::getParam('user')->id, $post['pid']);
                    if (empty($status)) {
                        $response['error'] = "Information about your relationships with this user not found.";
                    } else {
                        if (!$status['status'] && $status['ban']) {
                            if ($status['uid'] == Request::getParam('user')->id)
                                if ($model->processFriend('cancelBan', Request::getParam('user')->id, $post['pid'])) {
                                    $response['error'] = 0;
                                    $response['target_h']['#request'] = 'You have remove this user from blacklist.';
                                } else
                                    $response['error'] = 'Error occurs while processing ban request. Please try later.';
                            else
                                $response['error'] = 'You is banned by this user.';
                        }
                    }
                }
            }
        }

        echo json_encode($response);
        exit;
    }
    
}
/* End of file */