<section id="quick-request" class="py-10 bg-white relative">
    <div class="container mx-auto px-6">
        <div class="relative overflow-hidden rounded-3xl p-6 md:p-10 theme-gradient text-white shadow-xl">
            <!-- Decorative blobs -->
            <div class="pointer-events-none absolute -top-8 -right-8 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-10 -left-10 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>

            <div class="relative grid md:grid-cols-3 gap-6 md:gap-10 items-center">
                <div class="md:col-span-2">
                    <h3 class="text-2xl md:text-3xl font-extrabold tracking-tight">Quick Ad Request</h3>
                    <p class="mt-2 text-white/90">Boost your post across social media. Send the essentials, we’ll do the rest.</p>
                    <ul class="mt-5 space-y-2">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-white/20 text-white">✓</span>
                            <span>All major platforms supported (Facebook, Instagram, TikTok, YouTube, more)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-white/20 text-white">✓</span>
                            <span>Define budget and duration to match your goals</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-white/20 text-white">✓</span>
                            <span>Fast turnaround with expert setup</span>
                        </li>
                    </ul>
                </div>
                <div class="flex md:justify-end">
                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('quick-request')); ?>" class="px-8 py-3 rounded-full font-semibold bg-white text-gray-900 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition transform">
                            Add Request
                        </a>
                    <?php else: ?>
                        <button id="qr-open" class="px-8 py-3 rounded-full font-semibold bg-white text-gray-900 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition transform">
                            Add Request
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal (auth only) -->
    <?php if(auth()->guard()->check()): ?>
    <div id="qr-modal" class="absolute inset-0 z-[9999] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 z-0" id="qr-backdrop"></div>
        <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl shadow-lg overflow-hidden max-h-[80vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h4 class="text-lg font-semibold">Quick Add Request</h4>
                <button id="qr-close" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>
            <div class="p-6">
                <form id="qr-form" method="POST" action="<?php echo e(route('customer.requests.store')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Page Name</label>
                        <input type="text" name="page_name" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., Wise Dynamic" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Social Media</label>
                        <select name="social_media" class="w-full border rounded-lg px-4 py-2" required>
                            <option value="">Select one</option>
                            <option value="facebook">Facebook</option>
                            <option value="instagram">Instagram</option>
                            <option value="tiktok">TikTok</option>
                            <option value="twitter">Twitter</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="youtube">YouTube</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ads Budget (BDT)</label>
                            <input type="number" min="0" step="1" name="ads_budget_bdt" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., 5000" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Days</label>
                            <input type="number" min="1" step="1" name="days" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., 7" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Post Link</label>
                        <input type="url" name="post_link" class="w-full border rounded-lg px-4 py-2" placeholder="https://" required>
                    </div>
                    <div class="h-2"></div>
                </form>
                <p id="qr-error" class="hidden mt-3 text-sm text-red-600"></p>
            </div>
            <div class="sticky bottom-0 bg-white border-t px-6 py-4 flex items-center justify-end gap-3">
                <button type="button" id="qr-cancel" class="btn-outline-primary px-5 py-2.5 rounded-full font-semibold">Cancel</button>
                <button type="submit" form="qr-form" class="btn-primary px-5 py-2.5 rounded-full font-semibold">Submit</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const openBtn = document.getElementById('qr-open');
  const modal = document.getElementById('qr-modal');
  const closeBtn = document.getElementById('qr-close');
  const cancelBtn = document.getElementById('qr-cancel');
  const backdrop = document.getElementById('qr-backdrop');
  const form = document.getElementById('qr-form');
  const errorEl = document.getElementById('qr-error');
  const section = document.getElementById('quick-request');

  function openModal(){
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    section && section.classList.add('min-h-screen');
    // Ensure section is in view and above adjacent sections
    try { section && section.scrollIntoView({ behavior: 'smooth', block: 'start' }); } catch(e) {}
    if (section) { section.style.zIndex = '9998'; section.classList.add('relative'); }
  }
  function closeModal(){
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    section && section.classList.remove('min-h-screen');
    if (section) { section.style.zIndex = ''; }
    errorEl.classList.add('hidden');
    errorEl.textContent='';
    form.reset();
  }

  openBtn && openBtn.addEventListener('click', openModal);
  closeBtn && closeBtn.addEventListener('click', closeModal);
  cancelBtn && cancelBtn.addEventListener('click', closeModal);
  backdrop && backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape' && !modal.classList.contains('hidden')) closeModal(); });

  form && form.addEventListener('submit', function(e){
    // Build title/description for request and submit to customer.requests.store
    e.preventDefault();
    const data = new FormData(form);
    const pageName = (data.get('page_name')||'').trim();
    const social = (data.get('social_media')||'').trim();
    const budget = (data.get('ads_budget_bdt')||'').trim();
    const days = (data.get('days')||'').trim();
    const link = (data.get('post_link')||'').trim();

    if(!pageName || !social || !budget || !days || !link){
      errorEl.textContent = 'Please fill in all fields.'; errorEl.classList.remove('hidden'); return;
    }

    // Create hidden fields expected by account.requests.store
    const title = `Boost ${pageName} on ${social}`;
    const description = `Quick Request for ${pageName}\nSocial: ${social}\nBudget (BDT): ${budget}\nDays: ${days}\nPost: ${link}`;

    // Append/ensure hidden inputs before submitting
    const ensureHidden = (name, value) => {
      let input = form.querySelector(`input[name="${name}"]`);
      if(!input){ input = document.createElement('input'); input.type = 'hidden'; input.name = name; form.appendChild(input); }
      input.value = value;
    };
    ensureHidden('title', title);
    ensureHidden('description', description);

    // Submit the form normally
    form.submit();
  });
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/home/sections/quick-request.blade.php ENDPATH**/ ?>