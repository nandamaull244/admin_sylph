<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>AR Tracking</title>

    <script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        a-scene {
            width: 100vw !important;
            height: 100vh !important;
            position: fixed;
            top: 0;
            left: 0;
            margin: 0;
            padding: 0;
        }

    </style>
</head>

<body>
    <a-scene mindar-image="imageTargetSrc: {{ $mindUrl }};" color-space="sRGB"
        renderer="colorManagement: true, physicallyCorrectLights" vr-mode-ui="enabled: false"
        device-orientation-permission-ui="enabled: false" embedded>
        <a-assets>
            <video id="video" src="{{ $videoUrl }}" loop crossorigin="anonymous" webkit-playsinline playsinline></video>
        </a-assets>

        <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

        <a-entity mindar-image-target="targetIndex: 0" videohandler>
            <a-plane width="0.75" height="1" scale="1.5 1.5 1" position="0 0 0" material="shader: flat; src: #video">
            </a-plane>
        </a-entity>
    </a-scene>

    <script>
        AFRAME.registerComponent('videohandler', {
            init: function () {
                const video = document.querySelector("#video");
                this.el.addEventListener("targetFound", e => {
                    video.play();
                });
                this.el.addEventListener("targetLost", e => {
                    video.pause();
                });
            }
        });

        // Untuk mobile permission
        window.onload = () => {
            const sceneEl = document.querySelector('a-scene');
            if (AFRAME.utils.device.isMobile()) {
                document.body.addEventListener('click', () => {
                    sceneEl.components['mindar-image'].start();
                }, {
                    once: true
                });
            }
        };

    </script>
</body>

</html>
