<h1> Hello, {{ $data["fname"] }} </h1>

<p>
    Click the link below to reset your password:
    <a href="{{ url($data['link'])}}">Reset</a>
</p>