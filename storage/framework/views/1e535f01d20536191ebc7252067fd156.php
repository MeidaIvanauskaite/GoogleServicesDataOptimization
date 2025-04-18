<?php $__env->startSection('analytics-content'); ?>
<div class="container mt-5">
    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <h1 class="mb-4 text-center">Google Analytics Accounts and Properties</h1>

    <!-- Search & Filter -->
    <form method="GET" action="<?php echo e(route('services')); ?>" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by property name" value="<?php echo e(request('search')); ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="tag" class="form-control" placeholder="Filter by tag" value="<?php echo e(request('tag')); ?>">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Filter by status</option>
                <option value="Needs Review" <?php echo e(request('status') === 'Needs Review' ? 'selected' : ''); ?>>Needs Review</option>
                <option value="Optimized" <?php echo e(request('status') === 'Optimized' ? 'selected' : ''); ?>>Optimized</option>
                <option value="Archived" <?php echo e(request('status') === 'Archived' ? 'selected' : ''); ?>>Archived</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-1">
            <a href="<?php echo e(route('services')); ?>" class="btn btn-outline-secondary w-100">Clear</a>
        </div>
    </form>

    <!-- Export files -->
    <div class="mb-3">
        <a href="<?php echo e(route('export.csv', request()->query())); ?>" class="btn btn-outline-secondary btn-sm me-2">Export CSV</a>
        <a href="<?php echo e(route('export.pdf', request()->query())); ?>" class="btn btn-outline-secondary btn-sm">Export PDF</a>
    </div>

    <!-- Accounts & Properties -->
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
                        <button class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-<?php echo e($index); ?>"
                                aria-expanded="<?php echo e($index === 0 ? 'true' : 'false'); ?>"
                                aria-controls="collapse-<?php echo e($index); ?>">
                            <p style="margin:0"><strong>Account: </strong><?php echo e($data['account']['name']); ?></p>
                        </button>
                    </h2>

                    <div id="collapse-<?php echo e($index); ?>" class="accordion-collapse collapse show" aria-labelledby="heading-<?php echo e($index); ?>">
                        <div class="accordion-body">
                            <p class="mb-0"><strong>Name: </strong><?php echo e($data['account']['name']); ?></p>
                            <p><strong>ID: </strong><?php echo e($data['account']['id']); ?></p>
                            <?php if(count($data['properties']) > 0): ?>
                                <h6><strong>Properties:</strong></h6>
                                <ul class="list-group">
                                    <?php $__currentLoopData = $data['properties']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propertyData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $property = $propertyData['raw'];
                                            $meta = $propertyData['meta'];
                                        ?>
                                        <li class="list-group-item">
                                            <?php
                                                $property = $propertyData['raw'];
                                                $meta = $propertyData['meta'];
                                                $uniqueId = str_replace(['/', '.'], '_', $property->name);
                                                $pagespeed = \App\Models\PageSpeedResult::where('property_id', $property->name)->first();
                                            ?>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="text-primary mb-0"><?php echo e($property->displayName); ?></h5>
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#propertyDetails-<?php echo e($uniqueId); ?>">
                                                    Show/Hide Details
                                                </button>
                                            </div>

                                            <p class="mb-2">
                                                <strong>Time Zone:</strong> <?php echo e($property->timeZone); ?> <br>
                                                <strong>Currency:</strong> <?php echo e($property->currencyCode); ?> <br>
                                                <strong>Industry:</strong> <?php echo e($property->industryCategory); ?> <br>
                                                <strong>Service Level:</strong>
                                                <span class="badge bg-success"><?php echo e($property->serviceLevel); ?></span>
                                            </p>

                                            <div class="collapse" id="propertyDetails-<?php echo e($uniqueId); ?>">
                                                <?php if(Auth::user()->role === 'admin'): ?>
                                                    <!-- Admin Editable Fields -->
                                                    <form method="POST" action="<?php echo e(route('update.property.meta')); ?>" class="mb-3">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="property_id" value="<?php echo e($property->name); ?>">

                                                        <div class="mb-2">
                                                            <label class="form-label">Tag</label>
                                                            <input type="text" name="tag" class="form-control" value="<?php echo e($meta->tag ?? ''); ?>">
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-select">
                                                                <option <?php echo e(($meta->status ?? '') == 'Needs Review' ? 'selected' : ''); ?>>Needs Review</option>
                                                                <option <?php echo e(($meta->status ?? '') == 'Optimized' ? 'selected' : ''); ?>>Optimized</option>
                                                                <option <?php echo e(($meta->status ?? '') == 'Archived' ? 'selected' : ''); ?>>Archived</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="form-label">Note</label>
                                                            <textarea name="note" class="form-control" rows="2"><?php echo e($meta->note ?? ''); ?></textarea>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                                    </form>

                                                    <!-- PageSpeed Form -->
                                                    <form method="POST" action="<?php echo e(route('pagespeed.scan')); ?>" class="mb-2">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="property_id" value="<?php echo e($property->name); ?>">
                                                        <div class="input-group">
                                                            <input type="text" name="url" placeholder="https://example.com" class="form-control" required>
                                                            <button class="btn btn-outline-success" type="submit">Scan PageSpeed</button>
                                                        </div>
                                                    </form>

                                                    <?php if($pagespeed): ?>
                                                        <div class="mt-2">
                                                            <p class="mb-1"><strong>URL:</strong> <?php echo e($pagespeed->url); ?></p>
                                                            <p><strong>Performance Score:</strong> <?php echo e($pagespeed->performance_score); ?></p>
                                                            <ul>
                                                                <li>LCP (loading): <?php echo e($pagespeed->metrics['LCP'] ?? 'N/A'); ?></li>
                                                                <li>FID (interactivity): <?php echo e($pagespeed->metrics['FID'] ?? 'N/A'); ?></li>
                                                                <li>CLS: (visual stability) <?php echo e($pagespeed->metrics['CLS'] ?? 'N/A'); ?></li>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?php if($meta): ?>
                                                        <p class="mt-2">
                                                            <strong>Tag:</strong> <?php echo e($meta->tag); ?><br>
                                                            <strong>Status:</strong> <?php echo e($meta->status); ?><br>
                                                            <strong>Note:</strong> <?php echo e($meta->note); ?>

                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if($pagespeed): ?>
                                                        <div class="mt-2">
                                                            <h5><strong>PageSpeed Results:</strong></h5>
                                                            <p class="mb-0"><strong>URL:</strong> <?php echo e($pagespeed->url); ?></p>
                                                            <p class="mb-0"><strong>Performance Score:</strong> <?php echo e($pagespeed->performance_score); ?></p>
                                                            <ul class="">
                                                                <li>LCP (loading): <?php echo e($pagespeed->metrics['LCP'] ?? 'N/A'); ?></li>
                                                                <li>FID (interactivity): <?php echo e($pagespeed->metrics['FID'] ?? 'N/A'); ?></li>
                                                                <li>CLS: (visual stability) <?php echo e($pagespeed->metrics['CLS'] ?? 'N/A'); ?></li>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <p>No properties available for this account.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/services.blade.php ENDPATH**/ ?>