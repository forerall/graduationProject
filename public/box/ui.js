/**
 * Created by arts-mgcx on 2017/1/12.
 */

/**
 * 上传轮播图
 * @param dom
 * @param url
 * @param value
 * @param height
 */
function multiUploadBannerBox(dom, url, value, height, jump_types) {
    var name = dom.attr('upload-name');
    if (typeof name == 'undefined') {
        console.log('upload upload-name is undefined!');
    }
    //文件上传input
    $('body').append('<input name="upload_obj_' + name + '" id="upload_obj_' + name + '" type="file" style="display: none">');
    //文件地址input，预览
    //dom.append('<input type="hidden" name="'+name+'[]">');
    dom.append('<div class="upload-preview" id="upload_preview_' + name + '"></div>');
    dom.append('<div class="upload-progress" id="upload_progress_' + name + '"></div>');

    //var input = $('input[name="'+name+'"]');
    var upload_obj = $('#upload_obj_' + name);
    var upload_preview = $('#upload_preview_' + name);
    var upload_progress = $('#upload_progress_' + name);
    //
    //上传事件触发
    upload_preview.on('click', '.upload-btn', function () {
        //upload_obj.trigger('click');//这样只能上传一次
        //var a=document.createEvent("MouseEvents");//FF的处理
        //a.initEvent("click", true, true);
        //document.getElementById('upload_obj_'+name).dispatchEvent(a);
        $('#upload_obj_' + name).click();

    });

    //初始化显示
    if (value) {
        for (i in value) {
            var t = '<div class="img-item-container"><div class="img-item"><div class="img-item-close">x</div><img style="max-height: ' + height + 'px" src="' + value[i].image + '">'
                + '<input type="hidden" name="' + name + '['+i+'][image]" value="' + value[i].image + '"></div>' +
                '<div class="options">' +
                '<p><label>跳转类型：</label><select name="'+name+'['+i+'][jump_type]">';
            for (var x in jump_types) {
                if (value[i].jump_type == x) {
                    t += '<option value="' + x + '" selected="selected">' + jump_types[x] + '</option>'
                } else {
                    t += '<option value="' + x + '">' + jump_types[x] + '</option>'
                }
            }
            t += '</select></p>' +
                '<p><label>跳转Id：&nbsp;&nbsp;&nbsp;&nbsp;</label><input  name="'+name+'['+i+'][jump_value]" value="' + (value[i].jump_value||'') + '"></p>' +
                '</div>' +
                '</div>';
            upload_preview.append(t);
        }
    }
    upload_preview.append('<div class="upload-btn">添加</div>');

    upload_obj.fileupload({
        url: url + '&name=upload_obj_' + name
    }).bind('fileuploadprogress', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        upload_progress.show().css('width', progress + '%');
        console.log(progress);
    }).bind('fileuploaddone', function (e, data) {
        data = data.result;
        console.log(data);
        var uid = new Date().getTime();
        var t = '<div class="img-item-container">' +
            '<div class="img-item">' +
            '<div class="img-item-close">x</div>' +
            '<img style="max-height: ' + height + 'px" src="' + data.files[0].thumb[0] + '">' +
            '<input type="hidden" name="' + name + '['+uid+'][image]" value="' + data.files[0].filename + '">' +
            '</div>' +
            '<div class="options">' +
            '<p><label>跳转类型：</label><select name="'+name+'['+uid+'][jump_type]">';
        for (var x in jump_types) {
            t += '<option value="' + x + '">' + jump_types[x] + '</option>'
        }
        t += '</select></p>' +
            '<p><label>跳转Id：&nbsp;&nbsp;&nbsp;&nbsp;</label><input  name="'+name+'['+uid+'][jump_value]"></p>' +
            '</div>' +
            '</div>';
        $(t).insertBefore($('.upload-btn'));
        upload_progress.hide();

    });

    $('#upload_preview_' + name).on('click', '.img-item-close', function () {
        $(this).closest('.img-item-container').remove();
    })
}
/**
 * 上传图集
 * @param dom
 * @param url
 * @param value
 * @param height
 */
function multiUploadImageBox(dom,url,value,height){
    var name = dom.attr('upload-name');
    if(typeof name=='undefined'){
        console.log('upload upload-name is undefined!');
    }
    //文件上传input
    $('body').append('<input name="upload_obj_'+name+'" multiple id="upload_obj_'+name+'" type="file" style="display: none">');
    //文件地址input，预览
    //dom.append('<input type="hidden" name="'+name+'[]">');
    dom.append('<div class="upload-preview" id="upload_preview_'+name+'"></div>');
    dom.append('<div class="upload-progress" id="upload_progress_'+name+'"></div>');

    //var input = $('input[name="'+name+'"]');
    var upload_obj = $('#upload_obj_'+name);
    var upload_preview = $('#upload_preview_'+name);
    var upload_progress = $('#upload_progress_'+name);
    //
    //上传事件触发
    upload_preview.on('click','.upload-btn',function(){
        //upload_obj.trigger('click');//这样只能上传一次
        //var a=document.createEvent("MouseEvents");//FF的处理
        //a.initEvent("click", true, true);
        //document.getElementById('upload_obj_'+name).dispatchEvent(a);
        $('#upload_obj_'+name).click();

    });

    //初始化显示
    if(value){
        for(i in value){
            upload_preview.append(
                '<div class="img-item"><div class="img-item-close">x</div><img style="max-height: '+height+'px" src="'+value[i]+'">'
                +'<input type="hidden" name="'+name+'[]" value="'+value[i]+'"></div>');
        }
    }
    upload_preview.append('<div class="upload-btn">添加</div>');

    upload_obj.fileupload({
        //url: url+'&name=upload_obj_'+name
        url: url
    }).bind('fileuploadprogress', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        upload_progress.show().css('width',progress+'%');
        console.log(progress);
    }).bind('fileuploaddone', function (e, data) {
        data = data.result;
        console.log(data);
        for(var i in data.files){
            $('<div class="img-item"><div class="img-item-close">x</div><img style="max-height: '+height+'px" src="'+data.files[i].thumb[0]+'">'
                +'<input type="hidden" name="'+name+'[]" value="'+data.files[i].filename+'"></div>').insertBefore($('.upload-btn'));
        }
        upload_progress.hide();

    });

    $('#upload_preview_'+name).on('click','.img-item-close',function(){
        $(this).closest('.img-item').remove();
    })
}

/**
 * 上传图片
 * @param dom
 * @param url
 * @param value
 * @param height
 *
 *  <script src="/abox/jquery-ui.min.js"></script>
 *  <script src="/abox/jquery.fileupload.js"></script>
 *  <div id="upload" upload-name="avatar" class="upload-container"></div>
 *  uploadImageBox($('#upload'),'/upload?category=avatar','/uploads/images/avatar/48d78b2992a3854ccdc94a24dc4092a0_s.jpeg',80);
 */
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

/**
 * 编辑器
 * @param dom
 * @param value
 * @param height
 * <div id="edit" data-name="content"></div>
 * editorInit($('#edit'),'',200);
 */
function editorInit(dom,value,height){
    var name = dom.data('name');
    if(typeof name=='undefined'){
        console.log('editor data-name is undefined!');
    }
    //dom.append('<input type="hidden" name="'+name+'" value="">');
    dom.append('<script id="editor_obj_'+name+'" type="text/plain" style="width:100%;height:'+height+'px;"></script>');
    var ue = UE.getEditor('editor_obj_'+name);
    ue.addListener( 'ready', function( editor ) {
        ue.setContent(value, false);
    });
}

/**
 * 地区选择
 * @param dom
 * @param value
 *  <div id="address" data-name="address" class="address-container"></div>
 *  addressInit($('#address'),'131024');
 */
function addressInit(dom,value){
    var name = dom.attr('data-name');
    if(typeof name=='undefined'){
        console.log('address data-name is undefined!');
    }
    dom.append('<select></select>');
    dom.append('<select></select>');
    dom.append('<select name="'+name+'"></select>');
    var province = $(dom.find('select')[0]);
    var city = $(dom.find('select')[1]);
    var area = $(dom.find('select')[2]);
    if(typeof value == 'undefined'||value == ''){
        value = '000000';
    }
    var province_code = value.substr(0,2)+'0000';
    var city_code = value.substr(0,4)+'00';
    var area_code = value;
    for(i in Js_Area){
        if(Js_Area[i].code == province_code){
            province.append('<option selected="selected" value="'+Js_Area[i].code+'">'+Js_Area[i].name+'</option>');
        }else{
            province.append('<option value="'+Js_Area[i].code+'">'+Js_Area[i].name+'</option>');
        }
    }
    province.change(function(){
        var code = $(this).val();
        var sub = Js_Area[code].sub;
        city.empty();
        for(i in sub){
            city.append('<option value="'+sub[i].code+'">'+sub[i].name+'</option>');
        }
        city.change();
    });
    city.change(function(){
        var code = $(this).val();
        var parent_code = code.substr(0,2)+'0000';
        var sub = Js_Area[parent_code].sub[code].sub;
        //console.log(sub);
        area.empty();
        for(i in sub){
            area.append('<option value="'+sub[i].code+'">'+sub[i].name+'</option>');
        }
    });
    province.change();
    if(city_code!='000000'){
        city.val(city_code);
        city.change();
    }
    if(area_code!='000000'){
        area.val(area_code);
    }
}



//图片裁剪
function uploadImageWithCrop(dom,url,value,size,width,height){
    if(typeof width == 'undefined'){
        width = 400;
    }
    if(typeof height == 'undefined'){
        height = 400;
    }
    size = size.split(',');

    jcrop_api = null;
    function initJcrop(dom,name,load){
        dom.Jcrop({
            onSelect: function(c){
                //选择区域x,y,w,h,图片w,图片h,
                $('input[name="'+name+'_crop_size"]').val(c.x+','+ c.y+','+ c.w +','+ c.h+','+jcrop_api.getBounds().join(','));
            }
        },function(){
            jcrop_api = this;
            if(load){console.log(load);
                jcrop_api.setSelect([load[0],load[1],parseInt(load[0])+parseInt(load[2]),parseInt(load[1])+parseInt(load[3])]);
            }
        });
    }
    var name = dom.attr('upload-name');
    if(typeof name=='undefined'){
        console.log('upload upload-name is undefined!');
    }
    //文件上传input
    $('body').append('<input name="upload_obj_'+name+'" id="upload_obj_'+name+'" type="file" style="display: none">');
    //文件地址input，预览
    dom.append(
        '<div><input type="hidden" name="'+name+'">'//图片地址
        +'<input type="hidden" name="'+name+'_crop_size">'//裁剪尺寸
        +'<div class="upload-btn" id="upload_btn_'+name+'">上传</div>'
        +'</div>'
        +'<div>'
        +'<div class="upload-preview" id="upload_preview_'+name+'"></div>'
        +'<div class="upload-progress" id="upload_progress_'+name+'"></div>'
        +'</div>');

    var input = $('input[name="'+name+'"]');
    var upload_obj = $('#upload_obj_'+name);
    var upload_btn = $('#upload_btn_'+name);
    var upload_preview = $('#upload_preview_'+name);
    var upload_progress = $('#upload_progress_'+name);
    //上传事件触发
    upload_btn.click(function(){
        $('#upload_obj_'+name).click();
    });

    //初始化显示
    if(value){
        upload_preview.append('<img style="width:'+size[4]+'px;height: '+size[5]+'px" src="'+value+'">');
        input.val(value);
        initJcrop($($('#upload_preview_'+name).find('img')[0]),name,size);
    }

    upload_obj.fileupload({
        url: url+'&name=upload_obj_'+name
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
        upload_preview.find('img').attr('src',data.thumb[0]).show(200).css('max-width',width).css('max-height',height);
        input.val(data.filename);
        upload_progress.hide();
        //
        if(jcrop_api !=null){
            jcrop_api.destroy();
        }
        initJcrop($($('#upload_preview_'+name).find('img')[0]),name,false);
    });
}

/**
 * 多选下拉框
 * @param name
 * @param value
 * https://github.com/amazeui/chosen/blob/master/docs/options.md
 */
function mulSelectInit(name,value){
    $('input[name="'+name+'"]').val(value);
    var c = $('#'+name).chosen().change(function(){
        var v = $(this).val();
        if(v!=null){//删除最后一项后值是null
            v= v.join(',');
        }
        $('input[name="'+name+'"]').val(v);
        //console.log($('input[name="'+name+'"]').val());
    });
    value = value.split(',');
    if(value.length>0){
        $('#'+name).find('option').each(function(){
            for(i=0;i<value.length;i++){
                if($(this).val()==value[i]&&value[i]!=''){//console.log(value[i]);
                    $(this).attr('selected','selected');
                }
            }
        });
        c.trigger('chosen:updated');//刷新
    }


}

/**
 * 上传文件
 * @param dom
 * @param url
 * @param value
 * @param height
 */
function uploadFileBox(dom,url,value,height){
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
        upload_preview.html(value);
        input.val(value);
    }else{
        upload_preview.html('<div class="upload-btn">上传</div>');
    }

    upload_obj.fileupload({
        url: url+'&name=upload_obj_'+name
    }).bind('fileuploadprogress', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        upload_progress.show().css('width',progress+'%');
        console.log(progress);
    }).bind('fileuploaddone', function (e, data) {
        data = data.result;
        upload_preview.html(data[0].url);
        input.val(data[1].url);
        upload_progress.hide();
    });
}