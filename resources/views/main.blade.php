<html>
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @if($is_mobile)
        @vite(['resources/css/app.css', 'resources/js/mobile_app.js'])
    @endif
    @if(!$is_mobile)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
     <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<div id="app">
    <main-comp></main-comp>
</div>
</body>

</html>
