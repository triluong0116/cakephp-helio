<li><a><i class="fa fa-building"></i> Khách sạn <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'commit']) ?>">Danh sách Commit</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'add']) ?>">Thêm mới</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'indexVinpearl']) ?>">Danh sách Vinpearl</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'addVinpearl']) ?>">Thêm mới Vinpearl</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'indexChannel']) ?>">Danh sách Channel</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'addChannel']) ?>">Thêm mới Channel</a></li>
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
<!--li><a><i class="fa fa-percent"></i> Phụ thu <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Surcharges', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Surcharges', 'action' => 'add']) ?>">Thêm mới</a></li>
    </ul>
</li -->
