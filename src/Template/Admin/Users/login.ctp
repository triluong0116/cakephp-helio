<!--begin::Login Sign in form-->
<div class="login-signin"">
    <div class="mb-20">
        <h3>Đăng nhập vào quản trị viên</h3>
        <div class="text-muted font-weight-bold">Nhập thông tin để đăng nhập vào tài khoản của bạn</div>
    </div>
    <?php echo $this->Form->create(); ?>
    <div class="form-group mb-5">
        <input class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="Username" name="username" autocomplete="off"/>
    </div>
    <div class="form-group mb-5">
        <input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="Password" name="password"/>
    </div>
    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
        <div class="checkbox-inline">
            <label class="checkbox m-0 text-muted">
                <input type="checkbox" name="remember"/>
                <span></span>Ghi nhớ đăng nhập</label>
        </div>
        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'forget_password']) ?>" id="kt_login_forgot" class="text-muted text-hover-primary">Quên mật khẩu</a>
    </div>
    <button id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Đăng nhập</button>
    <?php echo $this->Form->end(); ?>
    <!--                    <div class="mt-10">-->
    <!--                        <span class="opacity-70 mr-4">Don't have an account yet?</span>-->
    <!--                        <a href="javascript:;" id="kt_login_signup" class="text-muted text-hover-primary font-weight-bold">Sign Up!</a>-->
    <!--                    </div>-->
</div>
<!--end::Login Sign in form-->
