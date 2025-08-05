<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sylph.art envelope</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets') }}/images/envelope/pavicon.png" type="image/x-icon" width="146" height="146">
    {{-- <link rel="stylesheet" href="css/envelope.css"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">
    <style>
        :root{
            --primary: white;
            --bg-color: #ffebf0;
            --bg-envelope-color: #ffb3c6;
            --envelope-tab: #d8a6a3;
            --envelope-cover: #ff4d73;
            --shadow-color: #b85c5c;
            --heart-color: #d6d1d1;
        }

        body{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            height: 100vh;
            display: grid;
            place-items: center;
        }
        .container > .envelope-wrapper {
            background: var(--bg-envelope-color);
            box-shadow: 0 0 40px var(--shadow-color);
        }
        .envelope-wrapper {
            background: var(--bg-envelope-color);
            background-color: #f08080;
            box-shadow: 0 0 40px var(--shadow-color);
            animation: float 3s ease-in-out infinite;
            border-radius: 15px !important;  
            /* overflow: hidden;  */
            margin-top: -200px;
        }

        @media screen and (min-width: 768px) and (max-width: 1024px) {
            .envelope-wrapper {
                margin-top: -150px !important;
            }
            h3 {
                margin-top: -50px !important; 
            }
        }

        @media screen and (max-width: 768px) {
            .envelope-wrapper {
                margin-top: -250px !important; 
            }
            h3 {
                margin-top: -100px !important;
            }
        }

        @media screen and (max-width: 1366px) {
            .envelope-wrapper {
                margin-top: -90px; 
            }
        }

        @media screen and (max-width: 1600px) {
            .envelope-wrapper {
                margin-top: -190px !important; 
            }
            h3 {
                margin-top: -110px !important;
            }
            .typing-effect {
                margin-top: -10px;
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .envelope-wrapper > .envelope {
            position: relative;
            width: 350px;
            height: 250px;
        }
        .envelope-wrapper > .envelope::before {
            content: "";
            position: absolute;
            top: 0;
            z-index: 2;
            border-top: 130px solid var(--envelope-tab);
            border-right: 175px solid transparent;
            border-left: 175px solid transparent;
            transform-origin: top;
            transition: all 0.5s ease-in-out 0.7s;
        }
        .envelope-wrapper > .envelope::after {
            content: "";
            position: absolute;
            z-index: 2;
            width: 0px;
            height: 0px;
            border-top: 130px solid transparent;
            border-right: 175px solid var(--envelope-cover);
            border-bottom: 120px solid var(--envelope-cover);
            border-left: 175px solid var(--envelope-cover);
        }
        .sincerely {
            text-align: right;
        }

        .letter {
            border: 1px dashed black;
        }

        .envelope > .letter {
            position: absolute;
            right: 20%;
            bottom: 0;
            width: 54%;
            height: 80%;
            background: var(--primary);
            text-align: center;
            transition: all 1s ease-in-out;
            box-shadow: 0 0 5px var(--shadow-color);
            padding: 20px 10px;
        }

        .envelope > .letter > .text {
            font-family: "Caveat", cursive;
            font-style: normal;
            color: var(--txt-color);
            text-align: justify;
            font-size: 11px;
            padding-right: 2px;
        }

        .text strong {
            font-size: 12px;
        }

        .heart {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 15px;
            height: 15px;
            background: var(--heart-color);
            z-index: 4;
            transform: translate(-50%, -20%) rotate(45deg);
            transition: transform 0.5s ease-in-out 1s;
            box-shadow: 0 1px 6px var(--shadow-color);
            cursor: pointer;
            will-change: transform;
            animation: smoothShake 0.9s linear infinite;
            }
            .heart:before, 
            .heart:after {
            content: "";
            position: absolute;
            width: 15px;
            height: 15px;
            background-color: var(--heart-color);
            border-radius: 50%;
            }
            .heart:before {
            top: -7.5px;
            }
            .heart:after {
            right: 7.5px;
            }
            @keyframes smoothShake {
            0%, 100% {
                transform: translate(-50%, -20%) rotate(30deg);
            }
            20% {
                transform: translate(-50%, -20%) rotate(45deg); 
            }
            50% {
                transform: translate(-50%, -20%) rotate(60deg);
            }
            80% {
                transform: translate(-50%, -20%) rotate(40deg);
            }
            }

            .flap > .envelope::before {
            transform: rotateX(180deg);
            pointer-events: none; /* << wajib! */
            z-index: 0; /* pastikan ini di bawah tombol */
            transition-delay: 0.7s;
            }

            .flap .envelope::after {
            pointer-events: none; /* Supaya tidak bisa intercept klik */
            z-index: 0;            /* Pastikan dia di bawah tombol */
            }

            .envelope::after {
            transition: all 0.5s ease;
            }
            /* Default (tertutup) */
            .envelope-wrapper > .envelope::before {
            transition-delay: 0.7s;
            }

            .flap > .envelope > .letter {
            bottom: 100px;
            transform: scale(1.5) translateY(0);
            transition-delay: 1s;
            }
            .flap > .heart {
            animation: none !important; 
            transform: rotate(90deg);
            transition: transform 0.6s ease; 
            }
            .envelope-wrapper .heart {
            transition: transform 0.6s ease;
            }

            @keyframes heartOpen {
            0% {
                transform: translate(-50%, -20%) rotate(45deg); 
            }
            100% {
                transform: translate(-50%, -20%) rotate(90deg);
            }
            }
            @keyframes heartClose {
            0% {
                transform: translate(-50%, -20%) rotate(90deg); 
            }
            100% {
                transform: translate(-50%, -20%) rotate(45deg);
            }
            }

            .hearts {
                position: absolute;
            }

            .one, .two, .three, .four, .five, .six {
                background-color: red;
                display: inline-block;
                height: 10px;
                width: 10px;
                margin: 0 10px;
                position: relative;
                transform: rotate(-45deg);
                top: 50px;
            }

            .one:before, .one:after, .two:before, .two:after, .three:before, .three:after, .four:before, .four:after, .five:before, .five:after, .six:before, .six:after {
                content: "";
                background-color: red;
                border-radius: 50%;
                width: 10px;
                height: 10px;
                position: absolute;
            }

            .one:before, .two:before, .three:before, .four:before, .five:before, .six:before {
                top: -5px;
                left: 0;
            }

            .one:after, .two:after, .three:after, .four:after, .five:after, .six:after {
                top: 0;
                left: 5px;
            }

            .one {
                left: 20px;
                top: -160px;
                animation: heart 2.65s ease-out infinite;
                animation-delay: 2s
            }

            .two {
                left: 40px;
                top: -160px;
                animation: heart 2s ease-out infinite;
                animation-delay: 2s
            }

            .three {
                left: 60px;
                top: -130px;
                animation: heart 1.5s ease-out infinite;
                animation-delay: 2s
            }

            .four {
                left: 80px;
                top: -130px;
                animation: heart 2.3s ease-out infinite;
                animation-delay: 2s
            }

            .five {
                left: 100px;
                top: -160px;
                animation: heart 1.7s ease-out infinite;
                animation-delay: 2s
            }

            .six {
                left: 120px;
                top: -160px;
                animation: heart 2.7s ease-out infinite;
                animation-delay: 2s
            }

            .typing-effect {
                position: absolute; 
                font-family: 'Caveat', cursive;
                font-size: 24px;
                color: #ff4d73;
                text-align: center;
                white-space: nowrap; 
                overflow: hidden; 
                padding: 0; 
                border-right: 3px solid #ff4d73; 
                animation: typing 5s steps(24, end) forwards, blinkCursor 0.5s step-end infinite;
                top: 120%;
                left: 50%; 
                transform: translateX(-50%);
                text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            }

            @keyframes typing {
                from {
                    width: 0;
                }
                to {
                    width: 100%;
                }
            }

            @keyframes blinkCursor {
                0% {
                    border-right-color: red;
                }
                50% {
                    border-right-color: transparent; 
                }
                100% {
                    border-right-color: red;
                }
            }

            @keyframes heart {
                0% {
                    transform: translateY(0) rotate(-45deg) scale(0.3);
                    opacity: 1;
                }
                100% {
                    transform: translateY(-190px) rotate(-45deg) scale(1.3);
                    opacity: 0.5;
                }
            }

            @keyframes burst {
            0% {
                transform: translate(0, 0) scale(1); 
                opacity: 1;
            }
            100% {
                transform: translate(var(--x-move), var(--y-move)) scale(0.5);
                opacity: 0; 
            }
            }

            h3 {
                position: relative;
                font-family: 'Caveat', cursive;
                font-size: 28px; 
                font-weight: 800;
                color: #ff4d73; 
                text-align: center;
                transform: translateX(-50%); 
                display: inline-block; 
                animation: wave 2s ease-in-out infinite; 
                margin-top: -50px;
            }

            h3 span {
                display: inline-block; 
                animation: wave 2s ease-in-out infinite;
                animation-delay: calc(var(--i) * 0.2s); 
            }

            @keyframes wave {
                0%, 100% {
                    transform: translateY(0); /* Neutral position */
                }
                50% {
                    transform: translateY(-10px); /* Move letters upward */
                }
            }


            .button-container {
            display: none;
            text-align: center;
            margin-top: 10px;
            z-index: 5;
            position: relative;
            }

            .flap .button-container {
            display: block;
            }

            .redirect-button {
            background-color: var(--envelope-cover);
            color: white;
            font-size: 6px;
            border-radius: 3px;
            position: relative;
            z-index: 6;
            pointer-events: auto;
            }



    </style>
</head>

<body>
    <audio id="bgm" src="{{ asset('assets/audios/lovely.mp3') }}"></audio>

    <div class='container'>
        <h3>
            <span style="--i: 0;">GIFT</span>
            <span style="--i: 1;">FOR</span>
            <span style="--i: 2;">YOU</span>
            <span style="--i: 3;">3</span>
        </h3>

        <div class='envelope-wrapper'>
            <div class='envelope'>
                <div class='letter'>
                    <div class='text'>
                        <strong id="dear"> 
                            Dear My beloved person,
                        </strong>
                        <p id="letter-body">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam rem esse porro magnam perspiciatis. Iusto nisi repellat nemo dolorum voluptate.
                        </p>
                        <p class='sincerely' id="sincerely">
                            Sincerely, Your Love < 3
                        </p>
                    </div>
                    <div class="image-envelope">
                        <img src="{{ asset('assets') }}/images/envelope/logo.png" alt="Envelope" class="envelope-image" width="44" height="9">
                    </div>
                    
                </div>
               
            </div>
            <div class='hearts'>
                <div class="one"></div>
                <div class="two"></div>
                <div class="three"></div>
                <div class="four"></div>
                <div class="five"></div>
                <div class="six"></div>
            </div>
            <div class='heart'></div>
            <div class='typing-effect' id="typing-effect"></div>
           
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- <script src="js/envelope.js"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const hash_id = urlParams.get('data');
            if(!hash_id) {
                window.location.href = '{{ url('/not-found') }}';
            }
            
            const envelope = document.querySelector('.envelope-wrapper');
            const heart = document.querySelector('.heart');
            
            heart.addEventListener('click', () => {
                envelope.classList.toggle('flap');
                console.log('Flap toggled:', envelope.classList.contains('flap'));
            });

            document.getElementsByClassName('heart')[0].addEventListener('click', () => {
                const audio = document.getElementById('bgm');
                audio.play().catch((error) => {
                    console.log('Audio playback failed.', error);
                });
                audio.volume = 1.0; 
            });
            
            document.addEventListener('DOMContentLoaded', function () {
                const audio = document.getElementById('bgm');
                const currentPage = window.location.pathname; 
                const desiredPage = "https://rhyneeai.github.io/letter-envelope/envelope.html"; 
                if (currentPage === desiredPage) {
                    audio.play().catch((error) => {
                        console.log('Audio playback failed. User interaction might be required.', error);
                    });
                    audio.volume = 1.0;
                } else {
                    audio.volume = 0.0; 
                }
            });
            
            document.querySelector('.heart').addEventListener('click', function () {
                const heart = this;
                const envelope = document.querySelector('.envelope-wrapper');
                const computedTransform = window.getComputedStyle(heart).transform;

                let letterData = '';
                if (hash_id) {
                    try {
                        fetch(`{{ url('/envelope/get') }}/${hash_id}`, {
                            method: 'GET'
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.success) {
                                console.log("Response:", response.data);
                                letterData = response.data;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal mengirim surat.',
                                    text: 'Silakan coba lagi.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi kesalahan',
                                text: 'Silakan coba lagi.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        });

                        setTimeout(() => {
                            console.log("Letter Data:", letterData);
                            
                            document.getElementById("dear").textContent = `Dear ${letterData.penerima}`;
                            document.getElementById("letter-body").textContent = letterData.body;
                            document.getElementById("sincerely").textContent = `Sincerely, ${letterData.pengirim}`;
                
                            const typingEffect = document.querySelector('.typing-effect');
                            const nameText = `    My Beloved, ${letterData.penerima} 💖`;
                
                            typingEffect.textContent = nameText;
                
                            const canvas = document.createElement('canvas');
                            const context = canvas.getContext('2d');
                
                            const computedStyle = window.getComputedStyle(typingEffect);
                            const font = `${computedStyle.fontSize} ${computedStyle.fontFamily}`;
                            context.font = font;
                            const textWidth = context.measureText(nameText).width;
                
                            typingEffect.style.maxWidth = `${textWidth - 10}px`;
                            typingEffect.style.animation = 'none'; 
                            void typingEffect.offsetWidth;
                            typingEffect.style.animation = `typing 5s steps(${Math.ceil(textWidth / 10)}, end) forwards, blinkCursor 0.5s step-end infinite`;
                        }, 500);
                    } catch (err) {
                        console.error("Failed to parse data:", err);
                        // window.location.href = 'index.html';
                    }
                } else {
                    // window.location.href = 'index.html';
                }
            
            
                const audio = document.getElementById('bgm');
            
                if (!computedTransform.includes('matrix')) {
                    heart.style.animation = 'none';
                    void heart.offsetWidth;
                    heart.style.animation = 'heartOpen 1s ease-in-out forwards';
                    envelope.style.overflow = 'visible';
                    audio.play().catch((error) => {
                        console.log('Audio playback failed. User interaction might be required.', error);
                    });
                } else {
                    heart.style.animation = 'none';
                    void heart.offsetWidth;
                    heart.style.animation = 'heartClose 1s ease-in-out forwards';
            
                    setTimeout(() => {
                        heart.style.animation = 'none';
                        void heart.offsetWidth;
                        heart.style.animation = 'smoothShake 0.9s linear infinite';
                        envelope.style.overflow = 'visible';
                    }, 1000);
                }
            });
        })





        function redirectToDrive() {
            if (confirm("Kamu akan diarahkan ke folder Google Drive! Masukan no pesanan anda di drive ya! Apakah kamu ingin melanjutkan?")) {
                window.location.href = "https://drive.google.com/drive/folders/12Zyvb04BOdORpXw0A3VOiT5RMXjI0Svv?usp=drive_link";
            } else {
                console.log("Pengalihan dibatalkan oleh pengguna.");
            }
        }
    </script>
</body>
</html>