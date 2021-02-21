$(function () {

    $('.liste_image').on('click', 'p', function () {

        $('._d').removeClass('detailtitle');
        $('._d').text('');
        $(this).parents('.liste_image').children('span').addClass('detailtitle');
        $(this).parents('.liste_image').children('span').text($(this).attr('title'));

    });
    $('._d').on('click', function () {
        $('._d').removeClass('detailtitle');
        $('._d').text('');
    });

    $('.my-carousel-items').on('mousewheel', function (event, delta) {
        event.preventDefault();
        this.scrollLeft -= (delta * 20);
    });

    $('.hd').click(function () {

        var btn = $(this);
        var name = btn.attr('name');
        var value = btn.attr('value');
        $('.btn' + value).prop('disabled', true);
     
        $.ajax({
            url: '/api/set/numberVoteIndex/'+name+'-'+value,
            type: 'POST',
            data: {},
            dataType: 'json',

            success: function (data) {
                var mydata = parseInt(data['nbr_vote']);
                var suffix = "";
               

                if (mydata >= 1000) {
                    if (mydata < 1000000) {
                        mydata = mydata / 1000;
                        suffix = "k";
                    }
                    else {
                        if (mydata < 1000000000) {
                            mydata = mydata / 1000000;
                            suffix = "M";
                        }
                        else {
                            mydata = mydata / 1000000000;
                            suffix = "T";
                        }
                    }

                    mydata = mydata.toFixed(0);
                    $('#p' + value).html(mydata + suffix).fadeIn("slow");
                }
                else {
                    $('#p' + value).html(mydata).fadeIn("slow");
                }

                $("#haut" + value).prop('disabled', true);
                $("#haut" + value).attr('disabled', 'disabled');

                $("#bas" + value).prop('disabled', true);
                $("#bas" + value).attr('disabled', 'disabled');

            }
        });
        return false;
    });

});