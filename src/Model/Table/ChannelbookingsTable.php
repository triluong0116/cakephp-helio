<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Channelbookings Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\SalesTable|\Cake\ORM\Association\BelongsTo $Sales
 * @property \App\Model\Table\AccountantsTable|\Cake\ORM\Association\BelongsTo $Accountants
 * @property \App\Model\Table\HotelsTable|\Cake\ORM\Association\BelongsTo $Hotels
 * @property \App\Model\Table\ChannelbookingRoomsTable|\Cake\ORM\Association\HasMany $ChannelbookingRooms
 *
 * @method \App\Model\Entity\Channelbooking get($primaryKey, $options = [])
 * @method \App\Model\Entity\Channelbooking newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Channelbooking[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Channelbooking|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Channelbooking|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Channelbooking patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Channelbooking[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Channelbooking findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ChannelbookingsTable extends Table
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

        $this->setTable('channelbookings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Hotels', [
            'foreignKey' => 'hotel_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ChannelbookingRooms', [
            'foreignKey' => 'channelbooking_id'
        ]);
        $this->hasOne('Vinpayments', [
            'foreignKey' => 'booking_id'
        ]);
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
//            ->scalar('code')
//            ->maxLength('code', 255)
//            ->requirePresence('code', 'create')
//            ->notEmpty('code');
//
//        $validator
//            ->scalar('first_name')
//            ->maxLength('first_name', 255)
//            ->requirePresence('first_name', 'create')
//            ->notEmpty('first_name');
//
//        $validator
//            ->scalar('sur_name')
//            ->maxLength('sur_name', 255)
//            ->requirePresence('sur_name', 'create')
//            ->notEmpty('sur_name');
//
//        $validator
//            ->dateTime('start_date')
//            ->allowEmpty('start_date');
//
//        $validator
//            ->dateTime('end_date')
//            ->allowEmpty('end_date');
//
//        $validator
//            ->dateTime('complete_date')
//            ->allowEmpty('complete_date');
//
//        $validator
//            ->integer('gender')
//            ->requirePresence('gender', 'create')
//            ->notEmpty('gender');
//
//        $validator
//            ->scalar('phone')
//            ->maxLength('phone', 50)
//            ->requirePresence('phone', 'create')
//            ->notEmpty('phone');
//
//        $validator
//            ->email('email')
//            ->requirePresence('email', 'create')
//            ->notEmpty('email');
//
//        $validator
//            ->scalar('nationality')
//            ->maxLength('nationality', 255)
//            ->requirePresence('nationality', 'create')
//            ->notEmpty('nationality');
//
//        $validator
//            ->scalar('nation')
//            ->maxLength('nation', 255)
//            ->requirePresence('nation', 'create')
//            ->notEmpty('nation');
//
//        $validator
//            ->integer('status')
//            ->requirePresence('status', 'create')
//            ->notEmpty('status');
//
//        $validator
//            ->integer('price')
//            ->requirePresence('price', 'create')
//            ->notEmpty('price');
//
//        $validator
//            ->integer('sale_revenue')
//            ->requirePresence('sale_revenue', 'create')
//            ->notEmpty('sale_revenue');
//
//        $validator
//            ->integer('price_default')
//            ->requirePresence('price_default', 'create')
//            ->notEmpty('price_default');
//
//        $validator
//            ->integer('sale_revenue_default')
//            ->requirePresence('sale_revenue_default', 'create')
//            ->notEmpty('sale_revenue_default');
//
//        $validator
//            ->scalar('information')
//            ->maxLength('information', 4294967295)
//            ->requirePresence('information', 'create')
//            ->notEmpty('information');
//
//        $validator
//            ->integer('agency_pay')
//            ->requirePresence('agency_pay', 'create')
//            ->notEmpty('agency_pay');
//
//        $validator
//            ->integer('is_paid')
//            ->requirePresence('is_paid', 'create')
//            ->notEmpty('is_paid');
//
//        $validator
//            ->integer('confirm_agency_pay')
//            ->requirePresence('confirm_agency_pay', 'create')
//            ->notEmpty('confirm_agency_pay');
//
//        $validator
//            ->integer('pay_hotel')
//            ->requirePresence('pay_hotel', 'create')
//            ->notEmpty('pay_hotel');
//
//        $validator
//            ->scalar('note_for_hotel_payment')
//            ->maxLength('note_for_hotel_payment', 16777215)
//            ->requirePresence('note_for_hotel_payment', 'create')
//            ->notEmpty('note_for_hotel_payment');
//
//        $validator
//            ->integer('sale_discount')
//            ->requirePresence('sale_discount', 'create')
//            ->notEmpty('sale_discount');
//
//        $validator
//            ->integer('agency_discount')
//            ->requirePresence('agency_discount', 'create')
//            ->notEmpty('agency_discount');
//
//        $validator
//            ->integer('is_send_customer')
//            ->requirePresence('is_send_customer', 'create')
//            ->notEmpty('is_send_customer');
//
//        $validator
//            ->integer('is_send_channel')
//            ->requirePresence('is_send_channel', 'create')
//            ->notEmpty('is_send_channel');
//
//        $validator
//            ->integer('mail_type')
//            ->requirePresence('mail_type', 'create')
//            ->notEmpty('mail_type');
//
//        $validator
//            ->integer('pay_hotel_type')
//            ->requirePresence('pay_hotel_type', 'create')
//            ->notEmpty('pay_hotel_type');
//
//        $validator
//            ->integer('creator_type')
//            ->requirePresence('creator_type', 'create')
//            ->notEmpty('creator_type');
//
//        $validator
//            ->scalar('note')
//            ->requirePresence('note', 'create')
//            ->notEmpty('note');
//
//        $validator
//            ->integer('change_price')
//            ->requirePresence('change_price', 'create')
//            ->notEmpty('change_price');

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
//        $rules->add($rules->existsIn(['hotel_id'], 'Hotels'));

        return $rules;
    }
}
