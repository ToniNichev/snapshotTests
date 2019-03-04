<html>
<head>
    <style>
        .panel {
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="panel">
        <img id="imgOld" src=""  />
        <img id="imgNew" src="" />
    </div>


    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        document.getElementById("imgOld").src = "screenshots/scr" + id + "-old.png";
        document.getElementById("imgNew").src = "screenshots/scr" + id + ".png";
    </script>    
</body>
</html>