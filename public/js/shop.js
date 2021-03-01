$(function(){

   $('.js-category').on('click',function(e){
       e.preventDefault();
     ($(this).children('.submenu').hasClass('show_list_category_menu') ) ? $(this).children('.submenu').removeClass('show_list_category_menu') : $(this).children('.submenu').addClass('show_list_category_menu');
   });
   $('.js-show-menu').on('click',function(e){
       e.preventDefault();
       $('.menu_v').css('transform','translate(0px)');
   })
   $('.js-hide-menu').on('click',function(e){
      e.preventDefault();
      $('.menu_v').css('transform','translate(-250px)');
  })
   /**
    * move boutique shop 
    */
  setInterval(slide_image_boutique_logo,3000);
    function slide_image_boutique_logo(){
          var first_child=$('#listshops .text-center').first();
             first_child.fadeOut('slow',function(){
             	$('#listshops').append(first_child);
             	first_child.fadeIn('slow');
             });

        last=$('#listshops .text-center').eq(1);
        var id=last.attr('id');

        document.cookie =$('.shop_type').val()+"="+parseInt(id); 


   }
    $('.aside').on('change',function(e){ 
        e.preventDefault(); 
         let data = new FormData(this);
/* 
         const url = new URL($(this).attr('action') || window.location.href );
         const params = new URLSearchParams();
         data.forEach((value, key)=>params.append(key,value));
         generateUrl(url.pathname+"?"+params.toString());

*/
        $.ajax({
            url: "/product/list",
            type : 'POST',
            data,
            contentType: false,
            processData : false,
            cache : false,
            dataType :'html',
             beforeSend : ()=>{
                 $('.div-inother').show();  
                 
             },
           error : ()=>{
                toastr.error('Erreur inconue'); 
                $('.div-inother').hide(); 
                //$("form")[0].reset()
           },
           success : (data)=>{
               $('.js_list_article').html(data);
               $('.div-inother').hide(); 
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
                      
           }
       });
    })

    function generateUrl(url){
       history.replaceState({},"",url);
    }
});