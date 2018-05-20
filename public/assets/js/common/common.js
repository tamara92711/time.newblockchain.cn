$(document).ready(function () {
    $(".return").on("click",function (e) {
        e.preventDefault();
        history.go(-1);
    })
})


function generateYear(date)
{
    var year = date.substring(0,4);
    return year;
}

function generateMonth(date)
{
    var month = date.substring(5,7);
    if(month<10)
        month = month.substring(1,2);
    return month;
}

function generateDay(date)
{
    var day = date.substring(8,10);
    if (day<10)
        day = day.substring(1,2);
    return day;
}



function getFirstMonthDay()
{
   var date = new Date();
   var firstDay = new Date(date.getFullYear(),date.getMonth(),1);
   return firstDay;

}

function getDemandStatusFindJobList(state_id,is_reviewed)
{
    if(state_id == 2 && is_reviewed == 0)
        return '发布中';
    else  if(state_id == 3 && is_reviewed == 0)
        return '已承接';
    else  if(state_id == 3 && is_reviewed == 3)
        return '已完成';
    else  if(state_id == 2 && is_reviewed == 4)
        return '已失效';
}

function getDemandStatusPubliher(state_id,is_reviewed)
{
    if(state_id == 1 && is_reviewed == 0)
        return '未发布';
    else  if(state_id == 2 && is_reviewed == 0)
        return '已发布';
    else  if(state_id == 3 && is_reviewed == 0)
        return '未完成';
    else  if(state_id == 3 && is_reviewed == 1)
        return '待评价';
    else  if(state_id == 3 && is_reviewed == 3)
        return '已完成';
    else  if(state_id == 2 && is_reviewed == 4)
        return '已失效';
    else  if(state_id == 3 && is_reviewed == 5)
        return '未完成';
}

function getDemandStatusFreelancer(state_id,is_reviewed)
{
    if(state_id == 2 && is_reviewed == 0)
        return '待承接';
    else  if(state_id == 3 && is_reviewed == 0)
        return '已承接';
    else  if(state_id == 3 && is_reviewed == 1)
        return '待评价';
    // else  if(state_id == 3 && is_reviewed == 2)
    //     return '待评价';
    else  if(state_id == 3 && is_reviewed == 3)
        return '已完成';
    else  if(is_reviewed == 4)
        return '已过期';
    else  if(state_id == 3 && is_reviewed == 5)
        return '已失效';

}

function time(o, wait) {
    if (wait == 0) {
        o.attr("disabled", false);
        o.val("获取验证码");
    } else {
        o.attr("disabled", true);
        o.val("重新发送(" + wait + ")");
        setTimeout(function () {
            time(o, wait - 1)
        },
            1000)
    }
}

function clear_phone_verify_code_session() {
    $.ajax({
        url: "clear_phone_verify_code_session",
        method: "GET",
        success: function(response) {

        }
    })
}

function format_date(date) {
    var dd = date.getDate();
    var mm = date.getMonth() + 1; //January is 0!
    var yyyy = date.getFullYear();

    if (dd < 10) {
        dd = '0' + dd
    }

    if (mm < 10) {
        mm = '0' + mm
    }

    date = yyyy + '-' + mm + '-' + dd;
    return date;
}