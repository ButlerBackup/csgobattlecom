<script>
    function searchUsers() {
        <?php echo ajaxLoad(url('admin','search_users'), 'process', '#uid!|#nickname!|#steamid!|#role!|page:1'); ?>
    }
</script>

<div class="box">
    <h1>{L:USERS_TITLE}</h1>

    <div class="usersSearch">
        <input type="text" id="uid" placeholder="{L:USERS_ID}" style="width: 90px;"/>
        <input type="text" id="nickname" placeholder="{L:USERS_NICKNAME}" style="width: 150px;"/>
        <input type="text" id="steamid" placeholder="{L:USERS_STEAMID}" style="width: 150px;"/>
        <select id="role" onchange="searchUsers();">
            <option value="">{L:USERS_ALL}</option>
            <option value="claim">{L:USERS_CLAIM}</option>
            <option value="user">{L:USERS_USER}</option>
            <option value="moder">{L:USERS_MODER}</option>
            <option value="admin">{L:USERS_ADMIN}</option>
            <option value="ban">{L:USERS_BANNED}</option>
        </select>
        <button class="btn" onclick="searchUsers();">{L:USERS_SEARCH}</button>
    </div>

    <div id="listing"></div>
</div>