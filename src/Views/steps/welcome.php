<?php
/**
 * @var \Installer\Core\Installer $installer
 * @var array $alerts
 */
?>

<div class="card mx-auto mt-5" style="max-width: 600px;">
    <div class="card-header text-center">
        <img src="<?= \Installer\Core\Utils::getBasePath('src/Assets/images/logo.png') ?>" alt="Logo" class="mb-3" style="max-height: 80px;">
        <h2 class="mb-0">Welcome to the Installer!</h2>
    </div>
    <div class="card-body">
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/alerts.php'); ?>
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/progressbar.php'); ?>

        <p class="card-text">This wizard will guide you through the installation process of your PHP application.</p>
        <p class="card-text">Please ensure you have the necessary database credentials and application settings ready.</p>

        <form action="index.php?step=welcome" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \Installer\Core\Utils::getCsrfToken() ?>">
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Start Installation</button>
            </div>
        </form>
    </div>
</div>