    <?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    if ($type != P_REG_CONNECT) {
        if ($type == P_BOOK_SHARE_HOTEL || $type == P_BOOK_SHARE_LOCATION) {
            echo $this->Form->control('object_id', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                    'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
                ],
                'options' => $objects,
                'empty' => 'Chọn đối tượng',
                'class' => 'form-control select2',
                'required' => 'required',
                'default' => $object_id,
                'label' => 'Chọn đối tượng *',
            ]);
        }
        echo $this->Form->control('num_booking', [
            'templates' => [
                'inputContainer' => '<div class="item form-group">{{content}}</div>',
                'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                'input' => '<div class="col-md-6 col-sm-6 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
            ],
            'type' => 'text',
            'default' => $num_book,
            'class' => 'form-control',
            'required' => 'required',
            'label' => 'Số lượt Booking yêu cầu *'
        ]);
        echo $this->Form->control('num_share', [
            'templates' => [
                'inputContainer' => '<div class="item form-group">{{content}}</div>',
                'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                'input' => '<div class="col-md-6 col-sm-6 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
            ],
            'type' => 'text',
            'default' => $num_share,
            'class' => 'form-control',
            'required' => 'required',
            'label' => 'Số lượt Chia sẻ yêu cầu *'
        ]);
    }