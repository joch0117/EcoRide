

function filterSearch(){
    document.getElementById('filters-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Empêche le rechargement

        // Récupération des filtres
        const prixMax = parseFloat(document.getElementById('maxPrice')?.value) || Infinity;
        const dureeMax = parseInt(document.getElementById('maxDuration')?.value) || Infinity;
        const noteMin = parseFloat(document.getElementById('minRating')?.value) || 0;
        const isEco = document.getElementById('isEcological')?.checked;

        // Parcourt toutes les cartes de trajet
        document.querySelectorAll('.card.card-eco').forEach(card => {
            const prix = parseFloat(card.dataset.price);
            const duree = parseInt(card.dataset.duration);
            const note = parseFloat(card.dataset.rating);
            const eco = card.dataset.eco === 'oui';

            const show =
                (prix <= prixMax) &&
                (duree <= dureeMax) &&
                (note >= noteMin) &&
                (!isEco || eco);

        // Affiche ou masque la carte
            card.closest('.col-12').style.display = show ? 'block' : 'none';
        });
    });
}

filterSearch();