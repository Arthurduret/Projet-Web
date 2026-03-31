<?php 
// 1. On inclut le header (qui contient tes balises <head> et tes menus)
include __DIR__ . '/partials/header.php'; 
?>

<link rel="stylesheet" href="public/css/offres_emplois.css">

<div class="container mt-4">
    <h1 class="mb-4">Mes candidatures envoyées</h1>

    <?php if (empty($candidatures)): ?>
        <div class="alert alert-info">
            Vous n'avez pas encore postulé à des offres. 
            <a href="index.php?page=offres_emplois">Voir les offres disponibles.</a>
        </div>
    <?php else: ?>
        
        <div class="row">
            <?php foreach ($candidatures as $c): ?>
                <div class="col-md-6 mb-3">
                    <div class="card-offre shadow-sm p-3"> 
                        <div class="card-body">
                            <h3 class="h5 text-primary"><?= htmlspecialchars($c['titre']) ?></h3>
                            <p class="text-muted small">Postulé avec le CV : <strong><?= htmlspecialchars($c['cv']) ?></strong></p>
                            <hr>
                            <p class="card-text">
                                <strong>Ma motivation :</strong><br>
                                <?= nl2br(htmlspecialchars($c['lettre_motivation'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<?php 
// 3. On inclut le footer pour fermer la page proprement
include __DIR__ . '/partials/footer.php'; 
?>