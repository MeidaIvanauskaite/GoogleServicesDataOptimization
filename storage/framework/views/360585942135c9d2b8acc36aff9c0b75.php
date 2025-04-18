<!DOCTYPE html>
<html>
    <head>
        <title>Analytics Export</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                border: 1px solid #ccc;
                padding: 6px;
                vertical-align: top;
                text-align: left;
            }

            th {
                background-color: #f0f0f0;
            }

            .section-title {
                margin-top: 30px;
                font-size: 14px;
                font-weight: bold;
            }

            .subheading {
                font-size: 14px;
                color: #666;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <h2>Google Analytics Properties Export</h2>
        <div class="subheading"><?php echo e(now()->format('F j, Y')); ?></div>

        <table>
            <thead>
                <tr>
                    <th>Property Name</th>
                    <th>Tag / Status</th>
                    <th>Note</th>
                    <th>PageSpeed</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <strong><?php echo e($prop['name']); ?></strong><br>
                            <small><?php echo e($prop['industry']); ?> / <?php echo e($prop['timeZone']); ?> / <?php echo e($prop['currency']); ?></small>
                        </td>
                        <td>
                            <strong>Tag:</strong> <?php echo e($prop['tag'] ?? '-'); ?><br>
                            <strong>Status:</strong> <?php echo e($prop['status'] ?? '-'); ?>

                        </td>
                        <td><?php echo e($prop['note'] ?? '-'); ?></td>
                        <td>
                            <strong>URL:</strong> <?php echo e($prop['url'] ?? '-'); ?><br>
                            <strong>Score:</strong> <?php echo e($prop['pagespeed_score'] ?? '-'); ?><br>
                            <strong>LCP:</strong> <?php echo e($prop['lcp'] ?? '-'); ?><br>
                            <strong>FID:</strong> <?php echo e($prop['fid'] ?? '-'); ?><br>
                            <strong>CLS:</strong> <?php echo e($prop['cls'] ?? '-'); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </body>
</html>
<?php /**PATH /var/www/html/resources/views/exports/analytics_pdf.blade.php ENDPATH**/ ?>