<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Payments Model
 *
 * @property \App\Model\Table\BookingsTable|\Cake\ORM\Association\BelongsTo $Bookings
 *
 * @method \App\Model\Entity\Payment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Payment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Payment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Payment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payment|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Payment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Payment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PaymentsTable extends Table
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

        $this->setTable('payments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Bookings', [
            'foreignKey' => 'booking_id',
            'joinType' => 'INNER'
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
//            ->requirePresence('type', 'create')
//            ->notEmpty('type');
//
//        $validator
//            ->requirePresence('invoice', 'create')
//            ->notEmpty('invoice');
//
//        $validator
//            ->scalar('invoice_information')
//            ->requirePresence('invoice_information', 'create')
//            ->notEmpty('invoice_information');
//
//        $validator
//            ->scalar('images')
//            ->requirePresence('images', 'create')
//            ->notEmpty('images');
//
//        $validator
//            ->scalar('address')
//            ->requirePresence('address', 'create')
//            ->notEmpty('address');

        return $validator;
    }

    public function validationPaymentType(Validator $validator)
    {

//        $validator
//            ->scalar('type')
//            ->requirePresence('type', 'create', 'Vui lòng chọn phương thức thanh toán')
//            ->notEmpty('type', 'Vui lòng chọn phương thức thanh toán');

        return $validator;
    }

    public function validationPaymentInvoice(Validator $validator) {
        $validator
            ->scalar('invoice')
            ->requirePresence('invoice', 'create', 'Phải chọn Xuất hóa đơn VAT hoặc Không xuất hóa đơn')
            ->notEmpty('invoice', 'Phải chọn Xuất hóa đơn VAT hoặc Không xuất hóa đơn');

        $validator
            ->scalar('images')
            ->requirePresence('images', 'create', 'Phải upload ảnh chụp chuyển khoản thành công')
            ->notEmpty('images', 'Phải upload ảnh chụp chuyển khoản thành công');
        return $validator;
    }

    public function validationPaymentExportInvoice(Validator $validator) {
        $validator
            ->scalar('invoice_information')
            ->requirePresence('invoice_information', 'create', 'Phải nhập thông tin xuất hóa đơn')
            ->notEmpty('invoice_information', 'Phải nhập thông tin xuất hóa đơn');

        return $validator;
    }

    public function validationPaymentAddress(Validator $validator) {
        $validator
            ->scalar('address')
            ->requirePresence('address', 'create', 'Phải nhập địa chỉ')
            ->notEmpty('address', 'Phải nhập địa chỉ');

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
        $rules->add($rules->existsIn(['booking_id'], 'Bookings'));

        return $rules;
    }
}
