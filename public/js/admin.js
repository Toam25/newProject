$(function () {
  var $category;


  //delete header 
  $('.del_header').on('click',function(e){
     e.preventDefault();
     $.ajax({
      url : "/api/delete/header_images",
      type: 'POST',
      dataType: 'json',
      success : (data)=>{
         
      },
      error : ()=>{
            toastr.error('Il y a un erreur')
      }
  });
});
  //edition vote

  $('._edit_vote').on('submit',function(e){
    e.preventDefault();
    let id = $('.id-vote').val();
    $.ajax({
      url : "/api/edit/vote/"+1 ,
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: () => {
        $('.btn-submit2').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
        $('.btn-submit2').prop('disabled', true)
      },
      success: () => {
        toastr.success('Enregistrer avec success');
        $('.btn-submit2').children('.loader_ajax').remove();
        $('.btn-submit2').prop('disabled', false)
      },
      error: () => {
        toastr.error('Une error à été survenue');
        $('.btn-submit2').children('.loader_ajax').remove();
        $('.btn-submit2').prop('disabled', false)
      },
      complete: function () {

      }
    });
  });
  //edition shop
  $('.shop_edit').on('submit',function(e){
    e.preventDefault();
    url = "/admin/boutique";
    $.ajax({
      url,
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: () => {
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
        $('.btn-submit').prop('disabled', true)
      },
      success: () => {
        toastr.success('Enregistrer avec success');
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)
      },
      error: () => {
        toastr.error('Une error à été survenue');
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)
      },
      complete: function () {

      }
    });
  })
  // delete shop
  $('body').on('click','.js-delate-shop',function(e){
    e.preventDefault();
    Lobibox.confirm({
      msg: 'Voulez vous supprimer cette Video ?',
      buttons : {
         yes : {
            text : 'Acceptez',

        },
        no : {
            text : 'Annulez',

        },
       
      },

      callback : ($this,type)=>{

      if(type==="yes"){
         
        
        url ='/api/delete/boutique/'+$(this).data('id');
        $(this).parents('.js-container-shop').remove()
        $.ajax({
          url,
         type :'POST',
         data :{
          },
          dataType : 'json',
         beforeSend : function(){
  
         },
         success : function(data){
          toastr.success('Supprimer avec success');
  
         },
         error : function(){
           toastr.error('Il y a un erreur');
  
         }
       });
     }  
    } 

   }); 
    
  });
    //detail article

  $('body').on('click','.article_delete',function(e){
    e.preventDefault();
      url ='/admin/article/delete/'+$(this).data('id');
      $(this).parents('.list_produit').remove()
      $.ajax({
        url,
       type :'POST',
       data :{
        },
        dataType : 'json',
       beforeSend : function(){

       },
       success : function(data){
        toastr.success('Supprimer avec success');

       },
       error : function(){
         toastr.error('Il y a un erreur');

       }
     });
  });
   //update image 
   $('.form_view_update_image').on("submit",function(e){
    e.preventDefault();
      $.ajax({
        url :'/api/update/images',
       type :'POST',
       data : new FormData(this),
       contentType: false,
       processData : false,
       cache : false,
       dataType : 'json',
       beforeSend : function(){
         $('#view_article').modal('show');
         $('.loader_ajax_').css('transform','scale(1)');
       },
       success : function(data){
        
        toastr.success('Enregistrer avec success');
        $('.loader_ajax_').css('transform','scale(0)');

       },
       error : function(){
         toastr.error('Il y a un erreur');
         $('.loader_ajax_').css('transform','scale(0)');
       }
     })
  });
  //update artilce 
  $('.form_article_in_shop_update').on("submit",function(e){
    e.preventDefault();
      $.ajax({
        url :'/api/update/article',
       type :'POST',
       data : new FormData(this),
       contentType: false,
       processData : false,
       cache : false,
       dataType : 'json',
       beforeSend : function(){
         $('#view_article').modal('show');
         $('.loader_ajax_').css('transform','scale(1)');
       },
       success : function(data){
        toastr.success('Enregistrer avecd success');
        $('.loader_ajax_').css('transform','scale(0)');
        $('input[value="'+data.id+'"]').parent('.list_produit').children('p').text(tronquer($('#view_name').val(),11,false));
        $('#view_article').modal('hide');
        
       },
       error : function(){
         toastr.error('Il y a un erreur');
         $('.loader_ajax_').css('transform','scale(0)');
       }
     })
  });

  //detail article

  $('body').on('click','.article_edit',function(e){
    e.preventDefault();
      url ='/api/get/article/'+$(this).data('id');
      $.ajax({
        url,
       type :'POST',
       data :{
        },
        dataType : 'json',
       beforeSend : function(){

        $('.loader_ajax_').css('transform','scale(1)');

         $('#view_article').modal('show');
       },
       success : function(data){
          $('view_slide').prop('checked', false);
          $('.id-article').val(data.id);
          $('._view_category').val(data.category);
          $('#preview_image').attr('src','/images/'+data.images['name']);
          $('#view_id-image').val(data.images['id']);
          $('#view_name').val(data.name);
          $('#view_prix').val(data.price);
          $('#view_prix_g').val(data.global_price);
          $('#view_stock').val(data.quantity);
          $('#slide'+data.slider).prop('checked', true);
          $('._view_simple_article_sous_category').val(data.sous_category)
          $('#view_sous_category').html(list_option(data.list_menu,data.sous_category));
          $('#view_type').html(list_type(data.list_menu,data.sous_category,data.type));
          $('#view_simple_article_type').html(typeArticle(data.sous_category,data.type))
          $('#view_referency').val(data.referency)
          $('#view_promo').children('option[value="'+data.promo+'"]').prop('selected',true);
          $('#view_promo_price').val(data.promo_price);
          $('#view_detail').val(data.description);
          $('.loader_ajax_').css('transform','scale(0)');
       },
       error : function(){
         toastr.error('Il y a un erreur');
         $('.loader_ajax_').css('transform','scale(0)');
       }
     })
  })


  function list_type(arrays, value, type){
    let option="";
    let selected ="";
    let classe="";
     for (const array of arrays) {
       if(array.type===value){
           for (const arrayoption of array.option) {
     
            if(arrayoption===type){
              selected="selected";
              classe="selected_option";
              }
              else{
                selected ="";
                classe="";
              }
              option+= `<option class="view_sous_categorie  `+classe+`" data-name="`+arrayoption+`" value="`+arrayoption+`" `+selected+`>
              `+arrayoption+`
              </option>`;
           }

       }
     }

    return option
}
  function list_option(arrays, value){
    let option="";
    let selected ="";
    let classe="";
    let type ="";
     for (const array of arrays) {
            type = array.type ;
       if(array.type===value){
           selected="selected";
           classe="selected_option";
       }
       else{
         selected ="";
         classe="";
       }
       option+= `<option class="view_sous_categorie  `+classe+`" data-name="`+type+`" value="`+type+`" `+selected+`>
       `+type+`
       </option>`;
     }

    return option
}
  //tab paramtre option

  $('.list_option').on('click','.delete_option',function(e){
    e.preventDefault();

       var id=$(this).attr('value');
      
      $.ajax({
         url :'/api/delete/option/'+id,
        type :'POST',
        data :{
         },
         dataType : 'json',
        beforeSend : ()=>{
          $(this).parent('.option_menu').addClass('scale0');
        },
        success : (data)=>{
          toastr.success('Supprimer avec success');
          $(this).parent('.option_menu').remove();
        },
        error : ()=>{
          toastr.error('Il y a un erreur');
          $(this).parent('.option_menu').removeClass('scale1');
        }
      })
});

 /* $('body').on('click','.tab_men',function(e){
             
    categorie_menu=$(this).attr('name');
     $('.add_option').children('input[name="categorie_sante"]').val(categorie_menu);
     $('.items-menu-selected').removeClass('items-menu-selected');
     $(`button[name="`+categorie_menu+`"]`).parent('.items-menu ').addClass('items-menu-selected');
     $('.list_option').html("");
    containt_parameter(categorie_menu);
    return false ;
});

  $('#onglets').tabs();
*/
  $('.attibute_article_in').on('change',function(){
    let sous_category= $(this).parent('form').children("input[name='categorie_sante']").val();
    let category = $(this).attr('id');
    
    console.log(category);

    let attr_categorie_menu = $(this).val();

    $('.add_option_'+sous_category).attr('data-value',attr_categorie_menu);

    $('fieldset').children('.loader_li').remove();
    $.ajax({
     url :'/api/get/listOption',
     type: 'POST',
     data : {
       categorie_sante_in : attr_categorie_menu,
     },
     dataType : 'json',
     beforeSend : function(){ 
      $('.btn-submit'+category).prop('disabled', true)
       $('#container_'+category).children('.loader_li').remove();
      $('#listOption'+category).after('<img class="loader_li" style="height: 14px" src="/images/images_default/ajax-loader.gif"/>');
  },
     success : function(data){
       let content="";
       data.forEach(element => {
         content = `
        <div class="option_menu">`+element.name+`<button class="delete_option" value="`+element.id+`">
        <span class="fa fa-trash"></span></button></div>
        ` + content;
       });
       $('#list-option-'+category).html(content);
     },
     complete : function(){
      $('.btn-submit'+category).prop('disabled', false)
      $('#container_'+category).children('.loader_li').remove();
     },
     error : ()=>{
      $('.btn-submit'+category).prop('disabled', false)
     }
    })
  });
  
  $('.modifi_menu_in').on('click', function (e) {
    e.preventDefault();
    var categorie_menu = $(this).attr('name');
    var category = $(this).data('category');
    var listOption = [];
    var cible = '.attibute_article_in';

    $('#container_button_add_'+category).children('.items-menu-selected').removeClass('items-menu-selected');
    $(this).parent('div').addClass('items-menu-selected');

    $('.name_menu').attr("placeholder", $(this).parents('.items-menu').children('.name_menu_sante').text());
    $('.add_option').children('input[name="categorie_sante"]').val(categorie_menu);

    $.ajax({
      url : "/api/get/listOption",
      type: 'POST',
      data: {
        categorie_sante_in: $(this).data('value') ,
      },
      dataType: 'json',
      beforeSend: function () {
        $('#container_'+category).children('.loader_li').remove();
        $('#listOption'+category).after('<img class="loader_li" style="height: 14px" src="/images/images_default/ajax-loader.gif"/>');
      },
      success: function (data) {
        let content="";
       data.forEach(element => {
         content = `
        <div class="option_menu">`+element.name+`<button class="delete_option" value="`+element.id+`">
        <span class="fa fa-trash"></span></button></div>
        ` + content;
       });
       $('#list-option-'+category).html(content);
      },
      complete: function () {
        $('#container_'+category).children('.loader_li').remove();
      }
    });

    //containt_parameter(categorie_menu);

  });
  $('.add_option').on('submit',function(e){
    e.preventDefault();
     var  form=$(this);
     let category = $(this).children('select').attr('id');
     $.ajax({
          url :'/api/add/listOption',
          type: 'POST',
          data : new FormData(this),
          contentType: false,
          processData : false,
          cache : false,
          dataType : 'json',
          beforeSend : ()=>{
              $('#container_'+category).children('.loader_li').remove();
               $('#listOption'+category).after('<img class="loader_li" style="height: 14px" src="/images/images_default/ajax-loader.gif"/>');
              $('.btn-submit'+category).prop('disabled', true)
          },
          success : (data)=>{

            toastr.success('Enregistrer avec success');
              let content = `
              <div class="option_menu">`+data.results.name+`<button class="delete_option" value="`+data.results.id+`">
              <span class="fa fa-trash"></span></button></div>
              `;
              $('#list-option-'+category).append(content);

              //$('.list_option').prepend(data.content);
             // $(this)[0].reset();
              $('.btn-submit'+category).prop('disabled', false)

          
          },
          complete : ()=>{
            $('#container_'+category).children('.loader_li').remove();
          },
          error: () => {
            toastr.error('Une error à été survenue');
            $('#container_'+category).children('.loader_li').remove();
            $('.btn-submit'+category).prop('disabled', false)
          },
     });
  });
  //add category 
  $('body').on('click', '.add_category', function (e) {
    e.preventDefault();
    var $form = $('.form_add_category');
    var parentId = $(this).data('parent');
    $('#name_category').text("");
    let namecategory = $(this).parent('div').children('p').text();
    $('#name_category').text(namecategory);
    $form.find('#category_parent_id').val(parentId);
    $category = $('#category-' + parentId);
    $("#ajout_category").modal('show');
  });

  $('body').on('submit', '.form_add_category ', function (e) {
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
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
        $('.btn-submit').prop('disabled', true)
      },
      success: (data) => {
        toastr.success('Enregistrer avec success');
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)

        let category = `
           <div 
            id="category-`+ data.id + `"
            data-parent="category-`+ data.parentId + `"
           class="listcategory"
          >
          <p>`+ data.name + `</p>
          <button  data-parent = `+ data.id + ` class="btn btn-primary add_category">Ajout</button>
          </div>`;
        if (parseInt(data.parentId) != 0) {
          category = `<div style="margin-left:40px">` + category + `</div>`;
          $category.after(category);
        }
        else {
          $('.container-fluid').append(category)
        }
      },
      error: () => {
        toastr.error('Une error à été survenue');
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)
      },
      complete: function () {

      }
    });
  });
  // edit article
  $('.edit_article_in_shop').on('submit', function (e) {
    e.preventDefault()
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
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
        $('.btn-submit').prop('disabled', true)
      },
      success: (data) => {
        toastr.success('Enregistrer avec success');
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)

      },
      error: () => {
        toastr.error('Une error à été survenue');
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)
      },
      complete: function () {

      }
    });
  });
  //add article
  $('.add_article_in_shop').on('submit', function (e) {
    e.preventDefault()
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
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
        $('.btn-submit').prop('disabled', true)
      },
      success: (data) => {
        toastr.success('Ajouter avec success');
        $('#ajout_article').modal('hide');
        $('.all_article').append(`
              <div class="container_article col-xs-6 col-sm-3 col-md-2 col-lg-2">
                      <div class="list_produit">
                          <input type="hidden" name="table" value="article_in">
                          <input type="hidden" name="id_image" value="`+ data.id + `">
                  
                            <img class="image_produit" src="/images/`+ data.images[0] + `" alt="` + data.name + `">
                          <p class="name_article">`+ tronquer(data.name,11,false) + `</p>
                            <span class="remove_article glyphicon glyphicon-remove"></span> 
                            <div>
                         <button class="btn btn-warning article_edit" data-id="`+ data.id + `">Détail</button>
                         <button class="btn btn-danger article_delete" data-id="`+ data.id + `">Supprimer</button>
                      </div>
                      </div>
            </div>
         `);
        $(this)[0].reset();
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)

      },
      error: () => {
        toastr.error('Une error à été survenue');
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)
      },
      complete: function () {

      }
    });
  });
  //add type sous_category
  $('.ajout_article_ev').on('click', function (e) {
    e.preventDefault();


    $('.radio1').children('label').removeClass('promotion_selected');
    $('input[value="Normale"]').parents('label').addClass('promotion_selected');

    let category = $(this).attr('id');

    $('._category').val(category);
    $('._sous_category').val(category);
    $('#div-inother').hide()
    $('#article_sous_category').attr('value', $(this).attr('id'));
    $('#article_type').html(typeArticle($(this).attr('id')));
    $('#ajout_article').modal('show');

    $.ajax({
      url: '/api/get/sous_category/type/'+category,
      type: 'GET',
      data: {},
      dataType: 'json',
      beforeSend: () => {

      },
      success: (data) => {
          let results=data.results;
          let sous_category ="";
          let type ="";
          let selected = "";

         
          if(results.length!=0){
          for (let index = 0; index < results.length; index++) {
            selected=(index==0) ? "selected" : "";
           sous_category+=
            `<option class="sous_categorie_menu_on_type" data-name="`+results[index].type+`" value="`+results[index].type+`" `+selected+`>
            `+results[index].type+`
            </option>`;
          }
         
            for (let indexo= 0; indexo < results[0].option.length; indexo++) {
              selected=(indexo==0) ? "selected" : "";
              type+=
               `<option class="sous_categorie_menu_on_type" data-name="`+results[0].option[indexo]+`" value="`+results[0].option[indexo]+`" `+selected+`>
               `+results[0].option[indexo]+`
               </option>`
           
          }
        }
          $('#sous_category').html(sous_category);
          $('.type').html(type);
      },
      error: () => {
      },
      complete: function () {

      }
    });


  });
 
  // get Type 

  $('.sous_category').on('change',function(e){
             
    var sous_categorie= $(this).val();

    $('#type').parent('div').children('img').remove();
    $.ajax({
     url : '/api/get/list/type/'+sous_categorie,
     type :'POST',
     dataType : 'json',
     data :{
          
      },
     beforeSend : function(){
            
       $('.valeur_type').after('<img style="height: 14px" src="/images/images_default/ajax-loader.gif "/>');
     },
     success : function(datas){
      
      var selected;
      var option=" ";
      data=datas.results;
      for(var $i=0; $i<data.length;$i++){
        if($i==0){
            selected="selected";
        }
        else{
          selected=" ";
        }
        option+=`<option data_name="`+data[$i]+`" value="`+data[$i]+`" `+selected+` >`+data[$i]+
                  `</option>`;
      }
      $('#type').html(option);
     },

     complete : function(){
      $('.valeur_type').parent('div').children('img').remove();
     },

     error :function(){
      $('.valeur_type').parent('div').children('img').remove();

     }

    });

});

///
  $('.close').on('click', function (e) {
    e.preventDefault();

    $('.ajout_article_ev').removeClass('red');
    $('.ajout_article_ev').children('span').css('transform', 'rotate(0)');

    $('.ajout_article_sante').removeClass('red');

  });
  //change promo 
  $('#promo_in').on('change', function (e) {
    e.preventDefault();
    if ($(this).val() === "Promo") {
      $('.inter-promo').css('display', 'block');
 

    }

    else {
      $('.inter-promo').hide();
      $('input[name="promo_in"]').attr('value', 0);
      $('input[name="promo_in"]').val(0);
    }

  });
  // delete reference

  $('.delete_reference').on('click', function (e) {
    e.preventDefault()
    var id = parseInt($(this).attr('data-idreference'));
    $.ajax({
      url: '/admin/reference/' + id,
      type: 'DELETE',
      data: {},
      dataType: 'json',
      beforeSend: () => {
        $(this).append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
        $(this).prop('disabled', true)
      },
      success: (data) => {
        toastr.success('Supprimer avec avec success')
        ;
        $(this).parents('.slide_animation ').css('transform', 'scale(0)');
        setTimeout(() => {
          $(this).parents('.slide_animation ').remove();
        }, 500);
      },
      error: () => {
        toastr.error('Une error à été survenue');
        $(this).children('.loader_ajax').remove();
        $(this).prop('disabled', false)
      },
      complete: function () {

      }
    });
  });
  // delete social network

  $('.delete_social_network').on('click', function (e) {
    e.preventDefault()
    var id = parseInt($(this).attr('val'));
    $.ajax({
      url: '/admin/social/network/delete/' + id,
      type: 'DELETE',
      data: {},
      dataType: 'json',
      beforeSend: () => {
        $(this).append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
        $(this).prop('disabled', true)
      },
      success: (data) => {
        toastr.success('Supprimer avec avec success');
        ;
        $(this).parents('.slide_animation ').css('transform', 'scale(0)');
        setTimeout(() => {
          $(this).parents('.slide_animation ').remove();
        }, 500);
      },
      error: () => {
        toastr.error('Une error à été survenue');
        $(this).children('.loader_ajax').remove();
        $(this).prop('disabled', false)
      },
      complete: function () {

      }
    });
  });
  //edit reference

  $('.edit_reference').on('submit', function (e) {
    e.preventDefault();
    var url = $(this).attr('action');
    $.ajax({
      url,
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: function () {
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>')
        $('.btn-submit').prop('disabled', true);
      },
      success: (data) => {
        toastr.success('Enregistrer avec success');
        setTimeout(() => {
          $('.btn-submit').children('.loader_ajax').remove();
          $('.btn-submit').prop('disabled', false);
        }, 300);


      },
      error: function (jqXHR, exception, error) {
        toastr.error('Une error à été survenue ' + error);
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false);
      },
      complete: function () {

      }
    });

  });
  //edit social network

  $('.edit_form_social_network').on('submit', function (e) {
    e.preventDefault();
    var id = $('.id_social_network').val();
    $.ajax({
      url: '/admin/social/network/' + id + '/edit',
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: function () {
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>')
        $('.btn-submit').prop('disabled', true);
      },
      success: (data) => {
        toastr.success('Enregistrer avec success');
        setTimeout(() => {
          $('.btn-submit').children('.loader_ajax').remove();
          $('.btn-submit').prop('disabled', false);
        }, 300);


      },
      error: function (jqXHR, exception, error) {
        toastr.error('Une error à été survenue ' + error);
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false);
      },
      complete: function () {

      }
    });

  });

  // add social network
  $('.namelink').on('keyup', function (e) {
    let description = $('.description').val();
    $('.a_link_social_network').html($('.namelink').val());
  })
  $('.description').on('keyup', function (e) {
    let description = $('.description').val();
   // let namelink = $('.a_link_social_network').html($('.namelink').val());
    $('.des').html(description);
  })
  $('.link').on('keyup', function (e) {
     let link = $('.link').val();
     $('.a_link_social_network').prop('href',link);
  })
  $('.social_network').on('submit', function (e) {
    e.preventDefault(e);

    $.ajax({
      url: '/admin/social/network/new',
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: function () {
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>')
        $('.btn-submit').prop('disabled', true);
      },
      success: (data) => {
        toastr.success('Enregistrer avec success');
        setTimeout(() => {
          $('.btn-submit').children('.loader_ajax').remove();
          $('.btn-submit').prop('disabled', false);
          $('#preview_image').prop('src', '');
          $(this)[0].reset();
        }, 300);


      },
      error: function (jqXHR, exception, error) {
        toastr.error('Une error à été survenue ' + error);
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false);
      },
      complete: function () {

      }
    });

  });
  //add reference 

  $('.add_reference').on('click', function (e) {
    e.preventDefault(e);
    $("#add_reference").modal('show');
  });
  // add vote 
  $('.add_vote').on('click', function (e) {
    e.preventDefault(e);
    $('#add_vote').modal('show');
  });

  $('._add_vote').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: '/admin/vote/list',
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: function () {
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>')
        $('.btn-submit').prop('disabled', true);
      },
      success: (data) => {
        toastr.success('Enregistrer avec success');
        $('.container_all_image_vote').prepend('<div  class="margin-top-5 image_vote col-xs-6 col-sm-4 col-md-2 col-lg-2"> <div class="container_image_for_header" style="background-color: ' + data.color + '"> <img name="' + data.id + '" alt="' + data.apropos + '" class="image_for_header" src="/images/images_default/default_image.jpg"> </div> <div class="container_image_for_body"><img name="' + data.id + '" alt="' + data.apropos + '" class="image_for_body" src="/images/' + data.images + '"><input type="hidden" value="' + data.description + '"></div><button class="remove_vote btn btn-danger" data-idartcle="' + data.id + '" style="width: 100%;border: 0;border-radius: 0px !important;">Supprimer</button></div>');
        $('#add_vote').modal('hide');

        setTimeout(() => {
          $('.btn-submit').children('.loader_ajax').remove();
          $('.btn-submit').prop('disabled', false);
          $('#preview_image').prop('src', '');
          $(this)[0].reset();
        }, 300);


      },
      error: function () {
        toastr.error('Une error à été survenue');
        $('.btn-submit').children('.loader_ajax').remove();
          $('.btn-submit').prop('disabled', false);
      },
      complete: function () {

      }
    });

  });
  // edit vote 
  $('body').on('click', '.image_for_body', function (e) {
    e.preventDefault();
    let src = $(this).attr('src');
     $('.id-vote').val($(this).attr('name'));
    let description = $(this).parent('div').children('input').val();
    $('.text_area_edit').val(description);
    $('._preview_image').prop('src', src);
    $('#edit_vote').modal('show');

  });
  //delete vote 
  $('body').on('click', '.remove_vote ', function (e) {
    e.preventDefault();
    var id = parseInt($(this).attr('data-idartcle'));
    $.ajax({
      url: '/admin/vote/delete/' + id,
      type: 'DELETE',
      data: {},
      dataType: 'json',
      beforeSend: () => {
        $(this).prop('disabled', true)
      },
      success: (data) => {
        toastr.success('Supprimer avec avec success');
        ;
        $(this).parents('.image_vote ').css('transform', 'scale(0)');
        setTimeout(() => {
          $(this).parents('.image_vote ').remove();
        }, 500);
      },
      error: () => {
        toastr.error('Une error à été survenue');
        $(this).prop('disabled', false)
      },
      complete: function () {

      }
    });

  });
  //delate header

  $('.delete_header').on('click', function (e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    $.ajax({
      url: '/api/header_image/delete/' + id,
      type: 'DELETE',
      dataType: 'json',
      beforeSend: function () {

      },
      success: function (data) {
        toastr.success('Supprimer avec succes');
        $('#header_index').children('img').prop('src', 'images/images_default/default_image.jpg');
        // $('#header_index').children('img').prop('src','images/'+data.images);
      },
      error: function () {
        toastr.error('Une error à été survenue');
      },
      complete: function () {

      }
    });
  })
  //show header 
  $('.show_header').on('click', function (e) {
    e.preventDefault()
    $('#view_header').modal('show');
    $('#_view_header').children('img').prop('src', $('#header_index').children('img').prop('src'));
  })
  //edit header boutique
  $('.edit_header').on('click', function (e) {
    e.preventDefault();
    $('#add_header').modal('show');
  });

  $('.header_image').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: '/api/header_image/edit',
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: function () {
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>');
        $('.btn-submit').prop('disabled', true)
      },
      success: (data)=>{
        toastr.success('Enregistrer avec success');
        $('#header_index').children('img').prop('src', 'images/' + data.images);
        $(this).children('div').children('button').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false);
        $('#add_header').modal('hide');
      },
      error:  ()=>{
        toastr.error('Une erreur à été survenue');
        $(this).children('div').children('button').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false);
      },
      complete: function () {

      }
    });
  });

  // preview image  
  //preview image
  $('body').on('change', '.file', function (event) {
    var reader = new FileReader();
    reader.onload = function () {
      var preview_image = document.getElementById('preview_image');
      preview_image.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
  });
  //add ess article 
  // add es article
  $('body').on('submit', '.add_ess_article', function (e) {
    e.preventDefault();

    var form = $(this);
    $.ajax({
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: function () {
        $('.name_menu').append(`<span><img class="loader_img_default"src="/images/images_default/ajax-loader.gif"/>`);
      },
      success: function (data) {
        form[0].reset();
        toastr.success('Enregistrer avec success');
        $('#preview_image').attr('src',"")
        $('.liste_article_in').prepend(`
            <div class="container_article col-xs-6 col-sm-3 col-md-2 col-lg-2">
            <div class="list_produit">
              <input type="hidden" name="table" value="article_in">
                <input type="hidden" name="id_image" value="`+ data.id + `">
                  <img class="image_produit" src="/images/`+ data.images + `" alt="` + data.type + `">
                    <p class="name_article">`+ data.type + `</p>
                    <div class="action_for_article">
                       <button class="btn btn-success btn_detail_article">Détail</button>
                       <button class="btn btn-danger bnt_delete_article">Supprimer</button>
                    </div>
              </div>
          </div>`);

      },
      complete: function () {
        form.children('.inother-ajax').children('.div-inother').hide();
        $('.name_menu').children('span').remove();

      }
    });
  });
  //add user admin
  $('#add_user_admin').on('submit', function (e) {
    e.preventDefault();
    var url = $('this').attr('action');
  
    $.ajax({
      url: url,
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json',
      beforeSend: () => {
        $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>')
        $('.btn-submit').prop('disabled', true);

      },
      error: () => {
        toastr.error('Erreur inconue');
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)
      },
      success: (data) => {
        if (data.status == 'ok') {
          toastr.success(data.msg);
          $("form[name='user']")[0].reset();
        }
        if (data.status == 'ko') {
           toastr.error(data.msg);
        }
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)

      },
      complete: () => {
        $('.logo').css('display', 'none');
      }
    });

  });
  
  // add nnew ess article
  $('.ajout_ess_article_ev').on('click', function () {
    activeAddArticle();
    $("#ajout_article").modal('show');
    
    $('.file').prop('required', true);
    $('.type_article_in').attr('value', $(this).attr('id'));
    $('.form_article').addClass('add_ess_article');
    $('.form_article').removeClass('edit_article');
    $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>')
    $('.btn-submit').prop('disabled', true);
    $.ajax({
      url : '/api/getOptions/'+$(this).attr('id'),
      type : "GET",
      dataType : 'json',
      success : (data)=>{
         $('#es_article_type').html(getMyOption(data['option']));
         $('.btn-submit').children('.loader_ajax').remove();
         $('.btn-submit').prop('disabled', false)
      },
      error : ()=>{
        $('.btn-submit').children('.loader_ajax').remove();
        $('.btn-submit').prop('disabled', false)
      }

    })
   // $('#es_article_type').html(typeArticle($(this).attr('id')));
  });


  function typeArticle(name, item = null) {
    var options = "";
    var classe = "";
    var types = [
      {
        name: "Vetements_homme",
        value: ['Chemise', 'Jeans', 'T-shirts', 'Polos', 'Pulls', 'Gilets', 'Sweats-Shirts', 'Manteaux', 'Costumes', 'Vestes', 'Pantalons', 'Short', 'Bermudas', 'Tenues de Sports', 'Vetement de nuit', 'Impermeable', 'Maillots de Bains', 'Chaussettes', 'Ensemble', 'Jogging', 'Blousons']
      },
      {
        name: "Chaussures_homme",
        value: ['Derbies', 'Chaussures de ville', 'Slippers', 'Derbie', 'Baskets', 'Bottes', 'Boots', 'Chaussons', 'Chaussures bateau', 'Chaussures de Securité', 'Chaussures de sports', 'Espadrilles', 'Mocassins', 'Mulle', ' Sabot', 'Sandales', 'Tongs']
      },
      {
        name: "Lingeries_homme",
        value: ['Caleçon', 'Boxeur', 'Slips', 'Short', 'Jock strap', 'Chaussette', 'Pantie']
      },
      {
        name: "Vetement-femme",
        value: ['Robe de ceremonie', 'Robe de mariée', 'Robe de fiançaille', 'Sweats-Shirts', 'Jeans', 'Cardigans', 'Chemise', 'Body', 'Blouse', 'T-shirts', 'Polos', 'Débardeur', 'Pulls', 'Gilets', 'Sweats-Shirts', 'Manteaux', 'Tailleurs', 'Vestes', 'Blousons', 'Jupes', 'Pantalons', 'Salopettes', 'Combinaisons', 'Combi-short', 'Chaussettes', 'Collants', 'Vêtements de Grossesse', 'Vetement de nuit', 'Vetement de Sports', 'Imperméable', 'Maillots de Bains', 'Costumes', 'Ensemble', 'Jogging']
      },
      {
        name: "Chaussure-femmme",
        value: ['Ballerines ', 'Baskets', 'Bottes', 'Boots', 'Chaussons', 'Chaussures Bateau', 'Chaussures de Securité', 'Chaussures de ville', 'Derbie', 'Designer', 'Escarpins', 'Espadrilles', ' Mary Janes', 'Mocassins', 'Mulle', 'Sabot', 'Sandales', 'Sport', 'Tongs']
      },
      {
        name: "Lingeries-femme",
        value: ['Slips','Dentelle côté', 'Tanga', 'Boxer', 'Accessoires', 'Bas', 'Jarretières', 'Bodys', 'Bustiers', 'corsets', 'Caracos', 'Combinaisons', 'Jupons', 'Culottes', 'Shorties', 'Strings', 'Ensembles de Lingeries', 'Lingeries Sculptantes', 'Nuisettes', 'Deshabillés', 'Vêtements Thérmiques', 'Soutiens Gorges']
      },
      {
        name: "Vetements_enfant",
        value: ['Pantalon', 'Cardigans', ' Blousons', 'Sweats-Shirts', 'Bermudas', 'Chemise', 'T-shirts', 'Polos', 'Pulls', 'Jean', 'Short', 'Bermudas', 'Salopettes', 'Ensembles', 'Maillots de bains', 'Sous - vêtements', 'Joggins', 'Imperméable']
      },
      {
        name: "Lingeries_enfant",
        value: ['Salopette', 'Jeans', 'Sweat-Shirt', 'Tailleur', 'Veste', 'Chemise', 'Blouses', 'T-shirts', 'Pulls', 'Débardeur', 'Gilets', 'Cardigans', 'Manteaux', 'Blousons', 'Jupes', 'Short', 'Bermudas', 'Pantalons', 'Leggins', 'Robes', 'Ensembles', 'Salopettes', 'Combinaisons', 'Combi-short', 'Sous - vêtements', 'Collants', 'Maillots de Bains', 'Tenues de Sports', 'Vêtements de nuits', 'Peignoirs', 'Imperméable']
      },
      {
        name: "Chaussures_enfant",
        value:
          ['Escarpins', 'Boots', ' Babies', 'Ballerines', 'Baskets mode', 'Bottes', 'Bottines', 'Chaussures Bateau', 'Chaussures de Sport', 'Chaussures de ville', 'Espadrilles', 'Mocassins', 'Mulle', 'Sabot', 'Sandales', 'Tongs ']
      },

      {
        name: "bebe_garcons",
        value:
          ['Couverture Bébé', 'Peluches', 'Veste', 'Salopettes', 'Combinaison', 'Sweat-Shirt', 'Polos', 'Bodys', 'Grenoullière', 'T-shirts', 'Débardeur', 'Chemise', 'Pulls', 'Cardigans', 'Pantalons', 'Shor', 'Pyjamas', 'Ensembles', 'Chaussettes', 'Maillots de bains']
      },

      {
        name: "bebe_filles",
        value:
          ['Couverture Bébé', 'Peluches', 'Vestes', 'Salopette', 'Combinaisons', 'Sweats-Shirts', 'Polos', 'Bodys', 'Grenoullère', 'T-shirts', 'Débardeur', 'Chemise  ', 'Pulls', 'Cardigans', 'Pantalons', 'Short', 'Pyjamas', 'Ensembles', 'Robes', 'Chaussettes', 'Maillots de bains']
      },

      {
        name: "bebe_chaussure",
        value:
          ['Baskets', 'Babies', 'Bottes', 'Botillons', 'Boots', 'Chaussons', 'Sandales']
      },

      {
        name: "maroquineries",
        value:
          ['Sac à main', 'Sac à dos', 'Sac de voyage', 'Sac bandoulière', 'Portefeuilles et porte-cartes', 'Cabas', 'Pochettes', 'Sacs portes épaule']
      },

      {
        name: "bijoux",
        value: ['Alliances', 'Montre', 'arure de bague', 'Boutons de manchette', 'Bagues', 'Pendentifs', 'Boucles d\'oreilles', 'Bracelets', 'Broches', 'Colliers', 'Sautoir', 'Parures de bijoux', 'Chaîne']
      },
      {
        name: "pierre_precieuse",
        value: ['Citrine', 'Quartz', 'Jade', 'Rubis', 'Saphir', 'Diamant', 'Eméraude']
      },
      {
        name: "artistique",
        value: ['Vannerie', 'Poterie', 'Miniature']
      },
      {
        name: "travail_du_bois",
        value: ['Fruits / corbeilles à pain', 'Sets de table en bambou', 'Boîte à épices']
      },
      {
        name: "decoration_interieure",
        value: ['Cadre photo', 'Support de pot de fleur', 'Pots de fleur', 'Décoration murale', 'Objet design fer forge', 'Embout', 'Montre mural en fer forgé', 'Bougeoir', 'Photophore', 'Applique & Luminaires', 'Cornes de zébu']
      },
      {
        name: "travail_du_fer",
        value: ['Literie', 'Penderie', 'Table', 'Porte cintre', 'Chaise', 'Table avec chaise', ' Range chaussures']
      },
      {
        name: "raphia",
        value: ['Sacs ', 'Panier', 'Chapeaux']
      },
      {
        name: "broderie",
        value: ['Nappe', 'Smock', 'Couvre lit', 'Richelieu', 'Crochet']
      },
      {
        name: "produit_en_soie",
        value: ['Châle', 'Malabary', 'Lambamena']
      },
      {
        name: "sisal",
        value: ['Sacs', 'Panier', 'Chapeaux']
      },
      {
        name: "acc_cheveux",
        value: ['Noeud', 'Serre tête ', 'Pince à cheveux', 'Brousse', 'Elastique de cheveux', 'Barettes', 'Bandeau']
      },
      {
        name: "acc_bijoux_montre",
        value: ['Boucle d’oreilles', 'Colliers', 'Pendentif', 'Gourmette', 'Alliances', 'Boutons de manchette', 'Bracelets', 'Bague', 'Parure de bague', 'Chaine', 'Sautoir', 'Montres']
      },
      {
        name: "acc_sacs_maroquinerie",
        value: ['Sac à main', 'Sac à dos', 'Sac de voyage', 'Sac bandoulière ', 'Portefeuilles et porte-cartes', 'Cabas', 'Pochettes', 'Sacs bowling', 'Sacs portés épaule ']
      },
      {
        name: "acc_fashion_plus",
        value:
          ['Ceinture', 'Gants', 'Casquettes', 'Chapeaux', 'Echarpes', 'Foulards', 'Bonnets', 'Headband', 'Cravates ', 'Lunettes']
      },
      {
        name: "acc_flowerbox",
        value:
          ['Petite Flowerbox', 'Moyenne Fowerbox', 'Grande Flowerbox', 'Flowerbox personnalisée']
      },

      {
        name: "acc_parfums",
        value:
          ['Eaux de toilettes', 'Déodorants homme', 'Déodorants femme', 'Parfums homme', 'Parfum femme', 'Eaux de Cologne']
      },

      {
        name: "acc_beaute_bio",
        value:
          ['Huile essentielle', 'Huile végétale', 'Huile massage', 'Produits naturels amincissant ']
      },

      {
        name: "acc_soins_corps_visage",
        value:
          ['Crayons et eyeliners', 'Mascaras', 'Ombres à paupières', 'Palettes et coffrets', 'Blush et poudres', 'Fonds de teint et BB crème', 'Rouges à lèvres', 'Primers et correcteurs', 'pilateurs sourcils', 'Dépilatoires', 'Accessoires maquillage ', 'Anti-rides et anti-âges', 'Masques et gommages', 'Nettoyants et démaquillants', 'Purifiants et matifiants', 'Soins des lèvres et des yeux', 'Crèmes', 'Crème solaire', 'Lotions', 'Baume', 'Emulsions', 'Huiles pour la peau', 'produits de bronzage', 'produits pour le rasage', 'Produits d’hygiène dentaire et buccale', 'Produits d’hygiène  intime externe', 'Bain & douche', 'Savons de toilette', 'Soins hydratants et nourrissants']
      },
      {
        name: "acc_soins_ongle",
        value:
          ['Base protectrice Clean', 'Vernis', 'Dissolvant', 'Faux ongles', 'Lime']
      },

      {
        name: "acc_soins_cheveux",
        value:
          ['Pack de produits', 'Vaporisateurs', 'Fixateurs', 'Shampooings', 'Après-shampooings', 'Masques', 'Gel', 'Colorants', 'Produit pour l\'ondulation', 'Produit de coiffage', 'Huiles', 'Soins traitements']
      },

      {
        name: "Accessoires",
        value:
          ['Coques', 'Batteries, Batteries externes', 'Ecouteurs bleutooth', 'Enceintes bleutooth', 'Chargeurs', 'Oreillette bleutooth', 'Kits mains libres', 'Protection ecran', 'Carte mémoire']
      },
      {
        name: "Téléphone",
        value:
          ['Téléphone fixe', 'Téléphone avec touche', 'Smartphone', 'I-phone']
      },
      {
        name: "TV",
        value:
          ['TV LED-LCD', 'TV 4K-UHD', 'Support TV', 'TV connectée', 'Smart TV']
      },
      {
        name: "Video_projecteur",
        value:
          ['HD Ready', 'Full HD', '4K/UHD', 'Accessoires']
      },
      {
        name: "Son",
        value:
          ['Casque auto', 'Enceintes bleutooth', 'MP3', 'MP4', 'Radio', 'Dict,aphone', 'Hifi', 'Bare de son']
      },

      {
        name: "Phone_et_caméra",
        value:
          ['Flash photo', 'Filtre', 'Caméscope caméra', 'Objectif reflex', 'Objectif caméra', 'GoPro', 'Autre']
      },

      {
        name: "Tous_les_accessoires",
        value: ['Câble et connectique', 'Accessoires audio et video', 'Accessoires photos', 'Accessoires caméra']
      },
      {
        name: "Materiels_informatiques",
        value: ['Ordinateurs de bureau', 'ordinateurs portables', 'Tablette', 'Univers gaming', 'Composants - périférique', 'Stockage', 'Réseaux']
      },
      {
        name: "Diagnostiques",
        value: ['Hardware', 'Software']
      },
      {
        name: "Impression",
        value: ['Imprimante jet d\'encre', 'Imprimante laser', 'Scaner', 'Cartouches', 'Toners']
      },
      {
        name: "Systeme_domotique",
        value: ['Motorisation', 'portails et volets', 'Accessoires', 'Interphone video', 'Alerme-Détecteur', 'Caméra de surveillance', 'Sécurité  incendie']
      },
      {
        name: "orale",
        value: ['Comprime', 'Gellule', 'Liquide']
      },
      {
        name: "injectable",
        value: ['Injectable']
      },
      {
        name: "dermique",
        value: ['Visage', 'Corps', 'Cheveux', 'Autres']
      },
      {
        name: "inhalee",
        value: ['Bébé', 'Enfant', 'Femme enceinte', 'adulte']
      },
      {
        name: "rectale",
        value: ['Rectale']
      },
      {
        name: "autre",
        value: ['Plantes médicinales', 'Produits de santé naturels', 'Complément alimentaire', 'Argile verte', 'Autres']
      },
      {
        name: "hev",
        value: ['Huile d\'amande douce ', 'Huile d\'arachide', 'Femme enceinte', 'Huile d\'argan', 'Huile d\'avocat', 'Huile de baobab', 'Huile de calendula', 'Huile de cameline', 'Huile de coco', 'Huile de colza', 'Huile de germe de blé', 'Beurre de Karité', 'Huile de Moutarde', 'Huile d\'Olive', 'Huile de Palme', 'Huile de Ricin', 'Huile de Tournesol', 'Huile de Sésame', 'Huile de Lorenzo', 'Huile de poisson']
      },
      {
        name: "Outillages",
        value: ['Accessoires','Outils à main', 'Outils divers', 'Outils multifonctions', 'Outils éléctriques', 'Accessoires', 'Electricité', 'Pièces détachées']
      },
      {
        name: "outillages_pro",
        value: ['Outillage à main', 'Outillage éléctroportatif', 'Machines equipements', 'Eléctricité', 'Quincallerie']
      },
      {
        name: "outils_de_jardin",
        value: ['Outils de jardin']
      }
    ];
    if(types.find((items) => (items.name === name))!= undefined){
       var type = types.find((items) => (items.name === name)).value;


    for (var i = 0; i < type.length; i++) {
      if (item != null) {
        if ( type[i]===item) {
          classe = "class=' selected_option' selected";
        }
        else{
          classe ="";
        }
      }
      options = '<option value="' + type[i] + '" ' + classe + '>' + type[i] + '</option>' + options;

    }
  }

    return options;
  }
  
  function getMyOption(type,item=null){
    let classe ="";
    let options="";
    for (var i = 0; i < type.length; i++) {
      if (item != null) {
        if ( type[i]===item) {
          classe = "class=' selected_option' selected";
        }
        else{
          classe ="";
        }
      }
      options = '<option value="' + type[i] + '" ' + classe + '>' + type[i] + '</option>' + options;

    }
    return options;
  }
  var containt_parameter = (categorie_menu) => {
    //telefonie
    if (categorie_menu == "Accessoires") {
      cible = "#telephonie";
      listOption = ['Coques', 'Batteries, Batteries externes', 'Ecouteurs', 'Enceintes bluetooth', 'Chargeurs', 'Oreillette bluetooth', 'Kits mains libres', 'Protection écran', 'Carte mémoire', 'Casque'];
    }

    if (categorie_menu == "Téléphone") {
      cible = "#telephonie";
      listOption = ['Téléphone fixe', 'Téléphone avec touche', 'Smartphone', 'I-phone'];
    }
    //image et son
    if (categorie_menu == "TV") {
      cible = "#image_son";
      listOption = ['TV LED-LCD', 'TV 4K-UHD', 'Support TV', 'TV connectée', 'Smart TV'];
    }
    if (categorie_menu == "Video projecteur") {
      cible = "#image_son";
      listOption = ['HD Ready', 'Full HD', '4K/UHD', 'Accessoires'];
    }
    if (categorie_menu == "Son") {
      cible = "#image_son";
      listOption = ['Casque auto', 'Enceintes bleutooth, MP3, MP4', 'Radio', 'Dictaphone', 'Hifi', 'Bare de son'];
    }
    if (categorie_menu == "Phone et caméra") {
      cible = "#image_son";
      listOption = ['Flash photo', 'Filtre', 'Caméscope caméra', 'Objectif reflex', 'Objectif caméra', 'GoPro', 'Autre'];
    }
    if (categorie_menu == "Tous les accessoires") {
      cible = "#image_son";
      listOption = ['Câble et connectique', 'Accessoires audio et video', 'Accessoires photos', 'Accessoires caméra'];
    }
    //informatique
    if (categorie_menu == "Matériels informatiques") {
      cible = "#informatique";
      listOption = ['Ordinateurs de bureau', 'ordinateurs portables', 'Tablette', 'Univers gaming', 'Composants - périphériques', 'Stockage', 'Réseaux'];
    }
    if (categorie_menu == "Diagnostiques") {
      cible = "#informatique";
      listOption = ['Hardware', 'Software'];
    }
    //impression
    if (categorie_menu == "Impression") {
      cible = "#impression";
      listOption = ['Imprimante jet d\'encre', 'Imprimante laser', 'Scaner', 'Cartouches', 'Toners'];
    }

    if (categorie_menu == "Système domotique") {
      cible = "#systme_domotique";
      listOption = ['Motorisation', 'portails et volets', 'Accessoires', 'Interphone video', 'Alerme-Détecteur', 'Caméra de surveillance', 'Sécurité  incendie'];
    }
    //outilage
    if (categorie_menu == "Outillages Pro"){
      cible ="Professionnel",
      listOption = ['Outils à main', 'Outils divers', 'Outils multifonctions', 'Outils éléctriques', 'Accessoires', 'Electricité', 'Pièces détachées']
    }
    if (categorie_menu == "Outillages"){
      cible = "Bricolage",
      listOption = ['Accessoires','Outillage à main', 'Outillage éléctroportatif', 'Machines equipements', 'Eléctricité', 'Quincallerie']
    }
    if (categorie_menu == "Outils de jardin"){
      cible = "Jardinage",
      listOption = ['Outils de jardin']
    }
 
  
    var options = attribute_article_in(listOption);

    
    $(cible).html(options);

    var attr_categorie_menu = listOption[0];
    $('fieldset').children('.loader_li').remove();

    $.ajax({
      url : "/api/get/listOption",
      type: 'POST',
      data: {
        categorie_sante_in: attr_categorie_menu,
      },
      dataType: 'json',
      beforeSend: function () {

        $('.labelListOption').after('<img class="loader_li" style="height: 14px" src="/images/images_default/ajax-loader.gif"/>');
      },
      success: function (data) {
        let content="";
       data.forEach(element => {
         content = `
        <div class="option_menu">`+element.name+`<button class="delete_option" value="`+element.id+`">
        <span class="fa fa-trash"></span></button></div>
        ` + content;
       });
       $('.list_option').html(content);
      },
      complete: function () {
        $('fieldset').children('.loader_li').remove();
      }
    });

  }

  var attribute_article_in = (option) => {

    var options = " ";
    var selected = ""
    for (var i = option.length - 1; i >= 0; i--) {

      if (i == 0) {
        selected = "selected";
      }
      else {
        selected = " "
      }
      options = '<option value="' + option[i] + '" ' + selected + '>' + option[i] + '</option>' + options;
    }
    return options;

  }
  function disableAddArticle() {
    $('.form_article').find('input').prop('disabled', true);
    $('.form_article').find('select').prop('disabled', true);
    $('.form_article').find('textarea').prop('disabled', true);
    $('.form_article').find('#article_photo').hide();
  }
  function activeAddArticle() {
    $('.form_article').find('input').prop('disabled', false);
    $('.form_article').find('textarea').prop('disabled', false);
    $('.form_article').find('select').prop('disabled', false);
    $('.form_article').find('#article_photo').show();
  }
  function tronquer($description, $max_caracteres, $coup = true) {
    //nombre de caractères à afficher
    // Test si la longueur du texte dépasse la limite
    if ($description.length > $max_caracteres) {
      // Séléction du maximum de caractères
      $description = $description.substring(0, $max_caracteres);
      // Récupération de la position du dernier espace (afin déviter de tronquer un mot)
      if ($coup == true) {
        $position_espace = $description.indexOf(" ");
        $description = $description.substring(0, $position_espace);
      }
      // Ajout des "..."
      $description = $description + "...";
    }
    return $description;
  }
});