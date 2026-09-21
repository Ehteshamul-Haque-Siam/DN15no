
<?php $__env->startSection('title', 'Registration Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <a href="<?php echo e(route('admin.registrations')); ?>" class="btn btn-sm btn-outline-secondary mb-3">← Back</a>

    <?php if(session('success')): ?> <div class="alert alert-success"><?php echo e(session('success')); ?></div> <?php endif; ?>
    <?php if($errors->any()): ?> <div class="alert alert-danger"><?php echo e($errors->first()); ?></div> <?php endif; ?>

    
    <div class="alert alert-<?php echo e($registration->approval_status==='approved' ? 'success' : ($registration->approval_status==='rejected' ? 'danger' : 'warning')); ?> d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>Workflow Status:</strong>
            Payment
            <span class="badge bg-<?php echo e($registration->payment_status==='verified'?'success':($registration->payment_status==='rejected'?'danger':'warning')); ?>">
                <?php echo e($registration->payment_status); ?>

            </span>
            →
            Approval
            <span class="badge bg-<?php echo e($registration->approval_status==='approved'?'success':($registration->approval_status==='rejected'?'danger':'secondary')); ?>">
                <?php echo e($registration->approval_status); ?>

            </span>
        </div>
        <div class="small">
            <?php if($registration->payment_status !== 'verified'): ?>
                <span class="text-danger">⚠ Verify payment first to enable approval</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-soft">
                <div class="card-body">
                    <h4 class="mb-1"><?php echo e($registration->name_en); ?></h4>
                    <p class="text-muted mb-3"><code><?php echo e($registration->registration_id); ?></code></p>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name (BN):</strong> <?php echo e($registration->name_bn); ?></p>
                            <p><strong>Nickname:</strong> <?php echo e($registration->nickname); ?></p>
                            <p><strong>Father:</strong> <?php echo e($registration->father_en); ?><br><small><?php echo e($registration->father_bn); ?></small></p>
                            <p><strong>Mother:</strong> <?php echo e($registration->mother_en); ?><br><small><?php echo e($registration->mother_bn); ?></small></p>
                            <p><strong>Flat:</strong> <?php echo e($registration->flat); ?></p>
                            <p><strong>Duration:</strong> <?php echo e($registration->from_year); ?> – <?php echo e($registration->to_year); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mobile:</strong> <?php echo e($registration->contact_no); ?></p>
                            <p><strong>Email:</strong> <?php echo e($registration->email ?? '—'); ?></p>
                            <p><strong>Occupation:</strong> <?php echo e($registration->occupation ?? '—'); ?></p>
                            <p><strong>Present Address:</strong> <?php echo e($registration->present_add ?? '—'); ?></p>
                            <p><strong>Fee:</strong> ৳ <?php echo e(number_format($registration->membership_fee, 2)); ?></p>
                            <p><strong>Created:</strong> <?php echo e($registration->created_at->format('d M Y, h:i A')); ?></p>
                        </div>
                    </div>

                    <hr>
                    <h5>💳 Payment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>bKash Mobile:</strong> <?php echo e($registration->mfs_no); ?></p>
                            <p><strong>Transaction ID (TRN):</strong> <code><?php echo e($registration->mfs_trn); ?></code></p>
                            <p><strong>Amount:</strong> ৳ <?php echo e(number_format($registration->membership_fee, 2)); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                <span class="badge bg-<?php echo e($registration->payment_status==='verified'?'success':($registration->payment_status==='rejected'?'danger':'warning')); ?>">
                                    <?php echo e($registration->payment_status); ?>

                                </span>
                            </p>
                            <?php if($registration->verified_at): ?>
                                <p><strong>Verified At:</strong> <?php echo e($registration->verified_at->format('d M Y, h:i A')); ?></p>
                                <p><strong>Verified By:</strong> <?php echo e($registration->verifier?->name ?? '—'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <?php if($registration->payment_status !== 'verified'): ?>
                            <form method="POST" action="<?php echo e(route('admin.verify.payment', $registration->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-success"><i class="fa fa-check"></i> Verify Payment</button>
                            </form>
                        <?php endif; ?>
                        <?php if($registration->payment_status !== 'rejected'): ?>
                            <form method="POST" action="<?php echo e(route('admin.reject.payment', $registration->id)); ?>" class="d-flex gap-2 flex-wrap">
                                <?php echo csrf_field(); ?>
                                <input type="text" name="remarks" class="form-control" placeholder="Reason for rejection" required style="max-width:220px;">
                                <button class="btn btn-outline-danger">Reject Payment</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <hr>
                    <h5>✅ Approval</h5>
                    <?php if($registration->payment_status !== 'verified'): ?>
                        <div class="alert alert-warning py-2 mb-2 small">
                            Approval is disabled until payment is verified.
                        </div>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap gap-2">
                        <?php if($registration->approval_status !== 'approved'): ?>
                            <form method="POST" action="<?php echo e(route('admin.approve', $registration->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-success" <?php if($registration->payment_status !== 'verified'): echo 'disabled'; endif; ?>>
                                    <i class="fa fa-user-check"></i> Approve Membership
                                </button>
                            </form>
                        <?php endif; ?>
                        <?php if($registration->approval_status !== 'rejected'): ?>
                            <form method="POST" action="<?php echo e(route('admin.reject', $registration->id)); ?>" class="d-flex gap-2 flex-wrap">
                                <?php echo csrf_field(); ?>
                                <input type="text" name="remarks" class="form-control" placeholder="Reason for rejection" required style="max-width:220px;">
                                <button class="btn btn-outline-danger">Reject Application</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <?php if($registration->admin_remarks): ?>
                        <div class="alert alert-warning mt-3 mb-0">
                            <strong>Last Remarks:</strong> <?php echo e($registration->admin_remarks); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="card shadow-soft">
                <div class="card-body text-center">
                    <?php if($registration->hasPhoto()): ?>
                        <img src="<?php echo e(asset('storage/' . $registration->photo)); ?>"
                             class="img-fluid rounded mb-3"
                             alt="Applicant Photo"
                             style="max-height:280px;object-fit:cover;"
                             onerror="this.onerror=null; this.parentNode.querySelector('.no-photo').style.display='block'; this.style.display='none';">

                        <div class="bg-light border rounded d-flex align-items-center justify-content-center mb-3 no-photo"
                             style="height:180px; display:none !important;">
                            <div class="text-center text-muted">
                                <div style="font-size:36px;">📷</div>
                                <small>Image file missing</small>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-light border rounded d-flex align-items-center justify-content-center mb-3"
                             style="height:180px;">
                            <div class="text-center text-muted">
                                <div style="font-size:36px;">📷</div>
                                <small>No photo uploaded</small>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="text-muted small">Applicant photo</div>
                </div>
            </div>

            
            <div class="card shadow-soft mt-3">
                <div class="card-header"><strong>SMS History</strong></div>
                <div class="card-body p-2" style="max-height:300px;overflow-y:auto;">
                    <?php $__empty_1 = true; $__currentLoopData = $registration->smsLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border-bottom py-2 small">
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-secondary"><?php echo e($log->type); ?></span>
                                <span class="text-muted"><?php echo e($log->created_at->diffForHumans()); ?></span>
                            </div>
                            <div class="mt-1"><?php echo e($log->message); ?></div>
                            <span class="badge bg-<?php echo e($log->status==='sent'?'success':($log->status==='failed'?'danger':'warning')); ?> mt-1">
                                <?php echo e($log->status); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-muted small p-3">No SMS sent yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\optimumstudio\15_No\dhanmondi15\resources\views/admin/registrations/show.blade.php ENDPATH**/ ?>