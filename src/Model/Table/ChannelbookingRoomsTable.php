<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChannelbookingRooms Model
 *
 * @property \App\Model\Table\ChannelbookingsTable|\Cake\ORM\Association\BelongsTo $Channelbookings
 * @property \App\Model\Table\ChannelroomsTable|\Cake\ORM\Association\BelongsTo $Channelrooms
 * @property \App\Model\Table\ChannelrateplanesTable|\Cake\ORM\Association\BelongsTo $Channelrateplanes
 *
 * @method \App\Model\Entity\ChannelbookingRoom get($primaryKey, $options = [])
 * @method \App\Model\Entity\ChannelbookingRoom newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ChannelbookingRoom[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ChannelbookingRoom|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChannelbookingRoom|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChannelbookingRoom patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ChannelbookingRoom[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ChannelbookingRoom findOrCreate($search, callable $callback = null, $options = [])
 */
class ChannelbookingRoomsTable extends Table
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

        $this->setTable('channelbooking_rooms');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Channelbookings', [
            'foreignKey' => 'channelbooking_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Channelrooms', [
            'foreignKey' => 'channelroom_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Channelrateplanes', [
            'foreignKey' => 'channelrateplane_id',
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
//            ->integer('room_index')
//            ->requirePresence('room_index', 'create')
//            ->notEmpty('room_index');
//
//        $validator
//            ->scalar('channelroom_code')
//            ->maxLength('channelroom_code', 255)
//            ->requirePresence('channelroom_code', 'create')
//            ->notEmpty('channelroom_code');
//
//        $validator
//            ->scalar('channelrateplan_code')
//            ->maxLength('channelrateplan_code', 255)
//            ->requirePresence('channelrateplan_code', 'create')
//            ->notEmpty('channelrateplan_code');
//
//        $validator
//            ->integer('num_adult')
//            ->requirePresence('num_adult', 'create')
//            ->notEmpty('num_adult');
//
//        $validator
//            ->integer('num_kid')
//            ->requirePresence('num_kid', 'create')
//            ->notEmpty('num_kid');
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
//            ->integer('status')
//            ->requirePresence('status', 'create')
//            ->notEmpty('status');
//
//        $validator
//            ->dateTime('checkin')
//            ->requirePresence('checkin', 'create')
//            ->notEmpty('checkin');
//
//        $validator
//            ->dateTime('checkout')
//            ->requirePresence('checkout', 'create')
//            ->notEmpty('checkout');
//
//        $validator
//            ->scalar('customer_note')
//            ->requirePresence('customer_note', 'create')
//            ->notEmpty('customer_note');

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
//        $rules->add($rules->existsIn(['channelbooking_id'], 'Channelbookings'));
//        $rules->add($rules->existsIn(['channelroom_id'], 'Channelrooms'));
//        $rules->add($rules->existsIn(['channelrateplane_id'], 'Channelrateplanes'));

        return $rules;
    }
}
