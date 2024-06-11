<x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Replays</title>
</head>
<body>
    <h1>Your Replays</h1>

    @if($replays->isEmpty())
        <p>You have no replays yet.</p>
    @else
        <ul>
            @foreach($replays as $replay)
                <li>
                    <a href="{{ url('/replay/' . $replay->id) }}">
                        {{ $replay->name }} (Replay ID: {{ $replay->id }})
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ url('/') }}">Back to Home</a>
</body>
</html>
</x-app-layout>