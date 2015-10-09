// Retire le placeholder lorsque l'input est selectionné

//Récuperation des input concerné
var inputs = document.querySelectorAll('.inputText');



for (var i = 0; i < inputs.length; i++) {

  // sauvegarde du contenu des placeholder pour pouvoir les réinjecter à la deselection de l'input
  inputs[i].save = inputs[i].placeholder;

  inputs[i].addEventListener("focus",function () {
    this.placeholder = "";
  })
  inputs[i].addEventListener("blur",function () {
    this.placeholder = this.save;
  })
}

// Gestion de l'apparition/disparition du menu au click sur le bouton menu
//
// // Récupération des éléments du menu
// var menuButton = document.getElementById('menuButton');
// var menu = document.getElementById('menu');
// var lienMenu = document.querySelectorAll('.lienMenu');

// menuButton.addEventListener('click', function(e) {

  // Récuperation de la taille du navigateur
  // var tailleNav = document.body.clientWidth;
  //
  // if (menu.style.display == "inline-block") {
  //   menuButton.textContent = "Menu";
  //   menu.style.display = "none";
  // } else {
  //   menu.style.display = "inline-block";
  //   menuButton.textContent = "Fermer";
  // }
  //
  // if (tailleNav <= 600) {
  //     if (menu.style.display == "inline-block") {
  //         menuButton.classList.add("selectedButton");
  //     }
  //     else {
  //         menuButton.classList.remove("selectedButton");
  //
  //     }
  // }
  //
  //
  // });
