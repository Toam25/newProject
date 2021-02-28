$(function(){

    $('body').on('change', '.file', function (event) {
        var reader = new FileReader();
        reader.onload = function () {
          var preview_image = document.getElementById('profil_preview_image');
              preview_image.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
      });

  $('.profil_password').on('submit',function(e){
     e.preventDefault();
     let new_pass = $('.new_password').val();
     let confirm_pass = $('.confirm_password').val();
     let confirm_send = true;

     if( new_pass|| confirm_pass){
          if(new_pass === confirm_pass){
             confirm_send = true;
          }
          else{
            toastr.error('Le nouveau mot de passe ne correspond pas');
            confirm_send = false;
          }
     }
     else{
         confirm_send = true;
     }
     if(confirm_send){
      let url = $(this).attr('action');
      $.ajax({
        url,
        type: 'POST',
        data: new FormData(this),
        contentType: false,
        processData: false,
        cache: false,
        dataType: 'json',
        beforeSend: () => {
            $('.btn-submit2').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block; margin : 0 7px;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
            $('.btn-submit2').prop('disabled', true)
        },
        error: () => {
          toastr.error('Mot de passe incorect');
          $('.btn-submit2').children('.loader_ajax').remove();
          $('.btn-submit2').prop('disabled', false)
        },
        success: (data) => {
            toastr.success("Profil à jour avec success");
            $('.btn-submit2').children('.loader_ajax').remove();
            $('.btn-submit2').prop('disabled', false)
        },
        complete: () => {
        }
      });
     }
  });
 $('.profil_user_name').on('submit',function(e){
     e.preventDefault();
     let url = $(this).attr('action');
      $.ajax({
        url,
        type: 'POST',
        data: new FormData(this),
        contentType: false,
        processData: false,
        cache: false,
        dataType: 'json',
        beforeSend: () => {
            $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block; margin : 0 7px;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
            $('.btn-submit').prop('disabled', true)
        },
        error: () => {
          toastr.error('Mots de passe incorect');
          $('.btn-submit').children('.loader_ajax').remove();
          $('.btn-submit').prop('disabled', false)
        },
        success: (data) => {
            toastr.success("Profil à jour avec success");
            $('.btn-submit').children('.loader_ajax').remove();
            $('.btn-submit').prop('disabled', false)
        },
        complete: () => {
        }
      });
    });
})