<!-- Start content -->
<?php
echo $this->Html->css('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'cssHeader']);
echo $this->Html->script('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'scriptBottom']);
$this->Html->scriptBlock('Dropzone.autoDiscover = false;', ['block' => 'scriptBottom']);
?>
<div class="blog-detail bg-grey">
    <hr>
    <div class="text-right text-primary bg-white p20 ">
        <span><i
                class="fa-solid fa-sack-dollar"></i> Số dư tài khoản : <strong><?= number_format($balance) ?> VNĐ </strong> </span>
    </div>
    <div class="p20">
        <div class="row">
            <!--            <div>-->
            <!--                <center>-->
            <!--                    <div class="pt10 pb50">-->
            <!--                        <h2><span class="box-underline-center pb20">Quản lý giao dịch</span></h2>-->
            <!--                    </div>-->
            <!--                </center>-->
            <!--            </div>-->
            <div class="pt05 pb30 ">
                <form class="form-inline">
                    <div class="row ">
                        <div class="col-sm-18">

                        </div>
                        <div class="col-sm-18">
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="form-group">
                                    <input class="form-control" name="seachCode" id="seachCode"
                                           value="<?= $dataRequest['seachCode'] ?>" placeholder="Nhập từ mã giao dịch">
                                </div>
                            </div>
                            <div class="col-sm-6 ml10 mr10">
                                <div class="form-group">
                                    <select class="form-control " id="typeSelect" name="typeSelect">
                                        <option value="3" <?= $dataRequest['typeSelect'] == 3 ? 'selected' : '' ?> >Tất
                                            cả giao dịch
                                        </option>
                                        <option value="1" <?= $dataRequest['typeSelect'] == 1 ? 'selected' : '' ?> >Nạp
                                            tiền
                                        </option>
                                        <option value="2" <?= $dataRequest['typeSelect'] == 2 ? 'selected' : '' ?> >
                                            Thanh Toán
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 ml10 mr10">
                                <div class="form-group">
                                    <select class="form-control" id="statusSelect" name="statusSelect">
                                        <option value="3" <?= $dataRequest['statusSelect'] == 3 ? 'selected' : '' ?> >
                                            Tất cả trạng thái
                                        </option>
                                        <option value="2" <?= $dataRequest['statusSelect'] == 2 ? 'selected' : '' ?> >
                                            Wait
                                        </option>
                                        <option value="1" <?= $dataRequest['statusSelect'] == 1 ? 'selected' : '' ?> >
                                            Approved
                                        </option>
                                        <option value="0" <?= $dataRequest['statusSelect'] == 0 ? 'selected' : '' ?> >
                                            Reject
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 ml10 mr10">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary pt05 pb05"><i
                                            class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="chia">
                <div class="">
                    <div class="tab-pane" id="">
                        <div class="box-white mb30 p15">
                            <div class="filter-header mb15">
                                <div class="row vertical-center mr0-i ml0-i mb10">
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Mã giao dịch</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Tiêu đề</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-center semi-bold text-super-dark">Số tiền nạp</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-center semi-bold text-super-dark">Loại giao dịch</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-center semi-bold text-super-dark">Thời gian giao dịch</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Trạng thái</p>
                                    </div>
                                    <div class="col-sm-5">
                                        <p class="text-center semi-bold text-super-dark">Số dư</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Ảnh UNC</p>
                                    </div>
                                    <div class="clear-fix"></div>
                                </div>
                                <div id="agency_filter" class="accordion filter-accordion">
                                    <?php if ($datas): ?>
                                        <?php foreach ($datas as $key => $data): ?>
                                            <div class="panel" data-room-id="<?= $data->id ?>"
                                                 onclick="Frontend.filterHighlight(this, true);">
                                                <div
                                                    class="row pt10 pb10 mr0-i ml0-i text-center vertical-center panel-row <?= ($key == 0) ? 'panel-bg-blue' : '' ?>">
                                                    <div class="col-sm-3">
                                                        <?= $data->code ?>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <?= $data->title ?>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <?php echo number_format($data->amount); ?>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <?php
                                                        if ($data->type == 1) {
                                                            echo 'Nạp tiền';
                                                        } else {
                                                            echo ' Thanh toán đơn hàng';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div
                                                        class="col-sm-4"><?= date_format($data->created, 'd-m-Y') ?></div>
                                                    <div class="col-sm-3 statusCharge">
                                                        <?php
                                                        if ($data->status == 2) {
                                                            ?>
                                                            <div class="statusCharge" id="statusChargeWait">
                                                                <span>Đang chờ</span>
                                                            </div>
                                                            <?php
                                                        } else if ($data->status == 1) {
                                                            ?>
                                                            <div class="statusCharge" id="statusChargeApproved">
                                                                <span>Đã duyệt</span>
                                                            </div>
                                                            <?php
                                                        } else if ($data->status == 0) {
                                                            ?>
                                                            <div class="statusCharge" id="statusChargeRejected">
                                                                <span>Từ chối</span>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <?= $data->balance != 0 ? $data->balance : 'Chờ xác nhận số dư' ?>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <a class="btn btn-default-outline thumbnail"><img
                                                                src="<?= json_decode($data->images) ? $this->Url->assetUrl(json_decode($data->images)[0]) : $data->images ?>"
                                                                style="height: 100px;  width : 100px "></a>
                                                    </div>
                                                    <div class="clear-fix"></div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="paginator">
                                    <ul class="pagination">
                                        <?= $this->Paginator->first('<< ' . __('first')) ?>
                                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                                        <?= $this->Paginator->numbers() ?>
                                        <?= $this->Paginator->next(__('next') . ' >') ?>
                                        <?= $this->Paginator->last(__('last') . ' >>') ?>
                                    </ul>
                                    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="image-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <img class="img-responsive center-block" src="" alt="" style="max-height: 500px;  width : 100% ">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
