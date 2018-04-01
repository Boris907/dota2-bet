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
                document.getElementById('bet').innerHTML = "Your current bet:" + response.bet + "$";
                document.getElementById('cash').innerHTML = "<span class=\"glyphicon glyphicon-plus\"></span>" + "D-coins:" + response.cash;
                document.getElementById('bank').innerHTML = "Current bank in this room:" + response.bank + "$";
                document.getElementById('max').innerHTML = "Max bet in this room:" + response.max_bet + "$";
            }
        });
    });
});
