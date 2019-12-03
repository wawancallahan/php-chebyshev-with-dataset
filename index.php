<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chebyshev Distance</title>

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">

    <style>
        .container {
            padding-top: 2rem;
        }

        .navbar-header {
            float: left;
            text-align: center;
            width: 100%;
        }

        .navbar-brand {
            float: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Chebyshev Distance</a>
        </div>
    </nav>
    <div class="container">

        <div id="session-alert">
        </div>

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
                $('#session-alert').empty();
                $('#result').empty();
                var form = $(el).closest('form');

                var v1 = form.find('input[name=v1]').val();
                var v2 = form.find('input[name=v2]').val();
                var v3 = form.find('input[name=v3]').val();
                var v4 = form.find('input[name=v4]').val();

                if (v1.trim().length <= 0 || v2.trim().length <= 0 || v3.trim().length <= 0 || v4.trim().length <= 0) {
                    $('#session-alert').html(
                        `<div class="alert alert-danger"><div>Tolong isi semua input</div></div>`
                    );
                    el.removeAttr('disabled');
                    return;
                }

                $.ajax({
                        url: 'proses2.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            v1: v1,
                            v2: v2,
                            v3: v3,
                            v4: v4
                        }
                    })
                    .then(function(response) {

                        var result_text = `
                            <span class="alert alert-${response.result == 1 ? "success" : "danger"}">${response.result_text}</span>
                        `;

                        $('#result').html(
                            `<div class="form-group">
                                <label for="">K : </label> ${response.params.k}
                                <p>*Banyaknya hasil pernyataan yang diambil</p>
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
                                    Hasil : ${result_text}
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