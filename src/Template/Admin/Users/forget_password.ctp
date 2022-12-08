<!--begin::Login forgot password form-->
<div class="login-forgot-new">
    <div class="mb-20">
        <h3>Forgotten Password ?</h3>
        <div class="text-muted font-weight-bold">Enter your email to reset your password</div>
    </div>
    <?php echo $this->Form->create(); ?>
        <div class="form-group mb-10">
            <input class="form-control form-control-solid h-auto py-4 px-8" type="text" placeholder="Email" name="email" autocomplete="off"/>
        </div>
        <div class="form-group d-flex flex-wrap flex-center mt-10">
            <button id="kt_login_forgot_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">Request</button>
            <button id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2">Cancel</button>
        </div>
    <?php echo $this->Form->end(); ?>
</div>
<!--end::Login forgot password form-->
