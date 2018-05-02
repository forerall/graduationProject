/**
 * Created by arts-mgcx on 2016/10/19.
 */


$(function(){
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
    });
    //刷新
    if(localStorage&&localStorage.getItem('reload')){
        localStorage.setItem('reload','');
        location.reload();
    }

    //返回
    $('body').on('click','.event-back',function(){
        if($(this).data('refer')){
            location.href = $(this).data('refer');
            return;
        }
        history.back();
    });
})
//提示
function tip(msg){
    swal({
        title: msg,
        text: "",
        type: "error",
        showCancelButton: false,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        closeOnConfirm: true,
        closeOnCancel: true
    });
}
function showMessage(msg,type,callback){
    if(type=='error'){
        swal({
            title: msg,
            text: "",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
            closeOnCancel: true
        });
    }else if(type == 'success'){
        swal({
            title: msg,
            text: "",
            type: "success",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
            closeOnCancel: true
        },function(){
            if(typeof callback == 'function') {
                callback();
            }
        });
    }else if(type == 'warn'){
        swal({
            title: msg,
            text: "",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
            closeOnCancel: true
        });
    }
}
function showConfirm(title,text,type,succ){
    swal({
        title: title,
        text: text,
        type: type,//"warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText:"取消",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(){
        succ();
    });
}


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
function ajaxConfirm(url,data,type,succ,title,text){
    showConfirm(title,text,'warning',function(){
        ajax(url,data,type,succ);
    });
}



