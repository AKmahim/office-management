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
            /* padding: 20px; */
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
            background: linear-gradient(to bottom, #fef5e7, #2f3292);
            /* position: absolute; */
            bottom:0px;
            /* margin-top: 20rem; */
        }
    </style>
</head>
<body>

    <div class="container">
        {{-- <img src="{{ asset('home/icon.png') }}" width="150px" alt="" srcset=""> --}}
        <a href="{{ url('/') }}" rel="noopener noreferrer">
            <img width="300" height="400" src="{{ asset('home/icon.png') }}">
        </a>
        <form action="{{ route('find-content') }}" method="get" class="flex justify-center items-center flex-col mb-10">
            <input type="text" name="content_id"
             class="text-center bg-[#d9d9d9] border-0 px-10 py-2 mb-6 rounded-md" 
             placeholder="Enter Video/Photo ID" required>
             @if (session('error'))
                 <h1 class="text-center text-sm text-[#2f3292] mb-4">
                    {{ session('error') }}
                 </h1>
             @endif
            <button type="submit" 
            class="text-xl text-white font-medium px-6 py-2 rounded-lg bg-[#2f3292]"
            >Search</button>  <!-- Button to trigger the form submission -- !-->
        </form>

        <div class="footer bg-gradient-to-b from-[#fef5e7] to-[#2f3292] rounded-lg flex items-end justify-center h-20 relative">
            <a href="https://xri.com.bd" class="cursor-pointer" target="_blank">
                <p class="text-white text-sm font-thin pb-2">© 2025 - XR Interactive - All rights reserved</p>
            </a>
        </div>
        
    </div>

    
</body>
</html>