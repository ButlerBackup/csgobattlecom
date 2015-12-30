var scrollChat = false;

function chatScroll(target)
{
    target = 'dialog';

    var block = document.getElementById(target);

    if (scrollChat === false) {
        block.scrollTop = block.scrollHeight;
        scrollChat = block.scrollTop;
    }

    if (block.scrollTop >= scrollChat) {
        block.scrollTop = block.scrollHeight;
        scrollChat = block.scrollTop;
    }
}

function docW() {
    return $(document).width();
}

function docH() {
    return $(document).height();
}

function winW() {
    return $(window).width();
}

function winH() {
    return $(window).height();
}

function openPopup() {
    $("html,body").css("overflow","hidden");
}

function closePopup(target) {
    ajaxClear(target);
    $("html,body").css("overflow","auto");
}

function setReadMsg()
{
    target = target || '#msg';
    $(target).focus();
}

function delClass(el)
{
    var el2 = $(el).parent().parent().parent();
    $(el2).removeClass('gray');
}

function chatNickname(nickname)
{
    $('#msg').val($('#msg').val() + nickname +', ');
    $('#msg').focus();
}