<?php
/**
 * @var \Installer\Core\Installer $installer
 */
$currentStep = $installer->getCurrentStep();
$steps = $installer->getSteps();
$totalSteps = $installer->getTotalSteps();
$currentStepIndex = $installer->getStepIndex($currentStep);
?>

<div class="progress-container mb-4">
    <div class="progress" style="height: 20px;">
        <div class="progress-bar" role="progressbar" style="width: <?= (($currentStepIndex + 1) / $totalSteps) * 100 ?>%;" aria-valuenow="<?= $currentStepIndex + 1 ?>" aria-valuemin="0" aria-valuemax="<?= $totalSteps ?>">
            Step <?= $currentStepIndex + 1 ?> of <?= $totalSteps ?>
        </div>
    </div>
    <ul class="progress-steps list-unstyled d-flex justify-content-between mt-2">
        <?php foreach ($steps as $index => $stepName): ?>
            <li class="step-item <?= $index <= $currentStepIndex ? 'active' : '' ?>">
                <?= ucfirst(str_replace('_', ' ', $stepName)) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>