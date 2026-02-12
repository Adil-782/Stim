<?php
// Challenge: Community Takeover (Stored XSS + Cookie Theft)
// Créateur: Titouan
// Difficulté: ★★★★★

include '../includes/db_connect.php';
include '../includes/header.php';

// header.php links are like "store.php". If included from "challenges/", checks are needed.
// actually, verify header.php links.
?>

<!-- Fix header links for subdirectory -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.nav-links a, .logo a, .user-panel a');
        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href && !href.startsWith('http') && !href.startsWith('../')) {
                link.setAttribute('href', '../' + href);
            }
        });
        // Fix CSS
        const cssLink = document.querySelector('link[href^="css/"]');
        if(cssLink) cssLink.href = '../' + cssLink.getAttribute('href');
    });
</script>

<style>
    /* Copied from reset-password.php */
    .reset-container {
        max-width: 800px;
        margin: 60px auto;
        background: #1b2838; /* Valve dark blue */
        border-radius: 4px;
        padding: 40px;
        border: 1px solid rgba(102, 192, 244, 0.2);
        color: #c7d5e0;
        font-family: "Motiva Sans", Sans-serif;
    }

    .reset-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .reset-header h1 {
        font-size: 32px;
        color: #fff;
        text-transform: uppercase;
        font-weight: 300;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }

    .challenge-info {
        background: rgba(255, 107, 107, 0.1);
        border-left: 3px solid #ff6b6b;
        padding: 15px;
        border-radius: 0 4px 4px 0;
        margin-bottom: 25px;
        font-size: 13px;
        color: #8f98a0;
    }

    .challenge-info strong {
        color: #ff6b6b;
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .scenario-box {
        background: rgba(0, 0, 0, 0.2);
        padding: 20px;
        margin-bottom: 30px;
        border-radius: 4px;
        border: 1px solid #2a475e;
    }

    .scenario-box h3 {
        color: #66c0f4;
        margin-top: 0;
        font-weight: normal;
    }

    .scenario-box p {
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .action-btn {
        display: inline-block;
        padding: 15px 30px;
        background: linear-gradient(to right, #47bfff 0%, #1a9fff 100%);
        color: white;
        text-decoration: none;
        border-radius: 2px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .action-btn:hover {
        background: linear-gradient(to right, #1a9fff 0%, #47bfff 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 159, 255, 0.4);
    }
    
    .admin-bot-status {
        margin-top: 20px;
        padding: 10px;
        background: #000;
        color: #00ff00;
        font-family: monospace;
        display: none;
    }

    .back-link {
        display: inline-block;
        margin-top: 20px;
        color: #66c0f4;
        text-decoration: none;
        font-size: 14px;
    }

    .back-link:hover {
        color: #fff;
    }
</style>

<div class="reset-container">
    <div class="reset-header">
        <h1>🍪 Community Takeover</h1>
        <p style="color: #66c0f4; font-size: 14px;">Challenge créé par Titouan</p>
    </div>

    <div class="challenge-info">
        <strong>🎯 CHALLENGE CTF</strong>
        <div>CWE-79: Stored Cross-Site Scripting (XSS) + CWE-20: Missing HttpOnly Flag</div>
        <div>Difficulté: ★★★★★ | Points: 500</div>
    </div>

    <div class="scenario-box">
        <h3>📝 Scénario</h3>
        <p>
            L'administrateur de la communauté, <strong>admin_community</strong>, consulte régulièrement les avis postés sur les jeux, en particulier sur le jeu le plus populaire : <strong>"Half-Life 3"</strong>.
        </p>
        <p>
            Une faille de sécurité a été identifiée : les commentaires ne filtrent pas correctement le code JavaScript, et les cookies de session ne sont pas protégés par le drapeau <code>HttpOnly</code>.
        </p>
        <p>
            <strong>Votre mission :</strong> Injectez un payload XSS dans les avis pour voler le cookie de session de l'administrateur lorsqu'il visitera la page.
        </p>
        <p>
            <em>Une fois le cookie volé, utilisez-le pour usurper son identité. Le flag s'affichera sur votre écran d'accueil (Header) une fois connecté en tant qu'admin.</em>
        </p>
    </div>

    <div style="text-align: center;">
        <a href="../game.php?id=1" class="action-btn">Accéder à la page cible (Game #1)</a>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button onclick="simulateAdmin()" style="background: transparent; border: 1px solid #417a9b; color: #417a9b; padding: 8px 16px; cursor: pointer; border-radius: 4px;">🕵️ Simuler le passage de l'admin</button>
    </div>
    
    <div id="botStatus" class="admin-bot-status"></div>

    <a href="../challenges.php" class="back-link">← Retour aux challenges</a>
</div>

<script>
function simulateAdmin() {
    const status = document.getElementById('botStatus');
    status.style.display = 'block';
    status.innerHTML = '> Initializing Admin Bot...<br>';
    
    setTimeout(() => {
        status.innerHTML += '> Admin logging in... [SUCCESS]<br>';
    }, 1000);
    
    setTimeout(() => {
        status.innerHTML += '> Admin navigating to game.php?id=1...<br>';
    }, 2000);
    
    setTimeout(() => {
        status.innerHTML += '> Admin reading reviews...<br>';
    }, 3000);

    setTimeout(() => {
        status.innerHTML += '> ⚠️ XSS Payload Triggered! (If present)<br>';
        status.innerHTML += '> Cookie sent to attacker...<br>';
        status.innerHTML += '> Done.<br>';
    }, 4500);
}
</script>

<?php include '../includes/footer.php'; ?>
