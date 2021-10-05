<li class="nav-item ">
    <a class="nav-link border-bottom mb-2" href="#size" data-toggle="collapse" role="button" aria-expanded="true"
        aria-controls="size">
        <span class="font-weight-bold nav-link-text">{{ __('Size') }}</span>
    </a>

    <div class="collapse hide" id="size">
        @foreach ($sizes as $size)
            <div class="form-check mb-1">
                <input class="form-check-input filterSize d-none" type="radio" name="size" id="size{{ $size->id }}"
                    value="{{ $size->id }}"
                    {{ ($data['size'] ?? '') && $data['size'] == $size->id ? 'checked' : '' }}>
                <label class="form-check-label text-muted" for="size{{ $size->id }}">
                    {{ $size->name }}
                </label>
            </div>
        @endforeach
    </div>
</li>