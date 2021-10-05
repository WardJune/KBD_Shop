<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link active" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="true"
            aria-controls="navbar-examples">
            <i class="far fa-user fa-fw text-danger"></i>
            <span class="nav-link-text">Profile</span>
        </a>

        <div class="collapse show" id="navbar-examples">
            <ul class="nav nav-sm flex-column ml-md-4 text-sm text-muted">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.user') }}">
                        <span class="nav-link-text">User Information</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.password-edit') }}">
                        <span class="nav-link-text">Change Password</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.address') }}">
                        <span class="nav-link-text">Address Book</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('order.dashboard') }}">
            <i class="far fa-clipboard fa-fw text-red"></i>
            <span class="nav-link-text">Order </span>
        </a>
    </li>
</ul>
