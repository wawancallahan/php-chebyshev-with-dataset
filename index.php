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
                <label for="">Berapa bulan sejak terakhir donasi</label>
                <input type="number" min="0" name="v1" class="form-control" placeholder="Ex. 1" required>
            </div>

            <div class="form-group">
                <label for="">Total berapa kali donasi</label>
                <input type="number" min="0" name="v2" class="form-control" placeholder="Ex. 6" required>
            </div>

            <div class="form-group">
                <label for="">Total darah yang didonasikan dalam c.c.</label>
                <input type="number" min="0" name="v3" class="form-control" placeholder="Ex. 2000" required>
            </div>

            <div class="form-group">
                <label for="">Berapa bulan sejak pertama kali donasi</label>
                <input type="number" min="0" name="v4" class="form-control" placeholder="Ex. 7" required>
            </div>

            <div class="form-group">
                <button type="submit" id="form-submit" class="btn btn-md btn-primary btn-block">Proses</button>
            </div>
        </form>

        <div id="result"></div>

    </div>

    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/popper.js"></script>
    <script src="./assets/js/bootstrap.js"></script>

    <script>
        $(function() {
            $('#form-submit').on('click', function(e) {
                e.preventDefault();
                var el = $(e.currentTarget);
                el.attr('disabled', 'disabled');

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
                    .then(function(response) {
                        $('#result').html(
                            `<div class="form-group">
                                <label for="">K : </label> ${response.params.k}
                            </div>
                            <div class="form-group">
                                <label for="">Parameter : </label>
                                <div>
                                    <label for="">V1 : </label> ${response.input.v1}
                                </div>
                                <div>
                                    <label for="">V2 : </label> ${response.input.v2}
                                </div>
                                <div>
                                    <label for="">V3 : </label> ${response.input.v3}
                                </div>
                                <div>
                                    <label for="">V4 : </label> ${response.input.v4}
                                </div>
                            </div>
                            <div class="form-group">
                                <div>
                                    Hasil : ${response.result_text}
                                </div>
                            </div>`
                        );

                        el.removeAttr('disabled');
                    })
                    .catch(function(error) {
                        $('#result').html(
                            `<div class="alert alert-danger"><p>${error.responseText}</p></div>`
                        );

                        el.removeAttr('disabled');
                    });
            });
        });
    </script>
</body>

</html>