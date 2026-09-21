
<?php $__env->startSection('title', 'bKash Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h3 class="mb-0">bKash Credentials</h3>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <div class="card shadow-soft">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.bkash.update')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">App Key</label>
                        <input type="text" name="app_key" class="form-control" value="<?php echo e(old('app_key', $credential->app_key ?? '')); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">App Secret</label>
                        <input type="text" name="app_secret" class="form-control" value="<?php echo e(old('app_secret', $credential->app_secret ?? '')); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo e(old('username', $credential->username ?? '')); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" value="<?php echo e(old('password', $credential->password ?? '')); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Merchant Number</label>
                        <input type="text" name="merchant_number" class="form-control" value="<?php echo e(old('merchant_number', $credential->merchant_number ?? '01761983617')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Environment</label>
                        <select name="environment" class="form-select">
                            <option value="sandbox" <?php if(($credential->environment ?? '')==='sandbox'): echo 'selected'; endif; ?>>Sandbox</option>
                            <option value="live" <?php if(($credential->environment ?? '')==='live'): echo 'selected'; endif; ?>>Live</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Save Credentials</button>
                </div>
            </form>

            <form method="POST" action="<?php echo e(route('admin.bkash.test')); ?>" class="mt-2">
                <?php echo csrf_field(); ?>
                <button class="btn btn-outline-secondary">Test Connection</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\optimumstudio\15_No\dhanmondi15\resources\views/admin/bkash/index.blade.php ENDPATH**/ ?>