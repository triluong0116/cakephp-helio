<?php
$controller = $this->request->params['controller'];
$prefix = $this->request->params['prefix'];
$action = $this->request->params['action'];
$fullParam = $this->request->params;
$hasDate = false;
if (isset($fullParam['?'])) {
    $param = $fullParam['?'];
    if ($param) {
        if (isset($param['start_date']) && !empty($param['start_date'])) {
            $sDate = $param['start_date'];
            $hasDate = true;
        } else{
            $sDate ='';
        }
        if (isset($param['end_date']) && !empty($param['end_date'])) {
            $eDate = $param['end_date'];
            $hasDate = true;
        } else{
            $eDate ='';
        }
        if (isset($param['create_date']) && !empty($param['create_date'])) {
            $cDate = $param['create_date'];
            $hasDate = true;
        }
        else{
            $cDate ='';
        }
    }
}
?>
<?php if (($prefix == 'admin' && $controller == 'Dashboards' && ($action == 'indexBooking' || $action == 'indexBookingLandtour')) || ($prefix == 'sale' && $controller == 'Bookings' && $action == 'index')): ?>
    <div class="">
        <form class="form-inline">
            <div class="row">
                <?php if (($prefix == 'admin' && $controller == 'Dashboards' && ($action == 'indexBooking' || $action == 'indexBookingLandtour')) || ($prefix == 'sale' && $controller == 'Bookings' && $action == 'index')): ?>
                    <div class="col-sm-12">
                        <div id="search-by-date">
                            <?php if ($hasDate): ?>
                                <button type="button" class="btn btn-default" onclick="hideSearchByDate()"><i
                                        class="fa fa-filter"></i> Ẩn tìm kiếm theo ngày
                                </button>
                                <div class="form-group">
                                    <label for="exampleInputName2">Ngày đi</label>
                                    <input type="text" class="custom-singledate-picker form-control" name="start_date"
                                           value="<?= $sDate ?>"/>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName2">Ngày về</label>
                                    <input type="text" class="custom-singledate-picker form-control" name="end_date"
                                           value="<?= $eDate ?>"/>
                                </div>
                            <?php else: ?>
                                <button type="button" class="btn btn-default" onclick="showSearchByDate()"><i
                                        class="fa fa-filter"></i> Tìm kiếm theo ngày
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-sm-12 mt10">
                    <?php if ($prefix == 'admin' && $controller == 'Dashboards' && ($action == 'indexBooking' || $action == 'indexBookingLandtour')): ?>
                        <div class="form-group">
                            <label for="exampleInputName2">Xác nhận CTV CK</label>
                            <select type="text" class="form-control" id="exampleInputName2" name="confirm_agency_pay">
                                <option value="">Chọn hình thức</option>
                                <option value="0" <?= ($confirmAgencyPay === "0") ? 'selected' : '' ?>>Chưa chuyển
                                    khoản
                                </option>
                                <option value="1" <?= ($confirmAgencyPay === "1") ? 'selected' : '' ?>>Đã đặt cọc
                                </option>
                                <option value="2" <?= ($confirmAgencyPay === "2") ? 'selected' : '' ?>>Đã chuyển khoản
                                </option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="exampleInputName2">CTV thanh toán</label>
                        <select type="text" class="form-control" id="exampleInputName2" name="agency_pay">
                            <option value="">Chọn hình thức</option>
                            <option value="0" <?= ($agencyPay === "0") ? 'selected' : '' ?>>Chưa thanh toán</option>
                            <option value="1" <?= ($agencyPay === "1") ? 'selected' : '' ?>>Đã thanh toán</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName2">Thanh toán KS</label>
                        <select type="text" class="form-control" id="exampleInputName2" name="pay_hotel">
                            <option value="">Chọn hình thức</option>
                            <option value="0" <?= $payHotel === "0" ? 'selected' : '' ?>>Chưa thanh toán</option>
                            <option value="1" <?= $payHotel === "1" ? 'selected' : '' ?>>Đã thanh toán</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail2"></label>
                        <input class="form-control" id="exampleInputEmail2" name="search" placeholder="Từ khóa"
                               value="<?= $keyword ?>">
                    </div>
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="search">
        <form action="" method="get">
            <div class="form-row">
                <div class="col-md-3 mb-3">
                    <label for="search">Search</label>
                    <input type="text" class="form-control" placeholder="Search.." name="search" id="search" value="<?php  if (isset($keyword)) { echo $keyword;} ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" placeholder="Email.." name="email" id="email" value="<?php if (isset($email)) { echo $email;} ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" class="form-control" placeholder="Phone.." name="phone" id="phone" value="<?php if (isset($phone)) { echo $phone;} ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="code">Mã đặt phòng</label>
                    <input type="text" class="form-control" placeholder="Code.." name="code" id="code" value="<?php if (isset($code)) { echo $code;} ?>">
                </div>
            </div>
            <div class="form-inline">
                <div class="row">
                    <div class="col-sm-10 mb-3 mt10">
                        <div id="search-by-date">
                            <?php if ($hasDate): ?>
                                <button type="button" class="btn btn-default" onclick="hideSearchByDate2()"><i
                                        class="fa fa-filter"></i> Ẩn tìm kiếm theo ngày
                                </button>
                                <div class="form-group">
                                    <label for="exampleInputName2">Ngày đi</label>
                                    <input type="text" class="custom-singledate-picker form-control" name="start_date"
                                           value="<?= $sDate ?>"/>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName2">Ngày về</label>
                                    <input type="text" class="custom-singledate-picker form-control" name="end_date"
                                           value="<?= $eDate ?>"/>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName2">Ngày tạo</label>
                                    <input type="text" class="custom-singledate-picker form-control" name="create_date"
                                           value="<?= $cDate ?>"/>
                                </div>
                            <?php else: ?>
                                <button type="button" class="btn btn-default" onclick="showSearchByDate2()"><i
                                        class="fa fa-filter"></i> Tìm kiếm theo ngày
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="search-container">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>


<?php endif; ?>

