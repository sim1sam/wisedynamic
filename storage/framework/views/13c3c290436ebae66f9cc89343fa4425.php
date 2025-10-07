<?php $__env->startSection('title', 'WiseDynamic Admin'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->yieldContent('content_body'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/admin.css')); ?>">
    <style>
        /* Theme gradient (blue → purple) for sidebar */
        .main-sidebar { 
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%) !important; /* from-blue-600 to-purple-600 */
            color: #fff;
        }
        .main-sidebar .brand-link, .main-sidebar .brand-link .brand-text { color: #fff !important; }
        .main-sidebar .nav-sidebar .nav-item>.nav-link { color: rgba(255,255,255,.9); }
        .main-sidebar .nav-sidebar .nav-item>.nav-link.active, 
        .main-sidebar .nav-sidebar .nav-item>.nav-link:hover { 
            background: rgba(255,255,255,.15) !important; 
            color: #fff !important; 
        }
        .main-header.navbar { box-shadow: 0 1px 2px rgba(0,0,0,.06); }
        /* Sidebar icon color to white */
        .main-sidebar .nav-sidebar .nav-item > .nav-link .nav-icon { color: #fff !important; }
        /* Navbar user icon to theme color */
        .main-header .nav-link .fa-user { color: #7c3aed; }
        /* Dashboard small-box icons with gradient color */
        .small-box .icon i {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
            opacity: 1 !important;
        }
        .content-header h1 { font-weight: 800; }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(asset('assets/js/admin.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>