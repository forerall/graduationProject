/**
 * 前端
 */


$(function(){
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
    });
})

//ajax
function ajaxProcess(data,callback){//console.log(data);
    if(data.errCode == 1){
        showMessage(data.errMsg,'error');
    }else if(data.errCode == 0){
        showMessage(data.errMsg,'success',function(){

            if(typeof callback == 'function'){
                callback(data);
            }
        });
    }else if(data.errCode == 2){
        for(i in data.data.error){
            tip(data.data.error[i]);return false;
        }
    }

}
function ajax(url,data,type,succ){
    if(typeof type != 'undefined'){
        data._method = type;
    }
    $.ajax({
        url:url,
        data:data,
        dataType:'json',
        type:'POST',
        success:function(data,status,xhr){
            ajaxProcess(data,succ)
        },
        error: function (xhr, status, error) {
            //ajaxProcess(xhr.status,xhr.responseText);
            console.log('ajax error:'+xhr.status);
        }
    });
}




