<li><a><i class="fa fa-user"></i> Người dùng <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>">Danh sách CTV</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'newUser']) ?>">CTV mới</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'add']) ?>">Thêm mới</a></li>
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
<li><a><i class="fa fa-tags"></i> Danh mục <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Categories', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Categories', 'action' => 'add']) ?>">Thêm mới</a></li>
    </ul>
</li>
<li><a><i class="fa fa-map-marker"></i> Địa điểm <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Locations', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Locations', 'action' => 'add']) ?>">Thêm mới</a></li>
    </ul>
</li>
<li><a><i class="fa fa-building"></i> Khách sạn <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'index']) ?>">Danh sách</a></li>
    </ul>
</li>
<li><a><i class="fa fa-hotel"></i> Homestay <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'HomeStays', 'action' => 'index']) ?>">Danh sách</a></li>
    </ul>
</li>
<li><a><i class="fa fa-gift"></i> Voucher <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Vouchers', 'action' => 'index']) ?>">Danh sách</a></li>
    </ul>
</li>
<li><a><i class="fa fa-bus"></i> Land Tour <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'analytic']) ?>">Thống kê</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'controlPayment']) ?>">Thêm chi phí</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'listPaymentFee']) ?>">Công nợ đối tác</a></li>
    </ul>
</li>
<li><a><i class="fa fa-star"></i> Cẩm nang Du lịch <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'add']) ?>">Thêm mới</a></li>
    </ul>
</li>
<li><a><i class="fa fa-volume-up"></i> Khuyến mại <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Promotes', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Promotes', 'action' => 'add']) ?>">Thêm mới</a></li>
    </ul>
</li>
<li><a><i class="fa fa-book"></i> Huấn luyện CTV <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Blogs', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Blogs', 'action' => 'add']) ?>">Thêm mới</a></li>
    </ul>
</li>
<li><a><i class="fa fa-question-circle"></i> Câu hỏi cho CTV <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Questions', 'action' => 'index']) ?>">Danh sách</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Questions', 'action' => 'add']) ?>">Thêm mới</a></li>
    </ul>
</li>
<li><a><i class="fa fa-plane"></i> Book vé <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a target="_blank" href="<?= $this->Url->build('/vemaybaygiare') ?>">Tìm kiếm chuyến bay</a></li>
        <li><a target="_blank" href="http://agent.datacom.vn/">Xuất vé máy bay</a></li>
        <li><a target="_blank" href="<?= $this->Url->build('/xesanbaygiare') ?>">Tìm kiếm ô tô</a></li>
        <li><a target="_blank" href="http://agent.dichungtaxi.com">Xuất vé ô tô</a></li>
    </ul>
</li>
<li><a><i class="fa fa-user"></i> Partner <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'indexPartner']) ?>">Danh sách Partner</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'addPartner']) ?>">Thêm mới</a></li>
    </ul>
</li>
<li><a><i class="fa fa-cog"></i> Cài đặt <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'bankaccount']) ?>">Tài khoản ngân hàng phòng</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'bankaccountLandtour']) ?>">Tài khoản ngân hàng Landtour</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'rechargeAgentInfor']) ?>">Thông tin tài khoản nạp tiền</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'experiedhotelday']) ?>">Ngày hết hạn hợp đồng Khách Sạn</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'exportFile']) ?>">Thay đổi ảnh tiêu đề</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'header']) ?>">Thay đổi ảnh tiêu đề</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'policy']) ?>">Các lí do làm HDV online</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'mustgo']) ?>">Mustgo là gì</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'paymentmethod']) ?>">Hướng dẫn thanh toán</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'secretpolicy']) ?>">Chính sách riêng tư, bảo mật</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'usemethod']) ?>">Điều khoản sử dụng</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'bestprice']) ?>">Chính sách cam kết giá tốt nhất</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'simplequestion']) ?>">Câu hỏi thường gặp</a></li>
        <li><a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'dispute']) ?>">Chính sách giải quyết tranh chấp</a></li>
    </ul>
</li>
