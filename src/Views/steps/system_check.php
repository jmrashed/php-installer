<?php
/**
 * @var \Installer\Core\Installer $installer
 * @var array $alerts
 * @var array $requirements
 * @var array $errors
 */
?>

<div class="card mx-auto mt-5" style="max-width: 800px;">
    <div class="card-header text-center">
        <h2 class="mb-0">System Requirements Check</h2>
    </div>
    <div class="card-body">
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/alerts.php'); ?>
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/progressbar.php'); ?>

        <h4 class="mt-4">PHP Version</h4>
        <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                PHP Version (Required: <?= $requirements['php_version']['required'] ?>)
                <span class="badge bg-<?= $requirements['php_version']['status'] ? 'success' : 'danger' ?> rounded-pill">
                    <?= $requirements['php_version']['current'] ?> (<?= $requirements['php_version']['message'] ?>)
                </span>
            </li>
        </ul>

        <h4 class="mt-4">PHP Extensions</h4>
        <ul class="list-group mb-3">
            <?php foreach ($requirements['extensions'] as $ext): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $ext['name'] ?>
                    <span class="badge bg-<?= $ext['status'] ? 'success' : 'danger' ?> rounded-pill">
                        <?= $ext['message'] ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <h4 class="mt-4">Writable Directories</h4>
        <ul class="list-group mb-3">
            <?php foreach ($requirements['writable_directories'] as $dir): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $dir['name'] ?>
                    <span class="badge bg-<?= $dir['status'] ? 'success' : 'danger' ?> rounded-pill">
                        <?= $dir['message'] ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <form action="index.php?step=system_check" method="POST" class="mt-4">
            <input type="hidden" name="csrf_token" value="<?= \Installer\Core\Utils::getCsrfToken() ?>">
            <div class="d-flex justify-content-between">
                <a href="index.php?step=license" class="btn btn-secondary">Previous</a>
                <button type="submit" class="btn btn-primary" <?= !empty($errors) ? 'disabled' : '' ?>>Next</button>
            </div>
        </form>
    </div>
</div>