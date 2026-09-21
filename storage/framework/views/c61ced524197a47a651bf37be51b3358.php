
<?php $__env->startSection('title', 'SMS Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <?php if(session('success')): ?> <div class="alert alert-success"><?php echo e(session('success')); ?></div> <?php endif; ?>
    <?php if($errors->any()): ?> <div class="alert alert-danger"><?php echo e($errors->first()); ?></div> <?php endif; ?>

    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
        <h3 class="mb-0">SMS Logs</h3>
        <div class="text-muted small align-self-center">
            Gateway: <strong><?php echo e(config('services.sms.gateway')); ?></strong> |
            Status: <strong><?php echo e(config('services.sms.enabled') ? 'Enabled' : 'Disabled (dry-run)'); ?></strong>
        </div>
    </div>

    
    <div class="card shadow-soft mb-3">
        <div class="card-header"><strong>Send Test SMS</strong></div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.sms.test')); ?>" class="row g-2">
                <?php echo csrf_field(); ?>
                <div class="col-md-3">
                    <input type="text" name="mobile" class="form-control" placeholder="01XXXXXXXXX" required>
                </div>
                <div class="col-md-7">
                    <input type="text" name="message" class="form-control" placeholder="Test message (Bengali allowed)" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Send</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="table-responsive">
        <table class="table table-sm bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Mobile</th><th>Type</th>
                    <th>Message</th><th>Status</th><th>Sent</th><th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($log->id); ?></td>
                        <td><?php echo e($log->mobile); ?></td>
                        <td><span class="badge bg-secondary"><?php echo e($log->type); ?></span></td>
                        <td style="max-width:400px;">
                            <?php echo e(Str::limit($log->message, 90)); ?>

                            <?php if($log->response): ?>
                                <details class="small text-muted mt-1">
                                    <summary>Gateway response</summary>
                                    <code style="word-break:break-all;"><?php echo e($log->response); ?></code>
                                </details>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($log->status==='sent'?'success':($log->status==='failed'?'danger':'warning')); ?>">
                                <?php echo e($log->status); ?>

                            </span>
                        </td>
                        <td><?php echo e($log->created_at->format('d M H:i')); ?></td>
                        <td>
                            <form method="POST" action="<?php echo e(route('admin.sms.resend', $log->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-outline-primary">Resend</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-3">No logs.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($logs->links()); ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\optimumstudio\15_No\dhanmondi15\resources\views/admin/sms/index.blade.php ENDPATH**/ ?>