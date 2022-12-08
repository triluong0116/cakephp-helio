<?php
/**
 * @property \App\Controller\Component\UtilComponent $Util
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room[]|\Cake\Collection\CollectionInterface $rooms
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12 mt10">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách gói của phòng <?= $room->name ?></h2>
            <div class="clearfix"></div>
        </div>
        <?= $this->Form->create(null, ['class' => 'allotment-revenue-channel']) ?>
        <div class="x_content">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th scope="col">
                        Tên gói
                    </th>
                    <th scope="col">
                        Mã gói
                    </th>
                    <th scope="col">
                        Số người/gói
                    </th>
                    <th scope="col">
                        Số người lớn /gói
                    </th>
                    <th scope="col">
                        Số trẻ em /gói
                    </th>
                    <th scope="col">
                        Số người tối đa / gói
                    </th>
<!--                    <th scope="col">-->
<!--                        Bữa ăn-->
<!--                    </th>-->
                    <th scope="col">
                        Loại lợi nhuận Mustgo
                    </th>
                    <th scope="col">
                        Lợi nhuận Mustgo
                    </th>
                    <th scope="col">
                        Mô tả
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rateplanes as $key => $rateplane): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= $rateplane->name ?></td>
                        <td><?= $rateplane->rateplan_code ?></td>
                        <td><?= $rateplane->guest ?></td>
                        <td><?= $rateplane->adult ?></td>
                        <td><?= $rateplane->child ?></td>
                        <td><?= $rateplane->maxguest ?></td>
                        <td>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $rateplane->sale_revenue_type == 0 ? 'checked' : '' ?> name="rateplane[<?= $rateplane->id ?>][sale_revenue_type]" value="0"> Cố định</i></p>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $rateplane->sale_revenue_type == 1 ? 'checked' : '' ?> name="rateplane[<?= $rateplane->id ?>][sale_revenue_type]" value="1"> Theo %</i></p>
                        </td>
                        <td><input type="text" name="rateplane[<?= $rateplane->id ?>][sale_revenue]" value="<?= $rateplane->sale_revenue ?>" class="form-control"></td>
                        <td><textarea rows="3" name="rateplane[<?= $rateplane->id ?>][description]" class="form-control"><?= $rateplane->description ?></textarea></td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success" id="blog-submit">Submit</button>
            </div>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
