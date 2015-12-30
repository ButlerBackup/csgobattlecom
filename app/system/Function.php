<?php
/**
 * Function getAvatar
 * @param $id
 * @param null $size
 * @return string
 */
function getAvatar($id, $size = null)
{
    if ($size == 's')
        $size = '_s';
    elseif ($size == 'm')
        $size = '_m';
    else
        $size = '';

    if (file_exists(_SYSDIR_.'public/users/'.$id.'/avatar'.$size.'.jpg'))
        return _SITEDIR_.'public/users/'.$id.'/avatar'.$size.'.jpg';
    else
        return _SITEDIR_.'public/images/img/avatar'.$size.'.jpg';
}

/**
 * @param $Ra - Рейтинг игрока A
 * @param $Rb - Рейтинг игрока B
 * @param $countGame - Количество сыграных игр
 * @param $Sa - Результат (победа 1, ничья 0,5, проиграш 0)
 * @return mixed
 */
function elo($Ra, $Rb, $countGame, $Sa) {
    if ($countGame <= 15)
        $K = 25;
    elseif ($Ra >= 2400)
        $K = 10;
    else
        $K = 15;

    $Ea = 1/(1+10^(($Rb-$Ra)/400));
    $newRa = $K * ($Sa-$Ea);
    $rating = $Ra + $newRa;

    return $rating;
}

/**
 * Function getRank
 * @param $rating
 * @param bool $color
 * @return string
 */
function getRank($rating, $color = true)
{
    if ($rating >= 2400)
        $rank = 'Grandmaster';
    elseif ($rating >= 2200)
        $rank = 'Proficient';
    elseif ($rating >= 2000)
        $rank = 'Expert';
    elseif ($rating >= 1800)
        $rank = 'Elite';
    elseif ($rating >= 1600)
        $rank = 'Intermediate';
    elseif ($rating >= 1400)
        $rank = 'Class A';
    elseif ($rating >= 1200)
        $rank = 'Class B';
    elseif ($rating >= 1000)
        $rank = 'Class C';
    elseif ($rating >= 800)
        $rank = 'Class D';
    elseif ($rating >= 600)
        $rank = 'Class E';
    elseif ($rating >= 400)
        $rank = 'Learner';
    elseif ($rating >= 200)
        $rank = 'Peasant';
    else
        $rank = 'Noob';

    if ($color === true) {
        if ($rating >= 1600)
            $rank = '<span style="color: #000000">'.$rank.'</span>';
        elseif ($rating >= 800)
            $rank = '<span style="color: #ff0000">' .$rank.'</span>';
        else
            $rank = '<span style="color: #008000">' .$rank.'</span>';
    }

    return $rank;
}


/**
 * Function getCostsGames
 * @param $steamID
 * @return float|int
 */
function getCostsGames($steamID)
{
    $result = @file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key='.STEAM_API_KEY.'&steamid='.$steamID.'&format=json');
    $json = json_decode($result)->response;
    $costsGames = 0;

    if ($json->game_count > 0) {
        foreach ($json->games as $gameVal)
        {
            $appid = $gameVal->appid;
            $result = @file_get_contents('http://store.steampowered.com/api/appdetails?appids='.$appid);
            $gameInfo = json_decode($result);
            $costsGames += round($gameInfo->$appid->data->price_overview->final / 100, 2);
            if ($costsGames >= 100)
                break;
        }
    }

    return $costsGames;
}

/**
 * @param $num
 * @param $type
 * @return mixed
 */
function getNumEnding($num, $type){
    $array['days'] = array('day', 'days');
    $array['hours'] = array('hour', 'hours');
    $array['minutes'] = array('minute', 'min');
    $array['seconds'] = array('second', 'seconds');

    if ($num > 1)
        $ending = $array[$type][1];
    else
        $ending = $array[$type][0];

    return $ending;
}

function GetSteamID64($steamid) {
    if (!$steamid)
        return false;

    $steamid = str_replace("STEAM_", "", $steamid);
    $split = explode(":", $steamid); 
    
    return ( ($split[2] * 2) + $split[1] + 76561197960265728 );
    /* u can use code below but this is fucking magic, kostyl' in ukrainial
    $steamid = str_replace("STEAM_", "", $steamid);
    $split = explode(":", $steamid); 
    $part = (($split[2] * 2) + 60265728 + $split[1]);
    $first = substr($part, 0, 1);
    return "7656119" . ( "79" + $first ) . substr($part, 1); //this line is fucking magic */
}

function partnerIdToSteamId($partner)
{
    return "STEAM_0:".($partner & 1).":".($partner >> 1);
}

function geturl($url, $ref, $cookie, $postdata, $header, &$info, $debug = false)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36');
    if ($ref)
        curl_setopt($ch, CURLOPT_REFERER, $ref);
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    }

    if ($postdata) {
        curl_setopt($ch, CURLOPT_POST, true);
        $postStr = "";
        foreach ($postdata as $key => $value)
        {
            if ($postStr)
                $postStr .= "&";
            $postStr .= $key . "=" . $value;
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postStr);
    }

    curl_setopt($ch, CURLOPT_HEADER, $header);
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    if ($info['http_code'] == 301 || $info['http_code'] == 302) {
        if ($debug) {
            echo "-----301-----\n";
            echo "INFO:\n";
            var_dump($info);
            echo "OUTPUT:\n";
            echo "---NEXT LINK-----\n";
            var_dump($output);
        }
        $url = $info['redirect_url'];
        $url_parsed = parse_url($url);
        return (isset($url_parsed))? geturl($url, $ref, $cookie, $postdata, $header, $info) : '';
    }

    return $output;
}

/**
 * 
 * @param type $trades
 * @return type
 */
function sendOfferRequest($trades, $limit = false)
{
    $data = steamConnect($cookies, $encryptedPassword, $rsaTime);
            
    if ($data['captcha_needed']) {
        return json_encode(array(
                "error" => $data['message'].". Captcha needed. #".$data['captcha_gid']
        ));
    } else {
        if ($data['success'] && $data['login_complete']) {
            $output = geturl($data['transfer_url'], null, $cookies, $data['transfer_parameters'], 0, $info);
            
            foreach($trades as $user => $data) {
                /*
                echo "Sending trade...<br/>";
                echo "User: $user<br/>";
                echo "Link: ".$data['link']."<br/>";
                echo "Items: <br/>";
                print_r($data['assets']);
                echo "<br/>";
                echo "____________________________________________";
                echo "<br/>";
                */
                
                $output = geturl($data['link'], null, $cookies, null, 0, $info);

                if(preg_match('/'.preg_quote('/?partner=','/').'(.*)'.preg_quote('&token=', '/').'/Us', $data['link'], $match)) {
                    $partner = $match[1];
                }

                if(preg_match('/'.preg_quote('&token=','/').'(.*)'.preg_quote('<<<eof', '/').'/Us',$data['link'].'<<<eof',$match)) {
                    $token = $match[1];
                }

                $sessionfile =  file($cookies);

                foreach($sessionfile as $row) {
                    if(!preg_match('/'.preg_quote('sessionid','/').'(.*)/', $row, $match)) {
                        continue;
                    } else {
                        $sessionid = trim($match[1]);
                        break;
                    }
                }

                //$token; //partner token
                //$sessionid; //gets from cookies
                $partner = GetSteamID64(partnerIdToSteamId($partner)); //STEAMID64 of who we will send request
                //above MEAN NUMBER OF ACTIONS (RemoveItemFromTrade | SetAssetOrCurrencyInTrade), "2" means one action - one item
                $version = count($data['assets'])+1; //hardcoded as "1" on tradelink line 620, updates on https://steamcommunity-a.akamaihd.net/public/javascript/economy_tradeoffer.js?v=zdJrcUIq7ZUP&l=english line 473
                $serverid = 1; //const on https://steamcommunity-a.akamaihd.net/public/javascript/economy_tradeoffer.js?v=zdJrcUIq7ZUP&l=english lime 537

                $json_tradeoffer = array(
                        'newversion' => true,
                        'version' => $version,
                        'me' => array(
                                'assets' => array(),
                                'currency' => array(),
                                'ready' => false
                        ),
                        'them' => array(
                                'assets' => $data['assets'],
                                'currency' => array(),
                                'ready' => false
                        )
                );

                $params = array(
                        'sessionid' => $sessionid,
                        'serverid' => $serverid,
                        'partner' => $partner,
                        'tradeoffermessage' => '',
                        'json_tradeoffer' => json_encode($json_tradeoffer),
                        'captcha' => '',
                        'trade_offer_create_params' => '{"trade_offer_access_token":"'.$token.'"}'
                );


                $output = geturl("https://steamcommunity.com/tradeoffer/new/send", $data['link'], $cookies, $params, 0, $info);

                if($json = json_decode($output, true)) {
                    if(isset($json['tradeofferid'])) {
                        $result[$user] = array(
                                "success" => true,
                                "message" => "Trade sent with trade id #".$json['tradeofferid']
                        );
                    } elseif(isset($json['strError'])) {
                        $result[$user] = array(
                                "success" => false,
                                "message" => $json['strError']
                        );
                    }
                } else {
                   $result[$user] = array(
                            "success" => false,
                            "message" => "UNKNOWN_ERROR"
                    );
                }
            }

            if($result) return json_encode($result);

        } elseif(!$data['login_complete']) {
            if(!$data['success'] && $data['emailauth_needed']) {
                if(authSteam($encryptedPassword, $rsaTime)) {
                    if(!$limit) {
                        return sendOfferRequest($trades, true);
                    }
//                    return false;
                } else {
                    return false;
                }
            }
        }
    }
}

/**
 * 
 * @param string $cookies
 * @param type $encryptedPassord
 * @return boolean
 */
function steamConnect(&$cookies, &$encryptedPassword, &$rsaTime) {
    include_once _SYSDIR_.'private/libs/phpseclib/Math/BigInteger.php';
    include_once _SYSDIR_.'private/libs/phpseclib/Crypt/RSA.php';
    
    $login = STEAM_LOGIN;
    $password = STEAM_PASSWORD;
    $cookies = _SYSDIR_.'private/cookies/cookies.txt';
    
    $output = geturl("https://store.steamcommunity.com", null , $cookies,  null, 0, $info);
    $output = geturl("https://steamcommunity.com/login/getrsakey", null, $cookies, array('username' => $login,'donotcache' => time()), 0, $info);
    
    $data = json_decode($output, true);
    
    if (!$data['success'] === true)
        return false;
    
    $publickey_exp = $data['publickey_exp'];
    $publickey_mod = $data['publickey_mod'];
    $RSA = new Crypt_RSA();
    $RSA->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
    $n = new Math_BigInteger($publickey_mod, 16);
    $e = new Math_BigInteger($publickey_exp, 16);

    $key = array("modulus"=>$n, "publicExponent"=>$e);
    $RSA->loadKey($key, CRYPT_RSA_PUBLIC_FORMAT_RAW);
    $encryptedPassword = base64_encode($RSA->encrypt($password, false));
    $encryptedPassword = str_replace('/','%2F',$encryptedPassword);
    $encryptedPassword = str_replace('+','%2B',$encryptedPassword);
    $encryptedPassword = str_replace('=','%3D',$encryptedPassword);
    
    $params = array(
            'username' => $login,
            'password' => $encryptedPassword,
            'rsatimestamp' => $data['timestamp'],
            'captcha_gid' => -1,
            'captcha_text' => '',
            'emailauth' => '',
            'emailsteamid' => ''
        );
    
    $rsaTime = $data['timestamp'];
    
    $output = geturl("https://steamcommunity.com/login/dologin/", null, $cookies, $params, 0, $info);
    return json_decode($output, true);
    
}

/**
 * 
 * @param type $encryptedPassword
 * @param type $rsaTime
 * @return boolean
 */
function authSteam($encryptedPassword, $rsaTime, $debug = false) {
    include_once _SYSDIR_.'private/libs/phpseclib/Math/BigInteger.php';
    include_once _SYSDIR_.'private/libs/phpseclib/Crypt/RSA.php';
    include_once _SYSDIR_.'system/inc/Imap.php';
    
    $login = STEAM_LOGIN;
    $password = STEAM_PASSWORD;
    $cookies = _SYSDIR_.'private/cookies/cookies.txt';

	if ($debug)
		echo "[".date("H:i:s")."] "."Auth manager loaded. Sleeping for 10 s."."\n";

    sleep(10);

	if ($debug)
		echo "[".date("H:i:s")."] "."Opening IMAP."."\n";

    if (!Imap::open(EMAIL_IMAP, EMAIL_USERNAME, EMAIL_PASSWORD)) //connection to emailbox
        return false;
        
	if ($debug)
		echo "[".date("H:i:s")."] "."Opened. Ping Steam."."\n";

    if (!geturl("https://steamcommunity.com", null , $cookies,  null, 0, $info)) //ping Steam
        return false;
        
	if ($debug)
		echo "[".date("H:i:s")."] "."Steam accessible."."\n";

    if (!$encryptedPassword)
        return false;

    $captchaGid = -1;
    $emailSteamId = null;
    $captchaText = null;
    
	if ($debug)
		echo "[".date("H:i:s")."] "."Searching list on email server via IMAP."."\n";
    
    Imap::search('BODY "Steam Guard code"');
    //Imap::search('ALL');
    $array = Imap::getMail();
    $codes = array();

	if ($debug)
		echo "[".date("H:i:s")."] "."Found ".count($array)." emails in INBOX."."\n";

    foreach ($array as $row){
        //if ($row['from'] == 'noreply@steampowered.com') {
            //if (preg_match("/Here\'s the Steam Guard code you\'ll need to complete the process\:/", $row['plain'])) {
            if (preg_match("/need to complete the process\:/", $row['plain'])) {
                if (preg_match("/\<h2\>([A-Z0-9]{5})\<\/h2\>/", ($row['html']), $code)) {
                    $codes[strtotime($row['date'])] = $code[1];
                }
            }
        //}
    }
    
	if ($debug) {
		echo "[".date("H:i:s")."] "."Found ".count($codes)." codes in INBOX."."\n";
		echo "\n";
		var_dump($codes);
		echo "\n";
		echo "\n";
	}

    if ($codes && count($codes) > 0) {
        if (krsort($codes))
            $emailAuth = $codes[key($codes)];
    }
    

    if (!$emailAuth)
        return false;

	if ($debug)
		echo "[".date("H:i:s")."] "."Found last code - ".$emailAuth."."."\n";
	
    $params = array(
        'username' => $login,
        'password' => $encryptedPassword,
        'rsatimestamp' => $rsaTime,
        'captcha_gid' => $captchaGid,
        'captcha_text' => $captchaText,
        'emailauth' => $emailAuth,
        'emailsteamid' => $emailSteamId
    );
    
	if ($debug)
		echo "[".date("H:i:s")."] "."Sending AUTH CODE to Steam."."\n";
    
    $output = geturl("https://steamcommunity.com/login/dologin/", null, $cookies, $params, 0, $info);
    $data = json_decode($output, true);

    if ($data['captcha_needed'])
        return false;        
    else {
        if($data['success'] && $data['login_complete']) {
            $output = geturl($data['transfer_url'], null, $cookies, $data['transfer_parameters'], 0, $info); //ping Steam
            return true;
        } elseif(!$data['login_complete']) {
            if (!$data['success'] && $data['emailauth_needed'])
                return false; //authSteam($encryptedPassword, $rsaTime);
        }
    }
    
    return false;
}

/**
 * @param $text
 * @return mixed
 */
function bb($text)
{
    $text = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $text);
    $text = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $text);
    $text = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $text);
    $text = preg_replace('#\[red\](.*?)\[/red\]#si', '<span style="color:#FF0000">\1</span>', $text);
    $text = preg_replace('#\[green\](.*?)\[/green\]#si', '<span style="color:#00FF00">\1</span>', $text);
    $text = preg_replace('#\[blue\](.*?)\[/blue\]#si', '<span style="color:#0000FF">\1</span>', $text);
    $text = preg_replace('#\[yellow\](.*?)\[/yellow\]#si', '<span style="color:yellow">\1</span>', $text);
    $text = preg_replace('#\[center\](.*?)\[/center\]#si', '<center>\1</center>', $text);
    $text = preg_replace('#\[url=(.*?)\](.*?)\[/url\]#si', '<a href="\1">\2</a>', $text);
    $text = preg_replace('#\[code\](.*?)\[/code\]#si', '<div class="code">\1</div>', $text);
    $text = preg_replace('#\[hr]#si', '<hr/>', $text);
    $text = preg_replace('#\[br]#si', '<br/>', $text);
    $text = preg_replace('#\r\n#si', '<br/>', $text);

    return $text;
}

/**
 * @param $text
 * @return mixed
 */
function smiles($text)
{
    $tags = array(
        ':)',
        ':D',
        ':P',
        ':shit:',
        ':bowl:',
        ':help:',
        ':mosking:',
        ':pray:',
    );

    $smiles = array(
        '<img src="'._SITEDIR_.'public/images/smiles/smile.gif">',
        '<img src="'._SITEDIR_.'public/images/smiles/biggrin.gif">',
        '<img src="'._SITEDIR_.'public/images/smiles/beee.gif">',
        '<img src="'._SITEDIR_.'public/images/smiles/shit.gif">',
        '<img src="'._SITEDIR_.'public/images/smiles/bowl.gif">',
        '<img src="'._SITEDIR_.'public/images/smiles/help.gif">',
        '<img src="'._SITEDIR_.'public/images/smiles/mosking.gif">',
        '<img src="'._SITEDIR_.'public/images/smiles/pray.gif">',
    );

    $text = str_replace($tags, $smiles, $text);

    return $text;
}
/* End of file */