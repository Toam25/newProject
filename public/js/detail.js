$(function () {

    $('body').on('click', '.js-delete-vote',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let li = $(this).parent('.li-review');
       if(id){
       if(confirm('Supprimer ?')){
        $.ajax({
            url: "/api/delete/review/" + id,
            type: 'POST',
            data: {},
            dataType: 'json',
            beforeSend: () => {
                toastr.info('Suppression en cours...')
                li.css('transform', 'scale(0)');
                $(this).attr('desabled',true);
            },
            success: (data) => {
                toastr.success('Avis supprimé...')
                li.remove();
                $(this).attr('desabled',true);
                $('.review-form')[0].reset();
            },
            error: () => {
                toastr.error('Il y a un erreur...')
                li.css('transform', 'scale(1)');
                $(this).attr('desabled',true);
            }
        });
       }
    }
      /*  Lobibox.confirm({
            msg: 'Voulez vous supprimer  ?',
            buttons: {
                yes: {
                    text: 'Acceptez',

                },
                no: {
                    text: 'Annulez',

                },

            },

            callback: ($this, type) => {

                if (type === "yes") {
                    $.ajax({
                        url: "/api/delete/review/" + id,
                        type: 'POST',
                        data: {},
                        dataType: 'json',
                        beforeSend: () => {
                            toastr.info('Suppression en cours...')
                            li.css('transform', 'scale(0)');
                        },
                        success: (data) => {
                            toastr.success('Avis supprimé...')
                            li.remove();
                        },
                        error: () => {
                            toastr.error('Il y a un erreur...')
                            li.css('transform', 'scale(1)');
                        }
                    });
                }
            }
        });*/

    });
});