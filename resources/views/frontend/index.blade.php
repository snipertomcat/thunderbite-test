<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">Q                                                                                                                                                                    A
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Thunderbite</title>

    <script>
        var config = {!! $config !!};
        document.addEventListener('readystatechange', event => {
            // When window loaded ( external resources are loaded too- `css`,`src`, etc...)
            if (event.target.readyState === "complete") {
                const hiddenInput = document.createElement('input');
                const revealedTiles = config.revealedTiles;
                hiddenInput.id = "segment";
                hiddenInput.type = "hidden";
                hiddenInput.value = config.segment;
                document.body.appendChild(hiddenInput);
            }
        });
    </script>

</head>

<body>

<script type="text/javascript" crossorigin="anonymous" src="{{ asset('js/game.js') }}"></script>

</body>

</html>
