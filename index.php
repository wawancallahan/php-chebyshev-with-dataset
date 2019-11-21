<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chebyshev Distance</title>

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <form action="proses.php" method="POST">
            <div class="form-group">
                <label for="">months since last donation</label>
                <input type="number" min="0" name="v1" class="form-control">
            </div>

            <div class="form-group">
                <label for="">total number of donation</label>
                <input type="number" min="0" name="v2" class="form-control">
            </div>

            <div class="form-group">
                <label for="">total blood donated in c.c.</label>
                <input type="number" min="0" name="v3" class="form-control">
            </div>

            <div class="form-group">
                <label for="">months since first donation</label>
                <input type="number" min="0" name="v4" class="form-control">
            </div>

            <div class="form-group">
                <button type="submit" id="form-submit" class="btn btn-md btn-primary btn-block">Proses</button>
            </div>
        </form>

        <div id="result">

        </div>

    </div>

    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/propper.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>

    <script>
        $(function () {
            $('#form-submit').on('click', function (e) {
                e.preventDefault();

                var el = $(e.currentTarget);

                var form = $(el).closest('form');

                $.ajax({
                   url: 'proses.php',
                   type: 'POST',
                   dataType: 'json', 
                   data: {
                       v1: form.find('input[name=v1]').val(),
                       v2: form.find('input[name=v2]').val(),
                       v3: form.find('input[name=v3]').val(),
                       v4: form.find('input[name=v4]').val()
                   }
                })
                .then(function (response) {

                })
                .catch(function (error) {

                });
            });
        });
    </script>

    <script type="text/template">
        
    </script>
</body>
</html>