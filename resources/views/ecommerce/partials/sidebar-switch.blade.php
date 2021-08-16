<li class="nav-item ">
    <a class="nav-link border-bottom mb-2" href="#type" data-toggle="collapse" role="button" aria-expanded="true"
        aria-controls="type">
        <span class="font-weight-bold nav-link-text">{{ __('Type') }}</span>
    </a>

    <div class="collapse hide" id="type">
        <div class="form-check mb-1">
            <input class="form-check-input filterType d-none" type="radio" name="type" id="type1" value="1"
            {{ ($data['type'] ?? '') && $data['type'] == 1 ? 'checked' : '' }}>
            <label class="form-check-label text-muted" for="type1">
                Clicky
            </label>
        </div>

        <div class="form-check mb-1">
            <input class="form-check-input filterType d-none" type="radio" name="type" id="type2" value="2"
            {{ ($data['type'] ?? '') && $data['type'] == 2 ? 'checked' : '' }}>
            <label class="form-check-label text-muted" for="type2">
                Linear
            </label>
        </div>

        <div class="form-check mb-1">
            <input class="form-check-input filterType d-none" type="radio" name="type" id="type3" value="3"
                {{ ($data['type'] ?? '') && $data['type'] == 3 ? 'checked' : '' }}>
            <label class="form-check-label text-muted" for="type3">
                Tactile
            </label>
        </div>
    </div>
</li>