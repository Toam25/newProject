$(function () {

    setTimeout(() => {
        $('body .cke_bottom').append('<span class="ck_editor_liste_image"style="height: 100%;background: #f8f8f8;border: 1px solid #d1d1d1;/*! border-bottom: 0; */border-left: 0;padding: 1px 0 0 5px;border-radius: 5px 5px 0 0;">Votre galery</span>');


    }, 3000)


    let window = `
    <div  id="container_own_image">
        <div  class="container_own_image">
            <div class="title">Votre image <button class="uploid_image">Importer une image</button><button style="border: none;" class="close_my_image">Fermer</button></div>
                <div class="js_image all_image">
                     <div class="loader_form_ck">
                        <img src="/images/images_default/loading.gif" style="max-width: 100%;max-height: 100%;"> 
                     </div>
                </div>
            </div>
        </div>
    </div>`;
    let image_uploid = `
         <div class="container_form_add">
             <form class="form_add">
                 
                <input type="file" name = "my_image_file" class="form-controls">
                <div>
                    <button class="close_form_add_image btn btn-danger" type="button" >Fermer</button> <button type="submit"class="save_image btn btn-success btn-submit">Enregistrer</button>
                </div>
             </form>
         </div>
    `;

    $('body').on('click', '.close_form_add_image', function (e) {
        $('body .container_form_add').remove()
    });
    $('body').on('click', '.uploid_image', function (e) {
        $('body').append(image_uploid);
    });

    $('body').on('click', '.ck_editor_liste_image', function (e) {
        $('body').append(window);
        $.ajax({
            url: "/api/v1/add_image",
            type: 'GET',
            dataType: 'json',
            beforeSend: () => {
            },
            success: (data) => {

                let image = ``;
                data.images.forEach(element => {
                    image += `
                    <div class="container_image">
                          <img src="/images/`+ element.name + `">
                          <button class="delete_my_image" data-id=`+ element.id + `>Supprimer</button>
                    </div>
                `
                });
                $('.js_image').html(image);
                // $('.js_image').prepend(images(data.image));
                $('body .container_form_add').remove()

            },
            error: () => {
                toastr.error('Erreur de chargement des images ');
            }
        });
    });

    $('body').on('click', '.close_my_image', function () {
        $('body #container_own_image').remove();
    });

    $('body').on('submit', '.form_add', function (e) {
        e.preventDefault();
        $.ajax({
            url: "/api/v1/add_image",
            type: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            data: new FormData(this),
            beforeSend: () => {
                toastr.info('Enregistrement en cours ;) ');
                $('.btn-submit').prop('disabled', true)
            },
            success: (data) => {
                toastr.success('Enregistrer avec success ;) ');
                $('.btn-submit').prop('disabled', false)
                $('.js_image').prepend(`
                <div class="container_image">
                      <img src="/images/`+ data.image + `">
                      <button class="delete_my_image" data-id=`+ data.id + `>Supprimer</button>
                </div>
            `);
                $('body .container_form_add').remove()

            },
            error: () => {
                toastr.error('Erreur d\'enregistrement ');
                $('.btn-submit').prop('disabled', false)
            }
        });
    });

    $('body').on('click', '.container_image > img', function (e) {
        e.preventDefault();
        let href = document.location.origin;
        $('body .container_image').removeClass('image_selected');
        $(this).parent('.container_image').addClass('image_selected');

        let imageselected = $(this);
        let linkimage = imageselected.attr('src');
        CopyTextToClipboard(href + linkimage)

    })

    $('body').on('click', '.delete_my_image', function (e) {
        e.preventDefault();


        let id = $(this).attr('data-id');
        $.ajax({
            url: "/api/v1/add_image/" + id,
            type: 'POST',
            dataType: 'json',
            beforeSend: () => {
                toastr.info('Suppression en cours ;) ');
                $(this).prop('disabled', true);
                $('.close_my_image').prop('disabled', true);
            },
            success: (data) => {
                toastr.success('Supprimé avec success ;) ');
                $(this).parent('.container_image').remove();
                $('.close_my_image').prop('disabled', false);


            },
            error: () => {
                toastr.error('Erreur de suppression :( ');
                $(this).prop('disabled', false)
                $('.close_my_image').prop('disabled', false);
            }
        });
    });
    function CopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            var success = document.execCommand("copy");
            var msg = success ? "Lien Copié" : "Erreur de copie";
            toastr.info(msg);
        } catch (err) {
            toastr.error("Impossible de copier le lien");
        }

        document.body.removeChild(textArea);
    }

});