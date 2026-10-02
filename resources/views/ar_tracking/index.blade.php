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

        #soundToggle {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.65);
            color: #ffffff;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
    </style>
</head>

<body>
    <button id="soundToggle" title="Toggle Sound" aria-label="Toggle Sound">
        <svg id="iconMuted" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
            <line x1="23" y1="9" x2="17" y2="15"></line>
            <line x1="17" y1="9" x2="23" y2="15"></line>
        </svg>
        <svg id="iconUnmuted" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
        </svg>
    </button>

    <a-scene mindar-image="imageTargetSrc: {{ $mindUrl }};" color-space="sRGB"
        renderer="colorManagement: true, physicallyCorrectLights" vr-mode-ui="enabled: false"
        device-orientation-permission-ui="enabled: false" embedded>
        <a-assets>
            <video id="video" src="{{ $videoUrl }}" loop crossorigin="anonymous" webkit-playsinline playsinline muted></video>
        </a-assets>

        <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

        <a-entity mindar-image-target="targetIndex: 0" videohandler>
            <a-plane width="{{ $width }}" height="{{ $height }}" position="0 0.18 0" material="shader: flat; src: #video"
                rotation="0 0 0"></a-plane>
        </a-entity>
    </a-scene>

    <script>
        AFRAME.registerComponent('videohandler', {
            init: function () {
                const video = document.querySelector("#video");
                this.el.addEventListener("targetFound", () => {
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(error => {
                            console.warn("Autoplay prevented, falling back to muted:", error);
                            video.muted = true;
                            video.play();
                        });
                    }
                });
                this.el.addEventListener("targetLost", () => {
                    video.pause();
                });
            }
        });

        // Sound toggle button handler
        const soundToggle = document.querySelector("#soundToggle");
        const iconMuted = document.querySelector("#iconMuted");
        const iconUnmuted = document.querySelector("#iconUnmuted");

        soundToggle.addEventListener("click", () => {
            const video = document.querySelector("#video");
            video.muted = !video.muted;
            if (video.muted) {
                iconMuted.style.display = "block";
                iconUnmuted.style.display = "none";
            } else {
                iconMuted.style.display = "none";
                iconUnmuted.style.display = "block";
                video.play().catch(e => console.warn("Audio unlock required:", e));
            }
        });

        // Mobile interaction to start AR context
        window.onload = () => {
            const sceneEl = document.querySelector('a-scene');
            document.body.addEventListener('click', () => {
                if (sceneEl.components['mindar-image'] && !sceneEl.components['mindar-image'].started) {
                    sceneEl.components['mindar-image'].start();
                }
            }, { once: true });
        };
    </script>
</body>

</html>
