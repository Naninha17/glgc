
// Classe pour créer le système de galerie
class Gallery{

    // Constructeur, utilisé ici pour mettre en place les écouteurs "click" sur les galeries
    constructor(galeries){

        galeries.forEach( (galerie) => {

            galerie.addEventListener('click', () => {
                // Affichage de l'overlay + l'image dont le nom est dans le "data-open" de la galerie cliquée
                this.displayOverlayWithImage('images/galeries/' + galerie.dataset.open );
            })

        } );

    }

    // Méthode pour afficher l'overlay avec dedans une image passée en paramètre
    displayOverlayWithImage(imageToDisplay){

        // Création de l'overlay
        let overlay = document.createElement('div');
        overlay.classList.add('overlay');


        // Création de l'image
        let image = document.createElement('img');
        image.alt = '';
        image.src = imageToDisplay;

        // Ajout de l'image dans l'overlay
        overlay.append(image);

        // Ajout de l'overlay dans la page
        document.body.append(overlay);

        // Si l'overlay est cliqué, on appelle la méthode permettant de le supprimer (et l'image avec)
        overlay.addEventListener('click', () => {
            this.deleteOverlay();
        });

        // Stockage de l'overlay dans un attribut
        this.overlay = overlay;
    }

    // Méthode pour supprimer l'overlay (et donc l'image avec)
    deleteOverlay(){

        this.overlay.remove();
    }

} // accolade fermante de la class Gallery

// Sélection de toutes les galeries
let galeries = document.querySelectorAll('#galeries .galerie');

// Initialisation du système de galerie d'images avec la classe Gallery, en passant les *galeries* <-( *nom du dossier* ) en paramètre. (récupérées par le constructeur)
const gallery = new Gallery( galeries );



