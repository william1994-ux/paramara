<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        
        </style>
        <!-- <script>
        function onClick(e){
            e.preventDefault();
            fetch("https://df9daf247271.ngrok.io/api//pay");
        }
        </script> -->
    </head>
    <body>
    <div style="padding: 50px 0;text-align: center;">
    <h1> Payment </h1>
        <!-- <form action="/pay" class="mt-10">
                @csrf
                    <button type="submit"> Pay </button>
        </form> -->

        <a href="{{ url('/pay') }}"> pay </a>
    </div>
    </body>
</html>
