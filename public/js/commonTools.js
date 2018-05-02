function ajaxProcess(data, callback) {//console.log(data);
    if (data.errCode == 1) {
        alert(data.errMsg);
    } else if (data.errCode == 0) {
        alert(data.errMsg);
        callback(data);
    } else if (data.errCode == 2) {
        for (i in data.data.error) {
            alert(data.data.error[i]);
            return false;
        }
    }
}

function ajax(url, data, type, succ) {
    if (typeof type != 'undefined') {
        data._method = type;
    }
    $.ajax({
        url: url,
        data: data,
        dataType: 'json',
        type: 'POST',
        success: function (data, status, xhr) {
            ajaxProcess(data, succ)
        },
        error: function (xhr, status, error) {
            //ajaxProcess(xhr.status,xhr.responseText);
            console.log('ajax error:' + xhr.status);
        }
    });
}
function uploadImageBox(dom,url,value,height){
    var name = dom.attr('upload-name');
    if(typeof name=='undefined'){
        console.log('upload upload-name is undefined!');
    }
    //文件上传input
    $('body').append('<input name="upload_obj_'+name+'" id="upload_obj_'+name+'" type="file" style="display: none">');
    //文件地址input，预览
    dom.append('<input type="hidden" name="'+name+'">');
    dom.append('<div class="upload-preview" id="upload_preview_'+name+'"></div>');
    dom.append('<div class="upload-progress" id="upload_progress_'+name+'"></div>');

    var input = $('input[name="'+name+'"]');
    var upload_obj = $('#upload_obj_'+name);
    var upload_preview = $('#upload_preview_'+name);
    var upload_progress = $('#upload_progress_'+name);
    //
    //上传事件触发
    upload_preview.click(function(){
        //upload_obj.trigger('click');//这样只能上传一次
        //var a=document.createEvent("MouseEvents");//FF的处理
        //a.initEvent("click", true, true);
        //document.getElementById('upload_obj_'+name).dispatchEvent(a);
        $('#upload_obj_'+name).click();

    });

    //初始化显示
    if(value){
        upload_preview.append('<img style="max-height: '+height+'px" src="'+value+'">');
        input.val(value);
    }else{
        upload_preview.html('<div class="upload-btn">上传</div>');
    }

    upload_obj.fileupload({
        url: url
    }).bind('fileuploadprogress', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        upload_progress.show().css('width',progress+'%');
        console.log(progress);
    }).bind('fileuploaddone', function (e, data) {
        data = data.result;
        console.log(data);
        //data[0]是缩略图，data[1]大图
        if(upload_preview.find('img').length==0){
            upload_preview.empty().append('<img>');
        }
        upload_preview.find('img').attr('src',data.files[0].thumb[0]).show(200).css('max-height',height);
        input.val(data.files[0].filename);
        upload_progress.hide();

    });
}

$(function () {
    $.ajaxSetup({
        headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')}
    });
    $('.body-container').on('click', '.can_jump', function () {
        if (!checkLogin()) {
            alert('未登录')
            return;
        }

        var url = $(this).attr('_url');
        if (!url) {
            return;
        }
        location.href = url;
    })
    $('.body-container').on('click', '.pop-close', function () {
        $(this).closest('.pop-container').hide();
    })

    $('.body-container').on('click', '.htab-title', function () {
        $(this).closest('.htab-item').toggleClass('active')
    })
})

