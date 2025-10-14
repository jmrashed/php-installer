<?php
/**
 * @var \Installer\Core\Installer $installer
 * @var array $alerts
 */
?>

<div class="card mx-auto mt-5" style="max-width: 800px;">
    <div class="card-header text-center">
        <h2 class="mb-0">License Agreement</h2>
    </div>
    <div class="card-body">
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/alerts.php'); ?>
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/progressbar.php'); ?>

        <div class="license-content" style="height: 300px; overflow-y: scroll; border: 1px solid #dee2e6; padding: 15px; background-color: #f8f9fa;">
            <?php include \Installer\Core\Utils::getBasePath('LICENSE'); ?>
        </div>

        <form action="index.php?step=license" method="POST" class="mt-4">
            <input type="hidden" name="csrf_token" value="<?= \Installer\Core\Utils::getCsrfToken() ?>">
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="agreeLicense" name="agree_license" required>
                <label class="form-check-label" for="agreeLicense">
                    I agree to the terms and conditions of the license agreement.
                </label>
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php?step=welcome" class="btn btn-secondary">Previous</a>
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </form>
    </div>
</div>