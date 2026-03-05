<div class="">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="login-card-wrapper">
                <!-- Logo Section -->
                <div class="text-center mb-4">
                    <div class="login-logo mb-3">
                        <img src="<?php echo base_url('aset/gambar/apps-logo.png'); ?>" alt="Logo" class="img-fluid" style="max-height: 70px;">
                    </div>
                    <h4 class="login-title">Selamat Datang</h4>
                    <p class="login-subtitle">Silakan masuk untuk melanjutkan</p>
                </div>

                <!-- Login Card -->
                <div class="card login-card">
                    <div class="card-body p-4">
                        <!-- Alert -->
                        <div class="alert alert-danger" id="form_alert" role="alert" style="display: none;">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span id="form_alert_content"></span>
                        </div>

                        <form id="fm_login" class="login-form">
                            <!-- Username Input -->
                            <div class="form-group mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" autocomplete="off" autofocus>
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                            <i class="fas fa-eye text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                    <label class="custom-control-label text-muted" for="remember">Ingat saya</label>
                                </div>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="btn btn-primary btn-block login-btn" id="btnLogin">
                                <span class="btn-text">Masuk</span>
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

<!-- Custom Login Styles - FLAT DESIGN -->
<style>
.login-container {
    background: #f1f5f9;
    min-height: 100vh;
}

.login-logo img {
    filter: none;
    transition: transform 0.2s ease;
}

.login-logo:hover img {
    transform: scale(1.02);
}

.login-title {
    font-weight: 600;
    color: #1e293b;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.login-subtitle {
    color: #64748b;
    font-size: 0.9375rem;
}

.login-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.form-label {
    font-weight: 500;
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

/* Input Group Styling */
.input-group {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.input-group:focus-within {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.input-group-text {
    border: none;
    background: #f8fafc;
    color: #64748b;
    padding: 0.75rem 1rem;
}

.input-group-prepend .input-group-text {
    border-right: 1px solid #e2e8f0;
}

.input-group-append .input-group-text {
    border-left: 1px solid #e2e8f0;
}

.form-control {
    border: none;
    background: #fff;
    transition: all 0.2s ease;
    padding: 0.75rem 1rem;
}

.form-control:focus {
    border-color: transparent;
    box-shadow: none;
    background: #fff;
}

/* Remove default input group border from children */
.input-group > .form-control,
.input-group > .input-group-prepend > .input-group-text,
.input-group > .input-group-append > .input-group-text {
    border-radius: 0;
}

.input-group > .input-group-prepend:first-child > .input-group-text {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

.input-group > .input-group-append:last-child > .input-group-text {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

.login-btn {
    background: #2563eb;
    border: none;
    border-radius: 10px;
    padding: 0.75rem;
    font-weight: 500;
    font-size: 0.9375rem;
    transition: all 0.2s ease;
}

.login-btn:hover {
    background: #1d4ed8;
}

.login-btn:disabled {
    background: #93c5fd;
    cursor: not-allowed;
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #2563eb;
    border-color: #2563eb;
}

.alert {
    border-radius: 10px;
    border: none;
    font-size: 0.875rem;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
}

@media (max-width: 576px) {
    .login-card {
        margin: 0 1rem;
    }
    
    .login-card .card-body {
        padding: 1.5rem !important;
    }
    
    .login-title {
        font-size: 1.375rem;
    }
}

/* Shake Animation for error */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.shake {
    animation: shake 0.5s ease-in-out;
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
    
    const btn = $('#btnLogin');
    const btnText = btn.find('.btn-text');
    const originalText = btnText.text();
    
    // Validation
    let hasError = false;
    
    if($('#username').val() == '') {
        $('#username').focus();
        $('#username').addClass('is-invalid');
        hasError = true;
    } else {
        $('#username').removeClass('is-invalid');
    }
    
    if($('#password').val() == '') {
        if (!hasError) $('#password').focus();
        $('#password').addClass('is-invalid');
        hasError = true;
    } else {
        $('#password').removeClass('is-invalid');
    }
    
    if (hasError) return false;
    
    // Loading state
    btn.prop('disabled', true);
    btnText.text('Memuat...');
    
    $.ajax({
        url: situs + "login/masuk",
        data: $('#fm_login').serialize(),
        type: 'post',
        dataType: 'json',
        success: function(data) {
            if (data.status != true) {
                $('#form_alert_content').html(data.msg);
                $('#form_alert').show();
                
                // Shake animation
                $('.login-card').addClass('shake');
                setTimeout(() => {
                    $('.login-card').removeClass('shake');
                }, 500);
                
                // Reset button
                btn.prop('disabled', false);
                btnText.text(originalText);
            } else {
                btnText.text('Berhasil!');
                setTimeout(() => {
                    location.reload();
                }, 300);
            }
        },
        error: function() {
            $('#form_alert_content').html('Terjadi kesalahan. Silakan coba lagi.');
            $('#form_alert').show();
            
            // Reset button
            btn.prop('disabled', false);
            btnText.text(originalText);
        }
    });
});

// Remove invalid class on input
$('#username, #password').on('input', function() {
    $(this).removeClass('is-invalid');
});
</script>
