//ajax操作提示
function optMsg(eleid, text, bgcolor){
    $('#' + eleid).text(text);
    $('#' + eleid).css({
        'background-color': bgcolor
    });
    $('#' + eleid).fadeIn().delay(2000).fadeOut(400);
}