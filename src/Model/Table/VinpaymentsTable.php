<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vinpayments Model
 *
 * @property \App\Model\Table\BookingsTable|\Cake\ORM\Association\BelongsTo $Bookings
 *
 * @method \App\Model\Entity\Vinpayment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Vinpayment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Vinpayment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Vinpayment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vinpayment|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vinpayment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Vinpayment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Vinpayment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VinpaymentsTable extends Table
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

        $this->setTable('vinpayments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Vinbookings', [
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
//            ->maxLength('invoice_information', 16777215)
//            ->requirePresence('invoice_information', 'create')
//            ->notEmpty('invoice_information');
//
//        $validator
//            ->scalar('images')
//            ->maxLength('images', 16777215)
//            ->requirePresence('images', 'create')
//            ->notEmpty('images');
//
//        $validator
//            ->scalar('address')
//            ->maxLength('address', 16777215)
//            ->requirePresence('address', 'create')
//            ->notEmpty('address');
//
//        $validator
//            ->integer('pay_object')
//            ->requirePresence('pay_object', 'create')
//            ->notEmpty('pay_object');
//
//        $validator
//            ->integer('check_type')
//            ->requirePresence('check_type', 'create')
//            ->notEmpty('check_type');
//
//        $validator
//            ->scalar('partner_information')
//            ->maxLength('partner_information', 16777215)
//            ->allowEmpty('partner_information');
//
//        $validator
//            ->scalar('payment_photo')
//            ->maxLength('payment_photo', 16777215)
//            ->allowEmpty('payment_photo');
//
//        $validator
//            ->scalar('merchtxnref')
//            ->maxLength('merchtxnref', 255)
//            ->allowEmpty('merchtxnref');

        return $validator;
    }
    public function validationPaymentType(Validator $validator)
    {

//        $validator
//            ->scalar('type')
//            ->requirePresence('type', 'create', 'Vui l??ng ch???n ph????ng th???c thanh to??n')
//            ->notEmpty('type', 'Vui l??ng ch???n ph????ng th???c thanh to??n');

        return $validator;
    }

    public function validationPaymentInvoice(Validator $validator) {
        $validator
            ->scalar('invoice')
            ->requirePresence('invoice', 'create', 'Ph???i ch???n Xu???t h??a ????n VAT ho???c Kh??ng xu???t h??a ????n')
            ->notEmpty('invoice', 'Ph???i ch???n Xu???t h??a ????n VAT ho???c Kh??ng xu???t h??a ????n');

        $validator
            ->scalar('images')
            ->requirePresence('images', 'create', 'Ph???i upload ???nh ch???p chuy???n kho???n th??nh c??ng')
            ->notEmpty('images', 'Ph???i upload ???nh ch???p chuy???n kho???n th??nh c??ng');
        return $validator;
    }

    public function validationPaymentExportInvoice(Validator $validator) {
        $validator
            ->scalar('invoice_information')
            ->requirePresence('invoice_information', 'create', 'Ph???i nh???p th??ng tin xu???t h??a ????n')
            ->notEmpty('invoice_information', 'Ph???i nh???p th??ng tin xu???t h??a ????n');

        return $validator;
    }

    public function validationPaymentAddress(Validator $validator) {
        $validator
            ->scalar('address')
            ->requirePresence('address', 'create', 'Ph???i nh???p ?????a ch???')
            ->notEmpty('address', 'Ph???i nh???p ?????a ch???');

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
//        $rules->add($rules->existsIn(['booking_id'], 'Bookings'));

        return $rules;
    }
}
