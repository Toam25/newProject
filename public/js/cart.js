$(function () {

  $('.validat_command').on('click', function (e) {
    e.preventDefault();
    $.ajax({
      url: "/cart/validate",
      type: 'POST',
      beforeSend: () => {
        $(this).text('En cours ...');
        $(this).prop('disabled', true)
      },
      error: () => {
        $(this).text('Valider les commandes');
        $(this).prop('disabled', false);
        toastr.error("Erreur d'enregistrement");
      },
      success: (data) => {

        $(this).prop('disabled', false);
        $(this).text('Valider les commandes');
        // $(this).remove();
        toastr.success('Commande validé avec success')

      },
      complete: () => {

      }
    });
  });
  $('body').on('click', '.add-to-wishlist', function (e) {
    e.preventDefault();
    // addWishis($(this));
  });
  // update wish to panier
  $('body').on('click', '.update_cart', function (e) {
    e.preventDefault();
    $(this).parents('.cart_product').remove();

    if ($('.container_cart').html() === " ") {
      $('.container_cart').html(` <h4 align="center"> Pas encore de produit </h4>`);
    }
    incrimentCart();
    decrimentWish();
    deCrimentQuantityWish();

    deCrimentTotalCart(parseInt($(this).parent('div').children('.price').val()));
    update_cart($(this), parseInt($(this).attr('name')), 'panier');
  });

  //delete cart 

  $('.delete_cart').on('click', function (e) {
    e.preventDefault();
    removeCart($(this), parseInt($(this).attr('data-value')));
    $(this).parents('.cart_product').remove();

  });

  $('body').on('click', '.add_cart', function (e) {
    e.preventDefault();
    let id = $(this).attr('data-id-article');
    let type = "cart";
    let status = "panier";
    let cart_on_qty = $(this).parents('div').children('.cart-one-qty');
    let price = parseInt($(this).attr('data-price'));
    let that = $(this);

    that.children('i').toggleClass('fa-shopping-cart');
    that.children('i').toggleClass('fa-check');

    that.prop('disabled', true);

    $('.cart-qty').html(parseInt($('.cart-qty').html()) + 1);
    cart_on_qty.html(parseInt(cart_on_qty.html()) + 1);
    let total = that.parent('div').parent('span').parent('div').parent('.total_quatity').children('.total').children('span');
    total.children('.total_on_article').html(number_format(parseInt(cart_on_qty.html()) * price) + '&nbsp' + 'Ar');

    $('.cart_total').children('#total_all_cart').html(number_format(parseInt($('.total_all_cart').attr('value')) + price) + '&nbsp' + 'Ar')

    addCart(id, that, type, status);

  });

  $('body').on('click', '.add_wish', function (e) {
    e.preventDefault();
    let id = $(this).attr('data-id-article');
    let type = "wish";
    let status = "wish";
    let that = $(this);
    that.prop('disabled', true);
    $('.qtywish').text(parseInt($('.qtywish').text()) + 1);
    addCart(id, that, type, status)
  });

  $('body').on('click', '.delete_wish', function (e) {
    e.preventDefault();
    let id = parseInt($(this).attr('data-id-cart'));
    $('.qtywish').text(parseInt($('.qtywish').text()) - 1);
    let that = $(this);
    that.prop('disabled', true);
    removeWishis(id, that);
  });
  $('body').on('click', '._wish-btn', function (e) {
    var i = $(this).children('i');
    var that = $(this);

    if (i.hasClass('fa-heart')) {
      i.animate({
        opacity: 0.5
      }, 100, 'linear', function () {
        i.toggleClass('fa-heart');
        i.animate({
          opacity: 1
        }, 100, 'linear', function () {
          i.toggleClass('fa-heart-o');
          that.toggleClass('add_wish');
          that.toggleClass('delete_wish');
        })
      });
    }
    else {
      i.animate({
        opacity: 0.5
      }, 100, 'linear', function () {
        i.toggleClass('fa-heart-o');

        i.animate({
          opacity: 1
        }, 100, 'linear', function () {
          i.toggleClass('fa-heart');
          that.toggleClass('add_wish');
          that.toggleClass('delete_wish');


        })
      });
    }


  });

  $('body').on('click', '.delete_one_cart', function (e) {
    e.preventDefault();
    let id = $(this).attr('data-idcart');
    let type = "panier";
    let quatity = $(this).parent('div').children('.cart-one-qty');
    if (parseInt($('.cart-qty').html()) > 1) {
      quatity.html(parseInt(quatity.html()) - 1);
      $('.cart-qty').html(parseInt($('.cart-qty').html()) - 1);
    }

    removeOneItems($(this), id);

  });

  function addCart(id, that, type, status) {
    $.ajax({
      url: "/cart/add/" + id,
      type: 'POST',
      data: {
        type,
        status
      },
      beforeSend: () => {

      },
      error: () => {

      },
      success: (data) => {
        if (type == 'wish') {
          that.attr('data-id-cart', data.idCart);
          that.prop('disabled', false);
        }
        else {
          that.children('i').toggleClass('fa-shopping-cart');
          that.children('i').toggleClass('fa-check');
          that.prop('disabled', false);


        }


      },
      complete: () => {

      }
    });

  }

  function removeCart(that, idCart) {
    $.ajax({
      url: "/cart/remove/" + idCart,
      type: 'POST',
      beforeSend: () => {

      },
      error: () => {

      },
      success: (data) => {

      },
      complete: () => {

      }
    });
  }
  function removeOneItems(that, id) {
    $.ajax({
      url: "/cart/removeOneItems/" + id,
      type: 'POST',
      beforeSend: () => {

      },
      error: () => {

      },
      success: (data) => {

      },
      complete: () => {

      }
    });
  }

  function incrimentCart() {

    $('.cart-qty').html(parseInt($('.cart-qty').html()) + 1);
  }
  function incrimentWish() {

    $('.qtywish').html(parseInt($('.qtywish').html()) + 1);
  }

  function decrimentCart() {
    if (parseInt($('.cart-qty').html()) > 0) {
      $('.cart-qty').html(parseInt($('.cart-qty').html()) - 1);
    }

  }
  function decrimentWish() {
    if (parseInt($('.qtywish').html()) > 0) {
      $('.qtywish').html(parseInt($('.qtywish').html()) - 1);
    }

  }

  function incrimentQuantityCart() {

  }
  function incrimentQuantityCart() {

  }
  function deCrimentQuantityCart() {

  }

  function incrimentTotalCart(nombre) {
    $('.total_all_cart').val(parseInt($('.total_all_cart').val()) + nombre);
    let newTotal = parseInt($('.total_all_cart').val());
    $('#total_all_cart').html(number_format(newTotal + '&nbsp' + 'Ar'));
  }
  function deCrimentTotalCart(nombre) {
    $('.total_all_cart').val(parseInt($('.total_all_cart').val()) - nombre);
    let newTotal = parseInt($('.total_all_cart').val());
    if (newTotal > 0) {
      $('#total_all_cart').html(number_format(newTotal) + '&nbsp' + 'Ar');
    }
  }
  function deCrimentQuantityWish() {
    if (parseInt($('.wish-qty').html()) > 0) {
      $('.wish-qty').html(parseInt($('.wish-qty').html()) - 1);
    }
  }
  function update_cart(that, id, panier) {
    $.ajax({
      url: "/cart/update/" + id + "-" + panier,
      type: 'POST',
      beforeSend: () => {

      },
      error: () => {

      },
      success: (data) => {

      },
      complete: () => {

      }
    });
  }

  function addWishis(that) {
    $('#whishlist').children('a').children('div').text(parseInt($('#whishlist').children('a').children('div').text()) + 1);
    let idarticle = that.parents('.product-btns').attr('data-idarticle');
    let idboutique = that.parents('.product-btns').attr('data-idboutique');

    console.log(idarticle, idboutique);
  }

  function removeWishis(id, that) {
    $.ajax({
      url: "/cart/remove/" + id,
      type: 'POST',
      data: {

      },
      beforeSend: () => {

      },
      error: () => {
        toastr.error('Erreur inconue');
      },
      success: (data) => {
        that.prop('disabled', false);
      },
      complete: () => {

      }
    });
  }

  function number_format(a, b) {
    a = '' + a;
    b = b || ' ';
    var c = '', d = 0;

    while (a.match(/^0[0-9]/)) {
      a = a.substr(1);
    }
    for (var i = a.length - 1; i >= 0; i--) {
      c = (d != 0 && d % 3 == 0) ? a[i] + b + c : a[i] + c;
      d++
    }
    return c;
  }
});