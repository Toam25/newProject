$(function () {

    $('form').on('submit', function (e) {
        e.preventDefault()
        // let token = $('.token').val();
        removeError();
        $.ajax({
            type: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            data: new FormData(this),
            beforeSend: () => {

                $('.btn-submit').prop('disabled', true);
                $('.btn-submit').text('---');
            },
            success: (data) => {

                $('.btn-submit').prop('disabled', false)
                $(this)[0].reset();
                $('.btn-submit').text('Enregistrer')
                $('.btn-submit').prop('disabled', false)
                $('.alert').text(data.message);
                $('.alert').removeClass('alert-danger');
                $('.alert').removeClass('alert-success');
                $('.alert').addClass('alert-success');
                $('.alert').css('display', 'block ');



            },
            error: (data) => {

                $('.btn-submit').prop('disabled', false);
                $('.btn-submit').text('Enregistrer');
                $('.alert').text(data.responseJSON.message);
                $('.alert').removeClass('alert-danger');
                $('.alert').removeClass('alert-success');
                $('.alert').addClass('alert-danger');
                $('.alert').show();

            }
        });
    });

    $('input').on('change', () => {
        removeError();
    })
    $('select').on('change', () => {
        removeError();
    })
    let removeError = () => {
        $('.alert').removeClass('alert-danger');
        $('.alert').removeClass('alert-success');
        $('.alert').hide();
    }
});