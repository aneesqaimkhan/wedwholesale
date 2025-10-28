

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1 class="page-title">Welcome to Your Dashboard!</h1>
    <p class="page-subtitle">You are successfully logged in to your tenant account.</p>
</div>

<div class="card">
    <h3 style="margin-top: 0; color: #333;">Your Profile Information</h3>
    
    <div style="display: flex; margin-bottom: 10px;">
        <span style="font-weight: 500; color: #333; width: 120px;">Name:</span>
        <span style="color: #666;"><?php echo e($user->name); ?></span>
    </div>
    
    <div style="display: flex; margin-bottom: 10px;">
        <span style="font-weight: 500; color: #333; width: 120px;">Email:</span>
        <span style="color: #666;"><?php echo e($user->email); ?></span>
    </div>
    
    <?php if($user->phone): ?>
    <div style="display: flex; margin-bottom: 10px;">
        <span style="font-weight: 500; color: #333; width: 120px;">Phone:</span>
        <span style="color: #666;"><?php echo e($user->phone); ?></span>
    </div>
    <?php endif; ?>
    
    <?php if($user->company): ?>
    <div style="display: flex; margin-bottom: 10px;">
        <span style="font-weight: 500; color: #333; width: 120px;">Company:</span>
        <span style="color: #666;"><?php echo e($user->company); ?></span>
    </div>
    <?php endif; ?>
    
    <?php if($user->address): ?>
    <div style="display: flex; margin-bottom: 10px;">
        <span style="font-weight: 500; color: #333; width: 120px;">Address:</span>
        <span style="color: #666;"><?php echo e($user->address); ?></span>
    </div>
    <?php endif; ?>
    
    <div style="display: flex; margin-bottom: 10px;">
        <span style="font-weight: 500; color: #333; width: 120px;">Role:</span>
        <span style="color: #666; text-transform: capitalize;"><?php echo e($user->role); ?></span>
    </div>
    
    <div style="display: flex; margin-bottom: 10px;">
        <span style="font-weight: 500; color: #333; width: 120px;">Status:</span>
        <span style="color: <?php echo e($user->is_active ? '#28a745' : '#dc3545'); ?>;">
            <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

        </span>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
    <div class="card">
        <h3 style="margin-top: 0; color: #333;">Quick Actions</h3>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="<?php echo e(url('/customers/create')); ?>" class="btn">Add New Customer</a>
            <a href="<?php echo e(url('/salesmen/create')); ?>" class="btn">Add New Salesman</a>
        </div>
    </div>
    
    <div class="card">
        <h3 style="margin-top: 0; color: #333;">Recent Activity</h3>
        <p style="color: #666;">Welcome to your admin panel! Use the sidebar to navigate between different modules.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\webwholesale\resources\views/tenant/dashboard.blade.php ENDPATH**/ ?>