<section id="quick-request" class="py-10 bg-white relative">
    <div class="container mx-auto px-6">
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 md:p-8">
            <div class="grid md:grid-cols-3 gap-6 md:gap-8 items-center">
                <div class="md:col-span-2">
                    <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900">Quick Add Request</h3>
                    <p class="text-gray-600 mt-2">Boost your post across social media. Send the essentials, we’ll do the rest.</p>
                    <ul class="mt-4 space-y-2 text-gray-700 list-disc pl-5">
                        <li>All major platforms supported (Facebook, Instagram, TikTok, YouTube, more)</li>
                        <li>Define budget and duration to match your goals</li>
                        <li>Fast turnaround with expert setup</li>
                    </ul>
                </div>
                <div class="flex md:justify-end">
                    <button id="qr-open" class="btn-primary px-7 py-3 rounded-full font-semibold self-start md:self-auto">Add Request</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="qr-modal" class="absolute inset-0 z-[9999] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 z-0" id="qr-backdrop"></div>
        <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl shadow-lg overflow-hidden max-h-[80vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h4 class="text-lg font-semibold">Quick Add Request</h4>
                <button id="qr-close" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>
            <div class="p-6">
                <form id="qr-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Page Name</label>
                        <input type="text" name="page_name" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., Wise Dynamic" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Social Media</label>
                        <select name="social_media" class="w-full border rounded-lg px-4 py-2" required>
                            <option value="">Select one</option>
                            <option>Facebook</option>
                            <option>Instagram</option>
                            <option>TikTok</option>
                            <option>LinkedIn</option>
                            <option>Twitter/X</option>
                            <option>Youtube</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ads Budget (BDT)</label>
                            <input type="number" min="0" step="1" name="ads_budget" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., 5000" required>
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
</section>

@push('scripts')
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
    e.preventDefault();
    const data = new FormData(form);
    const pageName = (data.get('page_name')||'').trim();
    const social = (data.get('social_media')||'').trim();
    const budget = (data.get('ads_budget')||'').trim();
    const days = (data.get('days')||'').trim();
    const link = (data.get('post_link')||'').trim();

    if(!pageName || !social || !budget || !days || !link){
      errorEl.textContent = 'Please fill in all fields.'; errorEl.classList.remove('hidden'); return;
    }

    // Redirect to contact with context as query params
    const params = new URLSearchParams({
      quick: '1',
      type: 'quick_request',
      page: pageName,
      social: social,
      budget: budget,
      days: days,
      link: link
    });
    window.location.href = `${window.location.origin}/#contact?${params.toString()}`;
  });
})();
</script>
@endpush
