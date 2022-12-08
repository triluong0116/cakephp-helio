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
            <?= $this->element('Backend/searchv3') ?>
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
                                <th>Commit</th>
                                <th scope="col">
                                    Tên khách sạn
                                </th>
                                <th scope="col">
                                    Tình trạng
                                </th>
                                <th scope="col">
                                    Địa điểm
                                </th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($hotels as $key => $hotel): ?>
                                <tr>
                                    <td><?= ++$key ?></td>
                                    <td>
                                        <input type="checkbox" name="commit" value="<?= $hotel->id ?>" onclick="changeIsCommit(this, <?= $hotel->id ?>)"
                                        <?php
                                        if ($hotel->is_commit == 1){
                                            echo "checked";
                                        }
                                        ?>
                                    </td>
                                    <td><?= h($hotel->name) ?></td>
                                    <td>
                                        <?php if ($hotel->vinhms_code): ?>
                                            <h5 class="label label-success">Đã liên kết</h5>
                                        <?php else: ?>
                                            <h5 class="label label-warning">Chưa liên kết</h5>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= h($hotel->location->name) ?></td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'view', $hotel->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'editVinpearl', $hotel->id]) ?>">Sửa</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'addAllotment', $hotel->id]) ?>">Thêm giá gói</a>
                                        <a type="button" class="btn btn-xs btn-success" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'listRoomVin', $hotel->id]) ?>">Danh Sách hạng phòng</a>
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
