let antwoorden = { vibe: '', budget: '', geur: '' };

function nextStep(step, val) {
    if(step === 1) antwoorden.vibe = val;
    if(step === 2) antwoorden.budget = val;
    if(step === 3) antwoorden.geur = val;

    document.getElementById('step' + step).style.display = 'none';
    if(step < 3) {
        document.getElementById('step' + (step + 1)).style.display = 'block';
    } else {
        showResult();
    }
}

function showResult() {
    document.getElementById('result').style.display = 'block';
    
    // Logica (IDs uit products.json)
    let id = 1; // Default Sauvage
    
    if(antwoorden.vibe === 'niche') {
        id = 6; // Aventus
        if(antwoorden.geur === 'zoet') id = 3; // Oud Wood
    }
    else if(antwoorden.vibe === 'school') {
        if(antwoorden.budget === 'low') id = 9; // Explorer
        else id = 7; // Prada L'Homme
    }
    else if(antwoorden.vibe === 'uitgaan') {
        if(antwoorden.budget === 'low') id = 15; // Joop Homme
        else id = 4; // Le Male Elixir
    }
    else if(antwoorden.vibe === 'date') {
        if(antwoorden.geur === 'fris') id = 10; // La Nuit
        else id = 5; // The One
    }

    let html = `
        <div style="border: 1px solid #D4AF37; padding: 40px; background: #0f0f0f;">
            <p style="color:white; font-size: 1.2rem;">Wij hebben de perfecte match gevonden.</p>
            <br>
            <a href="detail.php?id=${id}" class="btn-gold">BEKIJK RESULTAAT</a>
        </div>
    `;
    document.getElementById('result-content').innerHTML = html;
}