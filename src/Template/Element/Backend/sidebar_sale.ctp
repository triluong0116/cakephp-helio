<li><a><i class="fa fa-user"></i> Người dùng <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'add']) ?>">Thêm mới</a></li>
        <?php if($this->request->session()->read('Auth.User.role_id') == 5): ?>
            <li><a href="<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'addLandtourAgencyPrice']) ?>">Thêm giá Đại lý</a></li>
        <?php endif; ?>
    </ul>
</li>
<li><a><i class="fa fa-shopping-cart"></i> Đơn hàng <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <?php if($this->request->session()->read('Auth.User.role_id') == 5): ?>
            <li><a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'manageLandtour']) ?>">Quản lý điều hành Landtour</a></li>
        <?php endif; ?>
        <li><a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'index']) ?>">Danh sách</a></li>
        <?php if($this->request->session()->read('Auth.User.role_id') == 2): ?>
            <li><a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'indexVin']) ?>">Danh sách Vin</a></li>
        <?php endif; ?>
        <li><a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'addNew']) ?>">Thêm booking</a></li>
        <?php if($this->request->session()->read('Auth.User.role_id') == 2): ?>
            <li><a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'addNewVinpearl']) ?>">Thêm booking Vin</a></li>
        <?php endif; ?>
    </ul>
</li>
<?php if ($this->request->session()->read('Auth.User.role_id') == 1 || $this->request->session()->read('Auth.User.role_id') == 4): ?>
    <li><a><i class="fa fa-building"></i> Khách sạn <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'index']) ?>">Danh sách</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'add']) ?>">Thêm mới</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'addPrice']) ?>">Thêm giá hạng phòng</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'expiredHotel']) ?>">Khách sạn sắp hết hạn</a></li>
        </ul>
    </li>
    <li><a><i class="fa fa-hotel"></i> Homestay <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="<?= $this->Url->build(['controller' => 'HomeStays', 'action' => 'index']) ?>">Danh sách</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'HomeStays', 'action' => 'add']) ?>">Thêm mới</a></li>
        </ul>
    </li>
    <li><a><i class="fa fa-th-large"></i> Combo <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="<?= $this->Url->build(['controller' => 'Combos', 'action' => 'index']) ?>">Danh sách</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'Combos', 'action' => 'add']) ?>">Thêm mới</a></li>
        </ul>
    </li>
    <li><a><i class="fa fa-gift"></i> Voucher <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="<?= $this->Url->build(['controller' => 'RequestVouchers', 'action' => 'index']) ?>">Bán Voucher</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'Vouchers', 'action' => 'index']) ?>">Danh sách</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'Vouchers', 'action' => 'add']) ?>">Thêm mới</a></li>
        </ul>
    </li>
    <li><a><i class="fa fa-bus"></i> Land Tour <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'index']) ?>">Danh sách</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'add']) ?>">Thêm mới</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'addLandtourAgencyPrice']) ?>">Thêm giá Đại lý</a></li>
        </ul>
    </li>
    <li><a><i class="fa fa-book"></i> Cẩm nang <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'index']) ?>">Danh sách</a></li>
            <li><a href="<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'add']) ?>">Thêm mới</a></li>
        </ul>
    </li>
    <li><a><i class="fa fa-plane"></i> Book vé <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a target="_blank" href="<?= $this->Url->build('/vemaybaygiare') ?>">Tìm kiếm chuyến bay</a></li>
            <li><a target="_blank" href="http://agent.datacom.vn/">Xuất vé máy bay</a></li>
            <li><a target="_blank" href="<?= $this->Url->build('/xesanbaygiare') ?>">Tìm kiếm ô tô</a></li>
            <li><a target="_blank" href="http://agent.dichungtaxi.com/">Xuất vé ô tô</a></li>
        </ul>
    </li>
<?php endif; ?>
<?php if ($this->request->session()->read('Auth.User.role_id') == 2): ?>
<li class=""><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'message']) ?>">
        <i class="fa fa-comments-o" aria-hidden="true"></i>
        Message
        <span id="new-message" class="d-none"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>
        </span>
    </a></li>
<?php endif; ?>
</li>
<li><a><i class="fa fa-cog"></i> Cài đặt <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'signature']) ?>">Chữ ký</a></li>
    </ul>
</li>

