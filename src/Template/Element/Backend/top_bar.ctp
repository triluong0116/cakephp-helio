<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?= $this->Url->assetUrl('backend/img/user.png') ?>" alt=""><?= $this->request->getSession()->read('Auth.User.screen_name') ?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'change_password']) ?>"><i class="fa fa-lock pull-right"></i> Đổi mật khẩu </a></li>
                        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                    </ul>
                </li>

                <li role="presentation" class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-green"></span>
                    </a>
                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->