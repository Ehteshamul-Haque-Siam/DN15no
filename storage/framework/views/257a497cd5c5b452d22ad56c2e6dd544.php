
<?php $__env->startSection('title', 'রেজিস্ট্রেশন সফল'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-soft border-0">
                <div class="card-body text-center p-4 p-lg-5">
                    <i class="fa fa-check-circle text-success" style="font-size:64px;"></i>
                    <h3 class="mt-3">ধন্যবাদ, <?php echo e($registration->name_bn); ?>!</h3>
                    <p class="lead">আপনার রেজিস্ট্রেশন সফলভাবে গৃহীত হয়েছে।</p>

                    <div class="alert alert-info mt-4">
                        <strong>Reg ID:</strong> <?php echo e($registration->registration_id); ?><br>
                        <strong>Status:</strong> <span class="badge bg-warning text-dark">Payment Pending</span>
                    </div>

                    <p>বার্ষিক চাঁদা <strong>১০২০ টাকা</strong> বিকাশ নাম্বারে পরিশোধ করুন: <strong>01761983617</strong>।</p>

                    <a href="https://shop.bkash.com/oitijjo01761983617/pay/bdt1020/XpxHaY"
                       target="_blank" class="btn btn-primary btn-lg mt-2">
                        <i class="fa fa-credit-card"></i> Pay Now
                    </a>

                    <hr class="my-4">
                    <a href="<?php echo e(route('register.status', $registration->tracking_token)); ?>" class="btn btn-outline-secondary">স্ট্যাটাস দেখুন</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\optimumstudio\15_No\dhanmondi15\resources\views/registration/success.blade.php ENDPATH**/ ?>