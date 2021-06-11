<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Your Registration</title>
</head>

<body>
    <h2>Hi, {{ $customer->name }}</h2>
    <p>Thank you for making transactions in our app. here's your password : <strong>{{ $password }}</strong></p>
    <p>Don't forget to verify your registration
        <a href="{{ route('customer.verify', $customer->activate_token) }}">HERE</a>
    </p>
</body>

</html>
