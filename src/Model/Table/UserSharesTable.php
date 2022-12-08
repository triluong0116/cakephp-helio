<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserShares Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Objects
 *
 * @method \App\Model\Entity\UserShare get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserShare newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserShare[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserShare|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserShare|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserShare patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserShare[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserShare findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserSharesTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('user_shares');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('CounterCache', [
            'Users' => [
                'share_fb_count' => [
                    'conditions' => ['type' => FACEBOOK_POST_TYPE]
                ],
                'share_zalo_count' => [
                    'conditions' => ['type' => ZALO_POST_TYPE]
                ]
            ]
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
//        $this->belongsTo('Objects', [
//            'foreignKey' => 'object_id',
//            'joinType' => 'INNER'
//        ]);
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
//            ->integer('type')
//            ->requirePresence('type', 'create')
//            ->notEmpty('type');
//
//        $validator
//            ->integer('object_type')
//            ->requirePresence('object_type', 'create')
//            ->notEmpty('object_type');

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
//        $rules->add($rules->existsIn(['user_id'], 'Users'));
//        $rules->add($rules->existsIn(['object_id'], 'Objects'));

        return $rules;
    }

}
