<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Withdraws Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Withdraw get($primaryKey, $options = [])
 * @method \App\Model\Entity\Withdraw newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Withdraw[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Withdraw|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Withdraw|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Withdraw patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Withdraw[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Withdraw findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WithdrawsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('withdraws');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
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

//        $validator
//                ->integer('amount')
//                ->requirePresence('amount', 'create')
//                ->notEmpty('amount');
//
//        $validator
//                ->scalar('bank_account')
//                ->maxLength('bank_account', 255)
//                ->requirePresence('bank_account', 'create')
//                ->notEmpty('bank_account');
//
//        $validator
//                ->integer('status')
//                ->requirePresence('status', 'create')
//                ->notEmpty('status');

        return $validator;
    }

    public function validationWithdraw(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->scalar('amount')
//                ->maxLength('username', 'Tên đăng nhập')
                ->requirePresence('amount', 'create')
                ->notEmpty('amount', 'Vui lòng nhập số tiền bạn muốn rút.');
//
//
//        $validator
//            ->scalar('screen_name')
//            ->maxLength('screen_name', 128)
//            ->requirePresence('screen_name', 'create')
//            ->notEmpty('screen_name');
//
//        $validator
//            ->email('email')
//            ->requirePresence('email', 'create')
//            ->notEmpty('email');
//
//        $validator
//            ->scalar('email_access_code')
//            ->maxLength('email_access_code', 128)
//            ->requirePresence('email_access_code', 'create')
//            ->notEmpty('email_access_code');
//        $validator
//            ->integer('is_active')
//            ->requirePresence('is_active', 'create')
//            ->notEmpty('is_active');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

}
