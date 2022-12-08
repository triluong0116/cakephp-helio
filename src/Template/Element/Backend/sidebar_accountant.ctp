<li><a><i class="fa fa-user"></i> Người dùng <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>">Danh sách CTV</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'newUser']) ?>">CTV mới</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'add']) ?>">Thêm mới</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'rechargeAgent']) ?>">Đại lý nạp tiền</a></li>
    </ul>
</li>
<li><a><i class="fa fa-money"></i> Thanh toán <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'index_booking']) ?>">Danh sách booking phòng</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'index_booking_vinpearl']) ?>">Danh sách booking Vinpearl</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'index_booking_landtour']) ?>">Danh sách booking landtour</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'pay_booking_hotel']) ?>">Phải thu khách hàng (BK thường)</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'pay_booking_for_hotel']) ?>">Phải thanh toán Booking cho khách sạn (BK thường)</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'pay_booking_vinpearl']) ?>">Phải thu khách hàng (Vinpearl)</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'pay_booking_for_vinpearl']) ?>">Phải thanh toán Booking cho Vinpearl</a></li>
        <li><a href="https://ma.onepay.vn" target="_blank">Kiểm tra luồng tiền Onepay</a></li>
    </ul>
</li>

