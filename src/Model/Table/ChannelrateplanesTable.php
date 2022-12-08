<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Channelrateplanes Model
 *
 * @property \App\Model\Table\ChannelroomsTable|\Cake\ORM\Association\BelongsTo $ChannelRooms
 *
 * @method \App\Model\Entity\Channelrateplane get($primaryKey, $options = [])
 * @method \App\Model\Entity\Channelrateplane newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Channelrateplane[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Channelrateplane|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Channelrateplane|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Channelrateplane patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Channelrateplane[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Channelrateplane findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ChannelrateplanesTable extends Table
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

        $this->setTable('channelrateplanes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ChannelRooms', [
            'foreignKey' => 'channel_room_id',
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
//            ->scalar('rateplan_code')
//            ->maxLength('rateplan_code', 255)
//            ->requirePresence('rateplan_code', 'create')
//            ->notEmpty('rateplan_code');
//
//        $validator
//            ->scalar('name')
//            ->maxLength('name', 255)
//            ->requirePresence('name', 'create')
//            ->notEmpty('name');
//
//        $validator
//            ->integer('guest')
//            ->allowEmpty('guest');
//
//        $validator
//            ->integer('adult')
//            ->allowEmpty('adult');
//
//        $validator
//            ->integer('child')
//            ->allowEmpty('child');
//
//        $validator
//            ->integer('maxguest')
//            ->allowEmpty('maxguest');
//
//        $validator
//            ->integer('extraguest')
//            ->allowEmpty('extraguest');
//
//        $validator
//            ->integer('sale_revenue_type')
//            ->requirePresence('sale_revenue_type', 'create')
//            ->notEmpty('sale_revenue_type');
//
//        $validator
//            ->integer('sale_revenue')
//            ->requirePresence('sale_revenue', 'create')
//            ->notEmpty('sale_revenue');
//
//        $validator
//            ->allowEmpty('meals');

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
        $rules->add($rules->existsIn(['channel_room_id'], 'ChannelRooms'));

        return $rules;
    }
}
