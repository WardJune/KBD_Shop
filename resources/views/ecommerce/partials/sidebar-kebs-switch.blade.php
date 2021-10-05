<li class="nav-item ">
    <a class="nav-link border-bottom mb-2" href="#switch" data-toggle="collapse" role="button" aria-expanded="true"
        aria-controls="switch">
        <span class="font-weight-bold nav-link-text">{{ __('Switch') }}</span>
    </a>

    <div class="collapse hide" id="switch">
        @foreach ($switches as $switch)
            <div class="form-check mb-1">
                <input class="form-check-input filterSwitch d-none" type="radio" name="switch" id="switch{{ $switch->id }}"
                    value="{{ $switch->id }}"
                    {{ ($data['switch'] ?? '') && $data['switch'] == $switch->id ? 'checked' : '' }}>
                <label class="form-check-label text-muted" for="switch{{ $switch->id }}">
                    {{ $switch->name }}
                </label>
            </div>
        @endforeach
    </div>
</li>