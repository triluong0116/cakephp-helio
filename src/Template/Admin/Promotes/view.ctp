<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive">
        <tr>
            <th scope="row"><?= __('Tên khuyến mãi') ?></th>
            <td><?= h($promote->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Loại khuyến mãi') ?></th>
            <td>
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
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Nội dung khuyến mãi') ?></th>
            <td><?= $promote->description ?></td>
        </tr>
        <?php if ($promote->locations) { ?>
        <tr>
            <th scope="row"><?= __('Tên địa điểm') ?></th>
            <td><?= $promote->locations->name ?></td>
        </tr>
        <?php } ?>
        <?php if ($promote->hotels) { ?>
        <tr>
            <th scope="row"><?= __('Tên khách sạn') ?></th>
            <td><?= $promote->hotels->name ?></td>
        </tr>
        <?php } ?>
        <tr>
            <th scope="row"><?= __('Số booking đạt được') ?></th>
            <td><?= $promote->num_booking ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Số share đạt được') ?></th>
            <td><?= $promote->num_share ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ngày bắt đầu') ?></th>
            <td><?= date_format($promote->start_date, 'd-m-Y') ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ngày kết thúc') ?></th>
            <td><?= date_format($promote->end_date, 'd-m-Y') ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mức thưởng') ?></th>
            <td>
                <?= number_format($promote->revenue) ?>
            </td>
        </tr>
        
    </table>   
</div>
