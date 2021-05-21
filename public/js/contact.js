$(function () {

    //get conversion
    var id_other_user = null;

    $('.parametre').on('click', function (e) {
        e.preventDefault();
        $('.parametre_message_in').toggle();
    });
    $('._my_message_').on('click', function (e) {
        e.preventDefault();
        $('._h4_type_').html('Mon Messages');
        $('.my_message_and_nofication').addClass('view_my_notification_and_message');
        $(".my_conversation").append(loader);
        $.ajax({
            url: "/conversations/",
            type: 'GET',
            dataType: 'json',
            success: (data) => {
                let html = ``;
                if (data.length > 0) {

                    data.forEach(element => {
                        date = element.createdAt ? formatDate(element.times) : "";

                        images = element.shop_logo ? element.shop_logo : element.avatar;
                        name = element.shop_name ? element.shop_name : element.name + " " + element.firstname
                        if (element.content != null) {
                            html += `<div class="_container_my_conversation  message-in" name="` + element.id + `" >
                                    <div class="_contaier_my_conversation_img">
                                        <img src="/images/`+ images + `" alt="` + name + `">
                                    </div>
                                    <div class="container_description_message">
                                        <h4 class="_name_message">`+ name + `</h4>
                                        <p class="conten_message">`+ element.content + `</p>
                                        <div class="content_date" >` + date + `</div>
                                    </div>
                                </div>`;

                        }


                    });
                }
                else {
                    html = "<h4>Votre message est vide</h4>"
                }
                $('.js_my_conversation').html(html);
                $('body .container_loader_message').remove();
            },
            error: (data) => {
                toastr.error("Vous êtes deconnecter, connecter à nouveau")
                $('body .container_loader_message').remove();
            }
        });

    });
    //close view message
    $('.js_close_my_message_notification').on('click', function (e) {
        e.preventDefault();
        $('.my_message_and_nofication').removeClass('view_my_notification_and_message')
    });

    //get memebre
    $('.membre').on('click', function (e) {
        e.preventDefault();
        $('#membre_user').show();
        $.ajax({
            url: "/api/v1/user/",
            type: 'GET',
            dataType: 'json',
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
    // search message
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
    $('body').on('click', '.message-in', function (e) {
        e.preventDefault();
        let id = $(this).attr('name');
        $('#container-message').show();
        $('.my_message_and_nofication').removeClass('view_my_notification_and_message')

        $.ajax({
            url: "/messages/" + id,
            type: 'GET',
            dataType: 'json',
            beforeSend: () => {
                $('#message').html(loader);
            },
            success: (data) => {

                $('._image_').attr('src', '/images/' + data.image)
                $('._message_name_boutique').html(data.name);
                let mymessage = ``;


                data.messages.forEach(message => {
                    my = (message.my) ? "my" : "your";
                    mymessage = `<div class="contaitboutique ` + my + ` ">` + message.content + `
                              fdf
                             <div class="time" >
                              `+ getStringDatePerTimestamp(message.times) + `
                              </div>
                          <button class="font_b delete_message" name="`+ message.id + `">
                               <span class="fa fa-trash"></span>
                        </button>
                        </div>`+ mymessage;
                });;
                id_other_user = data.id;
                $('.block_message').attr('name', id_other_user);
                $('.block_message').attr('name', id_other_user);
                $('.link_shop_or_user').attr('href', data.link);
                $('#conversation_id').val(data.id_conversation);
                $('.me_me').val(data.id);

                $("#message").html(mymessage);
                scrollToButton();
                // let html = ``;
                // data.forEach(element => {
                //     html += listShop(element.image, element.id, element.name)
                // });
                // $('.js_member').html(html);
                // $('#message .container_loader_message').remove();
            },
            error: () => {
                $('#message .container_loader_message').remove();
            }
        });
    });

    //preview image 

    $('body').on('change', '.file', function (event) {
        var reader = new FileReader();
        reader.onload = function () {
            var preview_image = document.getElementsByClassName('preview_img')[0];
            preview_image.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
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

    $('#formForMessageBoutique').on("submit", function (e) {
        e.preventDefault();

        let id = $('#conversation_id').val();
        $.ajax({
            url: "/messages/" + id,
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            beforeSend: () => {
                // $('#message').html(loader);
                $(this)[0].reset();
                $('.preview_img').attr('src', '');
            },
            success: (data) => {

                getLastmessage();
                // $('._image_').attr('src', '/images/' + data.image)
                // $('._message_name_boutique').html(data.name);
                // let mymessage = ``;

                // data.messages.forEach(message => {
                //     my = (message.my) ? "my" : "your";
                //     mymessage = `<div class="contaitboutique ` + my + ` ">` + message.content + `
                //               fdf
                //              <div class="time" data-time="29-3-2021 19:31:26">
                //               `+ message.createdAt + `
                //               </div>
                //           <button class="font_b delete_message" name="`+ message.id + `">
                //                <span class="glyphicon glyphicon-trash"></span>
                //         </button>
                //         </div>`+ mymessage;
                // });;
                // $('#conversation_id').val(data.id_conversation);
                // $("#message").html(mymessage);
                // let html = ``;
                // data.forEach(element => {
                //     html += listShop(element.image, element.id, element.name)
                // });
                // $('.js_member').html(html);
                // $('#message .container_loader_message').remove();
            },
            error: () => {
                $('#message .container_loader_message').remove();
            }
        });

    });


    //Notification 

    $(".mynotification").on('click', function (e) {
        e.preventDefault();

        $('._h4_type_').html('Ma Notification');
        $('.my_message_and_nofication').addClass('view_my_notification_and_message');
        $(".my_conversation").append(loader);

        if ($('.notify').children('.view_notification').html()) {
            $('.notify').children('.view_notification').remove();

        }
        else {
            $.ajax({
                url: "/api/v1/notification",
                type: 'GET',
                dataType: 'json',
                success: (data) => {
                    let newelement = ""
                    let link = ""
                    let view = "";
                    if (data.length != 0) {

                        data.forEach(element => {
                            if (element.status == "APPROUVED_BLOG") {
                                link = "/admin/list/Blog/#id-" + element.idBlog
                            }
                            else if (element.status == "REQUEST_APPROVAL_BLOG") {
                                link = "/superadmin/list/Blog"
                            }
                            view = !element.isView ? "notview" : "";
                            newelement += `
                     <a id="notif-`+ element.idNotification + `"class="dropdown-item d-flex align-items-center ` + view + `" href="` + link + `" target="_blank">
                     <div class="mr-3">
                       <div class="icon-circle bg-success">
                         <i class="fas fa-donate text-white"></i>
                       </div>
                     </div>
                     <div>
                       `+ element.message + `
                       <div class="content_date">`+ formatDate(element.createdat) + `</div>
                     </div>
                   </a>`
                                ;
                        });
                        $('.js_my_conversation').html(newelement);
                    }
                    else {
                        $('#container_message').html(`
          <a class="dropdown-item d-flex align-items-center" href="#">
          <div class="mr-3">
            <div class="icon-circle bg-success">
              <i class="fas fa-donate text-white"></i>
            </div>
          </div>
          <div>
             Votre notification est vide 
          </div>
        </a>`);
                    }
                    $('body .container_loader_message').remove();
                },
                complete: () => {

                },
                error: () => {
                    $('body .container_loader_message').remove();
                }

            });
        }
    });

    $('#container_message').on('click', '.notview', function (e) {

        let id = parseInt($(this).attr('id').split('-')['1'])
        $(this).removeClass('notview');
        let nbrNotification = parseInt($('.nbr_notification').text()) - 1;
        let newNbrNotification = nbrNotification === 0 ? "" : nbrNotification;
        $('.nbr_notification').text(newNbrNotification);
        $.ajax({
            url: "/api/v1/view/" + id,
            type: 'POST',
            dataType: 'json',
            success: (data) => {

            },
            complete: () => {

            }
        });

    })

    $('.message_footer').on('click', function (e) {

        e.preventDefault();
        $('.head_message').css({
            'height': '350px',
            'width': '266px',
            'display': 'block'
        });

    });

    let terms = [
        {
            time: 10,
            divide: 1,
            text: "environ %d secondes"
        },
        {
            time: 45,
            divide: 1,
            text: "moins d\'une minute"
        },

        {
            time: 90,
            divide: 60,
            text: "environ une minute"
        },

        {
            time: 45 * 60,
            divide: 60,
            text: "il y a %d minutes"
        },
        {
            time: 90 * 60,
            divide: 60 * 60,
            text: "environ une heure"
        },


        {
            time: 24 * 60 * 60,
            divide: 60 * 60,
            text: "il y a %d heures"
        },

        {
            time: 42 * 60 * 60,
            divide: 24 * 60 * 60,
            text: "environ un jour"
        },
        {
            time: 30 * 24 * 60 * 60,
            divide: 24 * 60 * 60,
            text: "il y a %d jours"
        },

        {
            time: 45 * 24 * 60 * 60,
            divide: 24 * 60 * 60 * 30,
            text: "environ un mois"
        },

        {
            time: 365 * 24 * 60 * 60,
            divide: 24 * 60 * 60 * 30,
            text: "il y a %d mois"
        },
        {
            time: 365 * 1.5 * 24 * 60 * 60,
            divide: 24 * 60 * 60 * 365,
            text: "environ un an"
        },
        {
            time: Infinity,
            divide: 24 * 60 * 60 * 365,
            text: "il y a %d ans"
        },
    ]

    function formatDate(date) {
        let secondes = Math.round((new Date().getTime()) / 1000 - parseInt(date, 10));


        secondes = Math.abs(secondes);

        let term = null;
        for (term of terms) {
            if (secondes < term.time) {
                break
            }
        }
        return term.text.replace('%d', Math.round(secondes / term.divide));

    }

    function getStringDatePerTimestamp(times) {
        let date = new Date(parseInt(times) * 1000);
        let month = ["janvier", "fevrier", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "decembre"];
        return date.getFullYear() + '/' + date.getMonth() + '/' + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds()

    }
    // function ago() {
    //     document.querySelectorAll(['data-ago']).forEach(function (node) {
    //         console.log('testes');
    //         function setText() {
    //             let seconds = Math.round((new Date()).getTime() / 1000 - parseInt(node.dataset.ago, 10));
    //             seconds = Math.abs(seconds);
    //             let term = null;
    //             for (term of terms) {
    //                 if (secondes < term.time) {
    //                     break
    //                 }
    //             }
    //             node.innerHTML = prefix + term.text.remplace('%d', Math.round(secondes / term.divide));
    //             window.setTimeout(function () {
    //                 window.requestAnimationFrame(setText)
    //             }, 1000);
    //         }

    //         setText();

    //     })
    // }


    let getLastmessage = () => {
        $.ajax({
            url: "/messages/last/" + id_other_user,
            type: 'GET',
            dataType: 'json',
            beforeSend: () => {
            },
            success: (message) => {

                my = (message.my) ? "my" : "your";


                $("#message").append(`<div class="contaitboutique ` + my + ` ">` + message.content + `
               <div class="time" >
                `+ getStringDatePerTimestamp(message.times) + `
                </div>
            <button class="font_b delete_message" name="`+ message.id + `">
                 <span class="fa fa-trash"></span>
          </button>
          </div>`);
                // let html = ``;
                // data.forEach(element => {
                //     html += listShop(element.image, element.id, element.name)
                // });
                // $('.js_member').html(html);
                // $('#message .container_loader_message').remove();
                scrollToButton();
            },
            error: () => {
                $('#message .container_loader_message').remove();
            }
        })
    }

    let scrollToButton = () => {
        $('#message').animate({ scrollTop: $('#message')[0].scrollHeight })
    }

    $('#container_message').on('click', '.notview', function (e) {

        let id = parseInt($(this).attr('id').split('-')['1'])
        $(this).removeClass('notview');
        let nbrNotification = parseInt($('.nbr_notification').text()) - 1;
        let newNbrNotification = nbrNotification === 0 ? "" : nbrNotification;
        $('.nbr_notification').text(newNbrNotification);
        $.ajax({
            url: "/api/v1/view/" + id,
            type: 'POST',
            dataType: 'json',
            success: (data) => {

            },
            complete: () => {

            }
        });

    })

    $('.message_footer').on('click', function (e) {

        e.preventDefault();
        $('.head_message').css({
            'height': '350px',
            'width': '266px',
            'display': 'block'
        });

    });

})