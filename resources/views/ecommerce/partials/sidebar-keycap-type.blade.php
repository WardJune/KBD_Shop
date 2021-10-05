<li class="nav-item ">
    <a class="nav-link border-bottom mb-2" href="#type_k" data-toggle="collapse" role="button" aria-expanded="true"
        aria-controls="type_k">
        <span class="font-weight-bold nav-link-text">{{ __('Type') }}</span>
    </a>

    <div class="collapse hide" id="type_k">
        @foreach ($keycaps as $keycap)
            <div class="form-check mb-1">
                <input class="form-check-input filterKeycap d-none" type="radio" name="type_k" id="type_k{{ $keycap->id }}"
                    value="{{ $keycap->id }}"
                    {{ ($data['type_k'] ?? '') && $data['type_k'] == $keycap->id ? 'checked' : '' }}>
                <label class="form-check-label text-muted" for="type_k{{ $keycap->id }}">
                    {{ $keycap->name }}
                </label>
            </div>
        @endforeach
    </div>
</li>