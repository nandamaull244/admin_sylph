<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sylph Letter</title>
    <link rel="icon" href="{{ asset('assets') }}/images/envelope/pavicon.png" type="image/x-icon" width="146" height="146">
    {{-- <link rel="stylesheet" href="css/index.css"> --}}
    <style>
        body {
            font-family: 'Caveat', cursive;
            background-color: #ffebf0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .valentine-frame {
            background: #ffe3e8;
            border: 5px solid #ffb3c6;
            border-radius: 20px;
            padding: 30px 50px 30px 30px;
            margin: 5px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(192, 89, 89, 0.5);
            text-align: center;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
        }

        h1 {
            color: #ff4d73;
            margin: 5px 0 30px 0;
            text-align: center;
            justify-content: center;
            display: flex;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            color: #ff4d73;
            font-size: 1.2em;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ff99aa;
            border-radius: 10px;
            background-color: #fff;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #ff4d73;
            box-shadow: 0 0 5px rgba(255, 77, 115, 0.5);
        }

        textarea {
            resize: none;
            height: 100px;
        }

        button {
            background-color: #ff4d73;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1em;
        }

        button:hover {
            background-color: #ff1c49;
        }

        button:active {
            background-color: #cc173d;
        }

        #qrModal {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease forwards;
        }

        .modal-content {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            position: relative;
            transform: scale(0.8);
            opacity: 0;
            animation: slideUp 0.3s ease forwards;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 300px;
            animation-fill-mode: forwards;
        }

        #qrcode {
            margin: 2rem auto;
            text-align: center;
            display: flex;
            justify-content: center;
            border: 2px dotted lightgrey;
            padding: 20px;
        }

        .modal-btn {
            background-color: #156082;
            color: #fff;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            margin: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        .modal-btn:hover {
            background-color: #104c67;
        }

        .modal-btn:active {
            transform: scale(0.95);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(0); opacity: 1; }
            to { transform: translateY(50px); opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="valentine-frame">
        <h1>💌 Send a Letter 💌</h1>
        <form id="valentine-form">
            <div class="form-group">
                <label for="pengirim">From:</label>
                <input type="text" id="pengirim" name="pengirim" placeholder="Your Name" required>
            </div>
            <div class="form-group">
                <label for="penerima">To:</label>
                <input type="text" id="penerima" name="penerima" placeholder="Recipient's Name" required>
            </div>
            <div class="form-group">
                <label for="body">Message:</label>
                <textarea id="body" name="body" placeholder="Write your heartfelt message here..." required></textarea>
            </div>

            <button type="button" id="submit-btn">Send Letter ❤️</button>
        </form>
    </div>

    <div id="qrModal">
        <div class="modal-content">
            <h3>Scan this QR Code</h3>
            <div id="qrcode"></div>
            <div id="qrcode-download" style="display: none; visibility: hidden;"></div>
            <button id="download-btn">Download QR</button>
            <button id="close-btn">Close</button>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="js/index.js"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("qrModal");
            const modalContent = modal.querySelector(".modal-content");
            const qrContainer = document.getElementById("qrcode");
            const qrDownload = document.getElementById("qrcode-download");
            const downloadBtn = document.getElementById("download-btn");
            let currentPenerima = "";

            modal.style.display = "none";

            function showModal() {
                modal.style.opacity = 0;
                modal.style.display = "flex";

                requestAnimationFrame(() => {
                    modal.style.animation = "fadeIn 0.3s ease forwards";
                    modalContent.style.animation = "slideUp 0.3s ease forwards";
                });
            }

            function hideModal() {
                modalContent.style.animation = "slideDown 0.2s ease forwards";
                modal.style.animation = "fadeOut 0.2s ease forwards";
                setTimeout(() => {
                    modal.style.display = "none";
                    modal.style.animation = "";
                    modalContent.style.animation = "";
                }, 200);
            }

            document.getElementById('close-btn').addEventListener('click', () => {
                qrContainer.innerHTML = "";
                hideModal();
            });

            document.getElementById("submit-btn").addEventListener("click", () => {
                const pengirim = document.getElementById("pengirim").value;
                const penerima = document.getElementById("penerima").value;
                const body = document.getElementById("body").value;

                if (!pengirim || !penerima || !body) {
                    alert("Lengkapi semua kolom terlebih dahulu.");
                    return;
                }
                let fullUrl = "";
                const formData = new FormData();
                formData.append('pengirim', pengirim);
                formData.append('penerima', penerima);
                formData.append('body', body);

                fetch("{{ url('/envelope-store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Surat berhasil dikirim!',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        console.log("Response Data:", response);
                        
                        fullUrl = `{{ url('/envelope-reciper') }}?data=${encodeURIComponent(response.data)}`;
                        // Bisa diarahkan ke fullUrl jika perlu:
                        // window.location.href = fullUrl;
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


                // const json = JSON.stringify({ pengirim, penerima, body });
                // const encoded = encodeURIComponent(json);
                // const fullUrl = `{{ url('/envelope-reciper') }}?data=${encoded}`;

                // currentPenerima = penerima;
                qrContainer.innerHTML = "";
                qrDownload.innerHTML = "";

                showModal();
                console.log("QR Code URL:", fullUrl);
                

                setTimeout(() => {
                    new QRCode(qrContainer, {
                        text: fullUrl,
                        width: 256, 
                        height: 256,
                        correctLevel: QRCode.CorrectLevel.H
                    });

                }, 300); 
                
                setTimeout(() => {
                    new QRCode(qrDownload, {
                        text: fullUrl,
                        width: 384, 
                        height: 384,
                        correctLevel: QRCode.CorrectLevel.H
                    });
                }, 300); 
            });

            downloadBtn.addEventListener("click", () => {
                const img = qrDownload.querySelector("img") || qrDownload.querySelector("canvas");
                if (!img) {
                    alert("QR Code belum siap. Coba lagi sebentar.");
                    return;
                }

                img.style.padding = "20px"
                let dataUrl = (img.tagName.toLowerCase() === "img") ? img.src : img.toDataURL("image/png");

                const a = document.createElement("a");
                a.href = dataUrl;
                a.download = "Sylph.art - " + currentPenerima + ".png";
                a.click();
            });
        });

    </script>
</body>
</html>
