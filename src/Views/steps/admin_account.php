<?php
    /**
     * @var \Installer\Core\Installer $installer
     * @var array $alerts
     * @var string $admin_username
     * @var string $admin_email
     */
?>

<div class="card mx-auto mt-5" style="max-width: 600px;">
    <div class="card-header text-center">
        <h2 class="mb-0">Admin Account Setup</h2>
    </div>
    <div class="card-body">
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/alerts.php'); ?>
        <?php include \Installer\Core\Utils::getBasePath('src/Views/partials/progressbar.php'); ?>

        <form action="install?step=admin_account" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo \Installer\Core\Utils::getCsrfToken()?>">
            <div class="mb-3">
                <label for="admin_username" class="form-label">Admin Username</label>
                <input type="text" class="form-control" id="admin_username" name="admin_username" value="<?php echo $admin_username ?? ''?>" required>
            </div>
            <div class="mb-3">
                <label for="admin_email" class="form-label">Admin Email</label>
                <input type="email" class="form-control" id="admin_email" name="admin_email" value="<?php echo $admin_email ?? ''?>" required>
            </div>
            <div class="mb-3">
                <label for="admin_password" class="form-label">Admin Password</label>
                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
            </div>
            <div class="mb-3">
                <label for="admin_password_confirm" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="admin_password_confirm" name="admin_password_confirm" required>
            </div>
            <div class="d-flex justify-content-between">
                <a href="install?step=app_config" class="btn btn-secondary">Previous</a>
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </form>
    </div>
</div>