$(document).ready(function () {
    $('.bet_submit').click(function (e) {
        e.preventDefault();
        var bet = $(this).attr("value");
        //$('.increase').prop('checked', false);

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
                var res = $(response).find('#money');
                $('#money').html(res);
                var cash = $(response).find('#cash_val');
                $('#cash').html(cash);
                var res_bet = $(response).find('#res_bet');
                $('#res_bet').html(res_bet);
                var min = $(response).find('.min');
                $('.min').html(min);
            }
        });
    });
});
