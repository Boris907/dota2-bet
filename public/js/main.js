$(document).ready(function () {
    $('#bet_submit').click(function () {
        var bet = $('#bet').val();

        $.ajax({
            type: "GET",
            url:"/lobby/{min_bet}",
            data:{
                min_bet : bet,
                _token:     '{{ csrf_token() }}'
            }
        });
    });
});
