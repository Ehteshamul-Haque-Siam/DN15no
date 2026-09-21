
<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h3 class="mb-0">Settings</h3>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
    </div>

    <?php if(session('success')): ?> <div class="alert alert-success"><?php echo e(session('success')); ?></div> <?php endif; ?>

    <div class="card shadow-soft">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Membership Fee (৳)</label>
                        <input name="membership_fee" class="form-control" value="<?php echo e($settings['membership_fee'] ?? '1020'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Numbers</label>
                        <input name="contact_numbers" class="form-control" value="<?php echo e($settings['contact_numbers'] ?? '01721308219, 01712370172, 01707269988'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" class="form-control" value="<?php echo e($settings['email'] ?? '15nocolony1966@gmail.com'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">bKash Merchant Number</label>
                        <input name="bkash_number" class="form-control" value="<?php echo e($settings['bkash_number'] ?? '01761983617'); ?>">
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Save</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\optimumstudio\15_No\dhanmondi15\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>