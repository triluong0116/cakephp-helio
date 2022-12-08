<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Trippal Admin Panel</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="<?= $this->Url->assetUrl('backend/img/user.png') ?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?= $this->request->getSession()->read('Auth.User.screen_name') ?></h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php if ($this->request->getParam('prefix') == 'sale'): ?>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'index']) ?>">Dashboard</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'exportFile']) ?>">Xuất file Excel</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'exportFileCtv']) ?>">Xuất Danh sách Đại lý</a></li>
                            <?php endif; ?>
                            <?php if ($this->request->getParam('prefix') == 'editor'): ?>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'hotelReport']) ?>">Báo cáo số đêm Khách sạn</a></li>
                            <?php endif; ?>
                            <?php if ($this->request->getParam('prefix') == 'admin' || $this->request->getParam('prefix') == 'accountant'): ?>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'profitReport']) ?>">Báo cáo Doanh thu Phòng</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'profitReportVin']) ?>">Báo cáo Doanh thu Vinpearl</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'profitReportLandtour']) ?>">Báo cáo Doanh thu Landtour</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'agencyReport']) ?>">Báo cáo số đêm ĐL</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'hotelReport']) ?>">Báo cáo số đêm Khách sạn</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'landtourReport']) ?>">Báo cáo Lợi nhuận Landtour</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'exportFileCtv']) ?>">Xuất Danh sách Đại lý</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'portExcelFile']) ?>">Xuất Danh sách booking công nợ</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'portExcelFileHotel']) ?>">Xuất Danh sách khách sạn công nợ</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'portExcelFileSale']) ?>">Xuất Danh sách báo cáo doanh thu</a></li>
                            <?php endif; ?>
                            <?php if ($this->request->getParam('prefix') == 'manager'): ?>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'index']) ?>">Dashboard</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'controlPayment']) ?>">Thêm chi phí</a></li>
                                <li><a href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'listPaymentFee']) ?>">Công nợ đối tác</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php if ($this->request->getParam('prefix') == 'admin'): ?>
                        <?= $this->element('Backend/sidebar_admin') ?>
                    <?php endif; ?>
                    <?php if ($this->request->getParam('prefix') == 'accountant'): ?>
                        <?= $this->element('Backend/sidebar_accountant') ?>
                    <?php endif; ?>
                    <?php if ($this->request->getParam('prefix') == 'sale' || $this->request->getParam('prefix') == 'sale_landtour'): ?>
                        <?= $this->element('Backend/sidebar_sale') ?>
                    <?php endif; ?>
                    <?php if ($this->request->getParam('prefix') == 'editor'): ?>
                        <?= $this->element('Backend/sidebar_editor') ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="menu_section">
                <!--                <h3>Live On</h3>
                                <ul class="nav side-menu">
                                    <li><a><i class="fa fa-bug"></i> Additional Pages <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="e_commerce.html">E-commerce</a></li>
                                            <li><a href="projects.html">Projects</a></li>
                                            <li><a href="project_detail.html">Project Detail</a></li>
                                            <li><a href="contacts.html">Contacts</a></li>
                                            <li><a href="profile.html">Profile</a></li>
                                        </ul>
                                    </li>
                                    <li><a><i class="fa fa-windows"></i> Extras <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="page_403.html">403 Error</a></li>
                                            <li><a href="page_404.html">404 Error</a></li>
                                            <li><a href="page_500.html">500 Error</a></li>
                                            <li><a href="plain_page.html">Plain Page</a></li>
                                            <li><a href="login.html">Login Page</a></li>
                                            <li><a href="pricing_tables.html">Pricing Tables</a></li>
                                        </ul>
                                    </li>
                                    <li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="#level1_1">Level One</a>
                                            <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li class="sub_menu"><a href="level2.html">Level Two</a>
                                                    </li>
                                                    <li><a href="#level2_1">Level Two</a>
                                                    </li>
                                                    <li><a href="#level2_2">Level Two</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a href="#level1_2">Level One</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li>
                                </ul>-->
            </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <!--        <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>-->
        <!-- /menu footer buttons -->
    </div>
</div>
