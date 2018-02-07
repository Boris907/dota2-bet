$(document).ready(function () {
    $('#bet_submit').click(function (e) {
        e.preventDefault();
        var bet = $('.increase:checked').val();
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
