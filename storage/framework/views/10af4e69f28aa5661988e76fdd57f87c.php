<!-- Additional Services -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Additional Services</h2>
            <div class="section-divider mb-6"></div>
        </div>
        
        <div class="grid lg:grid-cols-3 gap-8 relative">
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 p-8 rounded-lg shadow-lg card-hover">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center floating">
                    <i class="fas fa-music text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Background Music Creation</h3>
                <p class="text-gray-600 mb-4">Custom-composed 20-second original background music, perfect for Reels, Shorts, Ads, and YouTube videos. 100% copyright-free & brand-matched sound.</p>
                <div class="price-highlight text-xl font-bold mb-4">From BDT 5,000/-</div>
                <button class="btn-primary px-5 py-2.5 rounded-full font-semibold as-open" data-service="Background Music Creation">Get</button>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-blue-50 p-8 rounded-lg shadow-lg card-hover">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center floating">
                    <i class="fas fa-credit-card text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">SSL Payment Gateway</h3>
                <p class="text-gray-600 mb-4">Official SSL partnership with discounted signup prices, integration support, top-notch security, and full portal access after activation.</p>
                <div class="price-highlight text-xl font-bold mb-4">Best SSL Deals</div>
                <button class="btn-primary px-5 py-2.5 rounded-full font-semibold as-open" data-service="SSL Payment Gateway">Get</button>
            </div>
            
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-lg shadow-lg card-hover">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center floating">
                    <i class="fas fa-cogs text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Website Management</h3>
                <p class="text-gray-600 mb-4">Complete website management including product uploads, content creation, and ongoing maintenance for businesses without dedicated teams.</p>
                <div class="price-highlight text-xl font-bold mb-4">From BDT 5,000/- per month</div>
                <button class="btn-primary px-5 py-2.5 rounded-full font-semibold as-open" data-service="Website Management">Get</button>
            </div>
        </div>

        <!-- Modal (scoped to this section) -->
        <div id="as-modal" class="absolute inset-0 z-[60] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40" id="as-backdrop"></div>
            <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden max-h-[85vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h4 class="text-lg font-semibold">Request Service</h4>
                    <button id="as-close" class="text-gray-500 hover:text-gray-700">✕</button>
                </div>
                <div class="p-6">
                    <div class="mb-4 p-4 rounded-lg bg-gradient-to-br from-blue-50 to-purple-50 text-gray-800">
                        <div class="font-semibold">General Information</div>
                        <p class="text-sm mt-1">Provide your mobile number and a short note. We’ll contact you shortly to finalize details and pricing.</p>
                    </div>
                    <form id="as-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Service Name</label>
                            <input type="text" name="service_name" id="as-service-name" class="w-full border rounded-lg px-4 py-2 bg-gray-50" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                            <input type="tel" name="mobile" id="as-mobile" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., 018XXXXXXXX" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Service Note</label>
                            <textarea name="note" id="as-note" rows="4" class="w-full border rounded-lg px-4 py-2" placeholder="Tell us briefly what you need…" required></textarea>
                        </div>
                    </form>
                    <p id="as-error" class="hidden mt-3 text-sm text-red-600"></p>
                </div>
                <div class="sticky bottom-0 bg-white border-t px-6 py-4 flex items-center justify-end gap-3">
                    <button type="button" id="as-cancel" class="btn-outline-primary px-5 py-2.5 rounded-full font-semibold">Cancel</button>
                    <button type="submit" form="as-form" class="btn-primary px-5 py-2.5 rounded-full font-semibold">Submit</button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const section = document.currentScript.closest('section') || document.querySelector('section:has(#as-modal)');
  const openButtons = document.querySelectorAll('.as-open');
  const modal = document.getElementById('as-modal');
  const backdrop = document.getElementById('as-backdrop');
  const closeBtn = document.getElementById('as-close');
  const cancelBtn = document.getElementById('as-cancel');
  const form = document.getElementById('as-form');
  const serviceName = document.getElementById('as-service-name');
  const mobile = document.getElementById('as-mobile');
  const note = document.getElementById('as-note');
  const errorEl = document.getElementById('as-error');

  function open(service){
    if (serviceName) serviceName.value = service || '';
    modal.classList.remove('hidden');
    section && section.classList.add('relative','min-h-screen');
    try{ section && section.scrollIntoView({behavior:'smooth', block:'start'});}catch(e){}
  }
  function close(){
    modal.classList.add('hidden');
    section && section.classList.remove('min-h-screen');
    errorEl.classList.add('hidden');
    errorEl.textContent='';
    form && form.reset();
  }

  openButtons.forEach(btn=>{
    btn.addEventListener('click', ()=> open(btn.getAttribute('data-service')));
  });
  [backdrop, closeBtn, cancelBtn].forEach(el=>{ el && el.addEventListener('click', close); });
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape' && !modal.classList.contains('hidden')) close(); });

  form && form.addEventListener('submit', function(e){
    e.preventDefault();
    const s = (serviceName.value||'').trim();
    const m = (mobile.value||'').trim();
    const n = (note.value||'').trim();
    if(!s || !m || !n){ errorEl.textContent='Please fill in all fields.'; errorEl.classList.remove('hidden'); return; }
    const params = new URLSearchParams({
      quick: '1',
      type: 'additional_service',
      service: s,
      mobile: m,
      note: n
    });
    window.location.href = `${window.location.origin}/contact?${params.toString()}#contact`;
  });
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/home/sections/additional-services.blade.php ENDPATH**/ ?>