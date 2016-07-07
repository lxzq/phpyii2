/**
 * Created by Administrator on 2016-05-12.
 */

function openFile() {
    $("#upfile").click();
    return;
}

function backlist() {
    window.location =  '/admin/organization/courselist';
    // window.history.back();
}
function fileupload() {
    if ($("#upfile").val() == "") {
        alert("亲！还没有选择图片哦！");
        return false;
    }
    var file = $("#upfile").val();
    var type = file.split('.');
    var fileType = type[type.length - 1];
    if (fileType == 'png' || fileType == 'jpg' || fileType == 'PNG' || fileType == 'JPG') {
        $("#upload_ing").show();
        $("#orgimg_show").hide();
        // 为表单绑定异步上传的事件
        $("#fileForm").ajaxSubmit({
            url:  "/admin/video/uploadoss",
            secureuri: false,
            fileElementId: 'upfile',
            dataType: 'text',
            success: function (data) {
                $("#logo").val(data);
                $("#orgimg_show").attr('src', data);
                $("#upload_ing").hide();
                $("#orgimg_show").show();
            },
            error: function (data, status, e) {
                alert("图片上传失败..");
            }
        });
    } else {
        alert("图片上传格式错误");
    }
}
function tempImage(url) {
    $("#logo").val(url);
}

function addTeacher(orgId){
    $.ajax({
        url:'/admin/organization/ajax-teacher',// 跳转到 action
        data :{orgId:orgId},
        type:'get',
        dataType:"json",
        success:function(data) {
            $("#teacherList").empty();
        for (var i = 0; i < data.length; i++) {
                var id = data[i].id;
                var name = data[i].name;
                var  html = '<input type="checkbox" class="teacher" name="teacher[]" value="'+id+'">&nbsp;'+name+'&nbsp;';
                $("#teacherList").append(html);
         }
        },
        error : function(data) {
        }
    });
}
