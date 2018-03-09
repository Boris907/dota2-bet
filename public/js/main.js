// $('#wm-submit').click(function (e) {
//     e.preventDefault();
//
//     $('#wm-submit').hide();
//     var web_money = $('#web_money').val();
//     var s = document.createElement("script");
//     s.type = "text/javascript";
//     s.src = "//merchant.webmoney.ru/conf/lib/wm-simple-x20.min.js?wmid=396850847264&purse=R251053037627&key=398146463&amount=" + web_money + "&desc=Покупка валюты";
//     var container = document.getElementById('wm-form');
//     container.appendChild(s);
// });
$(document).ready(function () {
    $('.bet_submit').click(function (e) {
        e.preventDefault();
        var bet = $(this).attr("value");
        $('.increase').prop('checked', false);

        $('#exampleModal').modal('hide');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url:'/lobby/' +bet+ '/set',
            data:{
                bet: bet
            },
            success: function (response) {
                alert(response);
                var res = $(response).find('#money');
                $('#money').html(res);
                var res_bet = $(response).find('#res_bet');
                $('#res_bet').html(res_bet);
                var min = $(response).find('.min');
                $('.min').html(min);
                // var t = $(response).find('.alert-success');
                // $('.alert-success').html(t);
            }
        });
    });
});
