<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />

  <title>Kid Kit</title>

  <style>
    html { height: 100%; }

    body {
      min-height: 100%;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: sans-serif;
      text-align: center;
    }

    .illo {
      display: block;
      margin: 20px auto;
      background: #FDB;
      cursor: move;
    }
  </style>

</head>
<body>

<div class="container">
  <canvas class="illo" width="300" height="300"></canvas>
</div>

<script src="js/boilerplate.js"></script>
<script src="js/canvas-renderer.js"></script>
<script src="js/vector.js"></script>
<script src="js/anchor.js"></script>
<script src="js/path-command.js"></script>
<script src="js/shape.js"></script>
<script src="js/dragger.js"></script>
<script src="js/illustration.js"></script>
<script src="js/kid-kit.js"></script>

</body>
</html>
