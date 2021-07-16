<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <h5>{{ $title }} Periode ({{ $date[0] }} - {{ $date[1] }})</h5>
    <hr>
    <table width="100%" class="table-hover table-bordered">
        <thead>
            <tr>
                <th>InvoiceID</th>
                <th>Customer</th>
                <th class="text-center">Subtotal</th>
                <th class="text-center">Date</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse ($orders as $order)
                <tr>
                    <td><strong>{{ $order->invoice }}</strong></td>
                    <td>
                        <strong>{{ $order->customer_name }}</strong>
                        <label class="d-block"><strong>Telp:</strong> {{ $order->customer_phone }}</label>
                        <label><strong>Address:</strong> <span class="text-wrap">{{ $order->customer_address }}</span>
                            {{ $order->district->name }} - {{ $order->district->city->name }},
                            {{ $order->district->city->province->name }}</label>
                    </td>
                    <td class="text-center">Rp {{ number_format($order->total) }}</td>
                    <td class="text-center">{{ $order->created_at->format('d-m-Y') }}</td>
                </tr>

                @php $total += $order->total @endphp
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Total</th>
                <th class="text-center">Rp {{ number_format($total) }}</th>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
