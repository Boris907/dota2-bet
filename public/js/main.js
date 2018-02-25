$(document).ready(function () {
    var wm = $("script[id='wm-script']").attr("src");
    var amount = wm.split('&')[3];
    var val = amount.split('=')[1];
    var web_money = $('#web_money').val();
    var res = amount.replace(val, web_money);
    var result = wm.replace("amount=0", res);

    $('#wm-script').attr({
        src: result,
        type: 'text/javascript'
    }).appendTo('body');

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
                $res = $(response).find('#money');
                $('#money').html($res);
                $res_bet = $(response).find('#res_bet');
                $('#res_bet').html($res_bet);
                $min = $(response).find('.min');
                $('.min').html($min);
            }
        });
    });
});
