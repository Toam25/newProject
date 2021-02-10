$(function () {
  var numberTotal = recap();

  $(".FormulaireMembre").on("submit", function (e) {
    e.preventDefault();
    var isNumberTotal = $(".resultat").val();
    if (0 === 0) {
      $.ajax({
        type: "POST",
        url: "/inscription",
        data: new FormData(this),
        dataType: "html",
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
          $(".ajax").css("display", "inline-block");
        },
        success: function (data) {
          if (data == "Enregistrer") {
            $(".enregistrer")
              .fadeIn()
              .html("Le systeme vous dirigera vers Inother :)");
            $(".ajax").css("display", "none");
            alert(
              "Inscripation reussite, merci de vous connecter avec votre numéro téléphone et mots de passe"
            );
            document.location = "index.php";
          } else {
            $("#id_email").next(".error-message").fadeIn().html(data);
            $("#id_email").css("border-color", "red");
            $(".ajax").css("display", "none");
            numberTotal = recap();
            $(".resultat").val("");
          }
        },
      });
    } else {
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
});
