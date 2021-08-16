<li class="nav-item ">
    <a class="nav-link border-bottom mb-2" href="#merk" data-toggle="collapse" role="button" aria-expanded="true"
        aria-controls="merk">
        <span class="font-weight-bold nav-link-text">{{ __('Merk') }}</span>
    </a>

    <div class="collapse hide" id="merk">
        @foreach ($merks as $merk)
            <div class="form-check mb-1">
                <input class="form-check-input filterRadio d-none" type="radio" name="merk" id="{{ $merk->id }}"
                    value="{{ $merk->id }}"
                    {{ ($data['merk'] ?? '') && $data['merk'] == $merk->id ? 'checked' : '' }}>
                <label class="form-check-label text-muted" for="{{ $merk->id }}">
                    {{ $merk->name }}
                </label>
            </div>
        @endforeach
    </div>
</li>


{{-- <ul class="list-unstyled">
        <li>
            <a href="#" class="justify-content-between d-flex">
                <span>Keyboard Size</span>
                <span class="text-warning">+</span>
            </a>
        </li>
        <li>
            <a href="#" class="justify-content-between d-flex">
                <span>Keyboard Size</span>
                <span class="text-warning">+</span>
            </a>
        </li>
        <li>
            <a href="#" class="justify-content-between d-flex">
                <span>Keyboard Size</span>
                <span class="text-warning">+</span>
            </a>
        </li>
    </ul> --}}
