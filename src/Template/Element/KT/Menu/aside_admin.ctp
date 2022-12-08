<ul class="menu-nav">
    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
            <span class="menu-icon">
                <i class="fas fa-home"></i>
            </span>
            <span class="menu-text">Home</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu">
            <i class="menu-arrow"></i>
            <ul class="menu-subnav">
                <li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text">Home</span>
												</span>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'profitReport']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Báo cáo Doanh thu Phòng</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'profitReportVin']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Báo cáo Doanh thu Vinpearl</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'profitReportLandtour']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Báo cáo Doanh thu Landtour</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'agencyReport']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Báo cáo số đêm ĐL</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'hotelReport']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Báo cáo số đêm Khách sạn</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'landtourReport']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Báo cáo Lợi nhuận Landtour</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'exportFileCtv']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Xuất Danh sách Đại lý</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'portExcelFile']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Xuất Danh sách Booking công nợ</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'portExcelFileHotel']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Xuất Danh sách khách sạn công nợ</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'portExcelFileSale']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Xuất Danh sách báo cáo doanh thu</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
            <span class="menu-icon">
                <i class="fas fa-user"></i>
            </span>
            <span class="menu-text">Người dùng</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu">
            <i class="menu-arrow"></i>
            <ul class="menu-subnav">
                <li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text">Người dùng</span>
												</span>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Danh sách CTV</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'newUser']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">CTV mới</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'agencyReport']) ?>" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">Đại lý nạp tiền</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
</ul>
