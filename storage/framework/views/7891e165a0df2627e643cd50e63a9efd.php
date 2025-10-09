<?php $__env->startSection('content'); ?>
<header class="theme-gradient text-white pt-28 pb-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-extrabold">Checkout</h1>
        <p class="mt-2 text-white/90">Enter your customer details and billing address</p>
    </div>
</header>

<section class="py-12 bg-white">
    <div class="container mx-auto px-6 grid lg:grid-cols-3 gap-8">
        <!-- Read-only Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Review Details</h2>
                <?php
                    $cart = $cart ?? [];
                    // Ensure these variables are always defined
                    $itemType = $cart['item_type'] ?? 'package';
                    $itemKey = $cart['item_key'] ?? '';
                    $isService = $itemType === 'service';
                    $isMarketing = in_array($itemKey, ['social','seo','ads']);
                ?>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-gray-600"><?php echo e(($cart['item_type'] ?? 'package') === 'service' ? 'Service' : 'Package'); ?></div>
                        <div class="text-gray-900 font-medium"><?php echo e(ucfirst($cart['item_key'] ?? '—')); ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Total</div>
                        <div class="text-gray-900 font-semibold">BDT <?php echo e(number_format((int)($cart['amount'] ?? 0))); ?>/-</div>
                    </div>
                </div>

                <hr class="my-6">
                
                <h3 class="text-lg font-semibold mb-2"><?php echo e($isService ? 'Service Details' : ($isMarketing ? 'Marketing Info' : 'Project Details')); ?></h3>
                <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <?php if($isService): ?>
                        <div><?php echo e($isMarketing ? 'Business/Page' : 'Project Name'); ?>: <span class="text-gray-900"><?php echo e($cart['website_name'] ?? '—'); ?></span></div>
                        <div>Type: <span class="text-gray-900"><?php echo e($cart['website_type'] ?? '—'); ?></span></div>
                    <?php elseif($isMarketing): ?>
                        <div>Business/Page: <span class="text-gray-900"><?php echo e($cart['website_name'] ?? '—'); ?></span></div>
                        <div>Type: <span class="text-gray-900"><?php echo e($cart['website_type'] ?? '—'); ?></span></div>
                    <?php else: ?>
                        <div>Website/Business: <span class="text-gray-900"><?php echo e($cart['website_name'] ?? '—'); ?></span></div>
                        <div>Type: <span class="text-gray-900"><?php echo e($cart['website_type'] ?? '—'); ?></span></div>
                        <div>Pages: <span class="text-gray-900"><?php echo e($cart['page_count'] ?? '—'); ?></span></div>
                    <?php endif; ?>
                    <div class="md:col-span-2"><?php echo e($isService ? 'Requirements' : 'Notes'); ?>: <span class="text-gray-900 whitespace-pre-line"><?php echo e($cart['notes'] ?? '—'); ?></span></div>
                </div>

                <hr class="my-6">
                <h3 class="text-lg font-semibold mb-2">Customer</h3>
                <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>Name: <span class="text-gray-900"><?php echo e($customer['full_name'] ?? '—'); ?></span></div>
                    <div>Company: <span class="text-gray-900"><?php echo e($customer['company'] ?? '—'); ?></span></div>
                    <div>Email: <span class="text-gray-900"><?php echo e($customer['email'] ?? '—'); ?></span></div>
                    <div>Phone: <span class="text-gray-900"><?php echo e($customer['phone'] ?? '—'); ?></span></div>
                </div>

                <hr class="my-6">
                <h3 class="text-lg font-semibold mb-2">Billing Address</h3>
                <div class="text-sm text-gray-700">
                    <div><?php echo e($billing['address_line1'] ?? '—'); ?></div>
                    <?php if(!empty($billing['address_line2'])): ?>
                        <div><?php echo e($billing['address_line2']); ?></div>
                    <?php endif; ?>
                    <div><?php echo e(($billing['city'] ?? '—')); ?>, <?php echo e($billing['state'] ?? ''); ?> <?php echo e($billing['postal_code'] ?? ''); ?></div>
                    <div><?php echo e($billing['country'] ?? ''); ?></div>
                </div>

                <div class="flex gap-3 mt-6">
                    <?php if(($cart['item_type'] ?? 'package') === 'service'): ?>
                        <a href="<?php echo e(route('cart.show', ['service' => $cart['item_key'] ?? null])); ?>" class="btn-outline-primary px-6 py-3 rounded-full font-semibold">Back to Edit</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('cart.show', ['package' => $cart['item_key'] ?? null])); ?>" class="btn-outline-primary px-6 py-3 rounded-full font-semibold">Back to Edit</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <aside>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700">Total</span>
                    <span class="text-2xl font-bold">BDT <?php echo e(number_format((int)($cart['amount'] ?? 0))); ?>/-</span>
                </div>
                <p class="text-xs text-gray-500 mb-4">VAT/Tax included</p>
                <form action="<?php echo e(route('checkout.place')); ?>" method="POST" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-primary w-full text-center px-6 py-3 rounded-full font-semibold">Place Order</button>
                </form>
            </div>
        </aside>
    </div>
    
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/checkout/index.blade.php ENDPATH**/ ?>