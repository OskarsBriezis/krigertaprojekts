<x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mouse Tracker</title>
    <style>
        #canvas {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <button id="startBtn">Start Tracking</button>
    <button id="stopBtn" style="display:none;">Stop Tracking</button>
    <canvas id="canvas" width="800" height="600"></canvas>
    <div id="inputContainer" style="display:none;">
        <input type="text" id="replayName" placeholder="Enter replay name">
        <button id="saveBtn">Save Replay</button>
    </div>
    <div id="saveStatus"></div>
    <a id="replayBtn" style="display:none;" href="#">Replay Movements</a>

    <script>
        let tracking = false;
        let drawing = false;
        let movements = [];
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        document.getElementById('startBtn').addEventListener('click', () => {
            tracking = true;
            movements = [];
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById('startBtn').style.display = 'none';
            document.getElementById('stopBtn').style.display = 'block';
            document.getElementById('replayBtn').style.display = 'none'; // Hide replay button if restarting
            document.getElementById('saveStatus').innerText = ''; // Clear previous save status
        });

        document.getElementById('stopBtn').addEventListener('click', () => {
            tracking = false;
            document.getElementById('startBtn').style.display = 'block';
            document.getElementById('stopBtn').style.display = 'none';
            document.getElementById('inputContainer').style.display = 'block'; // Show the input container
        });

        document.getElementById('saveBtn').addEventListener('click', () => {
            const replayName = document.getElementById('replayName').value;
            if (!replayName) {
                document.getElementById('saveStatus').innerText = 'Please enter a name for the replay.';
                return;
            }

            fetch('/save-movements', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ movements, name: replayName })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      document.getElementById('saveStatus').innerText = `Movements saved! Replay at /replay/${data.id}`;
                      const replayBtn = document.getElementById('replayBtn');
                      replayBtn.href = `/replay/${data.id}`;
                      replayBtn.style.display = 'inline';
                  } else {
                      document.getElementById('saveStatus').innerText = 'Failed to save movements. Please try again.';
                  }
                  document.getElementById('inputContainer').style.display = 'none'; // Hide the input container
              })
              .catch(error => {
                  document.getElementById('saveStatus').innerText = 'Error occurred while saving movements.';
                  console.error('Error:', error);
              });
        });

        canvas.addEventListener('mousedown', (event) => {
            if (event.button === 0) { // Left mouse button
                drawing = true;
                movements.push({ x: event.clientX - canvas.offsetLeft, y: event.clientY - canvas.offsetTop, time: Date.now(), drawing: true });
            }
        });

        canvas.addEventListener('mouseup', () => {
            drawing = false;
        });

        canvas.addEventListener('mouseleave', () => {
            drawing = false;
        });

        canvas.addEventListener('mousemove', (event) => {
            if (tracking) {
                const x = event.clientX - canvas.offsetLeft;
                const y = event.clientY - canvas.offsetTop;
                if (drawing) {
                    movements.push({ x, y, time: Date.now(), drawing: true });
                    drawLine();
                } else {
                    movements.push({ x, y, time: Date.now(), drawing: false });
                }
            }
        });

        function drawLine() {
            if (movements.length < 2) return;

            const { x: x1, y: y1, drawing: drawing1 } = movements[movements.length - 2];
            const { x: x2, y: y2, drawing: drawing2 } = movements[movements.length - 1];

            if (drawing1 && drawing2) {
                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.strokeStyle = 'blue';
                ctx.lineWidth = 2;
                ctx.stroke();
                ctx.closePath();
            }
        }
    </script>
</body>
</html>

</x-app-layout>