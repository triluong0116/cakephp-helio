<div class="modal fade" id="modal-popup-promote" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content -->
        <div class="modal-content modal-voucher">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <h3 class="text-center">Các chương trình khuyến mại</h3>
                <?php foreach ($promotes as $promote): ?>
                    <div class="p10">
                        <p class="box-underline-center semi-bold main-color text-center"><?= $promote->title ?></p>
                        <p class="text-center">Hình thức khuyễn mãi:
                            <?php
                            if ($promote->type == P_REG_CONNECT) {
                                echo "Đăng ký tài khoản và kết nối fanpage";
                            } else if ($promote->type == P_BOOK_SHARE) {
                                echo "Số booking/chia sẻ trong 1 khoảng thời gian";
                            } else if ($promote->type == P_BOOK_SHARE_HOTEL) {
                                echo("Số booking/chia sẻ theo khách sạn trong 1 khoảng thời gian");
                            } else {
                                echo "Số booking/chia sẻ theo địa điểm trong 1 khoảng thời gian";
                            }
                            ?>
                        </p>
                        <p class="p10"><?= $promote->description ?></p>
                        <p class="text-center">
                            <?php
                            if ($promote->object_id != 0) {
                                if ($promote->locations) {
                                    echo "Địa điểm:" . $promote->locations->name;
                                }
                                if ($promote->hotels) {
                                    echo "Khách sạn: " . $promote->hotels->name;
                                }
                            }
                            ?>
                        </p>
                        <p class="text-center">
                            Thời hạn từ ngày <?= $promote->start_date ?> đến ngày <?= $promote->end_date ?>
                        </p>
                        <p class="text-center">Mức thưởng: <?= number_format($promote->revenue) ?>đ</p>
                        <hr class="pt10">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>