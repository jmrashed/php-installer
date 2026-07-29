<?php if (!empty($alerts)): ?>
    <div class="alerts-container mt-3">
        <?php foreach ($alerts as $alert): ?>
            <div class="alert alert-<?= \Installer\Core\Utils::e($alert['type']) ?> alert-dismissible fade show" role="alert">
                <?= \Installer\Core\Utils::e($alert['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>