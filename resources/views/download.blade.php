<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ReflectX</title>
    <link rel="shortcut icon" href="{{ asset('home/xri-logo.svg') }}" type="image/x-icon">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body {
            background-color: #eaeaea;
        }

        .container{
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            min-height: 100vh;
            position: relative;
        }

        .footer{
            /* background-color:  */
            min-height: 150px;
            width: 100%;
            background: linear-gradient(to bottom, #fef5e7, #7c2fe8);
            /* position: absolute;
            bottom:0px; */
            /* margin-top: 20rem; */
            border-radius: 10px;
        }
        button{
            cursor: pointer;
        }
    </style>
</head>
<body>

    @php
        $contentUrl = asset($contents->content_url);
        $extension = strtolower(pathinfo($contents->content_url, PATHINFO_EXTENSION));
        $isVideo = in_array($extension, ['mp4', 'webm', 'ogg','mov']);
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
    @endphp
    <div class="container">
        <img src="{{ asset('home/xri-logo.svg') }}" width="150px" alt="" srcset="">
        {{-- <a href="{{ url('/') }}" rel="noopener noreferrer">
            <img width="300" height="400" src="{{ asset('home/img-3.png') }}">
        </a> --}}
        <div class="w-1/2 m-10 h-[250px] mb-10 flex flex-col justify-center ">
            @if($isVideo)
                <video id="media" class="w-full h-full mb-10" autoplay controls src="{{ $contentUrl }}"></video>
            @elseif($isImage)
                <img id="media" class="w-full h-full mb-10 object-contain" src="{{ $contentUrl }}" alt="Content Image">
            @else
                <p>Unsupported file type.</p>
            @endif
            <button 
                onclick="downloadMedia()" 
                class="text-xl text-center text-white font-medium px-6 py-2 rounded-lg bg-[#7c2fe8] cursor"
            >Download</button>
        </div>
        <br>
        <br>
        <br>
        <div class="footer mt-10" class=" bg-linear-to-b from-[#fef5e7] to-[#f68b00]">
        </div>
    </div>

    <script>
        function downloadMedia() {
            let media = document.getElementById("media");
            let url = media.getAttribute("src");
            let extension = url.split('.').pop().split(/\#|\?/)[0];
            let filename = "downloaded-content." + extension;

            // Create a temporary <a> element
            let a = document.createElement("a");
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>

    
</body>
</html>