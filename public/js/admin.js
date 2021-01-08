$(function(){



//add reference 

$('.add_reference').on('click',function(e){
    e.preventDefault(e);
    $("#add_reference").modal('show');
});
// add vote 
$('.add_vote').on('click',function(e){
  e.preventDefault(e); 
  $('#add_vote').modal('show');
});

$('._add_vote').on('submit',function(e){
    e.preventDefault();
     $.ajax({
       url : '/admin/vote/list',
       type: 'POST',
       data : new FormData(this),
       contentType: false,
       processData : false,
       cache : false,
       dataType : 'json',
       beforeSend : function(){
          $('.btn-submit').append('<span class="loader_ajax" style="height: 20px;width: 20px;display: inline-block;"><img src="/images/images_default/ajax-loader.gif" style="height: 100%;width: 100%;"></span>')
          $('.btn-submit').prop('disabled',true);
        },
       success : (data)=>{
          toastr.success('Enregistrer avec success');
          $('.container_all_image_vote').prepend('<div  class="margin-top-5 image_vote col-xs-6 col-sm-4 col-md-2 col-lg-2"> <div class="container_image_for_header" style="background-color: '+data.color+'"> <img name="'+data.id+'" alt="'+data.apropos+'" class="image_for_header" src="/images/images_default/default_image.jpg"> </div> <div class="container_image_for_body"><img name="'+data.id+'" alt="'+data.apropos+'" class="image_for_body" src="/images/'+data.images+'"></div><button class="remove_vote btn btn-danger" data-idartcle="'+data.id+'" style="width: 100%;border: 0;border-radius: 0px !important;">Supprimer</button></div>');
          $('#add_vote').modal('hide');

          setTimeout(()=>{
            $('.btn-submit').children('.loader_ajax').remove();
            $('.btn-submit').prop('disabled',false);
            $('#preview_image').prop('src','');
            $(this)[0].reset();
          },300);
         
          
       },
       error: function(){
        toastr.error('Une error à été survenue'); 
       },
       complete : function(){
         
       }
  });

});
//delete vote 
$('body').on('click','.remove_vote ',function(e){
    e.preventDefault();
    var id=parseInt($(this).attr('data-idartcle'));
    $.ajax({
      url : '/admin/vote/delete/'+id,
      type: 'DELETE',
      data : {},
      dataType : 'json',
      beforeSend : ()=>{
        $(this).prop('disabled',true)
       },
      success : (data)=>{
         toastr.success('Supprimer avec avec success');
        ; 
         $(this).parents('.image_vote ').css('transform','scale(0)');
         setTimeout(()=>{
            $(this).parents('.image_vote ').remove();
         },500);
      },
      error:()=>{
        toastr.error('Une error à été survenue'); 
        $(this).prop('disabled',true)
      },
      complete : function(){
        
      }
 });

});
//delate header

$('.delete_header').on('click',function(e){
  e.preventDefault();
  var id=$(this).attr('data-id');
     $.ajax({
       url : '/api/header_image/delete/'+id,
       type: 'DELETE',
       dataType : 'json',
       beforeSend : function(){
          
       },
       success : function(data){
          toastr.success('Supprimer avec succes'); 
          $('#header_index').children('img').prop('src','images/images_default/default_image.jpg');
         // $('#header_index').children('img').prop('src','images/'+data.images);
       },
       error: function(){
        toastr.error('Une error à été survenue'); 
       },
       complete : function(){
         
       }
  });
})
//show header 
$('.show_header').on('click',function(e){
  e.preventDefault()
  $('#view_header').modal('show');
  $('#_view_header').children('img').prop('src',$('#header_index').children('img').prop('src')); 
})
//edit header boutique
$('.edit_header').on('click', function(e){
    e.preventDefault();
    $('#add_header').modal('show');
});

$('.header_image').on('submit', function(e){
    e.preventDefault();
     $.ajax({
       url : '/api/header_image/edit',
       type: 'POST',
       data : new FormData(this),
       contentType: false,
       processData : false,
       cache : false,
       dataType : 'json',
       beforeSend : function(){
          
       },
       success : function(data){
          toastr.success('Enregistrer avec success'); 
          $('#header_index').children('img').prop('src','images/'+data.images);
       },
       error: function(){
        toastr.error('Une error à été survenue'); 
       },
       complete : function(){
         
       }
  });
});
//add user admin
$('#add_user_admin').on('submit',function(e){
     e.preventDefault();
     var url = $('this').attr('action');
     console.log(url);
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
$('body').on('submit','.add_ess_article',function(e){
  e.preventDefault(); 

  $("#ajout_article").modal('hide');
  var  form=$(this);
  $.ajax({
       type: 'POST',
       data : new FormData(this),
       contentType: false,
       processData : false,
       cache : false,
       dataType : 'json',
       beforeSend : function(){
           $('.name_menu').append(`<span><img class="loader_img_default"src="/assets/images/defaults/default_loader.gif"/>`);
       },
       success : function(data){
            
            $('.liste_article_in').prepend(`
            <div class="container_article col-xs-6 col-sm-3 col-md-2 col-lg-2">
            <div class="list_produit">
              <input type="hidden" name="table" value="article_in">
                <input type="hidden" name="id_image" value="`+data.id+`">
                  <img class="image_produit" src="/images/`+data.images+`" alt="`+data.type+`">
                    <p class="name_article">`+data.type+`</p>
                    <div class="action_for_article">
                       <button class="btn btn-success btn_detail_article">Détail</button>
                       <button class="btn btn-danger bnt_delete_article">Supprimer</button>
                    </div>
              </div>
          </div>`);
       
       },
       complete : function(){
         form.children('.inother-ajax').children('.div-inother').hide();
         $('.name_menu').children('span').remove();
         
       }
  });
});
//add user admin
$('#add_user_admin').on('submit',function(e){
     e.preventDefault();
     var url = $('this').attr('action');
     console.log(url);
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
   console.log(typeArticle($(this).attr('id')));
   $('#es_article_type').html(typeArticle($(this).attr('id')));
   $('.modal-footer').html(`<button id="submitformarticle" type="submit" class="btn btn-success"> Enregistrer</button>`);
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
       name: "Vetements_femme",
       value: ['Robe de ceremonie', 'Robe de mariée', 'Robe de fiançaille', 'Sweats-Shirts', 'Jeans', 'Cardigans', 'Chemise', 'Body', 'Blouse', 'T-shirts', 'Polos', 'Débardeur', 'Pulls', 'Gilets', 'Sweats-Shirts', 'Manteaux', 'Tailleurs', 'Vestes', 'Blousons', 'Jupes', 'Pantalons', 'Salopettes', 'Combinaisons', 'Combi-short', 'Chaussettes', 'Collants', 'Vêtements de Grossesse', 'Vetement de nuit', 'Vetement de Sports', 'Imperméable', 'Maillots de Bains', 'Costumes', 'Ensemble', 'Jogging']
     },
     {
       name: "Chaussures_femmme",
       value: ['Ballerines ', 'Baskets', 'Bottes', 'Boots', 'Chaussons', 'Chaussures Bateau', 'Chaussures de Securité', 'Chaussures de ville', 'Derbie', 'Designer', 'Escarpins', 'Espadrilles', ' Mary Janes', 'Mocassins', 'Mulle', 'Sabot', 'Sandales', 'Sport', 'Tongs']
     },
     {
       name: "Lingeries_femme",
       value: ['Slips', 'Dentelle côté', 'Tanga', 'Boxer', 'Accessoires', 'Bas', 'Jarretières', 'Bodys', 'Bustiers', 'corsets', 'Caracos', 'Combinaisons', 'Jupons', 'Culottes', 'Shorties', 'Strings', 'Ensembles de Lingeries', 'Lingeries Sculptantes', 'Nuisettes', 'Deshabillés', 'Vêtements Thérmiques', 'Soutiens Gorges']
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
     }];

   var type = types.find((items) => (items.name === name)).value;


   for (var i = 0; i < type.length; i++) {
     if (item != null) {
       if (item == type[i]) {
         classe = "class='default' selected";
       }
     }
     options = '<option value="' + type[i] + '" ' + classe + '>' + type[i] + '</option>' + options;

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