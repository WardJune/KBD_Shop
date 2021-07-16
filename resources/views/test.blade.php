@foreach ($carts as $cart)
    <p>{{ $cart->id }}</p>
    @foreach ($cart->products as $product)
        <small>{{ $product->name }} {{ $product->pivot->cart_id }}</small>
    @endforeach
@endforeach
