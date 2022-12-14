<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bookings Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CombosTable|\Cake\ORM\Association\BelongsTo $Combos
 *
 * @method \App\Model\Entity\Booking get($primaryKey, $options = [])
 * @method \App\Model\Entity\Booking newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Booking[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Booking|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Booking|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Booking patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Booking[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Booking findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BookingsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('bookings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Sales', [
            'foreignKey' => 'sale_id',
            'joinType' => 'LEFT',
            'className' => 'Users',
            'propertyName' => 'sale'
        ]);
        $this->belongsTo('Combos', [
            'className' => 'Combos',
            'foreignKey' => 'item_id',
            'bindingKey' => 'id',
            'conditions' => ['type' => COMBO],
            'propertyName' => 'combos'
        ]);
        $this->belongsTo('HomeStays', [
            'className' => 'HomeStays',
            'foreignKey' => 'item_id',
            'bindingKey' => 'id',
            'conditions' => ['type' => HOMESTAY],
            'propertyName' => 'home_stays'
        ]);
        $this->belongsTo('Vouchers', [
            'className' => 'Vouchers',
            'foreignKey' => 'item_id',
            'bindingKey' => 'id',
            'conditions' => ['type' => VOUCHER],
            'propertyName' => 'vouchers'
        ]);
        $this->belongsTo('LandTours', [
            'className' => 'LandTours',
            'foreignKey' => 'item_id',
            'bindingKey' => 'id',
            'conditions' => ['type' => LANDTOUR],
            'propertyName' => 'land_tours'
        ]);
        $this->belongsTo('Hotels', [
            'className' => 'Hotels',
            'foreignKey' => 'item_id',
            'bindingKey' => 'id',
            'conditions' => ['type' => HOTEL],
            'propertyName' => 'hotels',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('BookingSurcharges', [
            'foreignKey' => 'booking_id',
            'saveStrategy' => 'replace'
        ]);
        $this->hasMany('BookingRooms', [
            'foreignKey' => 'booking_id',
            'saveStrategy' => 'replace'
        ]);
        $this->hasOne('BookingLandtours', [
            'foreignKey' => 'booking_id',
            'saveStrategy' => 'append'
        ]);
        $this->hasMany('BookingLandtourAccessories', [
            'foreignKey' => 'booking_id',
            'saveStrategy' => 'replace'
        ]);
        $this->hasOne('Payments', [
            'foreignKey' => 'booking_id',
            'saveStrategy' => 'append',
        ]);
//        $this->belongsToMany('Users', [
//            'through' => 'BookingsUsersRelations',
//        ]);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

//        $validator
//            ->integer('gender')
//            ->requirePresence('gender', 'create')
//            ->notEmpty('gender');
//
//        $validator
//            ->scalar('full_name')
//            ->maxLength('full_name', 255)
//            ->requirePresence('full_name', 'create')
//            ->notEmpty('full_name');
//
//        $validator
//            ->email('email')
//            ->requirePresence('email', 'create')
//            ->notEmpty('email');
//
//        $validator
//            ->scalar('phone')
//            ->maxLength('phone', 128)
//            ->requirePresence('phone', 'create')
//            ->notEmpty('phone');
//
//        $validator
//            ->integer('status')
//            ->requirePresence('status', 'create')
//            ->notEmpty('status');
//
//        $validator
//            ->scalar('other')
//            ->requirePresence('other', 'create')
//            ->notEmpty('other');

        return $validator;
    }

    public function validationAddBookingHotel(Validator $validator)
    {
        $validator
            ->scalar('full_name')
            ->requirePresence('full_name', 'create')
            ->notEmpty('full_name', 'Vui l??ng nh???p t??n tr?????ng ??o??n.');
//
        $validator
            ->scalar('phone')
            ->requirePresence('phone', 'create')
            ->notEmpty('phone', 'Vui l??ng nh???p s??? ??i???n tho???i tr?????ng ??o??n.');

        $validator
            ->scalar('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email', 'Vui l??ng nh???p email tr?????ng ??o??n.');

//        $validator->add('information', 'cc', [
//            'rule' => 'cc',
//            'message' => 'Vui l??ng nh???p th??ng tin tr??? em trong ??o??n',
//            'on' => function ($context) {
//                return $context['data']['is_special'] === "1";
//            }
//        ]);

        $validator->notEmpty('information', 'Vui l??ng nh???p th??ng tin ??o??n', function ($context) {
            return !empty($context['data']['is_special']);
        });

        $islogin = \Cake\Routing\Router::getRequest()->getSession()->read('Auth.User');
//        if ($islogin && $islogin['role_id']==3) {
//            $validator->requirePresence('payment_method', 'create', 'Vui l??ng ch???n m???t h??nh th???c thanh to??n.');
//        }
//        $validator->scalar('booking_rooms.*.room_id')
//            ->requirePresence('booking_rooms.*.room_id')
//            ->notEmpty('booking_rooms.*.room_id', 'Vui l??ng ch???n h???ng ph??ng');

        return $validator;
    }

    public function validationCalBookingHotel(Validator $validator)
    {
        return $validator;
    }

    public function validationaddBookingVoucher(Validator $validator)
    {
        $validator
            ->scalar('full_name')
            ->requirePresence('full_name', 'create')
            ->notEmpty('full_name', 'Vui l??ng nh???p t??n tr?????ng ??o??n.');
//
        $validator
            ->scalar('phone')
            ->requirePresence('phone', 'create')
            ->notEmpty('phone', 'Vui l??ng nh???p s??? ??i???n tho???i tr?????ng ??o??n.');

        $validator
            ->scalar('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email', 'Vui l??ng nh???p email tr?????ng ??o??n.');

        $validator
            ->scalar('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount', 'Vui l??ng nh???p s??? l?????ng.');

        $validator
            ->scalar('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date', 'Vui l??ng nh???p th???i gian check in.');

        $validator
            ->scalar('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date', 'Vui l??ng nh???p th???i gian check out.');

        $islogin = \Cake\Routing\Router::getRequest()->getSession()->read('Auth.User');
        if ($islogin && $islogin['role_id']==3) {
            $validator->requirePresence('payment_method', 'create', 'Vui l??ng ch???n m???t h??nh th???c thanh to??n.');
        }
        return $validator;
    }

    public function validationaddBookingHomeStay(Validator $validator)
    {
        $validator
            ->scalar('full_name')
            ->requirePresence('full_name', 'create')
            ->notEmpty('full_name', 'Vui l??ng nh???p t??n tr?????ng ??o??n.');

        $validator
            ->scalar('phone')
            ->requirePresence('phone', 'create')
            ->notEmpty('phone', 'Vui l??ng nh???p s??? ??i???n tho???i tr?????ng ??o??n.');

        $validator
            ->scalar('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email', 'Vui l??ng nh???p email tr?????ng ??o??n.');

        $validator
            ->scalar('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date', 'Vui l??ng nh???p th???i gian check in.');

        $validator
            ->scalar('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date', 'Vui l??ng nh???p th???i gian check out.');

        $islogin = \Cake\Routing\Router::getRequest()->getSession()->read('Auth.User');
        if ($islogin && $islogin['role_id']==3) {
            $validator->requirePresence('payment_method', 'create', 'Vui l??ng ch???n m???t h??nh th???c thanh to??n.');
        }
        return $validator;
    }

    public function validationAddHotelApi(Validator $validator)
    {
        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmpty('user_id');

        $validator
            ->integer('item_id')
            ->requirePresence('item_id', 'create')
            ->notEmpty('item_id');

        $validator
            ->integer('room_id')
            ->requirePresence('room_id', 'create')
            ->notEmpty('room_id');

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');

        $validator
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        $validator
            ->scalar('full_name')
            ->maxLength('full_name', 255)
            ->requirePresence('full_name', 'create')
            ->notEmpty('full_name');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 128)
            ->requirePresence('phone', 'create')
            ->notEmpty('phone');

        $validator
            ->scalar('other')
            ->requirePresence('other', 'create')
            ->notEmpty('other');

        return $validator;
    }

    public function validationAddBookingApi(Validator $validator)
    {
//        $validator
//            ->integer('user_id')
//            ->requirePresence('user_id', 'create')
//            ->notEmpty('user_id');

        $validator
            ->integer('item_id')
            ->requirePresence('item_id', 'create')
            ->notEmpty('item_id');

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');

        $validator
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        $validator
            ->scalar('full_name')
            ->maxLength('full_name', 255)
            ->requirePresence('full_name', 'create')
            ->notEmpty('full_name');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 128)
            ->requirePresence('phone', 'create')
            ->notEmpty('phone');

        $validator
            ->scalar('other')
            ->requirePresence('other', 'create')
            ->notEmpty('other');

        return $validator;
    }

    public function validationaddBookingLandtour(Validator $validator)
    {
        $validator
            ->scalar('full_name')
            ->requirePresence('full_name', 'create')
            ->notEmpty('full_name', 'Vui l??ng nh???p t??n tr?????ng ??o??n.');

        $validator
            ->scalar('phone')
            ->requirePresence('phone', 'create')
            ->notEmpty('phone', 'Vui l??ng nh???p s??? ??i???n tho???i tr?????ng ??o??n.');

        $validator
            ->scalar('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email', 'Vui l??ng nh???p email tr?????ng ??o??n.');

        $validator
            ->scalar('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date', 'Vui l??ng nh???p th???i gian check in.');

        $validator
            ->scalar('pickup_id')
            ->requirePresence('pickup_id', 'create', 'Vui l??ng ch???n ??i???m ????n.')
            ->notEmpty('pickup_id', 'Vui l??ng ch???n ??i???m ????n.');

        $validator
            ->scalar('drop_id')
            ->requirePresence('drop_id', 'create', 'Vui l??ng ch???n ??i???m tr???.')
            ->notEmpty('drop_id', 'Vui l??ng ch???n ??i???m tr???.');

//        $validator
//            ->scalar('num_adult')
//            ->requirePresence('num_adult', 'create')
//            ->notEmpty('num_adult', 'Vui l??ng nh???p s??? l?????ng.');

        $islogin = \Cake\Routing\Router::getRequest()->getSession()->read('Auth.User');
        if ($islogin && $islogin['role_id']==3) {
            $validator->requirePresence('payment_method', 'create', 'Vui l??ng ch???n m???t h??nh th???c thanh to??n.');
        }

        return $validator;
    }

    public function validationaddBookingVoucherApi(Validator $validator)
    {
        $validator
            ->scalar('full_name')
            ->requirePresence('full_name', 'create')
            ->notEmpty('full_name', 'Vui l??ng nh???p t??n tr?????ng ??o??n.');
//
        $validator
            ->scalar('phone')
            ->requirePresence('phone', 'create')
            ->notEmpty('phone', 'Vui l??ng nh???p s??? ??i???n tho???i tr?????ng ??o??n.');

        $validator
            ->scalar('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email', 'Vui l??ng nh???p email tr?????ng ??o??n.');

        $validator
            ->scalar('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount', 'Vui l??ng nh???p s??? l?????ng.');

        $validator
            ->scalar('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date', 'Vui l??ng nh???p th???i gian check in.');

        $islogin = \Cake\Routing\Router::getRequest()->getSession()->read('Auth.User.id');
        if ($islogin && $islogin['role_id']==3) {
            $validator->requirePresence('payment_method', 'create', 'Vui l??ng ch???n m???t h??nh th???c thanh to??n.');
        }
        return $validator;
    }
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
//        $rules->add($rules->isUnique(['email']));
//        $rules->add($rules->existsIn(['user_id'], 'Users'));
//        $rules->add($rules->existsIn(['combo_id'], 'Combos'));

        return $rules;
    }
}
