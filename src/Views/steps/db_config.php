<?php
/**
 * @var \Installer\Core\Installer $installer
 * @var array $alerts
 * @var string $db_host
 * @var string $db_port
 * @var string $db_name
 * @var string $db_username
 * @var string $db_password
 */
?>

<div class="card mx-auto mt-5" style="max-width: 600px;">
    <div class="card-header text-center">
        <h2 class="mb-0">Database Configuration</h2>
    </div>
    <div class="card-body">
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/alerts.php'); ?>
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/progressbar.php'); ?>

        <form action="index.php?step=db_config" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \Installer\Core\Utils::getCsrfToken() ?>">
            <div class="mb-3">
                <label for="db_host" class="form-label">Database Host</label>
                <input type="text" class="form-control" id="db_host" name="db_host" value="<?= $db_host ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="db_port" class="form-label">Database Port</label>
                <input type="number" class="form-control" id="db_port" name="db_port" value="<?= $db_port ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="db_name" class="form-label">Database Name</label>
                <input type="text" class="form-control" id="db_name" name="db_name" value="<?= $db_name ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="db_username" class="form-label">Database Username</label>
                <input type="text" class="form-control" id="db_username" name="db_username" value="<?= $db_username ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="db_password" class="form-label">Database Password</label>
                <input type="password" class="form-control" id="db_password" name="db_password" value="<?= $db_password ?? '' ?>">
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php?step=system_check" class="btn btn-secondary">Previous</a>
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </form>
    </div>
</div>