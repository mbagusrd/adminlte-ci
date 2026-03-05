<div class="login-container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="login-card-wrapper">
                <!-- Logo Section -->
                <div class="text-center mb-4">
                    <div class="login-logo mb-3">
                        <img src="<?php echo base_url('aset/gambar/apps-logo.png'); ?>" alt="Logo" class="img-fluid" style="max-height: 80px;">
                    </div>
                    <h4 class="login-title">Selamat Datang</h4>
                    <p class="login-subtitle text-muted">Silakan masuk untuk melanjutkan</p>
                </div>

                <!-- Login Card -->
                <div class="card login-card">
                    <div class="card-body p-4 p-md-5">
                        <!-- Alert -->
                        <div class="alert alert-danger alert-dismissible fade" id="form_alert" role="alert" style="display: none;">
                            <span id="form_alert_content"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="fm_login" class="login-form">
                            <!-- Username Input -->
                            <div class="form-group mb-4">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-right-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="username" name="username" class="form-control border-left-0 pl-0" placeholder="Masukkan username" autocomplete="off" autofocus>
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="form-group mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-right-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="password" id="password" name="password" class="form-control border-left-0 pl-0" placeholder="Masukkan password">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-transparent border-left-0 cursor-pointer" id="togglePassword" style="cursor: pointer;">
                                            <i class="fas fa-eye text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                    <label class="custom-control-label text-muted" for="remember">Ingat saya</label>
                                </div>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="btn btn-primary btn-lg btn-block login-btn">
                                <span class="btn-text">Masuk</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        &copy; <?php echo date('Y'); ?> 
                        <?php echo getenv("APP_NAME") ? getenv("APP_NAME") : "ADMINLTE CI"; ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Login Styles -->
<style>
.login-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.login-container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 50px 50px;
    opacity: 0.5;
    animation: backgroundMove 20s linear infinite;
}

@keyframes backgroundMove {
    0% { transform: translate(0, 0); }
    100% { transform: translate(50px, 50px); }
}

.login-card-wrapper {
    position: relative;
    z-index: 1;
}

.login-logo img {
    filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
    transition: transform 0.3s ease;
}

.login-logo:hover img {
    transform: scale(1.05);
}

.login-title {
    font-weight: 700;
    color: #fff;
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
}

.login-subtitle {
    color: rgba(255,255,255,0.8) !important;
    font-size: 0.95rem;
}

.login-card {
    background: rgba(255, 255, 255, 0.98);
    border: none;
    border-radius: 20px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(20px);
    transform: translateY(0);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.3);
}

.form-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.input-group-lg .form-control {
    font-size: 1rem;
    padding: 0.875rem 1rem;
}

.input-group-text {
    border: 2px solid #e5e7eb;
    border-right: none;
    background: #fff !important;
    padding: 0.875rem 1rem;
}

.form-control {
    border: 2px solid #e5e7eb;
    border-left: none;
    border-radius: 0 10px 10px 0 !important;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #4f46e5;
    box-shadow: none;
}

.input-group:focus-within .input-group-text {
    border-color: #4f46e5;
}

.login-btn {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem;
    font-weight: 600;
    font-size: 1rem;
    letter-spacing: 0.025em;
    box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px -5px rgba(79, 70, 229, 0.5);
}

.login-btn:active {
    transform: translateY(0);
}

.login-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.login-btn:active::after {
    width: 300px;
    height: 300px;
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #4f46e5;
    border-color: #4f46e5;
}

.alert {
    border-radius: 10px;
    border: none;
}

@media (max-width: 576px) {
    .login-card {
        margin: 1rem;
    }
    
    .login-card .card-body {
        padding: 1.5rem !important;
    }
    
    .login-title {
        font-size: 1.5rem;
    }
}

/* Loading Animation */
.login-btn.loading {
    pointer-events: none;
}

.login-btn.loading .btn-text::after {
    content: '';
    animation: dots 1.5s infinite;
}

@keyframes dots {
    0%, 20% { content: '.'; }
    40% { content: '..'; }
    60%, 100% { content: '...'; }
}
</style>

<script type="text/javascript">
$(document).ready(function() {
    // Focus on username field
    $('#username').focus();
    
    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const passwordInput = $('#password');
        const icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});

$('#fm_login').submit(function(e) {
    e.preventDefault();
    
    const btn = $('.login-btn');
    const btnText = btn.find('.btn-text');
    const originalText = btnText.text();
    
    // Validation
    if($('#username').val() == '') {
        $('#username').focus();
        $('#username').addClass('is-invalid');
        return false;
    } else {
        $('#username').removeClass('is-invalid');
    }
    
    if($('#password').val() == '') {
        $('#password').focus();
        $('#password').addClass('is-invalid');
        return false;
    } else {
        $('#password').removeClass('is-invalid');
    }
    
    // Loading state
    btn.addClass('loading');
    btnText.text('Memuat');
    btn.prop('disabled', true);
    
    $.ajax({
        url: situs + "login/masuk",
        data: $('#fm_login').serialize(),
        type: 'post',
        dataType: 'json',
        success: function(data) {
            if (data.status != true) {
                $('#form_alert_content').html(data.msg);
                $('#form_alert').show().addClass('show');
                
                // Reset button
                btn.removeClass('loading');
                btnText.text(originalText);
                btn.prop('disabled', false);
                
                // Shake animation
                $('.login-card').addClass('animate__animated animate__shakeX');
                setTimeout(() => {
                    $('.login-card').removeClass('animate__animated animate__shakeX');
                }, 500);
            } else {
                btnText.text('Berhasil!');
                setTimeout(() => {
                    location.reload();
                }, 500);
            }
        },
        error: function() {
            $('#form_alert_content').html('Terjadi kesalahan. Silakan coba lagi.');
            $('#form_alert').show().addClass('show');
            
            // Reset button
            btn.removeClass('loading');
            btnText.text(originalText);
            btn.prop('disabled', false);
        }
    });
});

// Remove invalid class on input
$('#username, #password').on('input', function() {
    $(this).removeClass('is-invalid');
});
</script>
