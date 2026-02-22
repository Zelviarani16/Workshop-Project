<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

@page {
    margin: 0;
}

body {
    margin: 0;
    padding: 0;
}

.container {
    position: relative;
    width: 100%;
    height: 100%;
}

/* background */
.bg {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
}

/* nama user */
.nama {
    position: absolute;
    
    /* DISESUAIKAN */
    top: 42%;
    left: 50%;
    
    transform: translate(-50%, -50%);
    
    font-size: 70px;
    font-weight: bold;
    color: #2c3e50;
}

</style>

</head>
<body>

<div class="container">

    <!-- background image -->
    <img src="{{ public_path('assets/images/sertifikat_workshop.png') }}" class="bg">

    <!-- nama user dari login -->
    <div class="nama">
        {{ Auth::user()->name }}
    </div>

</div>

</body>
</html>
