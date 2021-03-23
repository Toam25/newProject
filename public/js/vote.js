$(function(){
    
    var vote=1;
    $('input[name="rating"]').on('click',function(){
        /* var vote = $(this).val();*/
        $('input[name="rating"]').removeClass('js-value-note');
        $(this).removeClass('checkereview');
        $(this).addClass('js-value-note');
        console.log(vote);
    });

    $('.review-form').on('submit',function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var comment = $('.comment').val();
        let vote = $('.js-value-note').val() ?? 1;
        $.ajax({
            url: "/vote/"+id+"/add",
            type: 'POST',
            method : 'POSt',
            data: {
                 'comment':comment,
                  'vote' : vote
             },
             dataType : 'json',
             beforeSend : ()=>{
                   $('.logo').css('display','inline-block');
                   $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
                   $('.btn-submit').prop('disabled', true)
             },
           error : ()=>{
                  toastr.error('Erreur d\'enregistrement'); 
                //$("form[name='user']")[0].reset()
                $('.btn-submit').children('.loader_ajax').remove();
                $('.btn-submit').prop('disabled', false)
           },
           success : (data)=>{
               toastr.success("Merci pour votre avis");
              // $('.primary-btn').remove();
               $('.input').prop('disabled',true);
               $('.btn-submit').children('.loader_ajax').remove();
               $(this).parent('form').children('.delete-vote').data('id',data.id);
            /*  if(data.status=='ok'){
               toastr.success(data.msg); 
                $("form[name='user']")[0].reset()
           
             }
             if(data.status=='ko'){
                toastr.error(data.msg); 
                $("form[name='user']")[0].reset()
             }
              */
             
           },
           complete: ()=>{
             $('.logo').css('display','none');          
           }
       });
   
    })
    /*
    $.ajax({
        url: url,
        type: 'POST',
         data: new FormData(this),
         contentType: false,
         processData : false,
         cache : false,
         dataType : 'json',
         beforeSend : ()=>{
               $('.logo').css('display','inline-block');

         },
       error : ()=>{
            toastr.error('Erreur inconue'); 
            $("form[name='user']")[0].reset()
       },
       success : (data)=>{
         if(data.status=='ok'){
            toastr.success(data.msg); 
            $("form[name='user']")[0].reset()
         }
         if(data.status=='ko'){
            toastr.error(data.msg); 
            $("form[name='user']")[0].reset()
         }
         
       },
       complete: ()=>{
         $('.logo').css('display','none');          
       }
   });*/

});