<div class="row">
    <div class="col-md-4 offset-md-4">
        <div class="card" style="min-width: 300px">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Silahkan Log In</p>
                <div class="alert alert-danger alert-dismissible" id="form_alert" style="display: none;">
                    <span id="form_alert_content"></span>
                </div>
                <form id="fm_login">
                    <div class="input-group mb-3">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8"> </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Log In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#username').focus();
});

$('#fm_login').submit(function(e) {
    e.preventDefault();

    if($('#username').val() == '') {
        $('#username').focus()
    } else if($('#password').val() == '') {
        $('#password').focus()
    }

    $.ajax({
        url: situs + "login/masuk",
        data: $('#fm_login').serialize(),
        type: 'post',
        dataType: 'json',
        success: function(data) {
            if (data.status != true) {
                $('#form_alert_content').html(data.msg);
                $('#form_alert').show();
            } else {
                location.reload();
            }
        }
    });
});
</script>