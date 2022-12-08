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
        <a class="btn text-dark fs12" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'forget_password']) ?>">Quên mật khẩu ?</a>
    </div>
    <div class="clearfix"></div>
<?php echo $this->Form->end(); ?>