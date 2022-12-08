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
        } else {
            $sDate = date('d/m/Y');
        }
        if (isset($param['end_date']) && !empty($param['end_date'])) {
            $eDate = $param['end_date'];
            $hasDate = true;
        } else {
            $eDate = date('d/m/Y');
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
                               value="<?php
                               if (isset($keyword)){
                                   echo $keyword;
                               }
                               else {
                                    echo $data;
                               } ?>">
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
                    <input type="text" class="form-control" placeholder="Search.." name="search" id="search" value="<?php if (isset($keyword)) { echo $keyword;} ?>">
                </div>
                <div class="col-md-1 mb-1">
                    <div class="search-container float-l">
                        <button type="submit" class="custom-b"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>


<?php endif; ?>

