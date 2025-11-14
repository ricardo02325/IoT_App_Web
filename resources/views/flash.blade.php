<!DOCTYPE html>
<html>
<head>
    <title>Flashear Photon</title>
</head>
<body>

<h2>Flashear firmware al Photon</h2>

<form action="/flash" method="POST">
    @csrf
    <button type="submit" style="padding:15px; font-size:18px;">
        Flashear ahora
    </button>
</form>

</body>
</html>