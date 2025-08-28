@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Add Request</h1>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded shadow p-4">
        <form method="POST" action="{{ route('admin.requests.store') }}">
            @csrf
            <div class="form-group">
                <label for="user_id" class="font-medium">Assign to User</label>
                <select id="user_id" name="user_id" class="form-control">
                    <option value="">-- Select User --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(old('user_id')==$u->id)>
                            {{ $u->name }} {{ $u->email ? '('.$u->email.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="page_name" class="font-medium">Page Name <span class="text-danger">*</span></label>
                <input id="page_name" type="text" name="page_name" value="{{ old('page_name') }}" class="form-control" placeholder="e.g., WiseDynamic FB Page" required />
            </div>

            <div class="form-group mt-3">
                <label for="social_media" class="font-medium">Social Media <span class="text-danger">*</span></label>
                <select id="social_media" name="social_media" class="form-control" required>
                    <option value="">-- Select Platform --</option>
                    <option value="facebook" @selected(old('social_media')==='facebook')>Facebook</option>
                    <option value="instagram" @selected(old('social_media')==='instagram')>Instagram</option>
                    <option value="tiktok" @selected(old('social_media')==='tiktok')>TikTok</option>
                    <option value="twitter" @selected(old('social_media')==='twitter')>Twitter</option>
                    <option value="linkedin" @selected(old('social_media')==='linkedin')>LinkedIn</option>
                    <option value="youtube" @selected(old('social_media')==='youtube')>YouTube</option>
                </select>
            </div>

            <div class="form-row mt-3">
                <div class="form-group col-md-4">
                    <label for="ads_budget_bdt" class="font-medium">Ads Budget (BDT) <span class="text-danger">*</span></label>
                    <input id="ads_budget_bdt" type="number" step="0.01" min="0" name="ads_budget_bdt" value="{{ old('ads_budget_bdt') }}" class="form-control" placeholder="5000" required />
                </div>
                <div class="form-group col-md-4">
                    <label for="days" class="font-medium">Days <span class="text-danger">*</span></label>
                    <input id="days" type="number" min="1" name="days" value="{{ old('days') }}" class="form-control" placeholder="7" required />
                </div>
                <div class="form-group col-md-4">
                    <label for="post_link" class="font-medium">Post Link</label>
                    <input id="post_link" type="url" name="post_link" value="{{ old('post_link') }}" class="form-control" placeholder="https://..." />
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create Request</button>
                <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
