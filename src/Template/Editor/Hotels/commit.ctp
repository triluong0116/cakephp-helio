<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách khách sạn commit</h2>
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
                                    Địa điểm
                                </th>
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
                                            ?>>
                                    </td>
                                    <td><?= h($hotel->name) ?></td>
                                    <td><?= h($hotel->location->name) ?></td>
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


