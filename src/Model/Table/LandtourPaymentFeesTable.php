<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LandtourPaymentFees Model
 *
 * @method \App\Model\Entity\LandtourPaymentFee get($primaryKey, $options = [])
 * @method \App\Model\Entity\LandtourPaymentFee newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LandtourPaymentFee[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LandtourPaymentFee|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandtourPaymentFee|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandtourPaymentFee patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LandtourPaymentFee[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LandtourPaymentFee findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LandtourPaymentFeesTable extends Table
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

        $this->setTable('landtour_payment_fees');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
//            ->scalar('detail')
//            ->maxLength('detail', 255)
//            ->requirePresence('detail', 'create')
//            ->notEmpty('detail');
//
//        $validator
//            ->scalar('partner_name')
//            ->maxLength('partner_name', 255)
//            ->requirePresence('partner_name', 'create')
//            ->notEmpty('partner_name');
//
//        $validator
//            ->scalar('partnet_information')
//            ->maxLength('partnet_information', 255)
//            ->requirePresence('partnet_information', 'create')
//            ->notEmpty('partnet_information');
//
//        $validator
//            ->integer('single_price')
//            ->requirePresence('single_price', 'create')
//            ->notEmpty('single_price');
//
//        $validator
//            ->integer('amount')
//            ->requirePresence('amount', 'create')
//            ->notEmpty('amount');
//
//        $validator
//            ->integer('total')
//            ->requirePresence('total', 'create')
//            ->notEmpty('total');
//
//        $validator
//            ->integer('payment_status')
//            ->requirePresence('payment_status', 'create')
//            ->notEmpty('payment_status');
//
//        $validator
//            ->integer('payment_type')
//            ->requirePresence('payment_type', 'create')
//            ->notEmpty('payment_type');
//
//        $validator
//            ->dateTime('date')
//            ->requirePresence('date', 'create')
//            ->notEmpty('date');

        return $validator;
    }
}
