<?php
/**
 * @var \Installer\Core\Installer $installer
 * @var array $alerts
 * @var string $app_name
 * @var string $app_url
 */
?>

<div class="card mx-auto mt-5" style="max-width: 600px;">
    <div class="card-header text-center">
        <h2 class="mb-0">Application Configuration</h2>
    </div>
    <div class="card-body">
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/alerts.php'); ?>
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/progressbar.php'); ?>

        <form action="index.php?step=app_config" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \Installer\Core\Utils::getCsrfToken() ?>">
            <div class="mb-3">
                <label for="app_name" class="form-label">Application Name</label>
                <input type="text" class="form-control" id="app_name" name="app_name" value="<?= $app_name ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="app_url" class="form-label">Application URL</label>
                <input type="url" class="form-control" id="app_url" name="app_url" value="<?= $app_url ?? '' ?>" required>
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php?step=db_import" class="btn btn-secondary">Previous</a>
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </form>
    </div>
</div>