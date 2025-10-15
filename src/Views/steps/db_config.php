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

        <form action="install?step=db_config" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo \Installer\Core\Utils::getCsrfToken()?>">
            <div class="mb-3">
                <label for="db_driver" class="form-label">Database Type</label>
                <select class="form-control" id="db_driver" name="db_driver" onchange="toggleDatabaseFields()" required>
                    <?php foreach ($supported_databases as $key => $db): ?>
                        <option value="<?php echo $key?>" <?php echo ($db_driver ?? 'mysql') === $key ? 'selected' : ''?>>
                            <?php echo $db['name']?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="server-fields">
                <div class="mb-3">
                    <label for="db_host" class="form-label">Database Host</label>
                    <input type="text" class="form-control" id="db_host" name="db_host" value="<?php echo $db_host ?? 'localhost'?>" required>
                </div>
                <div class="mb-3">
                    <label for="db_port" class="form-label">Database Port</label>
                    <input type="number" class="form-control" id="db_port" name="db_port" value="<?php echo $db_port ?? '3306'?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="db_name" class="form-label" id="db_name_label">Database Name</label>
                <input type="text" class="form-control" id="db_name" name="db_name" value="<?php echo $db_name ?? ''?>" required>
                <small class="form-text text-muted" id="db_name_help">Enter the database name</small>
            </div>
            <div id="auth-fields">
                <div class="mb-3">
                    <label for="db_username" class="form-label">Database Username</label>
                    <input type="text" class="form-control" id="db_username" name="db_username" value="<?php echo $db_username ?? 'root'?>" required>
                </div>
                <div class="mb-3">
                    <label for="db_password" class="form-label">Database Password</label>
                    <input type="password" class="form-control" id="db_password" name="db_password" value="<?php echo $db_password ?? ''?>">
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="install?step=system_check" class="btn btn-secondary">Previous</a>
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDatabaseFields() {
    const driver = document.getElementById('db_driver').value;
    const serverFields = document.getElementById('server-fields');
    const authFields = document.getElementById('auth-fields');
    const dbNameLabel = document.getElementById('db_name_label');
    const dbNameHelp = document.getElementById('db_name_help');
    const dbPortField = document.getElementById('db_port');

    if (driver === 'sqlite') {
        serverFields.style.display = 'none';
        authFields.style.display = 'none';
        dbNameLabel.textContent = 'Database File Path';
        dbNameHelp.textContent = 'Enter the path to SQLite database file (e.g., /path/to/database.db)';
        document.getElementById('db_host').required = false;
        document.getElementById('db_port').required = false;
        document.getElementById('db_username').required = false;
    } else {
        serverFields.style.display = 'block';
        authFields.style.display = 'block';
        dbNameLabel.textContent = 'Database Name';
        dbNameHelp.textContent = 'Enter the database name';
        document.getElementById('db_host').required = true;
        document.getElementById('db_port').required = true;
        document.getElementById('db_username').required = true;

        // Set default ports
        const supportedDbs = <?php echo json_encode($supported_databases)?>;
        if (supportedDbs[driver] && supportedDbs[driver].default_port) {
            dbPortField.value = supportedDbs[driver].default_port;
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDatabaseFields();
});
</script>