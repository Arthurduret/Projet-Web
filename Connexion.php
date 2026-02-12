<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" type="image/png" href="/images/Gemini_Generated_Image_fa3fcbfa3fcbfa3f-removebg-preview (1).png">
    <link rel="stylesheet" href="style_global.css">
    <link rel="stylesheet" href="/Style_connexion.CSS">
    <link rel="stylesheet" href="/header_footer.css">
</head>

<body>
    <?php include 'header.php'; ?> 

    <main>
        <div class="login-container">
            <h1>Connexion</h1>
            
            <form>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email">
                </div>
                
                <div class="input-group">
                    <label>Mot de passe</label>
                    <div class="password-wrapper" style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="monInputPassword" style="width: 100%;">
                        <span id="boutonOeil" style="position: absolute; right: 10px; cursor: pointer; user-select: none;">
                            👁️
                        </span>
                    </div>
                </div>
                
                <p>
                    <a href="/mot_de_passe_oublié.php" class="back-link">Mot de passe oublié ?</a>
                </p>

                <button type="submit">Se connecter</button>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?> 

    <script>
        const boutonOeil = document.querySelector('#boutonOeil');
        const inputPass = document.querySelector('#monInputPassword');

        boutonOeil.addEventListener('click', function() {
            if (inputPass.type === 'password') {
                inputPass.type = 'text';
                this.textContent = '🙈'; 
            } else {
                inputPass.type = 'password';
                this.textContent = '👁️';
            }
        });
    </script>
</body>
</html>