{{-- <nav class="navbar navbar-expand-lg navbar-light bg-lighter">
    <div class="container">
        <a class="navbar-brand" href="{{route('front')}}">KBD <span class="text-orange">Shop</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto text-center">
                <a class="nav-link" href="{{route('front.product')}}">All Product</a>
                @foreach ($category as $cat)
                <a class="nav-link" href="{{ url('/category/' . $cat->slug)}}">{{$cat->name}}</a>
                @endforeach
            </div>
            <div class="navbar-nav ml-auto text-center">
                <a href="#" class="nav-link"><i class="fas fa-search"></i></a>
                <a href="#" class="nav-link"><i class="fas fa-user"></i></a>
                <a href="#" class="nav-link position-relative"><i class="fas fa-shopping-cart "></i><span class="badge badge-primary badge-circle badge-warning position-absolute top-0 right-1 translate-middle">5</span></a>
            </div>
        </div>
    </div>
</nav> --}}

<nav class="navbar navbar-horizontal navbar-expand-lg navbar-light bg-lighter">
    <div class="container">
        <a class="navbar-brand h1" href="{{route('front')}}">KBD <span class="text-orange">Shop</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-default">
            <div class="navbar-collapse-header rounded-0">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="javascript:void(0)">
                            <h5 class="navbar-brand">KBD <span class="text-orange">Shop</span></h5>
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false" aria-label="Toggle navigation">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="navbar-nav ml-auto">
                <a class="nav-link" href="{{route('front.product')}}">All Product</a>
                @foreach ($category as $cat)
                <a class="nav-link" href="{{ url('/category/' . $cat->slug)}}">{{$cat->name}}</a>
                @endforeach
            </div>
            <ul class="navbar-nav ml-lg-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="#">
                        <i class="fas fa-search"></i>
                        <span class="nav-link-inner--text d-lg-none">Search</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-icon" href="#" id="navbar-default_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        <span class="nav-link-inner--text d-lg-none">Profile</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="#">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="nav-link-inner--text d-lg-none">Chart</span>
                    </a>
                </li>
                
            </ul>
            
        </div>
    </div>
</nav>