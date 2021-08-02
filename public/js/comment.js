$(function () {

    $('.review-form-blog').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: "/comment",
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            beforeSend: () => {
                $('.btn-submit').prop('disabled', true);
            },
            success: (data) => {
                $('#review-form').html('<button class="btn btn-success">Merci pour votre commentaire</button>');
            },
            error: () => {
                $('.btn-submit').prop('disabled', false);
            }
        });
    });

    $('.delete-vote').on('click', function (e) {
        let id = $(this).attr('data-id');
        let that = $(this);
        $.confirm({
            title: 'Confirmation',
            content: 'Vous confirmez',
            buttons: {
                Oui: function () {

                    $.ajax({
                        url: "/comment/remove/" + id,
                        type: 'POST',
                        dataType: 'json',
                        beforeSend: () => {
                            $('.btn-submit').prop('disabled', true);
                        },
                        success: (data) => {
                            that.parent("li").remove();
                            alert("Supprimer avec success");
                        },
                        error: () => {
                            $('.btn-submit').prop('disabled', false);
                        }
                    });
                },
                Non: function () {

                },
            }
        });


    });
});
