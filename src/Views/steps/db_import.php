<?php
/**
 * @var \Installer\Core\Installer $installer
 * @var array $alerts
 */
?>

<div class="card mx-auto mt-5" style="max-width: 600px;">
    <div class="card-header text-center">
        <h2 class="mb-0">Database Import</h2>
    </div>
    <div class="card-body">
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/alerts.php'); ?>
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/progressbar.php'); ?>

        <p class="text-center">Click the button below to import the database schema and initial data.</p>

        <form action="index.php?step=db_import" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \Installer\Core\Utils::getCsrfToken() ?>">
            <div class="d-flex justify-content-between">
                <a href="index.php?step=db_config" class="btn btn-secondary">Previous</a>
                <button type="submit" class="btn btn-primary">Import Database</button>
            </div>
        </form>
    </div>
</div>