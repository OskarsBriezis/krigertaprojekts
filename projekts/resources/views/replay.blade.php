<x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Replay</title>
    <style>
        #canvas {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h1>Replay</h1>
    <label for="speed">Speed:</label>
    <input type="number" id="speed" value="50" min="1">
    <button id="replayBtn">Replay Drawing</button>
    <canvas id="canvas" width="800" height="600"></canvas>
    <br>
    <form action="{{ route('replay.delete', ['id' => $replayInfo->id ]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this replay?');">
        @csrf
        @method('DELETE')
        <button id="stopBtn" type="submit">Delete Drawing</button>
    </form>
    <a id="bck-hom"href="{{ route('replay.get') }}">Back to Your Drawings</a>

    <script>
        const movements = @json(json_decode($movements));
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let index = 0;

        document.getElementById('replayBtn').addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            index = 0;
            replayMovements();
        });

        function replayMovements() {
            if (index >= movements.length) {
                return;
            }

            const currentMovement = movements[index];
            const previousMovement = movements[index - 1] || currentMovement;

            if (currentMovement.drawing && previousMovement.drawing) {
                drawLine(previousMovement.x, previousMovement.y, currentMovement.x, currentMovement.y);
            }

            index++;

            const speed = parseInt(document.getElementById('speed').value);
            setTimeout(replayMovements, speed); // Adjust the timeout for the speed of the replay
        }

        function drawLine(x1, y1, x2, y2) {
            ctx.beginPath();
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.strokeStyle = 'red'; // Change the color as needed
            ctx.lineWidth = 2;
            ctx.stroke();
            ctx.closePath();
        }
    </script>
</body>
</html>
    </x-app-layout>
