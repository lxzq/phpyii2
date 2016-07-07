/**
 * Created by Administrator on 2016-05-11.
 */

function changePrice(){
    var pri = $("select[name='course_num[]']").val();
    var zk = $("input[name='option']:checked").val();

    if (pri == undefined){
        $("#tempPrice").html('¥0');
        $("#price").val(0);
        return;
    }
    var org_price = 0;
     $("select[name='course_num[]']").each(function(){
        var val = this.value;
        if(val != 0){
            var ps = this.value.split("|");
            org_price +=  new Number(ps[1]) ;
        }
     });
    if (zk == undefined){
        $("#tempPrice").html('¥'+org_price);
        $("#price").val(org_price);
        return;
    }

    var price = 0;
    //先算折扣
    $("input[name='option']:checked").each(function(){
        var types = this.value.split("|");
        var type = types[0];
        var op = types[1];
        var val = types[2];
        if(op == 2){//折扣价
            if(price == 0){
                price = org_price * val;
            }else {
                price = price * val;
            }
        }
    });

    //在算扫一扫 立减
    if(price > 0){
        org_price = price;
    }

    $("input[name='option']:checked").each(function(){
        var types = this.value.split("|");
        var type = types[0];
        var op = types[1];
        var val = types[2];
        if(op == 1){//表示立减价钱
            price = org_price - val;
        }
    });

    $("#tempPrice").html('¥'+price);
    $("#price").val(price);
}

function jsPrice(){
    var yhPr = $("#yhPrice").val();
    if(yhPr){
        var price = $("#price").val();
        price = price - yhPr;
        $("#tempPrice").html('¥'+price);
        $("#price").val(price);
    }
}

function addCourse(){
    //alert(1);
    $("#add_course").hide();
    var tr = $("<tr/>");
    tr.addClass("active");

    var td1 = $("<td/>");
    var course = $("<select/>");
    course.attr("name","course[]");
    course.addClass("form-control");
    course.appendTo(td1);

    var td2 = $("<td/>");
    var price = $("<select />");
    price.attr("name","course_num[]");
    price.addClass("form-control");
    price.appendTo(td2);
   $(course).change(function(){
      addPrice(price,this.value);
   });
   $(price).change(function(){
       changePrice();
    });
    td1.appendTo(tr);
    td2.appendTo(tr);
    tr.appendTo("#tablel");
    var op = $("<option value=\"0\">--选择课程--</option>")
    op.appendTo(course);
    for(var i = 0 ; i < courseList.length ; i++ ){
        var op = $("<option value=\""+courseList[i].id+"\">"+courseList[i].name+"</option>")
        op.appendTo(course);
    }

}

function addPrice(obj,courseId){
    $.ajax({
        url:'/admin/child/ajax-course',// 跳转到 action
        data :{courseId:courseId},
        type:'get',
        dataType:"json",
        success:function(data) {
            obj.html('');
            var op = $("<option value=\"0\">--选择课时/价格--</option>")
            op.appendTo(obj);
            for (var i = 0; i < data.length; i++) {
                var id = data[i].id;
                var name = data[i].name;
                var op2 = $("<option value=\"" + id + "\">" + name + "</option>")
                op2.appendTo(obj);
            }
        },
        error : function(data) {
        }
    });
}

function delCourse(){
    var size = $("#tablel tr").size();
    $("#tablel tr").each(function(index,ele){
        if(size -1  == index && index > 0){
            $(this).remove();
        }
    });
    $("#add_course").show();
    changePrice();
}