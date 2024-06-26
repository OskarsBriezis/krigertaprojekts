<x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Drawings</title>
</head>
<body>

    @if($replays->isEmpty())
        <p>You have no drawings yet.</p>
    @else
        <ul id="replay-item">
            @foreach($replays as $replay)
                <li>
                    <a id="drawings" href="{{ url('/replay/' . $replay->id) }}"> {{ $replay->name }}</a>
                    <p id="replay-details">Created at: {{ $replay->created_at->format('Y-m-d H:i:s') }}</p>
                    <p class="replay-details">Created by: {{ $user->name }}</p>
                </li>
            @endforeach
        </ul>
    @endif

    <a id="bck-hom" href="{{ url('/') }}">Back to Home</a>
</body>
</html>
</x-app-layout>