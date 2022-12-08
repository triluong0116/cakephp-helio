<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm mới Booking</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-sm-9 col-md-9 col-xs-12">
                <div class="row">
                    <div class="col-sm-3">
                        <label>
                            Chọn loại booking *
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <select name="booking_template" id="pick_template_edit" class="form-control select2" required
                                onchange="setBookingTemplate()">
                            <option value="">Chọn loại booking</option>
                            <?php foreach ($booking_type as $key => $type): ?>
                                <option
                                    value="<?= $key ?>" <?= $key == $booking->booking_type ? 'selected' : '' ?>><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="system-booking-template form-horizontal form-label-left" id="system_booking">
    <?= $this->Form->create($booking, ['class' => '', 'data-parsley-validate', 'id' => 'form-booking-system', 'type' => 'file']) ?>
    <input type="hidden" name="indexParams" value='<?= json_encode($indexParams) ?>'>
    <?php
    $this->Form->setTemplates([
        'formStart' => '<form class="" {{attrs}}>',
        'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
        'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
        'select' => '<div class="col-md-8 col-sm-8 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
        'textarea' => '<div class="col-md-8 col-sm-8 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
        'inputContainer' => '<div class="item form-group">{{content}}</div>',
        'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
        'checkContainer' => ''
    ]) ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Sửa Booking thuộc hệ thống</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <input type="hidden" value="<?= SYSTEM_BOOKING ?>" name="booking_type">
                <div class="row">
                    <input name="previous_url" type="hidden" value="<?= $referer ?>">
                    <div class="col-sm-6">
                        <?php
                        echo $this->Form->control('user_id', [
                            'empty' => 'Chọn cộng tác viên',
                            'label' => 'Chọn cộng tác viên *',
                            'class' => 'form-control select2',
                            'options' => $objects,
                            'required' => 'required',
                            'default' => $booking->user_id
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        echo $this->Form->control('type', [
                            'class' => 'form-control select2',
                            'label' => 'Chọn Loại hình *',
                            'default' => (isset($dataValiError['type']) && !empty($dataValiError['type'])) ? $dataValiError['type'] : $booking->type,
                            'options' => $object_types,
                            'required' => 'required',
                            'id' => $booking->booking_type == SYSTEM_BOOKING ? 'choose-type-booking-system' : '',
                            'data-item-id' => (isset($dataValiError['item_id']) && !empty($dataValiError['item_id'])) ? $dataValiError['item_id'] : $booking->item_id,
                            'data-room-level' => $booking->room_level,
                            'data-booking-type' => $booking->booking_type,
                            'data-booking-id' => $booking->id,
                            'onchange' => "showFormBookingByType(this, '#form-by-type-system')"
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="form-by-type-system">

    </div>

    <?= $this->Form->end() ?>
</div>

<!-- booking Log -->
<div class="x_panel">
    <div class="x_title">
        <h2>Booking Logs</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?php
        //                dd($bookingLogs, $userLogs);
        foreach ($bookingLogs as $bookingLog): ?>
            <div class="row">
                <div class="col-sm-2">
                    <h4><?= $bookingLog->created ?></h4>
                </div>
                <div class="col-sm-2">
                    <h4><?= $bookingLog->u['screen_name'] ?></h4>
                </div>
                <div class="col-sm-2">
                    <h4><?= $bookingLog->title ?></h4>
                </div>
                <div class="col-sm-6">
                    <h4><?= $bookingLog->comment ?></h4>
                </div>
            </div>
        <?php endforeach; ?>
        <form action="#" id="commentLog">
            <div class="row">
                <div class="col-sm-12">
                    <h4>comment</h4>
                </div>
                <div class="col-sm-12">
                    <textarea name="log-cmt" class="form-control" id="log-cmt" rows="5"></textarea>
                    <div class="clearfix h6"></div>
                </div>
                <div class="col-sm-12">
                    <a class="btn btn-success btn-log"  data-title="1" data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>">Gửi Comment</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- end booking Log -->


