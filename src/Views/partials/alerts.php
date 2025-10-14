<?php if (!empty($alerts)): ?>
    <div class="alerts-container mt-3">
        <?php foreach ($alerts as $alert): ?>
            <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                <?= $alert['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>