<html>

<head>

</head>

<body>
    <form method="post" action="file.php" enctype="multipart/form-data">
        <input type="file" name="file" onchange="test(this)">
        <input type="submit">
    </form>
</body>
<script>
    function test(file) {
        var reader = new FileReader(),
            image = new Image();

        console.log(reader.readAsDataURL(file));
        reader.onload = function(_file) {
            image.src = _file.target.result;
            
            image.onload = function() {
//                context.drawImage(image, 0, 0, ($('#section01').width() - 40), ($('#section01').height() - 120));
            };
        }
    }

</script>

</html>
