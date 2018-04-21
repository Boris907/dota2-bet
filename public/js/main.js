$(document).ready(function () {
    $('.bet_submit').click(function (e) {
        e.preventDefault();
        var bet = $(this).attr("value");
        //$('.increase').prop('checked', false);
        var lobby_id = $('meta[name="lobby_id"]').attr('content');
        //alert(test);
        $('#exampleModal').modal('hide');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: lobby_id+"/bet/"+bet,
            data:{
                bet: bet
            },
            success: function (response) {
                document.getElementById('bet').innerHTML = response.bet;
                document.getElementById('max').innerHTML = response.coins;
               document.getElementById('cash').innerHTML = "<span class=\"glyphicon glyphicon-plus\"></span>" + "D-coins:" + response.coins;
                document.getElementById('bank').innerHTML = "Current bank in this room:" + response.bank + "$";
            }
        });
    });
    $('.exit').click(function (e) {
        e.preventDefault();

        $('#exampleModal2').modal('hide');
    });
});
