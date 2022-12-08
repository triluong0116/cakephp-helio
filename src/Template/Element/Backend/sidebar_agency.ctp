<li><a><i class="fa fa-gift"></i> Voucher <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Vouchers', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Vouchers', 'action' => 'add']) ?>">Thêm mới</a></li>
    </ul>
</li>
<li><a><i class="fa fa-bookmark"></i> Booking <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'add']) ?>">Thêm mới</a></li>
    </ul>
</li>
<li><a><i class="fa fa-th-large"></i> Combo <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Combos', 'action' => 'index']) ?>">Danh sách</a></li>
    </ul>
</li>
<li><a><i class="fa fa-facebook-square"></i> Fanpage <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Fanpages', 'action' => 'index']) ?>">Danh sách</a></li>
    </ul>
</li>