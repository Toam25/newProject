$(function () {
  // var numberTotal = recap();

  $(".FormulaireMembre").on("submit", function (e) {
    e.preventDefault();
    var isNumberTotal = $(".resultat").val();
    /*if (0 === 0) {*/
    $.ajax({
      type: "POST",
      url: "/inscription",
      data: new FormData(this),
      dataType: "html",
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $("#bouton_inscr").text("....");
        $("#bouton_inscr").prop('disabled', true);
        toastr.info('Inscription en cours ...');
      },
      error: (response, error) => {
        $("#bouton_inscr").text("Enregistrer");
        $("#bouton_inscr").prop('disabled', false);
        toastr.error(eval(response.responseText));
        $("#id_email").next(".error-message").fadeIn().html('Adresse mail existe déjà');
        $("#id_email").css("border-color", "red");
        $(".ajax").css("display", "none");
        // numberTotal = recap();
        $(".resultat").val("");
      },
      success: function (data) {

        $(".ajax").css("display", "none");
        toastr.success(' Inscription reussite...');
        $('.message').text('Inscription reussie, veillez confirmez votre address e-mail');
        $('#container_message').css({ 'transform': 'translate(0)', 'z-index': 1, 'border-radius': '0%', 'height': '100%', 'width': '100%' })
        $('.container_btn').css('transform', 'scale(1)')

      }
    });
  });
});
    /*
} else {

toastr.error(data.msg);
numberTotal = recap();
console.log(numberTotal);
$(".resultat").addClass("hvr-buzz-out");
$(".resultat").addClass("border_red");
setTimeout(function () {
$(".resultat").removeClass("hvr-buzz-out");
$(".resultat").removeClass("border_red");
}, 1000);
$(".resultat").val("");
}
});
function recap() {
var alphabet = [
"A",
"B",
"C",
"D",
"E",
"F",
"G",
"H",
"I",
"J",
"K",
"L",
"M",
"N",
"O",
"P",
"Q",
"R",
"S",
"T",
"U",
"V",
"W",
"X",
"Y",
"Z",
];
var number1 = parseInt(Math.random() * 10);
var number2 = parseInt(Math.random() * 25);
var number3 = parseInt(Math.random() * 10);
var number4 = parseInt(Math.random() * 10);
for (var i = 0; i < 1; ) {
if (number1 == number2) {
number2 = parseInt(Math.random() * 10);
} else {
i = 1;
}
}
$(".number1").text(number1);
$(".number2").text(alphabet[number2]);
$(".number4").text(alphabet[number4].toLowerCase());
$(".number3").text(number3);
return (
number1.toString() +
alphabet[number2] +
alphabet[number4].toLowerCase() +
number3.toString()
);
}
});*/
