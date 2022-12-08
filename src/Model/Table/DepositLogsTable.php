<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DepositLogs Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\DepositLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\DepositLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DepositLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DepositLog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DepositLog|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DepositLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DepositLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DepositLog findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DepositLogsTable extends Table
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

        $this->setTable('deposit_logs');
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

//        $validator
//            ->scalar('code')
//            ->maxLength('code', 50)
//            ->requirePresence('code', 'create')
//            ->notEmpty('code');
//
//        $validator
//            ->integer('amount')
//            ->requirePresence('amount', 'create')
//            ->notEmpty('amount');
//
//        $validator
//            ->scalar('images')
//            ->requirePresence('images', 'create')
//            ->notEmpty('images');
//
//        $validator
//            ->integer('type')
//            ->requirePresence('type', 'create')
//            ->notEmpty('type');
//
//        $validator
//            ->integer('is_approve')
//            ->requirePresence('is_approve', 'create')
//            ->notEmpty('is_approve');

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
//        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
