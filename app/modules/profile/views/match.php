<script>
    var str = {last: 0, blocked: false};

    function show_loading(el) {
        var gif = '<?php echo _DIR_; ?>app/public/images/img/loading.gif';
        $(el).html('<img src="' + gif + '" class="loading_gif" alt="Loading..." style="width:50px;" />');
    }

    function exception(text) {
        var gif = $.find('.loading_gif');

        if (gif && gif.length > 0) {
            $(gif).each(function (id) { $($(gif[id]).parent('div')[0]).html(''); });
        }

        if(text.length > 0) {
            $('#status').html(text)
                .show();
            var timeout = window.setTimeout(function () {
                $('#status').hide()
                    .html("");
            }, 3000);
        }
    }

    function reloadInventory() {
        <?php echo ajaxLoad(url('profile', 'inventory'), 'inventory', 'mid:' . $this->match->id . '', 'resetTips|!exception'); ?>
    }

    function sync(){
        <?php echo ajaxLoad(url('profile', 'sync', $this->match->id), 'sync', '', 'resetTips|!exception'); ?>
    }

    function resetTips() {
        var assets = $('.assetsItem');
        if (assets.length > 0) {
            $(assets).each(function (el) {
                var id = $(assets[el]).attr('id');
                var name = $(assets[el]).attr('data-name');

                $('#' + id).unbind('click');
                $('#' + id).click(function () {
                    var fields = 'id:' + id + 'i|name:' + name;
                    var price = $('#p' + id).html();
                    //console.log(price);
                    if (price == 0)
                        ajaxLoad('<?php echo url('profile', 'itemprice'); ?>', 'itemprice', String(fields));

                    if($('#help_' + id).css('display') == 'none') {
                        $('.assetsInfo.active').hide();
                        $('.assetsItem.active').removeClass('active');
                        $('#' + id).addClass('active');
                        $('#help_' + id).show();
                        $('#help_' + id).addClass('active');
                        $('#help_' + id).css('position', 'absolute');
                        $('#help_' + id).css('z-index', '200');
                        $('#help_' + id).css('left', '0');
                        $('#help_' + id).css('margin-left', ($(this).offset().left - $(this).offsetParent().offset().left) + 'px' );
                        //$('#help_' + id).postion({at: 'bottom center', of: $(this), my: 'top'}); //require jQuery UI
                    } else {
                        $('#help_' + id).hide();
                        $('#help_' + id).removeClass('active');
                        $('#' + id).removeClass('active');
                    }
                });

                if (id.replace('my', '') != id) {
                    if ($('#i' + id.replace('my', '')).length) {
                        $('#i' + id.replace('my', '')).css('opacity', '0.4');
                    } else {
                        $('#i' + id).css('opacity', '0.4');
                        $('#i' + id).css('border', '2px solid red');
                    }
                }
            });
        }
    }

    function addAsset(id) {
        if ($('#i' + id).length && !$('#my' + id).length) {
            if(!str.blocked) {
                str.blocked = true;
                <?php echo ajaxLoad(url('profile', 'addAsset'), 'add_asset', 'assetId:\' + id + \'|mid:' . $this->match->id . '', 'addAssetAct|!exception'); ?>
            }
        }
    }

    function addAssetAct(data) {
        if (data) {
            var id = data.id;
            var assetNo = data.assetNo;

            if ($('#i' + id).length && !$('#my' + id).length) {
                var src = $('#i' + id).html();

                $('#myAssets').append('<div class="assetsItem" id="my' + id + '" ondblclick="removeAsset(\'' + id + '\')">'
                    + src
                    + '<div class="none" id="myAssetNo' + id + '">' + assetNo + '</div>'
                    + '</div>'
                );
            }

            str.blocked = false;

            sync();
            resetTips();
        }
    }

    function removeAsset(id) {
        if ($('#my' + id).length) {
            var assetNo = $('#my' + id).children('#myAssetNo' + id).html();
            <?php echo ajaxLoad(url('profile', 'removeAsset'), 'add_asset', 'aid:\'+id+\'|id:\'+assetNo+\'|mid:' . $this->match->id . '', 'rmAssetAct|!exception'); ?>;
        }
    }

    function rmAssetAct(inf) {
        if (inf) {
            var id = inf.id;

            if (!inf.blocked && $('#my' + id).length) {

                $('#my' + id).remove();
                if ($('#i' + id.replace('my', '')).length) {
                    $('#help_' + id.replace('my', '')).hide();
                    $('#i' + id.replace('my', '')).css('opacity', '1');
                }
            }

            sync();
            resetTips();
        }
    }

    $(function () {
        var partner = '<?php echo Request::getParam('user')->partner; ?>';
        var token = '<?php echo Request::getParam('user')->token; ?>';

        if (token.length == 0 || partner.length == 0) {
            alert('{L:MATCH_TRADE_MUST}');
            window.location.href = '{URL:settings/general}';
        }

        <?php if ($this->uid->id == Request::getParam('user')->id OR $this->pid->id == Request::getParam('user')->id): ?>
        /* initial load data */
        var inventory = function(){
            show_loading('#assets');
            reloadInventory();
        }
        var syncUsers = function(){
            show_loading("#themAssets");
            show_loading('#myAssets');
            sync();
        }

        $.when(inventory()).then(syncUsers());

        <?php endif;?>
    });
</script>
<!--interval updates-->

<?php
if ($this->uid->id == Request::getParam('user')->id OR $this->pid->id == Request::getParam('user')->id OR Request::getRole() == 'admin') {
	ajaxSetInterval('sync()', 2000, 'show_loading("#themAssets");show_loading("#myAssets")');
	if ($this->uid->id == Request::getParam('user')->id OR $this->pid->id == Request::getParam('user')->id) {
		ajaxSetInterval('reloadInventory()', 300000, 'show_loading("#assets")');
	}

}
?>

<!-- HEAD -->

 <!--   <div>
        <b>
            <?php echo $this->pid->nickname . ' {L:MATCH_VS} ' . $this->uid->nickname; ?>
        </b>

        <?php
switch ($this->match->status) {
case 2:
	$status = '{L:MATCH_CLOSED}';
	break;
case 1:
	$status = '{L:MATCH_ACTIVE}';
	break;
default:
	$status = '{L:MATCH_PENDING_CONFIRM}';
}
?>

        <?php echo $status; ?>
    </div>
-->

    <div class="matchBody clearfix">
        <div class="matchLeftInfo">
            <div class="avatar">
                <img src="<?php echo getAvatar($this->uid->id); ?>" alt="avatar" />
                <div class="nick">
                    <a href="<?php echo url($this->uid->id); ?>">
                        <?php echo $this->uid->nickname; ?>
                    </a>
                    <?php echo (($this->uid->country) ? '<img src="' . _SITEDIR_ . 'public/images/country/' . mb_strtolower($this->uid->country) . '.png">' : ''); ?>
                </div>
                <!--<div class="rank"><?php echo getRank($this->uid->elo); ?></div>-->
            </div>

            <div class="lDataInfo">
                <div>{L:INDEX_RANK}: <?php echo getRank($this->uid->elo); ?></div>
                <div>{L:INDEX_ELO}: <?php echo $this->uid->elo; ?></div>
                <div>{L:LADDERS_WINS}: <?php echo $this->uid->wins; ?></div>
                <div>{L:LADDERS_TIES}: <?php echo $this->uid->ties; ?></div>
                <div>{L:LADDERS_LOSSES}: <?php echo $this->uid->losses; ?></div>
                <?php /*
<div>
{L:MATCH_START_TIME}:
<?php echo (($this->uid->id == Request::getParam('user')->id) ? '<input type="datetime-local" id="time" value="'.$this->match->pTime.'">' : $this->match->pTime); ?>
</div>
<div>
{L:MATCH_MAP}:
<?php echo (($this->uid->id == Request::getParam('user')->id) ? '<input type="text" id="map" value="'.$this->match->pMap.'">' : $this->match->pMap); ?>
</div>
 */?>
            </div>
        </div>

        <div class="match-vs"></div>

        <div class="matchRightInfo">
            <div class="avatar"><img src="<?php echo getAvatar($this->pid->id); ?>" alt="avatar" />
                <div  class="nick">
                    <a href="<?php echo url($this->pid->id); ?>"><?php echo $this->pid->nickname; ?></a>
                    <?php echo (($this->pid->country) ? '<img src="' . _SITEDIR_ . 'public/images/country/' . mb_strtolower($this->pid->country) . '.png">' : ''); ?>
                </div>
                <!--<div  class="rank"><?php echo getRank($this->pid->elo); ?></div>-->
            </div>

            <div class="rDataInfo">

                <div>{L:INDEX_RANK}: <?php echo getRank($this->pid->elo); ?></div>
                <div>{L:INDEX_ELO}: <?php echo $this->pid->elo; ?></div>

                <div>{L:LADDERS_WINS}: <?php echo $this->pid->wins; ?></div>
                <div>{L:LADDERS_TIES}: <?php echo $this->pid->ties; ?></div>
                <div>{L:LADDERS_LOSSES}: <?php echo $this->pid->losses; ?></div>
                <?php /*
<div>
{L:MATCH_START_TIME}:
<?php echo (($this->pid->id == Request::getParam('user')->id) ? '<input type="datetime" id="time" value="'.$this->match->uTime.'">' : $this->match->uTime); ?>
</div>
<div>
{L:MATCH_MAP}:
<?php echo (($this->pid->id == Request::getParam('user')->id) ? '<input type="text" id="map" value="'.$this->match->uMap.'">' : $this->match->uMap); ?>
</div>
 */?>
            </div>
        </div>
    </div>

    <div class="clear"></div>

<?php
if (Request::getRole() == 'admin') {
	echo '<div class="btn control-panel" style="margin-top: 15px;" onclick="' . ajaxLoad(url('profile', 'control_match'), 'control_match', 'id:' . $this->match->id) . '">{L:MATCH_CONTROL}</div>';
	echo '<div id="control"></div>';
}
?>
<?php
// require _BASEPATH_ . '/SourceQuery/bootstrap.php';
// use xPaw\SourceQuery\SourceQuery;
// Header('Content-Type: text/plain');
// Header('X-Content-Type-Options: nosniff');

// define('SQ_SERVER_ADDR', '162.251.166.186');
// define('SQ_SERVER_PORT', 27016);
// define('SQ_TIMEOUT', 1);
// define('SQ_ENGINE', SourceQuery::SOURCE);

// $Query = new SourceQuery();

// try
// {
// 	$Query->Connect(SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE);

// 	echo ($Query->GetInfo());
// 	echo ($Query->GetPlayers());
// 	echo ($Query->GetRules());
// } catch (Exception $e) {
// 	echo $e->getMessage();
// } finally {
// 	$Query->Disconnect();
// }
?>
<div id="map_note"></div>

<!-- END HEAD -->

<!-- CONTENT -->

<?php if ($this->uid->id == Request::getParam('user')->id || $this->pid->id == Request::getParam('user')->id || Request::getRole() == 'admin') {
	?>

    <!-- MATCH RESULT -->

    <div id="battle">
      <?php
if ($this->match->pready == 0 AND $this->match->uready == 0 AND $this->math->status == 1) {
		echo '<div id="status_action">';
		echo '<span id="cancelMatch' . $this->match->id . '" class="cancel-match-btn" ><a onclick="ajaxLoad(\'' . url('profile', 'matchCancel') . '\',\'reqest\',\'mid:' . $this->match->id . '\');" >{L:MATCH_CANCEL}</a></span>';
		echo "</div>";
	}
	?>
    </div>

    <!-- END MATCH RESULT -->

    <!-- STATUS -->

    <div id="status"></div>

    <!-- END STATUS -->

    <div class="clear"></div>


    <!-- MY BETS -->
    <div class="match-items">
        <div class="matchLeft">
            <h1>{L:MATCH_MY_INVENTORY}</h1>
            <div id="usum"></div>

            <div id="myAssets"></div>

            <div id="myReady"></div>
        </div>

        <!-- END MY BETS -->

        <!-- OPPOSITE BETS -->

        <div class="matchRight ">
            <h1>{L:MATCH_THEM_INVENTORY}</h1>
            <div id="psum"></div>

            <div id="themAssets"></div>

            <div id="themReady"></div>
        </div>
    </div>
    <!-- END OPPOSITE BETS -->

    <div class="clear"></div>

<?php
}
if ($this->uid->id == Request::getParam('user')->id || $this->pid->id == Request::getParam('user')->id) {
	?>

    <!-- INVENTORY -->

    <div class="matchLeft inventory">
        <div style="text-align: center;">
            <a onclick="show_loading('#assets'); reloadInventory();" >
                {L:MATCH_LOAD_INVENTORY}
            </a>
        </div>

        <div id="assets"></div>
    </div>

    <?php }
?>

    <!-- END INVENTORY -->

    <!-- CHAT -->

    <?php
if ($this->uid->id == Request::getParam('user')->id || $this->pid->id == Request::getParam('user')->id || Request::getRole() == 'admin'):
	echo ajaxSetInterval(ajaxLoad(url('matchgetchat'), 'chat_get', 'mid:' . $this->match->id, 'chatScroll'), 2400);
	echo ajaxKeyDown(13, '$(".chatSubmit").click();');
	?>

								    <div class="matchRight chat">
								        {L:MATCH_CHAT}:<br/>

								        <div id="dialog" class="matchChatBody">
								            <?php
	while ($list = mysqli_fetch_object($this->list)) {
		$value = (array) $list;
		echo '<div class="chat_message">'
		. '<div class="chat_img"><a href="' . url($value['uid']) . '" target="_blank"><img src="' . getAvatar($value['uid'], 's') . '" alt="avatar"/></a></div>'
		. '<div class="chat_text">'
		. '<div><span class="chat_nickname" onclick="chatNickname(\'' . $value['uName'] . '\');">' . $value['uName'] . '</span> <span class="chat_time">' . printTime($value['time']) . '</span></div>'
			. '<div>' . $value['message'] . '</div>'
			. '</div>'
			. '</div>';

		setSession('match_chat_lid' . $list->mid, $list->id);
	}
	?>
								        </div>

								        <script>
								            chatScroll('dialog');
								        </script>

								        <div class="chatInput">
								            <input id="msg" class="chatMsg" type="text" autocomplete="off" maxlength="1000">
								            <div class="chatSubmit" onclick="<?php echo ajaxLoad(url('matchsendchat'), 'chat_get', '#msg|mid:' . $this->match->id, 'ajaxFocus'); ?>">{L:MATCH_CHAT_SEND}</div>
								        </div>
								    </div>

								    <!-- END CHAT -->

								    <div class="clear"></div>

								<?php endif;?>
<!-- END CONTENT -->