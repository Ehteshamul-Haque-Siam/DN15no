
<?php $__env->startSection('title', 'Registrations'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">

    <?php if(session('success')): ?> <div class="alert alert-success"><?php echo e(session('success')); ?></div> <?php endif; ?>
    <?php if($errors->any()): ?> <div class="alert alert-danger"><?php echo e($errors->first()); ?></div> <?php endif; ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h3 class="mb-0">Registrations</h3>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo e(route('admin.registrations.export')); ?>" class="btn btn-success btn-sm">Export CSV</a>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
        </div>
    </div>

    
    <form class="d-flex flex-wrap gap-2 mb-3" method="GET">
        <input type="text" name="search" class="form-control" style="max-width:240px;"
               placeholder="Search ID / name / mobile / TRN" value="<?php echo e(request('search')); ?>">

        <select name="status" class="form-select" style="max-width:170px;">
            <option value="">All Approval</option>
            <?php $__currentLoopData = ['pending','approved','rejected']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s); ?>" <?php if(request('status')==$s): echo 'selected'; endif; ?>><?php echo e(ucfirst($s)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="payment" class="form-select" style="max-width:170px;">
            <option value="">All Payment</option>
            <?php $__currentLoopData = ['pending','paid','verified','rejected']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s); ?>" <?php if(request('payment')==$s): echo 'selected'; endif; ?>><?php echo e(ucfirst($s)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <button class="btn btn-primary">Filter</button>
        <a href="<?php echo e(route('admin.registrations')); ?>" class="btn btn-outline-secondary">Reset</a>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Reg ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>TRN</th>
                    <th>Payment</th>
                    <th>Approval</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><code><?php echo e($r->registration_id); ?></code></td>
                        <td>
                            <?php echo e($r->name_en); ?><br>
                            <small class="text-muted"><?php echo e($r->name_bn); ?></small>
                        </td>
                        <td><?php echo e($r->contact_no); ?></td>
                        <td><?php echo e($r->mfs_trn ?? '—'); ?></td>
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
                        <td><?php echo e(optional($r->created_at)->format('d M Y') ?? '—'); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.registrations.show', $r->id)); ?>"
                               class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fa fa-inbox" style="font-size:32px;"></i>
                            <div class="mt-2">No registrations found.</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3"><?php echo e($registrations->links()); ?></div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\optimumstudio\15_No\dhanmondi15\resources\views/admin/registrations/index.blade.php ENDPATH**/ ?>