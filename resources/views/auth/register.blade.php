<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Register - ProdukApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <!-- FontAwesome CDN untuk toggle icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #ff4e50, #f9d423);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        .register-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            animation: fadeInUp 1s ease-out;
            background: #fff;
        }
        .register-header {
            background-color: #d72638;
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid #ff3c3c;
        }
        .register-header h3 {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #d72638;
            border: none;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #b71c2c;
        }
        .footer-text {
            font-size: 14px;
            color: #666;
        }
        @keyframes fadeInUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Tambahan untuk toggle password */
        .position-relative {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #d72638;
            font-size: 1.2rem;
            user-select: none;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: #b71c2c;
        }

        /* Keterangan kriteria password */
        .password-criteria {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
            margin-bottom: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6">
            <div class="card register-card">
                <div class="register-header">
                    <h3>🔥 Daftar Dulu Yuk!</h3>
                    <p>Isi datanya, gratis dan cepat 🚀</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ url('/register') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">👤 Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">📧 Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3 position-relative">
                            <label class="form-label">🔒 Password</label>
                            <input type="password" name="password" class="form-control pe-5" id="password" required>
                            <span id="password-toggle" class="password-toggle">
                                <i class="fa-solid fa-eye"></i>
                            </span>
                            <div class="password-criteria">
                                Password harus 6 Karakter dan mengandung minimal 1 angka dan 1 huruf besar.
                            </div>
                            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">🔁 Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">📝 Daftar Sekarang!</button>
                        </div>
                    </form>
                </div>
                <div class="text-center p-3">
                    <p class="footer-text">Sudah punya akun? 👉 <a href="{{ url('/login') }}">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- JQuery dan JS toggle -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#password-toggle').on('click', function () {
            const passwordField = $('#password');
            const icon = $(this).find('i');
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Validasi sederhana client-side password (angka & huruf besar)
        $('form').on('submit', function(e) {
            const pw = $('#password').val();
            const hasUpper = /[A-Z]/.test(pw);
            const hasNumber = /\d/.test(pw);
            if (!hasUpper || !hasNumber) {
                e.preventDefault();
                alert('Password harus 6 Karakter dan mengandung minimal 1 angka dan 1 huruf besar.');
                $('#password').focus();
            }
        });
    </script>
</body>
</html>
