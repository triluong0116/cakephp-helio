<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room[]|\Cake\Collection\CollectionInterface $rooms
 */
?>
<div class="col-sm-12">
    <form class="form-inline">
        <div class="form-group">
            <select name="hotel_id" class="select2 form-control" id="">
                <?php foreach ($hotelSearches as $key => $hotel): ?>
                    <option value="<?= $key ?>"><?= $hotel ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
    </form>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 mt10">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách phòng khách sạn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th scope="col">
                        Tên khách sạn
                    </th>
                    <th scope="col">
                        Số hạng phòng
                    </th>
                    <th scope="col" class="actions">
                        <?= __('Actions') ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($hotels as $key => $singleHotel): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= $singleHotel->name ?></td>
                        <td><?= h(count($singleHotel->rooms)) ?></td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'listRoom', $singleHotel->id]) ?>">Danh sách hạng phòng</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
