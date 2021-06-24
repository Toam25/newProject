$(function () {
  /*$('.login_form').on('submit',function(e){
      e.preventDefault();
      $.ajax({
          url: "/login",
          type: 'POST',
          data: new FormData(this),
          contentType: false,
          processData : false,
          cache : false,
          dataType :'json',
          beforeSend : ()=>{

          },
        error : ()=>{
              
        },
        success : (data)=>{
           console.log(data);         
        },
        complete: ()=>{
                 
        }
    });

  })
  */
  $('body').on('click', '._connexion', function (e) {
    e.preventDefault();
    $('._myconnexion').css('display', 'block');
  });
  $('.fermernewconnexion').on('click', function (e) {
    e.preventDefault();
    $('._myconnexion').hide();

  });

  $('body').on('click', '#ajout_panier_modal', function (e) {
    $("._myconnexion").slideToggle();
  });
  $('._myconnexion').hide();
  $('.form-recuperation').hide();

  $('.controle_view_connexion').on('click', function (e) {
    e.preventDefault();
    $('#title_connexion').html(($('#title_connexion').html() == "Connexion") ? 'Récuperation mots de passe' : 'Connexion');
    $('.form-connexion').slideToggle(200, 'linear');
    $('.form-recuperation').slideToggle(200, 'linear');;
  });

  $('#mdp_forget').on('click', function (e) {
    e.preventDefault();
    $('#label_connexion').html('Recuperation');
    $('.recuperation').slideToggle(500, 'linear');
    $('.connexion').slideToggle(500, 'linear');
    ;
  });
  $('#mdp_connexion').on('click', function (e) {
    e.preventDefault();
    $('#label_connexion').html('Connexion');
    $('.recuperation').slideToggle(500, 'linear');
    $('.connexion').slideToggle(500, 'linear');
    ;
  });

  $('.open_modal_connex').click(function () {
    $("._myconnexion").slideToggle();
  });

  $('body').on('click', '.close_message_modal', function () {
    $('.recuperation').slideToggle(300, 'linear');
    $('.connexion').slideToggle(300, 'linear');
    $('#container_message').css('display', 'none');
    $('#connexionModal').modal('hide');
  });
  $('.recupe').on('submit', function (e) {
    e.preventDefault();
    var number = $('.identification_recup').val();



    $.ajax({
      url: '/forgetPassword',
      type: 'POST',
      data: {
        'recupmessage': number,
      },
      dataType: 'json',
      beforeSend: function () {
        $('.ajax').fadeIn();
      },
      success: function (data) {

        $('#container_message').css('display', 'flex');
        $('#message').text(data.message);
        toastr.success(data.message)
        //setInterval(timers,1000);


        $('.ajax').fadeOut();
      },
      error: function () {
        $('#container_message').css('display', 'flex');
        $('#message').text('Erreur serveur');
        toastr.error("Erreur de connexion au serveur mail")

      },
      complete: function () {
        $('.ajax').fadeOut();
      }
    });

  });
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
          location.reload(true);
          //document.location='index.php';
        }
        /*else if(data.status=='non_accepter') {
            $('.message').css('background','green');
          //$('#message').text('On vas vous redirigé vers votre administration');
          $('.ajax').fadeOut();
         // document.location='index.php?in-action=condition';
          document.location='index.php';
        }*/
        else {
          toastr.error("Erreur d'identification : " + data.responseJSON.message);
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