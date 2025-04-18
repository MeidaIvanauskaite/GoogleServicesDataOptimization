<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h1 class="mb-4 text-center">Google Analytics Accounts and Properties</h1>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <strong>Error:</strong> <?php echo e($error); ?>

        </div>
    <?php elseif(count($accounts) === 0): ?>
        <div class="alert alert-warning text-center">
            No accounts or properties found.
        </div>
    <?php else: ?>
        <div class="accordion" id="analyticsAccountsAccordion">
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading-<?php echo e($index); ?>">
                        <button class="accordion-button <?php echo e($index === 0 ? '' : 'collapsed'); ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-<?php echo e($index); ?>"
                                aria-expanded="<?php echo e($index === 0 ? 'true' : 'false'); ?>"
                                aria-controls="collapse-<?php echo e($index); ?>">
                            <strong>Account: </strong> <?php echo e($data['account']['name']); ?>

                        </button>
                    </h2>
                    <div id="collapse-<?php echo e($index); ?>"
                         class="accordion-collapse collapse <?php echo e($index === 0 ? 'show' : ''); ?>"
                         aria-labelledby="heading-<?php echo e($index); ?>">
                        <div class="accordion-body">
                            <p><strong>ID:</strong> <?php echo e($data['account']['id']); ?></p>
                            <?php if(count($data['properties']) > 0): ?>
                                <h6 class="text-secondary">Properties:</h6>
                                <ul class="list-group">
                                    <?php $__currentLoopData = $data['properties']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="list-group-item">
                                            <h6 class="text-primary"><?php echo e($property->displayName); ?></h6>
                                            <p>
                                                <strong>Time Zone:</strong> <?php echo e($property->timeZone); ?> <br>
                                                <strong>Currency:</strong> <?php echo e($property->currencyCode); ?> <br>
                                                <strong>Industry:</strong> <?php echo e($property->industryCategory); ?> <br>
                                                <strong>Service Level:</strong>
                                                <span class="badge bg-success"><?php echo e($property->serviceLevel); ?></span>
                                            </p>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted">No properties available for this account.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/google.blade.php ENDPATH**/ ?>