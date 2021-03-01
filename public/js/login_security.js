$(function () {
    $('.connexion').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
      url: "/login",
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: function () {
        $('.ajax').fadeIn();
      },
      success: function (data) {

        console.log(data);
        if (data.status == 'success') {
          toastr.success('Connexion reussie, on actualisera votre page pour vous');
          $('.ajax').fadeOut();
          //location.reload(true);
          document.location='/';
        }
        /*else if(data.status=='non_accepter') {
            $('.message').css('background','green');
          //$('#message').text('On vas vous redirigé vers votre administration');
          $('.ajax').fadeOut();
         // document.location='index.php?in-action=condition';
          document.location='index.php';
        }*/
        else {
          toastr.error("Erreur d'identification : "+data.responseJSON.message);
          /* $('.message').css('background','red');
           $('.message').text(data.responseJSON.message);
         */
          $('.ajax').fadeOut();
        }
      },
      error: function (data) {
        toastr.error("Erreur d'identification ");
        /* $('.message').css('background','red');
         $('.message').text(data.responseJSON.message);
       */
        $('.ajax').fadeOut();
      }
    });

  });
});