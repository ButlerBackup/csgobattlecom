<script>
    function generateHTML()
    {
        var name = $("#name").val().trim();
        var addr = $("#addr").val().trim();
        var pic = $("#pic").val().trim();
        var html = "{L:SERVERS_ENTER_CODE}";

        if(name.length > 0 && addr.length > 0 && pic.length > 0) {
            html = '<a href="' + addr + '">'
            + '<img src="' + pic + '" alt="' + name + '">'
            + '</a>';
        }

        $("#code").text(html);
    }

    function addServer()
    {
        var name = $("#name").val().trim();
        var addr = $("#addr").val().trim();
        var pic = $("#pic").val().trim();

        if(name.length > 0 && addr.length > 0 && pic.length > 0) {
            <?php echo ajaxLoad(url('admin', 'servers', 'add'), 'add', '#name|#addr|#pic', 'hideForm|getServers') ?>;
        }

    }

    function saveServer(id)
    {
        var name = $("#name").val().trim();
        var addr = $("#addr").val().trim();
        var pic = $("#pic").val().trim();

        if(name.length > 0 && addr.length > 0 && pic.length > 0 && id > 0) {
            <?php echo ajaxLoad(url('admin', 'servers', 'save', "' + id + '"), 'save', '#name|#addr|#pic', 'hideForm|getServers') ?>;
        }
    }

    function addForm() {
        $("#form").show();
        $("#cancelBtn").show();
        $("#addBtn").click(function(){ addServer(); });
    }

    function hideForm() {
        $("#name").val("");
        $("#addr").val("");
        $("#pic").val("");
        $("#code").text("{L:SERVERS_ENTER_CODE}");
        $("#addBtn").click(function(){ addForm(); });
        $("#addBtn").html('{L:SERVERS_ADD_SERVER}');
        $("#form").hide();
        $("#cancelBtn").hide();
    }

    function editForm(data) {
        if(data.name && data.addr && data.pic) {
            $("#name").val(data.name);
            $("#addr").val(data.addr);
            $("#pic").val(data.pic);
            generateHTML();
            $("#addBtn").html('{L:SERVERS_EDIT_SAVE}');
            $("#addBtn").click(function(){ saveServer(data.id); }); /*todo fix*/
            $("#cancelBtn").show();
            $("#form").show();
        }
    }

    function editServer(id) {
        <?php echo ajaxLoad(url('admin', 'servers', 'edit', "' + id + '"), 'edit', '', 'editForm') ?>;
    }

    function getServers(){
        <?php echo ajaxLoad(url('admin', 'servers', 'get'), 'get') ?>;
    }

    function delServer(sid) {
        <?php echo ajaxLoad(url('admin', 'servers', 'delete', "' + sid + '"), 'delete', '', 'getServers') ?>;
    }

    $(function(){
        getServers();
        hideForm();
    });
</script>

<div>
    <h1>{L:SERVERS_HEAD}</h1>

    <div>
        <div id="form">
            <div class="row">
                <div class = "column">
                    <label for="name">{L:SERCERS_NAME}</label>
                </div>
                <div class="column">
                    <input type="text" name="name" id="name" placeholder="{L:SERVERS_ENTER_NAME}" onkeyup="generateHTML();" />
                </div>
            </div>

            <div class="row">
                <div class="column">
                    <label for="addr">{L:SERVERS_ADDR}</label>
                </div>
                <div class="column">
                    <input type="text" name="addr" id="addr" placeholder="{L:SERVERS_ENTER_ADDR}" onkeyup="generateHTML();" />
                </div>
            </div>

            <div class="row">
                <div class="column">
                    <label for="pic">{L:SERVERS_PICTURE}</label>
                </div>
                <div class="column">
                    <input type="text" name="pic" id="pic" placeholder="{L:SERVERS_ENTER_PICTURE}" onkeyup="generateHTML();"/>
                </div>
            </div>

            <div class="row">
                <div class="column">
                    <label for="code">{L:SERVERS_CODE}</label>
                </div>
                <div class="column">
                    <textarea name="code" id="code" onclick="generateHTML();">{L:SERVERS_ENTER_CODE}</textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="column">
                <button id="addBtn" onclick="addForm();">{L:SERVERS_ADD_SERVER}</button>
                <button id="cancelBtn" onclick="hideForm();">{L:SERVERS_CANCEL_ADD_SERVER}</button>
            </div>
        </div>
    </div>

    <hr/>

    <div id="servers">
    </div>
</div>