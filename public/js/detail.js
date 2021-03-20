$(function () {

    $('.delete-vote').on('click', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let li = $(this).parent('.li-review');
       if(confirm('Supprimer ?')){
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