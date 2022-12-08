<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách khách sạn</h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">
                                    Tên khuyến mại
                                </th>
                                <th scope="col">
                                    Loại khuyến mại
                                </th>
                                <th scope="col">
                                    Tên khách sạn/ Tên địa điểm
                                </th>
                                <th scope="col">
                                    Số booking
                                </th>
                                <th scope="col">
                                    Ngày bắt đầu
                                </th>
                                <th scope="col">
                                    Ngày kết thúc
                                </th>
                                <th scope="col">
                                    Mức thưởng
                                </th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($promotes as $key => $promote): ?>
                                <tr>
                                    <td><?= ++$key ?></td>
                                    <td><?= h($promote->title) ?></td>
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
                                    <td><?php
                                        if ($promote->locations) {
                                            echo $promote->locations->name;
                                        }
                                        if ($promote->hotels) {
                                            echo $promote->hotels->name;
                                        }
                                        ?>
                                    </td>
                                    <td><?= $promote->num_booking_share ?></td>
                                    <td><?= date_format($promote->start_date, 'd-m-Y') ?></td>
                                    <td><?= date_format($promote->end_date, 'd-m-Y') ?></td>
                                    <td><?= number_format($promote->revenue) ?></td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Promotes', 'action' => 'view', $promote->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Promotes', 'action' => 'edit', $promote->id]) ?>">Sửa</a>
                                        <?php
                                        echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $promote->id], ['confirm' => __('Bạn có chắc muốn xóa Danh mục: {0}?', $promote->name), 'class' => 'btn btn-xs btn-danger']);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
