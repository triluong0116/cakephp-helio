<?php echo $this->Form->create(); ?>
    <h1>Đăng Nhập</h1>
    <div>
        <input type="text" class="form-control" placeholder="Username" name="username" required="" />
    </div>
    <div>
        <input type="password" class="form-control" placeholder="Password" name="password" required="" />
    </div>
    <div>
        <button class="btn btn-default submit">Log in</button>
    </div>

    <div class="clearfix"></div>    
<?php echo $this->Form->end(); ?>
<hr />
<?php 
$helper = $fbGlobal->getRedirectLoginHelper();
$baseUrl = $this->Url->build('/', true);
$permissions = ['email', 'user_location', 'user_birthday', 'manage_pages', 'publish_pages'];
$loginUrl = $helper->getLoginUrl($baseUrl . 'agency/users/login_via_fb', $permissions);

?>
<a class="btn btn-primary login-facebook" href=" <?= htmlspecialchars($loginUrl) ?>"> <i class="fa fa-facebook"></i> Đăng nhập với Facebook!</a>