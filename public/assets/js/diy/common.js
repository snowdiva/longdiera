/**
 * 全局公共函数及全局绑定事件
 */
var topTips = function (msg,type,time) {
    time = time*1000 || 3000;
    var d=+new Date();
    $("body").append('<div class="ui-notify ui-notify-'+(type=='error' ? 'error' : 'success')+'" id="J_st'+d+'"><i class="icon icon-'+(type=='error' ? 'info' : 'ok')+'"></i> '+msg+'</div>');
    $("body").find('#J_st'+d).fadeIn(120).delay(time).fadeOut();
}

var messageBox = function (msg) {
    bootbox.dialog({
        message: "<span class='bigger-110'>" + msg + "</span>",
        buttons: {
            "success": {
                "label": "<i class='icon-ok'></i> " + NameSpace.button.ok,
                "className": "btn-sm btn-success",
                "callback": function () {
                    //Example.show("great success");
                }
            }
        }
    })
}

var confirmBox = function (msg, url) {
    bootbox.confirm(msg, function(result){
        if (result) {
            $.post(url, function(json){
                if (0 == json.error) {
                    location.reload()
                } else {
                    topTips(json.message, 'error')
                }
            }, "JSON")
        }
    })
}

var confirmFormBox = function (title, html, formId, url) {
    bootbox.dialog({
        title : title,
        message : html,
        buttons :
        {
            "succes" :
            {
                "label" : "<i class='icon icon-ok'></i> 确认审核",
                "className" : "btn-sm btn-success",
                "callback" : function() {
                    $.post(url, $("#"+formId).serialize(), function(json){
                        if (0 == json.error) {
                            location.reload()
                        } else {
                            topTips(json.message, 'error')
                        }
                    }, "JSON")
                }
            },
            "cancel" :
            {
                "label" : NameSpace.button.cancel,
                "className" : "btn-sm btn-default",
            }
        }
    })
}

$(function(){

    // 文章审核
    $("body").on("click", "[data-name=audit-btn]", function () {
        var url = $(this).attr("data-url")
        var id = $(this).attr("data-id")
        var startTime = $(this).attr("data-defualt-time")
        var html = '<form action="javascript:;" class="form-horizontal" id="j_datetimepicker" role="form">\
                        <fieldset>\
                            <div class="form-group">\
                                <label for="dtp_input1" class="col-md-4 control-label">定时审核(留空直接审核)</label>\
                                <div class="input-append date form_datetime3 col-md-8">\
                                    <input size="30" type="text" value="" name="audit_time" readonly="">\
                                    <span class="add-on"><i class="icon icon-remove"></i></span>\
                                    <span class="add-on"><i class="icon icon-calendar"></i></span>\
                                    <input type="hidden" name="id" value="' + id + '">\
                                </div>\
                            </div>\
                        </fieldset>\
                    </form>'
        confirmFormBox('审核发布', html, "j_datetimepicker", url)
        $(".form_datetime3").datetimepicker({
            language: "ch",
            format: "yyyy-mm-dd hh:ii",
            startDate: startTime,
            autoclose: true,
            todayBtn: true,
            minuteStep: 30
        })
    })


})