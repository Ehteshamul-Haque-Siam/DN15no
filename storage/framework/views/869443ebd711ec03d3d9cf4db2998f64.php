
<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">

    <?php if(session('success')): ?> <div class="alert alert-success"><?php echo e(session('success')); ?></div> <?php endif; ?>
    <?php if($errors->any()): ?> <div class="alert alert-danger"><?php echo e($errors->first()); ?></div> <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Admin Dashboard</h3>
        <div class="text-muted small">
            Logged in as <strong><?php echo e(auth()->user()->name); ?></strong>
            <span class="badge bg-primary"><?php echo e(auth()->user()->role); ?></span>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <?php
            $cards = [
                ['Total',            $stats['total'],    'primary', 'fa-list'],
                ['Pending Approval', $stats['pending'],  'warning', 'fa-hourglass-half'],
                ['Paid',             $stats['paid'],     'info',    'fa-credit-card'],
                ['Verified',         $stats['verified'], 'success', 'fa-check-circle'],
                ['Approved',         $stats['approved'], 'success', 'fa-user-check'],
                ['Rejected',         $stats['rejected'], 'danger',  'fa-times-circle'],
            ];
        ?>

        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card text-center shadow-soft border-0 h-100">
                    <div class="card-body">
                        <i class="fa <?php echo e($c[3]); ?> text-<?php echo e($c[2]); ?> mb-2" style="font-size:22px;"></i>
                        <div class="text-muted small"><?php echo e($c[0]); ?></div>
                        <h3 class="text-<?php echo e($c[2]); ?> mt-1 mb-0"><?php echo e($c[1]); ?></h3>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-soft border-0 h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Verified Revenue</h6>
                    <h2 class="mb-0">৳ <?php echo e(number_format($stats['revenue'], 2)); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-soft border-0 h-100">
                <div class="card-body d-flex flex-wrap gap-2 align-items-center">
                    <a href="<?php echo e(route('admin.registrations')); ?>" class="btn btn-primary">
                        <i class="fa fa-list"></i> Registrations
                    </a>
                    <a href="<?php echo e(route('admin.sms.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fa fa-comment"></i> SMS Logs
                    </a>
                    <?php if(auth()->user()->role !== 'moderator'): ?>
                        <a href="<?php echo e(route('admin.bkash.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fa fa-credit-card"></i> bKash
                        </a>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fa fa-users"></i> Users
                        </a>
                        <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fa fa-cog"></i> Settings
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($pendingVerification->count()): ?>
        <div class="card shadow-soft border-0 mb-4">
            <div class="card-header bg-warning-subtle d-flex justify-content-between align-items-center">
                <strong>⏳ Awaiting Payment Verification (<?php echo e($pendingVerification->count()); ?>)</strong>
                <a href="<?php echo e(route('admin.registrations', ['payment' => 'pending'])); ?>" class="btn btn-sm btn-outline-dark">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reg ID</th><th>Name</th><th>Mobile</th>
                            <th>TRN</th><th>Amount</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pendingVerification; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><code><?php echo e($r->registration_id); ?></code></td>
                                <td><?php echo e($r->name_en); ?></td>
                                <td><?php echo e($r->contact_no); ?></td>
                                <td><strong><?php echo e($r->mfs_trn); ?></strong></td>
                                <td>৳ <?php echo e(number_format($r->membership_fee, 2)); ?></td>
                                <td>
                                    <a href="<?php echo e(route('admin.registrations.show', $r->id)); ?>"
                                       class="btn btn-sm btn-primary">Review</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-soft border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Recent Registrations</strong>
            <a href="<?php echo e(route('admin.registrations')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Reg ID</th><th>Name</th><th>Mobile</th>
                        <th>Payment</th><th>Approval</th><th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><code><?php echo e($r->registration_id); ?></code></td>
                            <td><?php echo e($r->name_en); ?></td>
                            <td><?php echo e($r->contact_no); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($r->payment_status==='verified'?'success':($r->payment_status==='paid'?'info':($r->payment_status==='rejected'?'danger':'warning'))); ?>">
                                    <?php echo e($r->payment_status); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($r->approval_status==='approved'?'success':($r->approval_status==='rejected'?'danger':'secondary')); ?>">
                                    <?php echo e($r->approval_status); ?>

                                </span>
                            </td>
                            <td><?php echo e($r->created_at->format('d M Y')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No registrations yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\optimumstudio\15_No\dhanmondi15\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>