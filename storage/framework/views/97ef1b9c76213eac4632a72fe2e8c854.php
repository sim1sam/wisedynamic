<?php $__env->startSection('title', $page->title); ?>

<?php $__env->startSection('meta_description', $page->short_description); ?>

<?php $__env->startSection('css'); ?>
<style>
    /* Custom styles for page content */
    .page-header {
        padding: 4rem 0 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        margin-bottom: 3rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
    }
    
    .page-description {
        font-size: 1.25rem;
        color: #64748b;
        max-width: 800px;
        line-height: 1.6;
    }
    
    .page-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #334155;
    }
    
    .page-content h2 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1e293b;
    }
    
    .page-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 1.75rem;
        margin-bottom: 0.75rem;
        color: #1e293b;
    }
    
    .page-content p {
        margin-bottom: 1.5rem;
    }
    
    .page-content ul, .page-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .page-content li {
        margin-bottom: 0.5rem;
    }
    
    .page-content a {
        color: #2563eb;
        text-decoration: none;
    }
    
    .page-content a:hover {
        text-decoration: underline;
    }
    
    .page-content blockquote {
        border-left: 4px solid #e2e8f0;
        padding-left: 1rem;
        font-style: italic;
        color: #64748b;
        margin: 1.5rem 0;
    }
    
    .page-image {
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        max-height: 500px;
        object-fit: cover;
        width: 100%;
    }
    
    .page-meta {
        color: #94a3b8;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
    }
    
    .page-meta i {
        margin-right: 0.5rem;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title"><?php echo e($page->title); ?></h1>
        <?php if($page->short_description): ?>
            <p class="page-description"><?php echo e($page->short_description); ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="bg-white rounded-lg shadow-sm p-4 p-md-5 mb-5">
                <?php if($page->image): ?>
                    <div class="mb-5 text-center">
                        <img src="<?php echo e(asset('storage/' . $page->image)); ?>" alt="<?php echo e($page->title); ?>" class="page-image">
                    </div>
                <?php endif; ?>
                
                <div class="page-content">
                    <?php echo $page->content; ?>

                </div>
                
                <div class="mt-5 pt-4 border-top">
                    <p class="page-meta">
                        <i class="far fa-calendar-alt"></i> Last updated: <?php echo e($page->updated_at->format('M d, Y')); ?>

                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                <h3 class="h5 mb-3">Quick Links</h3>
                <ul class="list-unstyled">
                    <?php
                        $footerPages = \App\Models\Page::where('show_in_footer', true)
                            ->where('is_active', true)
                            ->where('id', '!=', $page->id)
                            ->orderBy('order')
                            ->limit(5)
                            ->get();
                    ?>
                    
                    <?php $__currentLoopData = $footerPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $footerPage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="mb-2">
                            <a href="<?php echo e(route('page.show', $footerPage->slug)); ?>" class="text-decoration-none">
                                <i class="fas fa-angle-right mr-2 text-primary"></i> <?php echo e($footerPage->title); ?>

                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <li class="mb-2">
                        <a href="<?php echo e(route('contact')); ?>" class="text-decoration-none">
                            <i class="fas fa-angle-right mr-2 text-primary"></i> Contact Us
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-4">
                <h3 class="h5 mb-3">Need Help?</h3>
                <p>Have questions about our services or need assistance? Our team is here to help!</p>
                <a href="<?php echo e(route('contact')); ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-envelope mr-2"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/pages/show.blade.php ENDPATH**/ ?>