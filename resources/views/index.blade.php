<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallapop - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Wallapop</a>
            <div class="ml-auto">
                @auth
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile') }}">My Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-user"></i> Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Products</h1>
        <div class="alert alert-success d-none" id="successMessage">Product purchased successfully!</div>
        @if($sales->isEmpty())
            <div class="alert alert-info">No products available for sale at the moment.</div>
        @else
            <div class="row">
                @foreach($sales as $sale)
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <a href="{{ route('products.show', $sale->id) }}">
                                @if($sale->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $sale->images->first()->ruta) }}" class="card-img-top" alt="{{ $sale->product }}">
                                @endif
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">{{ $sale->product }}</h5>
                                <p class="card-text">{{ $sale->description }}</p>
                                <p class="card-text"><strong>Category:</strong> {{ $sale->category->name }}</p>
                                <p class="card-text"><strong>Price:</strong> ${{ $sale->price }}</p>
                                <form method="POST" action="{{ route('products.toggleSold', $sale->id) }}" class="toggle-sold-form">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Buy Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-sold-form').on('submit', function(event) {
                event.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            form.closest('.col-md-4').remove();
                            $('#successMessage').removeClass('d-none');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>