 @forelse ($orders as $order)
     <div class="card shadow-none rounded-0 bg-secondary mb-3">
         <div class="card-header bg-transparent d-md-flex justify-content-md-between">
             <div>Invoice : <span class="h4">{{ $order->invoice }}</span></div>
             <div>Status : {!! $order->status_label !!}</div>
         </div>
         <div class="card-body">
             @foreach ($order->details as $detail)
                 <div class="row mb-3">
                     <div class="col-md-7 ">
                         <div class="media">
                             <img class="mr-3 rounded-sm" width="64" height="64"
                                 src="{{ asset('/storage/' . $detail->product->image) }}" alt="">
                             <div class="media-body align-self-center">
                                 <span class="text-sm">
                                     {{ $detail->product->name }}</span>
                                 <span class="text-sm text-muted d-md-block">x{{ $detail->qty }}</span>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-5 align-self-center text-right text-warning">
                         IDR {{ number_format($detail->product->price) }}
                     </div>
                 </div>
             @endforeach
         </div>
         <div class="card-footer bg-transparent d-flex justify-content-md-end">
             @if ($order->status == 3 && $order->return_count == 0)
                 <form action="{{ route('order.accept', $order->id) }}" class="inline mr-md-2"
                     onsubmit="return confirm('r u sure ?')" method="POST">
                     @csrf
                     @method('patch')
                     <button type="submit" class="btn btn-warning rounded-0">Receive
                         Order</button>
                 </form>
                 <a href="{{ route('order.return-form', $order->invoice) }}"
                     class="btn btn-warning rounded-0">Return</a>
             @endif
             <a href="{{ route('order.show', $order->invoice) }}" class="btn btn-warning rounded-0">Details</a>
         </div>
     </div>
 @empty

 @endforelse
