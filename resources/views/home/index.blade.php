<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>3D Kreasi Coklat</title>
        <style>
            /* CSS */
            body {
                font-family: 'Poppins', sans-serif;
                margin: 0;
                background-image: url('{{ asset('images/background.png') }}');
                color: white;
            }

            /* Style untuk section utama */
            #home-section {
                min-height: 100vh;
                background: url('{{ asset('images/background.jpg') }}') no-repeat center center/cover;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 40px;
                position: relative;
            }

            /* Style untuk overlay gelap */
            /* #home-section::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.5);
                z-index: 1;
            } */

            /* Style untuk container di dalam section utama */
            #home-section .home-container {
                position: relative;
                z-index: 2;
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 90%;
                max-width: 1200px;
                background: rgba(255, 255, 255, 0.5);
                
                padding: 20px;
                border-radius: 10px;
            }

            /* Style untuk content di dalam container */
            #home-section .home-content {
                width: 50%;
                text-align: left;
                padding: 20px;
            }

            /* Style untuk judul (h1) */
            #home-section .home-content h1 {
                font-size: 2.5em;
                margin-bottom: 20px;
                font-weight: 700;
                /* color: rgb(0, 132, 255) */
                color: black
            }

            /* Style untuk paragraf (p) */
            #home-section .home-content p {
                font-size: 1.2em;
                line-height: 1.6;
                margin-bottom: 30px;
                color: black
            }

            /* Style untuk tombol explore */
            #home-section .explore-button {
                background-color: #5293e6;
                color: black;
                padding: 15px 30px;
                border: none;
                cursor: pointer;
                font-size: 1.2em;
                border-radius: 8px;
                transition: background-color 0.3s ease;
                font-weight: 500;
            }

            #home-section .explore-button:hover {
                background-color: #1c3d72;
            }

            /* Style untuk gambar */
            #home-section .home-background-image {
                width: 40%;
            }

            #home-section .home-background-image img {
                width: 100%;
                height: auto;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            /* Media query untuk layar yang lebih kecil */
            @media (max-width: 768px) {
                #home-section .home-container {
                    flex-direction: column;
                    text-align: center;
                }

                #home-section .home-content, #home-section .home-background-image {
                    width: 100%;
                    padding: 0;
                }

                #home-section .home-background-image {
                    margin-top: 20px;
                }
            }
        </style>
    </head>
    <body>
        <!-- Pastikan navbar tidak terpengaruh oleh CSS lainnya -->
        <div id="navbar">
            <!-- Isi navbar di sini, jika ada -->
        </div>

        <!-- Main Section -->
        <div id="home-section">
            <div class="home-container">
                <div class="home-content">
                    <h1>"Kreasikan Imajinasi Anda dalam Bentuk Coklat"</h1>
                    <p>Selamat datang di 3D Kreasi Coklat, tempat di mana ide-ide manis Anda menjadi kenyataan dengan teknologi 3D printing tercanggih. Dari desain unik hingga detail yang memukau, biarkan kami membantu Anda menciptakan cetakan coklat yang sempurna untuk setiap kesempatan.</p>
                    <a href="{{ route('3d_models') }}">
                    <button  class="explore-button">Explore More</button>

                    </a>
                </div>
                <div class="home-background-image">
                    <img src="/images/home.png" alt="Cetakan Coklat 3D">
                </div>
            </div>
        </div>
    </body>
    </html>
</x-app-layout>
