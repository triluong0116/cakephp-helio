<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestVouchers Model
 *
 * @method \App\Model\Entity\RequestVoucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\RequestVoucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RequestVoucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RequestVoucher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RequestVoucher|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RequestVoucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RequestVoucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RequestVoucher findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RequestVouchersTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('request_vouchers');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');
//
//        $validator
//                ->scalar('title')
//                ->maxLength('title', 255)
//                ->requirePresence('title', 'create')
//                ->notEmpty('title');
//
//        $validator
//                ->scalar('time')
//                ->maxLength('time', 255)
//                ->requirePresence('time', 'create')
//                ->notEmpty('time');
//
//        $validator
//                ->scalar('price')
//                ->maxLength('price', 64)
//                ->requirePresence('price', 'create')
//                ->notEmpty('price');
//
//        $validator
//                ->scalar('full_name')
//                ->maxLength('full_name', 64)
//                ->requirePresence('full_name', 'create')
//                ->notEmpty('full_name');
//
//        $validator
//                ->scalar('phone')
//                ->maxLength('phone', 64)
//                ->requirePresence('phone', 'create')
//                ->notEmpty('phone');
//
//        $validator
//                ->email('email')
//                ->requirePresence('email', 'create')
//                ->notEmpty('email');

        return $validator;
    }

    public function validationAddVoucher(Validator $validator) {
        $validator
                ->scalar('title')
                ->notEmpty('title', 'B???n ch??a nh???p t??n voucher');
        $validator
                ->scalar('time')
                ->notEmpty('time', 'B???n ch??a nh???p th???i gian');
        $validator
                ->scalar('price')
                ->notEmpty('price', 'B???n ch??a nh???p gi??');
        $validator
                ->scalar('full_name')
                ->notEmpty('full_name', 'B???n ch??a nh???p t??n');
        $validator
                ->scalar('phone')
                ->notEmpty('phone', 'B???n ch??a nh???p s??? ??i???n tho???i');
        $validator
                ->scalar('email')
                ->notEmpty('email', 'B???n ch??a nh???p Email');
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
//        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

}
