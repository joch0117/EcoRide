//fichier traite les préférences utilisateur dans le dashboard.//
//préférences imposées//
const imposedMap = {
    'Fumeur': document.getElementById('pref-fumeur'),
    'Animal': document.getElementById('pref-animal')
};
//récupération des préférences
fetch('/api/preferences')
.then(response => response.json())
.then(data => {
data.forEach(pref => {
    //uniquement les préférense imposé par le site
    if (pref.label === 'Fumeur' || pref.label === 'Animal') {
        const select = imposedMap[pref.label];
        if (select) {
            // mise à jour 
            select.value = pref.value ? 'Accepté' : 'Refusé';
            select.disabled = false;
            //ajout d'un ecouteur
            select.addEventListener('change', () => {
            fetch(`/api/preferences/${pref.id}`, {
            method: 'PATCH',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                label: pref.label,
                value: select.value === 'Accepté'
                })
            });
        });
        }
    }
});
});
//ajout nouvelle préférence
const form = document.getElementById('add-pref-form');
const labelInput = document.getElementById('new-pref-label');
const valueSelect = document.getElementById('new-pref-value');
//préférence personnalisé en post
form.addEventListener('submit',(e)=>{
    e.preventDefault();

    const label = labelInput.value.trim();
    const value = valueSelect.value === 'true';
    //validation minimal
    if (label.length < 2)return;

    fetch('/api/preferences',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({label,value})
    
    }).then(()=>{
        //reset formulaire
        labelInput.value='';
        valueSelect.value='false';
        refreshPreferences();
    })
})


//affichage dynammique
function refreshPreferences() {
fetch('/api/preferences')
.then(response => response.json())
.then(data => {
    const list = document.getElementById('custom-pref-list');
    list.innerHTML = ''; 

    data.forEach(pref => {
        //on ignore ici les préférence déjà traité
    if (pref.label === 'Fumeur' || pref.label === 'Animal') return;

    const li = document.createElement('li');
    li.className = 'list-group-item d-flex justify-content-between align-items-center bg-eco-cream text-eco-green';

    const label = pref.label || 'Préférence';
    const isAccepted = pref.value === true;
    //Génération html de chaque ligne préférence
    li.innerHTML = `
        <div class="d-flex align-items-center gap-3">
        <span>${label}</span>
        <select class="form-select form-select-sm w-auto bg-eco-cream text-eco-green">
            <option value="true" ${isAccepted ? 'selected' : ''}>Accepté</option>
            <option value="false" ${!isAccepted ? 'selected' : ''}>Refusé</option>
        </select>
        </div>
        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
    `;
    //modification préférence existante
    const select = li.querySelector('select');
    select.addEventListener('change', () => {
        fetch(`/api/preferences/${pref.id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
                label: label,
                value: select.value === 'true'
            })
        });
    });

    //supression d'une préférence
    const delBtn = li.querySelector('button');
    delBtn.addEventListener('click', () => {
        fetch(`/api/preferences/${pref.id}`, {
            method: 'DELETE'
        }).then(refreshPreferences);
        
        li.remove();
    });

        list.appendChild(li);
    });
});
}

//chargement

refreshPreferences();