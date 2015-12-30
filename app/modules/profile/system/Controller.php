<?php
require _BASEPATH_ . '/SourceQuery/bootstrap.php';

class ProfileController extends Controller {
	public function indexAction() {
		//$Query = new SourceQuery();
		$model = new ProfileModel();
		$uriOptions = Request::getUriOptions();

		$this->view->discover = $model->checkDiscoverRecord(Request::getParam('user')->id);

		if (Request::getParam('user')->id == $uriOptions[0]) {
			$user = Request::getParam('user');
		} else {
			$user = $model->getUserByID($uriOptions[0]);
		}

		if (!$user) {
			error404();
		}

		if (Request::getParam('user')->id != $user->id) {
			$this->view->friend = $model->friendsStatus(Request::getParam('user')->id, $user->id);
		}

		if (Request::getParam('user')->id != $user->id && !$model->getMatchesByUP(Request::getParam('user')->id, $user->id)->id) {
			$this->view->challenge = true;
		}

		$this->view->langPars = true;
		$this->view->profile = $user;
		$this->view->steamtradelink = $model->getTradeLink($user->id);
		$this->view->country = $model->getCountryByCode($user->country);
		$this->view->ref_count = $model->countRefByID($user->id);
		$this->view->title = $user->nickname;

	}

	public function savetradelinkAction() {

		if (isPost()) {

			$model = new ProfileModel();

			$tradelink = post('tradelink');
			$tradelink = str_replace(".!.", ":", $tradelink);

			if (preg_match('/' . preg_quote('/?partner=', '/') . '(.*)' . preg_quote('&amp;token=', '/') . '/Us', $tradelink, $match)) {
				$partner = $match[1];
			}

			if (preg_match('/' . preg_quote('&amp;token=', '/') . '(.*)' . preg_quote('<<<eof', '/') . '/Us', $tradelink . '<<<eof', $match)) {
				$token = $match[1];
			}

			if ($partner && $token) {
				$data['partner'] = $partner;
				$data['token'] = $token;
				$model->setSteamTradeLink(Request::getParam('user')->id, $data);
				$response["error"] = "Successfully changed!";
			} else {
				$response["error"] = "Error! Try again! ";
			}

		} else { $response["error"] = "LAL!";}

		echo json_encode($response);
		exit;
	}

	// Discover Page Actions**********
	public function discoverAction() {
		$model = new ProfileModel();
		$uid = Request::getParam('user')->id;

		$this->view->list = $model->getDiscoverPageList($uid, 0, 14);
	}

	public function playerVisibilityAction() {
		if (isPost()) {
			$post = allPost();
			$model = new ProfileModel();
			$response['error'] = 0;
			$text = "Error caused..";
			if (isset($post['mid'])) {
				$recId = $model->checkDiscoverRecord($post['mid']);
				// TODO він страшнючий, але поки не вирішив куди його діти, і як без нього обійтись
				$column = "available";
				$show = 0;

				switch ($post["task"]) {
				case "show":
					$show = 1;
					$column = "available";
					$text = "INDEX_HIDE";
					$post["task"] = "hide";
					break;
				case "hide":
					$show = 0;
					$column = "available";
					$text = "INDEX_SHOW";
					$post["task"] = "show";
					break;
				case "sttop":
					$show = 0;
					$column = "looking";
					$post["task"] = "look";
					$text = "INDEX_LOOKING_CHALLENGE";
					break;
				case "look":
					$show = 1;
					$column = "looking";
					$text = "INDEX_STOP_LOOKING";
					$post["task"] = "sttop";
					break;
				}

				$class = ($show == 0) ? $column . "-h" : $column;
				$columns = "`uid`, `$column`, `last_$column`";
				$value = $post['mid'] . ", $show,  " . time();
				$updateString = "`$column` = $show, `last_$column` = " . time();

				if (isset($post['amount'])) {
					$updateString .= ", `amount` = '$post[amount]'";
				}

				if (!empty($recId->id)) {
					$model->updateDiscoverRecord($recId->id, $updateString);
				} else {
					$model->insertDiscoverRecord($columns, $value);
				}

				if ($column == "looking" AND $show == 0) {
					$response['target_h']["#" . $column] = '<input id="challenge-amount" class="challenge-amount" list="amount" value="5" name="amount">
                                                        <datalist  id="amount">
                                                         <option value="5">5$</option>
                                                         <option value="10">10$</option>
                                                         <option value="20">20$</option>
                                                         <option value="50">50$</option>
                                                         <option value="100">100$</option>
                                                       </datalist>';
				}

				$response['target_h']["#" . $column] .= '<a class="' . $class . '" onclick="' . ajaxLoad(url('profile', 'playerVisibility'), 'reqest', 'task:' . $post['task'] . '|mid:' . $post['mid']) . '">' . Lang::translate($text) . '</a>';

			} else { $response['error'] = "No id";}

		} else { $response['error'] = "No POST";}

		echo json_encode($response);
		exit;
	}

	//**********************************

	public function noticeAction() {
		$model = new ProfileModel();

		Pagination::calculate(get('page'), 20, $model->countNotice(Request::getParam('user')->id));

		$this->view->list = $model->getNotice(Request::getParam('user')->id, Pagination::$start, Pagination::$end);
		$this->view->title = Lang::translate('NOTICE_TITLE');
	}

	public function read_noticeAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		$model = new ProfileModel();

		$nid = post('id', 'int');

		$data['read'] = 1;
		$query = $model->update('notice', $data, "`id` = '$nid' AND `uid` = '" . Request::getParam('user')->id . "' LIMIT 1", true);
		$query .= "UPDATE `users` SET `notice` = `notice` -1 WHERE `id` = '" . Request::getParam('user')->id . "' LIMIT 1;";

		$model->multiQuery($query);

		$response['target_h']['#nt' . $nid] = '-';
		$response['error'] = 0;
		echo json_encode($response);
		exit;
	}

	public function read_all_noticeAction() {
		$model = new ProfileModel();

		$data['read'] = 1;
		$query = $model->update('notice', $data, "`uid` = '" . Request::getParam('user')->id . "'", true);
		$query .= "UPDATE `users` SET `notice` = '0' WHERE `id` = '" . Request::getParam('user')->id . "' LIMIT 1;";

		$model->multiQuery($query);

		redirect(url('notice'));
		exit;
	}

	public function send_chatAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		$model = new ProfileModel();
		$mid = post('mid', 'int');
		$sesMID = getSession('match_' . $mid, false);

		if ($mid == $sesMID) {
			$message['mid'] = $sesMID;
			$message['uid'] = Request::getParam('user')->id;
			$message['uName'] = filter(Request::getParam('user')->nickname);
			$message['message'] = post('__msg');
			$message['time'] = time();

			if (!empty($message['message'])) {
				$model->addMessage($message);
			}

			$response['error'] = 0;
		}

		echo json_encode($response);
		exit;
	}

	public function get_chatAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		$model = new ProfileModel();
		$dialog = '';

		$mid = post('mid', 'int');
		$sesMID = getSession('match_' . $mid, false);

		if ($mid == $sesMID) {
			$lastMessageID = getSession('match_chat_lid' . $sesMID, false);
			$listMessage = $model->getChatMessages($sesMID, $lastMessageID);

			while ($list = mysqli_fetch_object($listMessage)) {

				$value = (array) $list;
				$dialog .= '<div class="chat_message">'
				. '<div class="chat_img"><a href="' . url($value['uid']) . '" target="_blank"><img src="' . getAvatar($value['uid'], 's') . '"></a></div>'
				. '<div class="chat_text">'
				. '<div><span class="chat_nickname" onclick="chatNickname(\'' . $value['uName'] . '\');">' . $value['uName'] . '</span> <span class="chat_time">' . printTime($value['time']) . '</span></div>'
					. '<div>' . $value['message'] . '</div>'
					. '</div>'
					. '</div>';

				setSession('match_chat_lid' . $sesMID, $list->id);
			}
			unset($list);

			$response['error'] = 0;
			$response['target_a']['#dialog'] = $dialog;
		}

		echo json_encode($response);
		exit;
	}

	public function laddersAction() {
		$model = new ProfileModel();

		$mode = Request::getUri()[0];

		switch ($mode) {
		case 'high':
			$active['high'] = 'class="active"';
			$min = 1600;
			$max = 2400;
			break;

		case 'intermediate':
			$active['intermediate'] = 'class="active"';
			$min = 800;
			$max = 1599;
			break;

		default:
			$active['low'] = 'class="active"';
			$min = 0;
			$max = 799;
			break;
		}

		Pagination::calculate(get('page', 'int'), 20, $model->countLadderList($min, $max));

		$this->view->active = $active;
		$this->view->list = $model->getLadderList($min, $max, Pagination::$start, Pagination::$end);
		$this->view->title = Lang::translate('LADDERS_TITLE');
	}

	public function ladders_joinAction() {
		$model = new ProfileModel();

		if (Request::getParam('user')->steamid && Request::getParam('user')->role != 'claim' && Request::getParam('user')->ladder == 0) {
			$data['ladder'] = 1;
			$model->update('users', $data, "`id` = '" . Request::getParam('user')->id . "'");
		}
		redirect(url('ladders', false));

		$this->view->title = Lang::translate('LADDERS_TITLE');
	}

	public function matchesAction() {
		$model = new ProfileModel();

		Pagination::calculate(get('page', 'int'), 20, $model->countMatchesList(Request::getParam('user')->id));

		$this->view->langPars = true;
		$this->view->list = $model->getMatchesList(Request::getParam('user')->id, Pagination::$start, Pagination::$end);
		$this->view->title = Lang::translate('MATCHES_TITLE');
	}

	public function challengesAction() {
		$model = new ProfileModel();

		Pagination::calculate(get('page', 'int'), 20, Request::getParam('countChallenges'));

		$this->view->langPars = true;
		$this->view->list = $model->getChallengesList(Request::getParam('user')->id, Pagination::$start, Pagination::$end);
		$this->view->title = Lang::translate('CHALLENGES_TITLE');
	}

	public function historyAction() {
		$model = new ProfileModel();

		Pagination::calculate(get('page', 'int'), 20, $model->countMatchesHistory(Request::getParam('user')->id));

		$this->view->langPars = true;
		$this->view->list = $model->getHistoryList(Request::getParam('user')->id, Pagination::$start, Pagination::$end);
		$this->view->title = Lang::translate('HISTORY_TITLE');
	}

	public function challengeAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		$model = new ProfileModel();

		$data['uid'] = Request::getParam('user')->id;
		$role = Request::getParam('user')->role;
		$model->checkStamina($data['uid']);
		$data['pid'] = post('pid', 'int');
		$data['startTime'] = time();

		if (($model->getStamina($data['uid']) - 1) >= 0 OR $role == 'admin') {
			if ($data['uid'] != $data['pid']) {
				if ($model->getUserByID($data['pid'])->id && !$model->getMatchesByUP($data['uid'], $data['pid'])->id) {
					$result = $model->insert('matches', $data);
					if ($result) {
						if ($role != 'admin') {$model->updateStamina($data['uid'], "-1");}
						$response['target_h']['#challenge'] = 'Challenge sent';
						$response['target_h']['#challenge' . $data['pid']] = 'Challenge sent';
					} else {
						$response['target_h']['#challenge'] = 'Error challenge!';
						$response['target_h']['#challenge' . $data['pid']] = 'Error challenge!';
					}
				} else {
					$response['target_h']['#challenge'] = 'You have already challenged this profile!';
					$response['target_h']['#challenge' . $data['pid']] = 'You have already challenged this profile!';
				}
			} else {
				$response['target_h']['#challenge'] = 'You can not challenge for yourself!';
				$response['target_h']['#challenge' . $data['pid']] = 'You can not challenge for yourself!';
			}
		} else {
			$response['target_h']['#challenge'] = 'Not enough stamina, recover your stamina points or try again tomorrow.';
			$response['target_h']['#challenge' . $data['pid']] = 'Not enough stamina, recover your stamina points or try again tomorrow.';
		}

		$response['error'] = 0;

		echo json_encode($response);
		exit;
	}

	public function voice_plusAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		$model = new ProfileModel();

		$data['uid'] = Request::getParam('user')->id;
		$data['pid'] = post('pid', 'int');
		$data['time'] = time();

		if ($data['uid'] != $data['pid']) {
			if (!$model->getRattingHistory($data['uid'], $data['pid'])) {
				if ($model->countRattingToday($data['uid']) < 5) {
					$result = $model->insert('rating_history', $data);

					$uData['rating'] = '++';
					$model->update('users', $uData, "`id` = '" . $data['pid'] . "'");

					if ($result) {
						$response['error'] = 0;
						$response['target_h']['#rating'] = post('rat', 'int') + 1;
					} else {
						$response['error'] = 'Error voice!';
					}

				} else {
					$response['error'] = 'You can vote up to 5 times a day!';
				}

			} else {
				$response['error'] = 'You have already voted for this profile today!';
			}

		} else {
			$response['error'] = 'You can not vote for yourself!';
		}

		echo json_encode($response);
		exit;
	}

	public function regcodeAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		$model = new ProfileModel();

		$data['uid'] = Request::getParam('user')->id;
		$data['code'] = md5(Request::getParam('user')->nickname . '_' . randomHash());
		$data['time'] = time();

		$idCode = $model->insert('reg_code', $data);

		if ($idCode) {
			$response['error'] = 0;
			$response['target_h']['#reg_code'] = SITE_URL . 'reg_' . $data['code'];
		} else {
			$response['error'] = 'Error';
		}

		echo json_encode($response);
		exit;
	}

	public function steamAction() {
		$model = new ProfileModel();
		incFile('modules/page/system/inc/OpenId.inc.php');

		$openid = new LightOpenID(SITE_URL . 'profile/steam');
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

				$url = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . STEAM_API_KEY . '&steamids=' . $matches[1];
				$json_object = file_get_contents($url);
				$json_decoded = json_decode($json_object);

				if ($json_decoded) {
					$userData = $json_decoded->response->players[0];

					if ($matches[1] == $userData->steamid && $userData->steamid) {
						$model->setSteamID(Request::getParam('user')->id, $userData->steamid);
						redirect(url(Request::getParam('user')->id));
					} else {
						$errorMessage = 'Попробуйте еще раз';
					}

				} else {
					$errorMessage = 'Попробуйте еще раз';
				}

				unset($json_object, $json_decoded);
			} else {
				$errorMessage = 'User is not logged in.';
			}

		}

		echo $errorMessage;
	}

	public function exitAction() {
		setSession('user', null);
		unsetCookie('user');
		redirect(url());
	}

	/*----------------------------------------------MATCHES-----------------------------------------------------------*/

	public function matchAction() {
		$model = new ProfileModel();
		$uriOptions = Request::getUriOptions();

		$match = $model->getMatchByID($uriOptions[0]);
		if (!$match) {
			error404();
		}

		$users = $model->getMatchUsers($match->uid, $match->pid);

		setSession('match_' . $match->id, $match->id);

		//if ($match->uid == $users[0]->id) {
		if (Request::getParam('user')->id == $users[0]->id) {
			$this->view->uid = $users[0];
			$this->view->pid = $users[1];
		} else {
			$this->view->uid = $users[1];
			$this->view->pid = $users[0];
		}

		if ($match->uid == Request::getParam('user')->id) {
			$this->view->ready = $match->uready;
		} else {
			$this->view->ready = $match->pready;
		}

		$this->view->match = $match;
		//$this->view->assets = $model->getMatchAssets(Request::getParam('user')->id, $match->id); // enable if want init users assets load
		$this->view->list = $model->getChatMessages($match->id);
		$this->view->langPars = true;
		$this->view->title = $users[0]->nickname . ' ' . Lang::translate('MATCH_VS') . ' ' . $users[1]->nickname;
	}

	public function match_acceptAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		$model = new ProfileModel();
		$mid = post('mid', 'int');
		$sesMID = getSession('match_' . $mid, false);
		$response['error'] = 0;

		if ($mid == $sesMID) {
			$match = $model->getMatchByID($sesMID);

			if ($match->pid == Request::getParam('user')->id && $match->status == 0) {
				$data['status'] = 1;
				$model->update('matches', $data, "`id` = '$mid'");

				$userAccept = $model->getUserByID($match->pid);

				// TODO insert note
				$noteData['uid'] = $match->uid;
				$noteData['text'] = '<a href="' . url($userAccept->id) . '">' . $userAccept->nickname . '</a> accepted <a href="' . url('match' . $match->id) . '">match</a>';
				$noteData['time'] = time();
				$model->insert('notice', $noteData);

				$userData['notice'] = '++';
				$model->update('users', $userData, "`id` = '$match->uid'");

				$response['target_h']['#battle'] = Lang::translate('MATCH_SAVED');
			} else {
				$response['error'] = Lang::translate('MATCH_SAVE_ERROR');
			}
		}

		echo json_encode($response);
		exit;
	}

	public function matchCancelAction() {
		if (isPost()) {
			$mid = post('mid', 'int');
			$model = new ProfileModel();
			$response['error'] = 0;
			if ($model->delete("matches", " id = $mid AND uready=0 AND pready=0 ")) {
				$response['target_h']['#cancelMatch' . $mid] = "Match was deleted!";
			} else {
				$response['target_h']['#cancelMatch' . $mid] = "Match cannot be deleted!";
			}

			echo json_encode($response);
			exit;
		}
	}

	public function match_rejectAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		$model = new ProfileModel();
		$mid = post('mid', 'int');
		$sesMID = getSession('match_' . $mid, false);
		$response['error'] = 0;

		if ($mid == $sesMID) {
			$match = $model->getMatchByID($sesMID);

			if ($match->pid == Request::getParam('user')->id && $match->status != 2) {
				$data['status'] = 2;
				$model->update('matches', $data, "`id` = '$mid'");
				$response['target_h']['#battle'] = Lang::translate('MATCH_SAVED');
			} else {
				$response['error'] = Lang::translate('MATCH_SAVE_ERROR');
			}
		}

		echo json_encode($response);
		exit;
	}

	public function inventoryAction() {
		$response['error'] = 0;

		if (Request::getParam('user')->id) {
			$steamID = Request::getParam('user')->steamid;
			$steamNick = Request::getParam('user')->steamnick;

			if ($steamID && mb_strlen($steamID) > 16) {

				$result = json_decode(get_contents('http://steamcommunity.com/profiles/' . $steamID . '/inventory/json/730/2/'));

				if ((!$result || !$result->success) && !empty($steamNick)) {
					$result = json_decode(get_contents('http://steamcommunity.com/id/' . $steamNick . '/inventory/json/730/2/'));
				}

				if ($result && allPost() && $result->success) {
					$mid = post('mid', 'int');
					$model = new ProfileModel();
					$match = $model->getMatchByID($mid);
					if ($match->status == 1 || ($match->status == 2 && $match->blocked)) {

						if ($mid) {
							$response['target_h']['#assets'] = "";

							if ($result->rgInventory) {
								foreach ($result->rgInventory as $item) {
									$handle = $item->classid . "_" . $item->instanceid; //handler to parse descriptions

									if ($result->rgDescriptions->$handle) {
										$asset = new stdClass();

										$asset->assetId = $item->id;
										$asset->amount = $item->amount;
										$asset->pos = $item->pos;
										$asset->name = $result->rgDescriptions->$handle->name;
										$asset->market_name = $result->rgDescriptions->$handle->market_name;
										$asset->icon_url = $result->rgDescriptions->$handle->icon_url;
										$asset->icon_url_large = $result->rgDescriptions->$handle->icon_url_large;
										$asset->classid = $item->classid;
										$asset->instanceid = $item->instanceid;
										$asset->status = $result->rgDescriptions->$handle->descriptions[0]->value;

										if ($result->rgDescriptions->$handle->cache_expiration) {
											$asset->cache_expiration = 1;
										} else {
											$asset->cache_expiration = 0;
										}

										$assets[$item->id] = $asset;
										unset($asset);
									}
								}

								if ($assets) {
									setSession('myAssets' . $mid, json_encode($assets), false); //save assets to session for future validation

									foreach ($assets as $asset) {
										if ($asset->cache_expiration == 0) {
											$response['target_h']['#assets'] .= '<div class="assetsItem" id="i' . $asset->assetId . '" data-name="' . urlencode($asset->market_name) . '" ';
											if (!$match->blocked) {
												$response['target_h']['#assets'] .= ' ondblclick="addAsset(\'' . $asset->assetId . '\');" ';
											}
											$response['target_h']['#assets'] .= '>'
											. '<img src="http://steamcommunity-a.akamaihd.net/economy/image/' . $asset->icon_url . '" alt="icon">'
											. '</div>'
											. '<div class="assetsInfo none" id="help_i' . $asset->assetId . '">'
											. '<div class="text">'
											. $asset->name . '<br/>' . "\n"
											. 'Price: <span id="pi' . $asset->assetId . '">0</span><br/>' . "\n"
											. '' . $asset->status . '<br/>' . "\n"
												. '</div>'
												. '</div>';
										}
									}

								} else {
									setSession('myAssets', '');
									$response['error'] = Lang::translate("MATCH_INVENTORY_EMPTY_INVENTORY");
								}
							} else {
								$response['error'] = Lang::translate("MATCH_INVENTORY_EMPTY_INVENTORY");
							}
						} else {
							$response['error'] = Lang::translate("MATCH_INVENTORY_EMPTY_MATCH_ID");
						}
					} else {
						$response['target_h']['#assets'] = "";
					}
				} else {
					$response['error'] = Lang::translate("MATCH_INVENTORY_LOAD_ERROR");
				}

			}
		}

		echo json_encode($response);
		exit;
	}

	public function itempriceAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		//$model = new ProfileModel();
		$id = str_replace('i', '', post('id'));
		$name = post('name');

		$marketPrice = @get_contents("http://steamcommunity.com/market/priceoverview/?currency=1&appid=730&market_hash_name=" . $name);
		$marketJson = json_decode($marketPrice);
		$response['target_h']['#pi' . $id] = $marketJson->median_price;
		$response['target_h']['#bpi' . $id] = $marketJson->median_price;
		$response['error'] = 0;

		echo json_encode($response);
		exit;
	}

	public function control_matchAction() {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			error404();
		}

		$model = new ProfileModel();

		$id = post('id');
		$winner = post('__control_win', 'int');

		$match = $model->getMatchByID($id);
		if (!$match) {
			error404();
		}

		$uid = $model->getUserByID($match->uid);
		$pid = $model->getUserByID($match->pid);

		if ($winner) {

			if ($winner == $uid->id) {
				$data['status'] = 2;
				$data['uwin'] = 1;
				$data['pwin'] = 2;
				$loser = $pid->id;

				$countGamesW = $uid->wins + $uid->losses + 1;
				$countGamesL = $pid->wins + $pid->losses + 1;
				$eloW = elo($uid->elo, $uid->elo, $countGamesW, 1);
				$eloL = elo($pid->elo, $pid->elo, $countGamesL, 0);
			}

			if ($winner == $pid->id) {
				$data['status'] = 2;
				$data['uwin'] = 2;
				$data['pwin'] = 1;
				$loser = $uid->id;

				$countGamesW = $pid->wins + $pid->losses + 1;
				$countGamesL = $uid->wins + $uid->losses + 1;
				$eloW = elo($pid->elo, $pid->elo, $countGamesW, 1);
				$eloL = elo($uid->elo, $uid->elo, $countGamesL, 0);
			}

			if ($data && $model->updateMatchWL($match->id, $data)) {
				if ($winner && $loser) {

					$model->updateWLStat($winner, $loser, $eloW, $eloL);

					$response['target_h']['#control'] = '';

				}
			}

		} else {

			$response['target_h']['#control'] = '<div>'
			. '' . Lang::translate('MATCH_WINNER') . ' <select id="control_win">'
			. '<option value="' . $uid->id . '">' . $uid->nickname . '</option>'
			. '<option value="' . $pid->id . '">' . $pid->nickname . '</option>'
			. '</select>'
			. '<span class="btn" onclick="' . ajaxLoad(url('profile', 'control_match'), 'control_match', 'id:' . $match->id . '|#control_win!') . '">' . Lang::translate('MATCH_CONTROL') . '</span>'
				. '<div>';
			$response['error'] = 0;

		}

		echo json_encode($response);
		exit;
	}

	public function syncAction() {
		$response = array(
			'error' => 0,
			'target_h' => array(
				'#myAssets' => '',
				'#myReady' => '',
				'#themAssets' => '',
				'#themReady' => '',
				'#battle' => '',
			),
		);

		if (Request::getUri()[0]) {
			$mid = Request::getUri()[0];
			$model = new ProfileModel();

			$match = $model->getMatchByID($mid);
			if ($match) {
				// TODO True Deposit Value
				if ($match->uid == Request::getParam('user')->id) {
					$response['target_h']['#usum'] = 'Current deposit value: $' . $match->usum;
					$response['target_h']['#psum'] = 'Current deposit value: $' . $match->psum;
				} else {
					$response['target_h']['#usum'] = 'Current deposit value: $' . $match->psum;
					$response['target_h']['#psum'] = 'Current deposit value: $' . $match->usum;
				}

				if ($match->status == 0 && $match->pid == Request::getParam('user')->id) {
					$response['target_h']['#battle'] = '<div id="status_action">'
					. ' <a onclick="' . ajaxLoad(url('matchaccept'), 'match_action', 'mid:' . $match->id, 'reloadInventory|!exception') . '">' . Lang::translate("MATCH_ACCEPT") . '</a> '
					. ' <a onclick="' . ajaxLoad(url('matchreject'), 'match_action', 'mid:' . $match->id, 'reloadInventory|!exception') . '">' . Lang::translate("MATCH_REJECT") . '</a></div>';
				}

				if ($match->status == 1 || ($match->status == 2 && $match->blocked)) {
					if ($match->status == 1) {
						//
						$servers = $model->getServersList();
						//use xPaw\SourceQuery\SourceQuery;
						//
						//$response['target_h']['#map_note'] = 'Go to our <a href="' . url('servers') . '" target="_blank">servers page</a> to find a server to play on';
						//$response['target_h']['#map_note'] = var_export($servers, true);
						//$response['target_h']['#map_note'] = var_export($servers, true);
					} else {
						$response['target_h']['#map_note'] = '';
					}

					if (Request::getParam('user')->id == $match->uid) {
						//if userId is UID
						$assets = $model->getMatchAssets($match->uid, $match->id);
						$myReady = $match->uready;
						$myWin = (!$match->uwin || $match->uwin == null) ? 0 : $match->uwin;
						$oppositeAssets = $model->getMatchAssets($match->pid, $match->id);
						$oppositeReady = $match->pready;
						$oppositeWin = (!$match->pwin || $match->pwin == null) ? 0 : $match->pwin;
					} else {
						//else if userId is PID
						$assets = $model->getMatchAssets($match->pid, $match->id);
						$myReady = $match->pready;
						$myWin = (!$match->pwin || $match->pwin == null) ? 0 : $match->pwin;
						$oppositeAssets = $model->getMatchAssets($match->uid, $match->id);
						$oppositeReady = $match->uready;
						$oppositeWin = (!$match->uwin || $match->uwin == null) ? 0 : $match->uwin;
					}

					//current user's data
					if ($myWin == 0) {
						if ($myReady) {
							if ($oppositeReady && $match->blocked) {
								$response['target_h']['#myReady'] = Lang::translate("MATCH_READY");
							} else {
								$response['target_h']['#myReady'] = '<button id="readyBtn" onclick="' . ajaxLoad(url('profile', 'setReady', $match->id), 'ready', '', 'sync|reloadInventory|!exception') . ';">'
								. Lang::translate("MATCH_CANCEL_READY")
									. '</button>';
							}
						} else {
							if ($assets && count($assets) > 0) {
								$response['target_h']['#myReady'] = '<button id="readyBtn" onclick="' . ajaxLoad(url('profile', 'setReady', $match->id), 'ready', '', 'sync|reloadInventory|!exception') . ';">'
								. Lang::translate("MATCH_READY")
									. '</button>';
							} else {
								$response['target_h']['#myReady'] = '<button id="readyBtn" disabled>'
								. Lang::translate("MATCH_CANT_READY")
									. '</button>';
							}
						}
					}

					if ($assets && count($assets) > 0) {
						foreach ($assets as $asset) {
							$response['target_h']['#myAssets'] .=
							'<div class="assetsItem" id="my' . $asset->oldAssetId . '" ';
							if (!$match->blocked && $myWin == 0) {
								$response['target_h']['#myAssets'] .= 'ondblclick="removeAsset(' . $asset->oldAssetId . ');"';
							}

							$response['target_h']['#myAssets'] .= '><img src="http://steamcommunity-a.akamaihd.net/economy/image/' . $asset->icon_url . '" alt="icon">'
							. '<div class="none" id="myAssetNo' . $asset->oldAssetId . '">' . $asset->id . '</div>'
							. '</div>'
							. '<div class="assetsInfo none" id="help_my' . $asset->oldAssetId . '">'
							. '<div class="text">'
							. $asset->name . '<br/>' . "\n"
							. 'Price: ' . $asset->price . '<br/>' . "\n"
							. '' . $asset->status . '<br/>' . "\n"
								. '</div>'
								. '</div>';

						}
					}

					//opposite user data
					if ($oppositeWin == 0) {
						if ($oppositeReady) {
							$response['target_h']['#themReady'] = Lang::translate("MATCH_THEM_READY");
						} else {
							if ($oppositeAssets && count($oppositeAssets) > 0) {
								$response['target_h']['#themReady'] = Lang::translate("MATCH_THEM_NOT_READY");
							} else {
								$response['target_h']['#themReady'] = Lang::translate("MATCH_THEM_NOT_READY_EMPTY");
							}
						}
					}

					if ($oppositeAssets && count($oppositeAssets) > 0) {
						foreach ($oppositeAssets as $asset) {
							$response['target_h']['#themAssets'] .= '<div class="assetsItem" id="them' . $asset->oldAssetId . '" >'
							. '<img src="http://steamcommunity-a.akamaihd.net/economy/image/' . $asset->icon_url . '" alt="icon">'
							. '</div>'
							. '<div class="assetsInfo none" id="help_them' . $asset->oldAssetId . '">'
							. '<div class="text">'
							. $asset->name . '<br/>' . "\n"
							. 'Price: ' . $asset->price . '<br/>' . "\n"
							. '' . $asset->status . '<br/>' . "\n"
								. '</div>'
								. '</div>';

						}
					}

					//match battle status
					if ($match->blocked) {
						$count = $model->getCountMatchAssets($match->id);
						$countRequested = $model->getCountRequestedMatchAssets($match->id);

						if ($countRequested < $count) {
							$response['target_h']['#battle'] = Lang::translate("MATCH_BATTLE_WAIT_FOR_REQUEST") . ' ' . $countRequested . '/' . $count;
						} else {
							$countReceived = $model->getCountReceivedMatchAssets($match->id);

							if ($count > $countReceived) {
								$response['target_h']['#battle'] = Lang::translate("MATCH_BATTLE_WAIT_FOR_RECEIVE") . ' ' . $countReceived . '/' . $count;
							} else {
								$response['target_h']['#battle'] = '<div>';

								if (($myWin == 1 && $oppositeWin == 2) || ($myWin == 2 && $oppositeWin == 1)) {
									$response['target_h']['#battle'] = Lang::translate("MATCH_ENDED");
								} else {

									$response['target_h']['#battle'] .= '<div>';
									if ($myWin > 0) {
										if ($myWin == 1) {
											$response['target_h']['#battle'] .= Lang::translate("MATCH_WIN")
											. '<input type="button" id="loseBttn" value="' . Lang::translate('MATCH_LOSE_CHANGE') . '" onclick="' . ajaxLoad(url('profile', 'setLose'), '', 'mid:' . $match->id, 'sync|!exception') . '"/>';
										}
										if ($myWin == 2) {
											$response['target_h']['#battle'] .= Lang::translate("MATCH_LOSE")
											. '<input type="button" id="winBttn" value="' . Lang::translate('MATCH_WIN_CHANGE') . '" onclick="' . ajaxLoad(url('profile', 'setWin'), '', 'mid:' . $match->id, 'sync|!exception') . '"/>';
										}
									} else {
										$response['target_h']['#battle'] .= '<input type="button" id="winBttn" value="' . Lang::translate('MATCH_SET_WIN') . '" onclick="' . ajaxLoad(url('profile', 'setWin'), '', 'mid:' . $match->id, 'sync|!exception') . '"/>';
										$response['target_h']['#battle'] .= '<input type="button" id="loseBttn" value="' . Lang::translate('MATCH_SET_LOSE') . '" onclick="' . ajaxLoad(url('profile', 'setLose'), '', 'mid:' . $match->id, 'sync|!exception') . '"/>';
									}
									$response['target_h']['#battle'] .= '</div>';

									$response['target_h']['#battle'] .= '</div>';
									if ($oppositeWin > 0) {
										if ($oppositeWin == 1) {
											$response['target_h']['#battle'] .= Lang::translate("MATCH_THEM_WIN");
										}
										if ($oppositeWin == 2) {
											$response['target_h']['#battle'] .= Lang::translate("MATCH_THEM_LOSE");
										}
									} else {
										$response['target_h']['#battle'] .= Lang::translate("MATCH_THEM_BATTLE_THINK");

									}
									$response['target_h']['#battle'] .= '</div>';
								}

								$response['target_h']['#battle'] .= '</div>';
							}
						}
					}
				}
			} else {
				$response['error'] = Lang::translate("MATCH_ACCESS_DENIED");
			}

		} else {
			$response['error'] = Lang::translate("MATCH_ACCESS_DENIED");
		}

		echo json_encode($response);
		exit;
	}

	public function addAssetAction() {
		$response['error'] = 0;
		$response['id'] = false;
		$response['blocked'] = false;

		if (isPost()) {
			$model = new ProfileModel();
			$post = allPost();

			if (!$post['mid'] || !$post['assetId']) {
				$response['error'] = Lang::translate("MATCH_WRONG_DATA");
			} else {
				$mid = $post['mid'];
				$response['id'] = $post['assetId'];
				$match = $model->getMatchByID($mid);

				if (!$match->blocked) {
					$assets = getSession('myAssets' . $mid, false);
					if (!$assets) {
						$response['error'] = Lang::translate("MATCH_RELOAD_PAGE");
					} else {
						$assets = json_decode($assets);

						if (!$assets) {
							$response['error'] = Lang::translate("MATCH_RELOAD_PAGE");
						} else {
							if (!$assets->$post['assetId']) {
								$response['error'] = Lang::translate('MATCH_WRONG_ASSET');
							} else {
								$asset = $assets->$post['assetId'];

								$marketPrice = get_contents("http://steamcommunity.com/market/priceoverview/?currency=1&appid=730&market_hash_name=" . urlencode($asset->market_name));
								$marketJson = json_decode($marketPrice);

								$arr1 = array("$", "&#8364;");
								$arr2 = array("", "");
								$price = str_replace($arr1, $arr2, $marketJson->median_price);

								$item = array(
									'uid' => Request::getParam('user')->id,
									'mid' => $mid,
									'oldAssetId' => $asset->assetId,
									'amount' => $asset->amount,
									'pos' => $asset->pos,
									'name' => $asset->name,
									'market_name' => $asset->market_name,
									'status' => $asset->status,
									'price' => $price,
									'icon_url' => $asset->icon_url,
									'icon_url_large' => $asset->icon_url_large,
									'classid' => $asset->classid,
									'instanceid' => $asset->instanceid,
								);

								if ($model->addMatchAsset($item)) {
									$response['assetNo'] = $model->insertID();

									if ($match) {
										if (Request::getParam('user')->id == $match->uid) {
											$field = 'uready';
										} else {
											$field = 'pready';
										}

										if ($match->$field == '1') {
											$data[$field] = '0';
											$response['target_h']['#readyBtn'] = Lang::translate("MATCH_NOT_READY");
										}

										if (Request::getParam('user')->id == $match->uid) {
											$data['usum'] = $match->usum + $price;
										} else {
											$data['psum'] = $match->psum + $price;
										}

										if (!$model->setMatchReady($mid, $data)) {
											$response['error'] = Lang::translate("MATCH_DB_ERROR");
										}

									} else {
										$response['error'] = Lang::translate("MATCH_WRONG");
									}

								} else {
									$response['error'] = Lang::translate("MATCH_DB_ERROR");
								}

							}
						}
					}
				} else {
					$response['error'] = Lang::translate("MATCH_BLOCKED");
					$response['blocked'] = true;
				}
			}
		} else {
			$response['error'] = Lang::translate("MATCH_EMPTY_DATA");
		}

		echo json_encode($response);
		exit;
	}

	public function removeAssetAction() {
		$response['error'] = 0;
		$response['id'] = false;
		$response['blocked'] = false;

		if (isPost()) {
			$model = new ProfileModel();
			$post = allPost();
			$match = $model->getMatchByID(post('mid', 'int'));

			if (!$match->blocked) {
				$response['id'] = $post['aid'];

				if (!$post['id']) {
					$response['error'] = Lang::translate("MATCH_WRONG_DATA");
				} else {
					$asset = $model->getMatchAsset(Request::getParam('user')->id, $post['id']);
					if (!$model->removeAsset(Request::getParam('user')->id, $post['id'])) {
						$response['error'] = Lang::translate("MATCH_DB_ERROR");
					} else {
						if ($match) {
							if (Request::getParam('user')->id == $match->uid) {
								$field = 'uready';
							} else {
								$field = 'pready';
							}

							if ($match->$field == '1') {
								$data[$field] = '0';
								$response['target_h']['#readyBtn'] = Lang::translate("MATCH_NOT_READY");
							}

							if ($match->$field == '1') {
								$data[$field] = '0';
								$response['target_h']['#readyBtn'] = "MATCH_NOT_READY";
							}

							/*
								                            $marketPrice = get_contents("http://steamcommunity.com/market/priceoverview/?currency=1&appid=730&market_hash_name=".urlencode($asset->market_name));
								                            $marketJson = json_decode($marketPrice);

								                            $arr1 = array("$", "&#8364;");
								                            $arr2 = array("", "");
								                            $price = str_replace($arr1, $arr2, $marketJson->median_price);
							*/

							$price = $asset->price;

							if (Request::getParam('user')->id == $match->uid) {
								$data['usum'] = floatval($match->usum - $price);
							} else {
								$data['psum'] = floatval($match->psum - $price);
							}

							echo $model->update('matches', $data, " `id` = '$match->id' ", true);

							if (!$model->setMatchReady($match->id, $data)) {
								$response['error'] = Lang::translate("MATCH_DB_ERROR");
							}

						} else {
							$response['error'] = Lang::translate("MATCH_WRONG");
						}

					}
				}
			} else {
				$response['error'] = Lang::translate("MATCH_BLOCKED");
				$response['blocked'] = true;
			}
		} else {
			$response['error'] = Lang::translate("MATCH_EMPTY_DATA");
		}

		echo json_encode($response);
		exit;
	}

	public function setReadyAction() {
		$response['error'] = 0;
		$response['target_h']['#battle'] = '';
		$response['blocked'] = false;

		if (Request::getUri()[0]) {
			$mid = Request::getUri()[0];
			$assets = getSession('myAssets' . $mid, false);
			if ($assets) {
				$assets = json_decode($assets);

				if ($assets && is_object($assets) && count($assets) > 0) {
					$model = new ProfileModel();
					$myAssets = $model->getMatchAssets(Request::getParam('user')->id, $mid);

					if ($myAssets && count($myAssets) > 0) {
						foreach ($myAssets as $asset) {
							if (!$asset->oldAssetId) {
								$response['error'] = Lang::translate("MATCH_ONE_EMPTY");
								break;
							}
						}

						if (!$response['error']) {
							$match = $model->getMatchByID($mid);

							if ($match && !$match->blocked) {
								if (Request::getParam('user')->id == $match->uid) {
									if ($match->uready == '0') {
										$data['uready'] = '1';
										$status = Lang::translate("MATCH_READY");
									} else {
										$data['uready'] = '0';
										$status = Lang::translate("MATCH_NOT_READY");
									}
								} else {
									if ($match->pready == '0') {
										$data['pready'] = '1';
										$status = Lang::translate("MATCH_READY");
									} else {
										$data['pready'] = '0';
										$status = Lang::translate("MATCH_NOT_READY");
									}
								}

								if ($model->setMatchReady($mid, $data)) {
									$response['target_h']['#readyBtn'] = $status;

									$match = $model->getMatchByID($mid);
									if ($match->uready && $match->pready) {
										if ($model->setMatchBlocked($match->id)) {
											$response['target_h']['#battle'] = Lang::translate("MATCH_BLOCKED_WAIT_FOR_REQUEST") . " 0%";
											$response['blocked'] = true;
										} else {
											$response['tradeOffer'] = Lang::translate("MATCH_DB_ERROR");
										}
									}
								} else {
									$response['error'] = Lang::translate("MATCH_DB_ERROR");
								}

							} else {
								$response['blocked'] = true;
								$response['error'] = Lang::translate("MATCH_WRONG_OR_BLOCKED");
							}
						}
					} else {
						$response['error'] = Lang::translate("MATCH_EMPTY_MY_ASSETS");
					}

				} else {
					$response['error'] = Lang::translate("MATCH_WRONG_ASSETS");
				}

			} else {
				$response['error'] = Lang::translate("MATCH_EMPTY_ASSETS");
			}

		}

		echo json_encode($response);
		exit;
	}

	public function setWinAction() {
		$response['error'] = 0;

		if (isPost()) {
			$post = allPost();
			$model = new ProfileModel();

			if ($post['mid']) {
				$match = $model->getMatchByID($post['mid']);

				if ($match->blocked && !($match->pwin == "1" && $match->uwin == "2") && !($match->pwin == "2" && $match->uwin == "1")) {

					if (Request::getParam('user')->id == $match->uid) {
						$data['uwin'] = '1';
						if ($match->pwin == "2") {
							$data['status'] = 2;
							$winner = $match->uid;
							$loser = $match->pid;
						}
					} elseif (Request::getParam('user')->id == $match->pid) {
						$data['pwin'] = '1';
						if ($match->uwin == "2") {
							$data['status'] = 2;
							$winner = $match->pid;
							$loser = $match->uid;
						}
					}

					if ($data && $model->updateMatchWL($post['mid'], $data)) {
						if ($winner && $loser) {
							if ($winner == Request::getParam('user')->id) {
								$userW = Request::getParam('user');
								$userL = $model->getUserByID($loser);
							} else {
								$userW = $model->getUserByID($winner);
								$userL = Request::getParam('user');
							}

							$countGamesW = $userW->wins + $userW->losses + 1;
							$countGamesL = $userL->wins + $userL->losses + 1;
							$eloW = elo($userW->elo, $userL->elo, $countGamesW, 1);
							$eloL = elo($userL->elo, $userW->elo, $countGamesL, 0);

							$model->updateWLStat($winner, $loser, $eloW, $eloL);
						}
					} else {
						$response['error'] = Lang::translate("MATCH_DB_ERROR");
					}

				} else {
					$response['error'] = Lang::translate("MATCH_ENDED");
				}

			}
		} else {
			$response['error'] = Lang::translate("MATCH_EMPTY_DATA");
		}

		echo json_encode($response);
		exit;
	}

	public function setLoseAction() {
		$response['error'] = 0;

		if (isPost()) {
			$post = allPost();
			$model = new ProfileModel();

			if ($post['mid']) {
				$match = $model->getMatchByID($post['mid']);

				if ($match->blocked && !($match->pwin == "1" && $match->uwin == "2") && !($match->pwin == "2" && $match->uwin == "1")) {

					if (Request::getParam('user')->id == $match->uid) {
						$data['uwin'] = '2';
						if ($match->pwin == "1") {
							$data['status'] = 2;
							$winner = $match->pid;
							$loser = $match->uid;
						}
					} elseif (Request::getParam('user')->id == $match->pid) {
						$data['pwin'] = '2';
						if ($match->uwin == "1") {
							$data['status'] = 2;
							$winner = $match->uid;
							$loser = $match->pid;
						}
					}

					if ($data && $model->updateMatchWL($post['mid'], $data)) {
						if ($winner && $loser) {
							if ($winner == Request::getParam('user')->id) {
								$userW = Request::getParam('user');
								$userL = $model->getUserByID($loser);
							} else {
								$userW = $model->getUserByID($winner);
								$userL = Request::getParam('user');
							}

							$countGamesW = $userW->wins + $userW->losses + 1;
							$countGamesL = $userL->wins + $userL->losses + 1;
							$eloW = elo($userW->elo, $userL->elo, $countGamesW, 1);
							$eloL = elo($userL->elo, $userW->elo, $countGamesL, 0);

							$model->updateWLStat($winner, $loser, $eloW, $eloL);
						}
					} else {
						$response['error'] = Lang::translate("MATCH_DB_ERROR");
					}

				} else {
					$response['error'] = Lang::translate("MATCH_ENDED");
				}

			}
		} else {
			$response['error'] = Lang::translate("MATCH_EMPTY_DATA");
		}

		echo json_encode($response);
		exit;
	}

	public function verificationAction() {
		//$model = new ProfileModel();
		$this->view->title = Lang::translate('VERIFICATION_TITLE');
	}

	public function banAction() {
		//$model = new ProfileModel();
		$this->view->title = Lang::translate('BAN_TITLE');
	}
}
/* End of file */