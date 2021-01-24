$(function(){
    var stateNotification=0
    var stateMessage=0;
    var id_admin= " ";
    var last_id_message=" ";
    var date= date();
    var id;

    setInterval(viewMessage,3000);
    //var  get_message=setInterval(viewMessage,3000);
  // var  get_notification=setInterval(getNotification,4000);
    
    $('.notification').on('click',function(e){
         e.preventDefault();
         $('.mess_in').children('.view_notification').remove();
          stateMessage=0;
        if(stateNotification==0){
         var notification = $(this);
          stateNotification=1;
         $.ajax({
                url :'../app/controller/notificationsAndMessages.php',
                type: 'POST',
                data : {
                      'notification': 'notification',
                },
                dataType : 'html',
                success : function (data){
                   $(".notif").append(data);
                }
        });
        }
        else{
            $('.notif').children('.view_notification').remove();
            stateNotification=0;
        }
    });
    
    
    $('.head_message')
    //chercher un membre
       $('#membre_user').on('keyup','.search-in', function(){
             var element = $(this).val(); 
             var cible= $(this).parents('div').parents('div').children('#container_membre_in');
             $.ajax({
                  url: '../app/controller/membreController.php',
                  method: 'POST',
                  data: {
                      'search_membre' : element,
                    },
                  dataTypes: 'html',
                  beforeSend : function(){
                    
                  },
                  success: function(data){
                    cible.html(data);
                  }  
         });
      });
    //fermer membre
    $('#membre_user').on('click','.fermer_membre',function(e){
        e.preventDefault();
        $('#membre_user').html('');
    });
    //affiche membre

    $('#membre_user').on('click',function(e){
       e.preventDefault();
       $('#container-message').children('.head_message').css('z-index','5');
    });

    $('#container-message').on('click',function(e){
      
       $('#container-message').children('.head_message').css('z-index','6');
    });

   $('body').on('click','.message-in ',function(e){
      $('#container-message').children('.head_message').css('z-index','6')
   });

    $('.membre_user_in').on('click',function(e){
         $.ajax({
                url :'../app/controller/membreController.php',
                type: 'POST',
                data : {
                      'getmembre':'getmembre',
                },
                dataType : 'html',
                success : function (data){
                   $("#membre_user").html(data);
                }
        });
    });
    $('.view_message_in').on('click',function(e){
         e.preventDefault();
         $('.notif').children('.view_notification').remove();
         stateNotification=0;
         
        if(stateMessage==0){
         var notification = $(this);
          stateMessage=1;
         $.ajax({
                url :'../app/controller/notificationsAndMessages.php',
                type: 'POST',
                data : {
                      'liste_message':'liste_message',
                },
                dataType : 'html',
                success : function (data){
                   $(".mess_in").append(data);     
                }
        });
        }
        else{
            $('.mess_in').children('.view_notification').remove();
            stateMessage=0;
        }
    });
   
     $('#container-message').on('click',function(e){
         
         $('.mess_in').children('.view_notification').remove();
          stateMessage=0;
     });
     $('.mess_in').on('click','.message_view',function(e){
       e.preventDefault();
       $(this).removeClass('unread');

       if(parseInt($('.message_notification').html())>0){
        $('.message_notification').html(parseInt($('.message_notification').html())-1);
       }
        id=$(this).children('.id_message').val() ;
      $.ajax({
          url :'../app/controller/messageController.php',
          type: 'POST',
          data : {
              'readMessage': id,
          },
          beforeSend : function(){
         },
         dataType : 'text',
          success : function (data){     
           }
      });
       
  });

  //supprimer le message
  $('body').on('click','.delete_message',function(e){
        e.preventDefault();
       if(confirm('Supprimer cet message ?')){

        $(this).parent('div').remove();
        id_message= $(this).attr('name');
           $.ajax({
                url :'../app/controller/jaimeController.php',
                type: 'POST',
                data : {
                    'delete_message': "delete_message",
                    'id_message':id_message,
                },
                dataType : 'json',
                success : function (data){
               }
          });
      }
    });

  //Débloquer une message le message
  $('.debloqued').on('click',function(e){
        e.preventDefault();

        if(parseInt($('.nbrMessageBloqued').html())>0){
           $('.nbrMessageBloqued').html(parseInt($('.nbrMessageBloqued').html())-1);
        }
        $(this).parent('span').parent('.container-message-debloqued').remove();
        var relation= $(this).attr('name');
           $.ajax({
                url :'../app/controller/jaimeController.php',
                type: 'POST',
                data : {
                    'deblocked_message': "deblocked_message",
                    'relation':relation,
                },
                dataType : 'json',
                success : function (data){
               }
          });
  });
  //supprimer tout les message
  $('body').on('click','.delete_all_message',function(e){
        e.preventDefault();
        var relation=$(this).attr('name');
        var button=$(this);
        if(confirm("Supprimer tous les messages ?")){
        var id_boutique=parseInt($(this).parent('form').children('input[name="_id_boutique"]').val());
        $('.mess_in').children('.view_notification').children('p[name="'+id_boutique+'"]').remove();
        $('#container-message').children('.head_message').children('#message').html("");
         $.ajax({
                url :'../app/controller/jaimeController.php',
                type: 'POST',
                data : {
                    'delete_all_message': "delete_all_message",
                    'relation':relation,
                },
                dataType : 'json',
                success : function (data){
                   var i;
                   for(i=0; i<data.length;i++){

                   }
               }
          });
      }
  });
  
  $('body').on('change','.input_preview_image_for_article',function (event){
          var reader = new FileReader();
          reader.onload= function(){
            var preview_image = document.getElementById('preview_image_for_article');
            preview_image.src=reader.result;
          }
          reader.readAsDataURL(event.target.files[0]);
        });
  //parametre
  $('#container-message').on('click','.parametre',function(e){
     
       $('#container-message').children('.head_message').children('.parametre_message_in').toggle();
  });
  //bloque le message
   $('body').on('click','.block_message',function(e){
        e.preventDefault();
      if(confirm("Bloquer le message ?. \n Pour débloquer, aller dans votre paramètre")){
        var actionClass= $(this).parents('.parametre_message_in').parents('.head_message').children('.action');

        actionClass.children('form').children('input:first').attr('value','Message bloqué');
        actionClass.children('form').children('input').prop('disabled','true');
        var relation = $(this).attr('name');
        $.ajax({
                url :'../app/controller/jaimeController.php',
                type: 'POST',
                data : {
                    'blocked_message': "blocked_message",
                    'relation':relation,
                },
                dataType : 'json',
                success : function (data){
           }
      });
     }
  });
  //fermer le message
  $('body').on('click','.fermer',function(e){
        e.preventDefault();
         $(this).parent('.head_message').remove();
         id_admin=" ";
  });
  $('body').on('click','.message-in ',function(e){
       e.preventDefault();
       //clearInterval(get_message);

       $('.head_message').css('display','block');
       $('.id_shop').val(id);
       id= $(this).attr('data-id');
       $('.id_shop').val(id);
       var url = '/message/'+id;
       $.ajax({
          url,
          type: 'POST',
          beforeSend : function(){
         },
         dataType : 'text',
          success : function (data){ 
            $('#container-message').html(data.message); 
            last_id_message = $('#container-message').children('.head_message').children('#message').children('.contaitboutique:last-child').children('.delete_message').attr('name');     
          var message=$('#container-message').children('.head_message').children('#message');
          message.scrollTop(parseInt(message.height())*1000);
           
           setTimeout(function(){
            get_message=setInterval(viewMessage,3000);
           },5000)

        }
      });
  });
     $('#container-message').on('submit','#formForMessageBoutique',function(e){
          e.preventDefault();

      // clearInterval(get_message);
      //  clearInterval(get_notification);
        var date= new Date();
        var url = $(this).attr('action')+"new/"+id;
        var now = date.getDate()+"-"+date.getMonth()+"-"+date.getFullYear()+" "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
        var formdata = new FormData(this);

        formdata.append('date',now);
       var click=$(this);
       $('#preview_image_for_article').attr('src','');
       $('input[name="message"]').val('');
       $('.input_preview_image_for_article').val('');
       /*var  that=click.parent('#formForMessageBoutique').children('input[name="_id_boutique"]');
       var id=that.val();
       var inputMessage= click.parent('#formForMessageBoutique').children('input[name="messageForBoutique"]');*/
       var message = $('input[name="messageForBoutique"]').val();
       if(message!="" || $('.input_preview_image_for_article').val()!=""){
          $.ajax({
                url,
                type: 'POST',
                data : formdata ,
                contentType: false,
                 processData : false,
                 cache : false,
                dataType : 'json',
                success : function(data){

           }
         });
                 //  get_message= setInterval(viewMessage,3000);
                  // get_notification=setInterval(getNotification,4000);
                   //inputMessage.val("");
       }
       else{

       }
     });
   
   
   function viewMessage(){
      last_id_message = $('#container-message').children('.head_message').children('#message').children('.contaitboutique:last-child').children('.delete_message').attr('name');
      $.ajax({
          url :'/message/'+id,
          type: 'GET',
          data : {
              'last_id_message': last_id_message
          },
          dataType : 'html',
          success : function (data){ 
            if(data!=" "){
                var message=$('#container-message').children('.head_message').children('#message');
                    message.append(data);
                    element = $('#container-message').children('.head_message');
                    message.scrollTop(parseInt(message.height())*1000);     
               
               } 
            }

         });

      
   }
   function date(){
      var d = new Date();
      var m=d.getMonth();
      var y=d.getFullYear();
      var day=d.getDate();
      var n = d.getHours();
      var min = d.getMinutes();
      var sec = d.getSeconds();
      var month=['Janvier','Février','mars','avril','mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
      return day+" "+month[m]+" "+y+" / "+n+"h:"+min+"min";

   }
   function getNotification(){
          $.ajax({
                url :'../app/controller/notificationsAndMessages.php',
                type: 'POST',
                data : {
                      'getnotification': 'getnotification',
                      'readMessage':id_admin,
                },
                dataType : 'json',
                success : function (data){
                  if(data.getNumberNotification > 0){
                    $(".nbr_notifi").text(data.getNumberNotification);
                  }
                  if(data.getNumberMessage > 0){
                    $(".message_notification").text(data.getNumberMessage);
                  }
                }
        });
    }
 function recherche(element,cible,filter,folder){
    
}

 var date=mydate();
       
      $('.accepation_condition').on('submit',function(e){
      e.preventDefault();
      var nom_proprietaire=$('.nom_proprietaire').val();
      var nomboutique=$('.nomboutique').val();
      var adresse_proprietaire=$('.adresse_proprietaire').val();
      var num_proprietaire=$('.num_proprietaire').val();
      var CIN_proprietaire=$('.CIN_proprietaire').val();
      var date_cin=$('.date_cin').val();
      var date_acceptation_utilisation=$('.date_acceptation_utilisation').val();
      var lieu=$('.lieu').val();

          $.ajax({
                url :'../app/controller/notificationsAndMessages.php',
                type: 'POST',
                data :{
                   nom_proprietaire : nom_proprietaire,
                   nomboutique : nomboutique,
                   num_proprietaire : num_proprietaire,
                   adresse_proprietaire : adresse_proprietaire,
                   CIN_proprietaire : CIN_proprietaire,
                   date_cin : date_cin,
                   date: date_acceptation_utilisation,
                   lieu : lieu,
                   valider: 'valider',
                },
                dataType : 'html',
                beforeSend : function(){

                },
                success : function(data){
                  location.reload(true);
              }
           });
          
      });

     function mydate(){
      var d = new Date();
      var m=d.getMonth();
      var y=d.getFullYear();
      var day=d.getDate();
      var n = d.getHours();
      var min = d.getMinutes();
      var sec = d.getSeconds();
      var month=['Janvier','Février','mars','avril','mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
      return day+" "+month[m]+" "+y+" / "+n+"h:"+min+"min";

   }
});

