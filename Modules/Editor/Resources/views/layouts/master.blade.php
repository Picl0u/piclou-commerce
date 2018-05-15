<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Module Editor</title>

        <link href="{{ asset('css/editor.css') }}" rel="stylesheet">

    </head>
    <body>

        <div class="editor-right-actions">

            <div class="action edit-dom active">
                <i class="fas fa-mouse-pointer"></i>
            </div>
            <div class="action search-dom">
                <i class="fas fa-search"></i>
            </div>
            <div class="action edit-css">
                <i class="fas fa-code"></i>
            </div>

            <div class="action responsive-mode">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <div class="action pixel-mode">
                <i class="fas fa-crop"></i>
            </div>

            <div class="action undo">
                <i class="fas fa-undo"></i>
            </div>
            <div class="action redo">
                <i class="fas fa-redo"></i>
            </div>

        </div>

        <div class="piclou-metric-div"></div>
        <div class="axe-draw-x"></div>
        <div class="axe-draw-y"></div>

        @yield('content')

        <!-- JavaScript -->
        <script src="{{ asset('js/editor.js') }}"></script>
    </body>
</html>
