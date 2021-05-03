$(function () {
    $('.membre').on('click', function (e) {
        e.preventDefault();
        $('#membre_user').show();
        $.ajax({
            url: "/api/v1/user",
            type: 'GET',
            dataType: 'json',
            timeout: 3000,
            beforeSend: () => {
                $('.js_member').append(loader)
            },
            success: (data) => {
                let html = ``;
                data.forEach(element => {
                    html += listShop(element.image, element.id, element.name)
                });
                $('.js_member').html(html);
                $('body .container_loader_message').remove();
            },
            error: () => {
                $('body .container_loader_message').remove();
            }
        });
    });

    $('.js-search-shop').on('keyup', function (e) {
        let text = $(this).val();
        $.ajax({
            url: "/api/v1/user/?q=" + text,
            type: 'GET',
            dataType: 'json',
            timeout: 3000,
            beforeSend: () => {
                $('.js_member').append(loader)
            },
            success: (data) => {
                let html = ``;
                data.forEach(element => {
                    html += listShop(element.image, element.id, element.name)
                });
                $('.js_member').html(html);
                $('body .container_loader_message').remove();
            },
            error: () => {
                $('body .container_loader_message').remove();
            }
        });
    });

    function listShop(image, id, name) {
        return `<div class="membre_in">
            <div class="imagep">
                <img class="image_p" src="/images/`+ image + `">
            </div>
            <div class="imagec">
                <div class="namemembre">
                    <input type="hidden" value="`+ id + `">
                    <a href="" target="blank">
                        <span style="font-weight: bold; ">`+ name + `</span><br></a>
                </div>
                <!--span class="addfriend btn btn-primary aspectButton">Ajouter</span-->
                <span>
                    <button class="message-in btn btn-success aspectButton" name="`+ id + `" desabled>Message</button>
                </span>
            </div>
        </div>`
    }

    $('.close_message').on('click', function (e) {
        e.preventDefault();
        $('#container-message').fadeOut();

    });
    $('#container_membre_in').on('click', '.message-in', function (e) {
        e.preventDefault();
        let id = $(this).attr('name');
        $('#container-message').show();
        $.ajax({
            url: "/api/v1/message/" + id,
            type: 'GET',
            dataType: 'json',
            timeout: 3000,
            beforeSend: () => {
                $('#message').html(loader);
            },
            success: (data) => {
                let html = ``;
                data.forEach(element => {
                    html += listShop(element.image, element.id, element.name)
                });
                $('.js_member').html(html);
                $('#message .container_loader_message').remove();
            },
            error: () => {
                $('#message .container_loader_message').remove();
            }
        });
    });
    $('.fermer_membre').on('click', function (e) {
        e.preventDefault()
        $('#membre_user').fadeOut();
    });
    const loader = () => {
        return ` <div class="container_loader_message">
        <img src="/images/images_default/loading.gif">
    </div>`;
    }
})