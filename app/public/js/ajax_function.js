var ajaxVars = {};
ajaxVars.intervals = [];

/**
 * Function load ajax page
 * @param url
 * @param permit
 * @param target
 * @param type
 * @param fields
 * @param func
 */
function ajaxLoad(url, permit, fields, func, type)
{
    permit = permit || false;
    fields = fields || false;
    func = func || false;
    type = (type == 'GET') ? 'GET' : 'POST';
    var data = {};

    if (permit && typeof ajaxVars[permit] === 'undefined')
        ajaxVars[permit] = true;

    if (!permit || ajaxVars[permit] === true) {
        if (permit)
            ajaxVars[permit] = false;

        if (fields) {
            fields = fields.split('|');

            for (var i = 0; i < fields.length; i++) {
                var value = fields[i].replace(":", "");
                if (fields[i] == value) {
                    value = fields[i].replace("!", "");
                    var key = value.replace(".", "_");
                    key = key.replace("#", "__");
                    data[key] = $(value).val();
                    if (fields[i] == value)
                        $(value).val('');
                } else {
                    var myField = fields[i].split(':');
                    if (typeof window[myField[1]] === 'undefined')
                        data[myField[0]] = myField[1];
                    else
                        data[myField[0]] = window[myField[1]];
                }
            }
        }

        if (func)
            func = func.split('|');

        $.ajax({
            url: url,
            type: type,
            data: data,
            dataType: 'json',
            success: function (result) {
                if (result.error == false) {
                    if (result.delete != false) {
                        for (var key in result.delete)
                            $(key).remove();
                    }

                    if (result.target_h != false) {
                        for (var key in result.target_h)
                            $(key).html(result.target_h[key]);
                    }

                    if (result.target_p != false) {
                        for (var key in result.target_p)
                            $(key).prepend(result.target_p[key]);
                    }

                    if (result.target_a != false) {
                        for (var key in result.target_a)
                            $(key).append(result.target_a[key]);
                    }

                    if (result.target_v != false) {
                        for (var key in result.target_v)
                            $(key).val(result.target_v[key]);
                    }

                    if (func) {
                        for (var i = 0; i < func.length; i++) {
                            var value = func[i].replace("!", "");
                            if (func[i] == value)
                                window[value](result);
                            // TODO
                            //window[value](result);
                        }
                    }
                } else {
                    if (func) {
                        for (var i = 0; i < func.length; i++) {
                            var value = func[i].replace("!", "");
                            if (func[i] != value)
                                window[value](result.error);
                        }
                    } else {
                        ajaxPrint(result.error);
                    }
                }
            },
            error: function (result) {
                //alert("Error!");
            }
        })
        if (permit)
            ajaxVars[permit] = true;
    }
}

/**
 * Function ajaxScroll
 * @param target
 */
function ajaxScroll(target)
{
    target = target || 'dialog';
    var block = document.getElementById(target);
    block.scrollTop = block.scrollHeight;
}

/**
 * Function ajaxFocus
 * @param target
 */
function ajaxFocus(target)
{
    target = target || '#msg';
    $(target).focus();
}

/**
 * Function ajaxPrint
 * @param text
 */
function ajaxPrint(text)
{
    alert(text);
}

/**
 * Function ajaxCheckUrl
 * @param url
 * @param result
 */
function ajaxCheckUrl(url, result)
{
    var objRE = /^(https?:\/\/)?([0-9a-z][0-9a-z\-]+\.[0-9a-z]+)+$/i;
    if (objRE.test($(url).val()))
        $(result).html('yes');
    else
        $(result).html('no');
}

/**
 * Function ajaxFill
 * @param target
 * @param content
 */
function ajaxFill(target, content)
{
    if (target !== false) {
        target = target.split('|');

        for (var i = 0; i < target.length; i++) {
            var key = target[i].replace("(p)", "");
            key = key.replace("(a)", "");
            key = key.replace("(h)", "");

            if (target[i] == key+'(p)')
                $(key).prepend(content);
            else if (target[i] == key+'(a)')
                $(key).append(content);
            else
                $(key).html(content);
        }
    }
}

/**
 * @param target
 * @param content
 */
function ajaxFillField(target, content)
{
    $(target).val(content);
}

function ajaxClear(target)
{
    $(target).html('');
}

function ajaxRemove(target)
{
    $(target).remove();
}