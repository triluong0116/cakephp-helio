<div class="row">
    <?= $this->Flash->render() ?>
    <?php echo $this->Form->create(); ?>
    <h1>Quên mật khẩu</h1>
    <div>
        <input type="text" class="form-control" placeholder="Email" name="email" required=""/>
    </div>
    <div>
        <button class="btn btn-default submit" type="submit">Gửi lại mật khẩu</button>
    </div>
    <div class="clearfix"></div>
    <?php echo $this->Form->end(); ?>
</div>
