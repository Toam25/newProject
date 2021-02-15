$(function(){

    
    var ESS_ARTICLE = "";

  $('.modenav').on('click', function (e) {
    e.preventDefault();
    //$('.menu_index').hide();
    $('.listeMode').toggle();
    $('.liste_high_teck').hide();
    $('.maison').hide();
    ESS_ARTICLE = "MODE";
  
  })

  $('body').on('click', '.hightechnav',function (e) {
    e.preventDefault();
    //$('.menu_index').hide();
    $('.liste_high_teck').toggle();
    $('.listeMode').hide();
    $('.maison').hide();

    ESS_ARTICLE = "HIGH-TECH";
    
  });
  $('body').on('click', '.maisonnav',function (e) {
    e.preventDefault();
    //$('.menu_index').hide();
    $('.liste_high_teck').hide();
    $('.listeMode').hide();
    $('.maison').toggle();

    ESS_ARTICLE = "MAISON";
    
  });

  $('.hd').on('click', function (e) {
    e.preventDefault();
    var btn = $(this);
    var name = btn.attr('name');
    var value = btn.attr('value');

    if (name === 'haut') {
      btn.prop('disabled', true);
      btn.attr('disabled', 'disabled');
    }
    if (name === 'bas') {
      $('#hd' + value).prop('disabled', true);
      $('#hd' + value).attr('disabled', 'disabled');
    }


    $.ajax({
      url: "/",
      type: 'POST',
      data: { name: name, value: value },
      dataType: 'json',

      success: function (data) {
        var mydata = parseInt(data[1]);
        var suffix = "";
        if (mydata === 0) {
          $('#bas' + data[0]).prop('disabled', true);
        }
        else {
          $('#bas' + data[0]).prop('disabled', false);
        }

        if (mydata >= 1000) {
          if (mydata < 1000000) {
            mydata = mydata / 1000;
            suffix = "k";
          }
          else {
            if (mydata < 1000000000) {
              mydata = mydata / 1000000;
              suffix = "M";
            }
            else {
              mydata = mydata / 1000000000;
              suffix = "T";
            }
          }

          mydata = mydata.toFixed(0);
          $('#p' + data[0]).html(mydata + suffix).fadeIn("slow");
        }
        else {
          $('#p' + data[0]).html(mydata).fadeIn("slow");
        }

        $("#haut" + value).prop('disabled', true);
        $("#haut" + value).attr('disabled', 'disabled');

        $("#bas" + value).prop('disabled', true);
        $("#bas" + value).attr('disabled', 'disabled');

      }
    });
  })
  $('body').on('click', '.echantillon_image', function (e) {
    e.preventDefault();

    var habillement = ["Vetements_homme", "Chaussures_homme", "Lingeries_homme", "Vetements_femme", "Chaussures_femmme", "Lingeries_femme", "enfant_garcon", "enfant_fille", "Chaussures_enfant", "bebe_garcons", "bebe_chaussure", "bebe_filles"];
    var accessoires = ["acc_cheveux", "acc_bijoux_montre", "acc_sacs_maroquinerie", "acc_fashion_plus"];
    var beaute_bien = ["acc_soins_cheveux", "acc_soins_ongle", "acc_soins_corps_visage", "acc_beaute_bio", "acc_parfums"];
    var art_malagasy = ["sisal", "produit_en_soie", "broderie", "raphia", "bijoux", "artistique", "travail_du_bois", "decoration_interieure", "travail_du_fer"]
    var menu_v = $(this).attr('name');
    $('#menu_detail').children('#' + menu_v).remove();
    $('.menu_index').hide();

    $('.sous_menu').hide();
    var categorie = $(this).attr('id');
    $('#container_echatillon').show();

    if (ESS_ARTICLE == "MODE") {
      $('#menu_detail').html(mode());

      if ($.inArray(categorie, habillement) != -1) {
        $('#sous_menu_habillement').show();

      }
      if ($.inArray(categorie, accessoires) != -1) {
        $('#sous_menu_habillement').show();

      }
      if ($.inArray(categorie, beaute_bien) != -1) {
        $('#sous_menu_bien_etre').show();

      }
      if ($.inArray(categorie, art_malagasy) != -1) {
        $('#sous_menu_art').show();

      }

    }
    else {
      if (ESS_ARTICLE == "HIGH-TECH") {
        $('#menu_detail').html(high_tech());
      }
      if (ESS_ARTICLE == "MAISON") {
        $('#menu_detail').html(maison());
      }
    }
    $('.listeMode').hide();
    $('._boutiqueContaintEchantillon').html("");
    $.ajax({
      url: '/api/es_article/get/' + categorie,
      type: 'POST',
      dataType: 'json',
      beforeSend: function () {
        $('.div-inother').css('display', 'block');
      },
      success: function (data) {
        let message = "";
        let i;

        $('.div-inother').css('display', 'none');
        if (data.length == 0) {
          message = "<h4 align='center'> Pas d'échantillon </h4>";
        }
        else {
          for (i = 0; i < data.length; i++) {
            message += `<div class='liste_categorie_vetement'>
                  <p class='name'><span class=''>`+ data[i]['type'] + `</span></p>
                  <input type='hidden' class='categorie' value=`+ data[i]['category'] + `>
                  <img src='/images/`+ data[i]['image'] + `' class='echantillon'/>
                  </div>`

          }
        }
        $('._container_echatillon').html(message);

        //$('.header').prop('id','echantillon_image');
      },
      complete: function () {
      }
    });
  });
  $('body').on('click', '.liste_categorie_vetement', function () {
    var vetement_selectionner = $(this);

    $('._container_echatillon').children('.liste_categorie_vetement').removeClass('border_click');
    vetement_selectionner.addClass('border_click');
    var categorie = $('.categorie').val();
    var nom_vetement_selectionner = vetement_selectionner.children('.name').text();
    $.ajax({
      url: '/api/get/listArticlePerBoutique/' + categorie + '/' + nom_vetement_selectionner,
      type: 'GET',
      dataType: 'json',
      beforeSend: function () {
        $('.div-inother').css('display', 'block');
      },
      success: function (data) {
       
        if (data.length > 0) {
          var message = " ";
          for (var i = 0; i < data.length; i++) {
            message += `
              <div class="container_boutique">
                  <a href="/shop/`+ data[i].type + `/` + data[i].id + `" target="blank">
                     <p class="nom_boutique_vetement">` + data[i].name+ `</p>
                  </a>
                  <div class="boutique">`

            for (let j = 0; j < data[i].article.length; j++) {
              $prix = (data[i].article[j].price == 0) ? "<span class='prix_ala'>Prix : A la demande</span>" : 'Prix : ' + data[i].article[j].price;
              message += ` <div class="_my_aricle_in">
                                 <input type="hidden" value='.$resultat['id'].'>
                                 <img class="image_vetement_boutique" id="`+ data[i].article[j].images + `" src="/images/`+ data[i].article[j].images + `" value="'.$resultat['name'].'">
                                 <span class="separe"></span>
                                 <p class="prix"> `+ $prix + `</p>
                                 <p>
                                 <span class="ref">Ref : `+ data[i].article[j].referency+ `</span>
                                 </p>
                          </div>`;
            }




            message += `</div>
               </div>`

          }

          $('._boutiqueContaintEchantillon').html(message);
        }
        else {
          $('._boutiqueContaintEchantillon').html("<h4 align='center'>Pas de resultat</h4'>");
        }

        $('.div-inother').css('display', 'none');
      },
      complete: function () {

      }
    });


  });
  $('#container_echatillon').on('click', '.remove', function (e) {
    e.preventDefault();
    $('#container_echatillon').hide();
  });
  $('body').on('click', '.titre_menu_detail', function () {
    $('.sous_menu').hide();
    $(this).next('div').show();
  });
  function mode() {
    return `
      <h3 class="titre_menu_detail" id="habillement">Habillement</h3>
       <div class="sous_menu" id="sous_menu_habillement" style="display: none ;" >
         <h4 class="h4_menu1">  > Homme </h4>
            <p class="font_size_p">  <span id="Vetements_homme" class="echantillon_image">Vêtements </span></p>
            <p class="font_size_p"> <span id="Chaussures_homme" class="echantillon_image">Chaussures </span></p>
            <p class="font_size_p">  <span id="Lingeries_homme" class="echantillon_image">Lingeries </span> </p>
           <h4 class="h4_menu1">   > Femme  </h4>
            <p class="font_size_p"> <span id="Vetements_femme" class="echantillon_image">Vêtements </span></p>
            <p class="font_size_p"><span id="Chaussures_femmme" class="echantillon_image">Chaussures </span></p>
            <p class="font_size_p"> <span id="Lingeries_femme" class="echantillon_image">Lingeries </span></p>
          <h4 class="h4_menu1">  > Enfant  </h4>
            <p class="font_size_p"> <span id="Vetements_enfant" class="echantillon_image">Garçon </span> </p>
            <p class="font_size_p"> <span id="Lingeries_enfant" class="echantillon_image">Fille </span> </p>
            <p class="font_size_p"> <span id="Chaussures_enfant" class="echantillon_image">Chaussures </span> </p>
             <h4 class="h4_menu1">  > Bébé </h4>
            <p class="font_size_p"> <span id="bebe_garcons" class="echantillon_image">Garçon moins de 24 mois </span> </p>
            <p class="font_size_p" > <span id="bebe_filles" class="echantillon_image">Fille moins de 24 mois </span> </p>
            <p class="font_size_p" > <span id="bebe_chaussure" class="echantillon_image"> Chaussures </span> </p>
       </div>
   
       <h3 class="titre_menu_detail" id="beaute_bien_etre">Beauté et bien-être</h3>
       <div class="sous_menu" id="sous_menu_bien_etre" style="display: none ;" >
           <h4 id="acc_parfums" class="echantillon_image  menu1 h4_menu1" >  Parfumeries </h4>
           <h4 id="acc_beaute_bio" class="echantillon_image  menu1 h4_menu1">   Beauté bio</h4>
           <h4 class="h4_menu1"> Cosmétiques </h4>
           <p class="font_size_p menu1"> <span id="acc_soins_corps_visage"class="echantillon_image"> Soins de corps et visage</span> </p>
           <p class="font_size_p menu1"> <span id="acc_soins_ongle"class="echantillon_image"> Soins des ongles</span> </p>
           <p class="font_size_p menu1"> <span id="acc_soins_cheveux"class="echantillon_image"> Soins des cheveux</span> </p>
       </div>
   
       <h3 class="titre_menu_detail" id="accessoire">Accessoires</h3>
       <div class="sous_menu" id="sous_menu_accessoire" style="display: none ;">
         <h4 id="acc_cheveux" class="echantillon_image font_size_p menu1">  Accessoires de cheveux </h4> 
         <h4 id="acc_bijoux_montre" class="echantillon_image font_size_p menu1">  Bijoux et montres </h4>
         <h4 id="acc_sacs_maroquinerie" class="echantillon_image font_size_p menu1">  Sacs et maroquineries </h4>
       </div>
   
   
       <!--h3 class="titre_menu_detail" id="art_malagasy">Art Malagasy </h3>
       <div class="sous_menu" id="sous_menu_art" style="display: none ;" >
          <h4 id="bijoux_pp" class="h4_menu1">   Bijoux et pierre précieuse </h4>
               <p class="font_size_p menu1"> <span id="bijoux" class="echantillon_image"> Bijoux</span> </p>
         <p class="font_size_p menu1"> <span id="pierre_precieuse" class="echantillon_image"> Pierre précieuse</span> </p>
       <h4  class="art_1  h4_menu1">  Accessoires décoratifs </h4>
           <p class="font_size_p menu1"> <span id="artistique" class="echantillon_image"> Artistiques</span> </p>
         <p class="font_size_p menu1"> <span id="travail_du_bois" class="echantillon_image"> Sculpture sur bois</span> </p>
         <p class="font_size_p menu1"> <span id="decoration_interieure" class="echantillon_image">  Décoration interieure</span> </p>
         <p class="font_size_p menu1"> <span id="travail_du_fer" class="echantillon_image">  Meuble interieure</span> </p>
   
       <h4 id="chapeaux_art" class="art_1 h4_menu1"> Soie, raphia et broderies </h4>
           <p class="font_size_p menu1"> <span id="raphia" class="echantillon_image"> Raphia</span> </p>
         <p class="font_size_p menu1"> <span id="broderie" class="echantillon_image"> Broderie</span> </p>
         <p class="font_size_p menu1"> <span id="produit_en_soie" class="echantillon_image"> soie</span> </p>
         <p class="font_size_p menu1"> <span id="sisal" span class="echantillon_image"> Sisal</span> </p-->
       </div>`;
  }

  function high_tech() {
    return `<h3 class="souligne">   Téléphonie </h3>
                 <h4 class="h4_menu">   <span id="Telephone" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Téléphone </span>  </h4>
                 <h4 class="h4_menu">   <span id="Accessoires" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Accessoires </span>  </h4>
             <h3 class="souligne">   Informatique </h3>
                 <h4 class="h4_menu font_size_p menu1">   <span id="Matériels informatiques" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Matériels informatiques </span>  </h4>
                 <h4 class="h4_menu font_size_p menu1">   <span id="Diagnostiques" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Diagnostiques </span>  </h4>
             <h3 class="souligne">   Image et son </h3>
                 <h4 class="h4_menu">   <span id="TV" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;  TV </span>  </h4>
                 <h4 class="h4_menu">   <span id="Videoprojecteur" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Videoprojecteur </span>  </h4>
                 <h4 class="h4_menu">   <span id="Photos et caméra" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Photos et caméra </span>  </h4>
                 <h4 class="h4_menu">   <span id="Tous accessoires" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Tous accessoires </span>  </h4>
             <h3 class="souligne">   <h4 class="h4_menu">   <span id="Impression" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Impression </span>  </h4> </h3>
             <h3 class="souligne">   <h4 class="h4_menu">   <span id="Système domotique" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Système domotique </span>  </h4> </h3>
              `;
  }
  function maison() {
    return `<h3 class="souligne">Professonnel</h3>
                 <h4 class="h4_menu">   <span id="Outillages Pro" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Outillages Pro </span>  </h4>
            <h3 class="souligne">Jardinage</h3>
                 <h4 class="h4_menu font_size_p menu1">   <span id="Outils de jardin" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;Outils de jardin </span>  </h4>
            <h3 class="souligne">   Bricolage</h3>
              <h4 class="h4_menu"><span id="outillages" class="echantillon_image font_size_p menu1"> &nbsp;&nbsp;&nbsp;outillages </span>  </h4>
            `;
  }
});