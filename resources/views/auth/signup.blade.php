<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quzquz - Sign Up</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.min.css" rel="stylesheet" />

    <style>
        /* Styling untuk mengisi seluruh viewport dan menengahkan form */
        body,
        html {
            height: 100%;
            margin: 0;
            background-color: #f8f9fa;
            /* Warna background sedikit abu-abu muda */
        }

        .form-signup-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>

    <div class="container form-signup-container">
        <div class="row w-100 justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-5-strong">
                    <div class="card-body p-5">
                        <h3 class="mb-4 text-center">Buat Akun Quzquz</h3>

                        <form method="POST" action="{{ route('signup.register') }}">
                            @csrf

                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required autocomplete="name" autofocus />
                                <label class="form-label" for="name">Nama Lengkap</label>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required autocomplete="email" />
                                <label class="form-label" for="email">Alamat Email</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required
                                    autocomplete="new-password" />
                                <label class="form-label" for="password">Kata Sandi</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mb-4" style="background-color: #234C6A;">
                                Sign Up
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-block w-100 mb-4">Kembali</a>
                            <div class="text-center">
                                <p>Sudah punya akun? <a href="{{ route('login') }}"
                                        class="text-primary fw-bold">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
</body>

</html>