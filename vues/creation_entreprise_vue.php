<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Création d'une entreprise</title>

    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/form.css">

</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
      <div class="login-container">

            <a href="#" class="back-link">← Retour</a>
            <h1>Creation d'une entreprise</h1>

            <form class="form-entreprise" action="#" method="POST" enctype="multipart/form-data">
                
                <div class="input-group">
                    <label>Nom</label>
                    <input id="nom" type="text" name="nom" maxlength="50" placeholder="Nom de l'entreprise" required>
                </div>

                <div class="input-group input-group--commentaire">
                    <label for="commentaire">Description de l'entreprise</label>
                    <textarea
                        id="commentaire"
                        name="description"
                        placeholder="Faîtes une description de votre entreprise (Taille de l'entreprise, domaine d'expertise...)"
                        rows="5"
                        maxlength="500"
                        required
                    ></textarea>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input id="email" type="email" name="email" placeholder="contact@exemple.com" required>
                </div>

                <div class="input-group">
                    <label>Numéro de téléphone</label>
                    <input id="tel" type="tel" name="tel" placeholder="06 12 34 56 78" required>
                </div>
                
                <div class="input-group">
                    <label>Image de fond</label>
                    <input 
                    id="image_fond" 
                    type="file" 
                    name="image_fond" 
                    accept="image/*" >
                </div>
                
                <div class="input-group">
                    <label>Logo entreprise</label>
                    <input id="image_logo" type="file" name="image_logo" accept="image/*">
                </div>

                <button type="submit">Inscrire l’entreprise</button>

            </form>
        </div>
    </main>


    <?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>