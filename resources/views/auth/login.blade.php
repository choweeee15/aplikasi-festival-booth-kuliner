<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - BengkelApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff4e50, #f9d423);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        .login-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            animation: fadeInUp 0.8s ease-out;
            background: #fff;
        }
        .login-header {
            background-color: #d72638;
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid #ff3c3c;
        }
        .login-header h3 {
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
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-5">
            <div class="card login-card">
                <div class="login-header">
                    <h3>🔧 Selamat Datang Kembali!</h3>
                    <p>Masuk ke BengkelApp untuk melanjutkan</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ url('/login') }}" id="loginForm" autocomplete="off">
    @csrf
    <div class="mb-3">
        <label class="form-label">📧 Email</label>
        <input
            type="email"
            name="email"
            class="form-control"
            value="{{ old('email') }}"
            required
            autocomplete="email"
            autofocus
        >
        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    

    <div class="mb-3 position-relative">
  <label class="form-label">🔒 Password</label>
  <input type="password" class="form-control" id="password" name="password" required>
  <span id="password-toggle" class="password-toggle" title="Tampilkan / sembunyikan password" role="button" tabindex="0" aria-label="Toggle password visibility">
    <i class="fas fa-eye"></i>
  </span>
</div>

<style>
.password-toggle {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #d72638;
    font-size: 1.2rem;
    user-select: none;
    transition: color 0.3s;
    z-index: 1000;
}
.password-toggle:hover,
.password-toggle:focus {
    color: #b71c2c;
    outline: none;
}
</style>

<script>
document.getElementById('password-toggle').addEventListener('click', function () {
  const pw = document.getElementById('password');
  const icon = this.querySelector('i');
  if (pw.type === 'password') {
    pw.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    pw.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
});
</script>


    <!-- Captcha Stylish -->
    <div class="mb-3">
        <label class="form-label">🛡️ Verifikasi Captcha</label>
        <div class="d-flex align-items-center gap-2 captcha-container">
            <div id="captchaCode" class="captcha-box"></div>
            <button type="button" id="refreshCaptcha" class="btn btn-outline-secondary btn-sm" title="Refresh Captcha" aria-label="Refresh Captcha">
                &#x21bb;
            </button>
        </div>
        <input type="text" name="captcha" id="captchaInput" class="form-control mt-2" placeholder="Masukkan kode captcha" required />
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">🚀 Masuk Sekarang</button>
    </div>
</form>

<style>
    /* Styling untuk kotak captcha */
    .captcha-container {
        user-select: none;
    }
    .captcha-box {
        padding: 10px 20px;
        font-weight: 700;
        font-family: 'Courier New', Courier, monospace;
        font-size: 1.3rem;
        background-color: #f1f3f5;
        border: 1.5px solid #ced4da;
        border-radius: 8px;
        letter-spacing: 4px;
        color: #495057;
        min-width: 130px;
        text-align: center;
    }
    #refreshCaptcha {
        cursor: pointer;
        transition: color 0.3s;
    }
    #refreshCaptcha:hover {
        color: #0d6efd;
        background-color: #e7f1ff;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    function generateCaptcha() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        let captcha = '';
        for (let i = 0; i < 5; i++) {
            captcha += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return captcha;
    }

    let currentCaptcha = generateCaptcha();
    $('#captchaCode').text(currentCaptcha);

    $('#refreshCaptcha').click(function () {
        currentCaptcha = generateCaptcha();
        $('#captchaCode').text(currentCaptcha);
        $('#captchaInput').val('').focus();
    });

    $('#loginForm').on('submit', function (e) {
        let userCaptcha = $('#captchaInput').val().trim().toUpperCase();
        if (userCaptcha !== currentCaptcha) {
            e.preventDefault();
            alert('Captcha salah, silakan coba lagi.');
            currentCaptcha = generateCaptcha();
            $('#captchaCode').text(currentCaptcha);
            $('#captchaInput').val('').focus();
        }
    });
});
</script>

                </div>
                <div class="text-center p-3">
                    <p class="footer-text">Belum punya akun? 👉 <a href="{{ url('/register') }}">Daftar di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
