/**
 * Created by ostfr on 09.12.2015.
 */

    function hideOnClick(hidethis){
        $(hidethis).hide();
    }

    function doEdit(editThis,itemClass, id){
        ajaxLoadFucnction =
        editThis.replaceWith("<input id='"+id+"' class='"+itemClass+"' value='"+editThis.text()+"' />");
    }


    $(document).ready(function(){

        $(".hideOnClick").click(function(){
            hidethis = $(this);
            hideOnClick(hidethis);
        });

        $(document).on("click","a.steam-trade-link",function(){
            doEdit($(this),"steam-trade-link",'steam-trade-link');
        });

        $(document).on("change","input#steam-trade-link",function(){
            var url = $(this).val();
            var value = "tradelink:"+url.replace(":",".!.");

            ajaxLoad("/profile/savetradelink","reqest",value);
            $(this).replaceWith('<a style="font-size:12px;cursor:pointer" class="steam-trade-link" >'+url+'</a>')
        });

    });

