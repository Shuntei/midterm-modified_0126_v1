#document (https://threejs.org/examples/webgl_decals.html)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

</head>
<body>
    <div id="threeCanvas"></div>
    <canvas data-engine="three.js r161" width="1020" height="1020" style="display: block; width: 589px; height: 764px; touch-action: none;"></canvas>
    <script>
        
// 3D partials start

// 創建場景
var scene = new THREE.Scene();

// 創建相機
var camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.z = 5;

// 創建渲染器
var renderer = new THREE.WebGLRenderer();
renderer.setSize(window.innerWidth / 2, window.innerHeight / 2); // 調整大小
document.getElementById('threeCanvas').appendChild(renderer.domElement);

var geometry = new THREE.BoxGeometry();
var material = new THREE.MeshBasicMaterial({ color: 0x00ff00 });
var cube = new THREE.Mesh(geometry, material);
scene.add(cube);

var loader = new THREE.GLTFLoader();

loader.load('3dmodel/Shine_Sprite.gltf', function (gltf) {
  scene.add(gltf.scene);
});

function animate() {
            requestAnimationFrame(animate);
            cube.rotation.x += 0.01;
            cube.rotation.y += 0.01;
            renderer.render(scene, camera);
        }

        animate();

        window.addEventListener('resize', function () {
            var newWidth = window.innerWidth / 2;
            var newHeight = window.innerHeight / 2;
            camera.aspect = newWidth / newHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(newWidth, newHeight);
        });
    </script>
</body>
</html>