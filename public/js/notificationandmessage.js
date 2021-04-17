$(function () {

  $(".mynotification").on('click', function (e) {
    e.preventDefault();
    $('#container_message').html('<div><img src="/images/images_default/loading.gif"></div>');
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
                       <div class="small text-gray-500">`+ element.createdat + `</div>
                     </div>
                   </a>`
                ;
            });
            $('#container_message').html(newelement);
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
        },
        complete: () => {

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
})