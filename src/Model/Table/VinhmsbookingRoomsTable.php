<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VinhmsbookingRooms Model
 *
 * @property \App\Model\Table\VinhmsbookingsTable|\Cake\ORM\Association\BelongsTo $Vinhmsbookings
 * @property \App\Model\Table\VinhmsPackagesTable|\Cake\ORM\Association\BelongsTo $VinhmsPackages
 * @property \App\Model\Table\VinhmsRoomsTable|\Cake\ORM\Association\BelongsTo $VinhmsRooms
 * @property \App\Model\Table\RoomsTable|\Cake\ORM\Association\BelongsTo $Rooms
 *
 * @method \App\Model\Entity\VinhmsbookingRoom get($primaryKey, $options = [])
 * @method \App\Model\Entity\VinhmsbookingRoom newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingRoom[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingRoom|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VinhmsbookingRoom|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VinhmsbookingRoom patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingRoom[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingRoom findOrCreate($search, callable $callback = null, $options = [])
 */
class VinhmsbookingRoomsTable extends Table
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

        $this->setTable('vinhmsbooking_rooms');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

//        $this->belongsTo('Vinhmsbookings', [
//            'foreignKey' => 'vinhmsbooking_id',
//            'joinType' => 'INNER'
//        ]);
//        $this->belongsTo('VinhmsPackages', [
//            'foreignKey' => 'vinhms_package_id',
//            'joinType' => 'INNER'
//        ]);
//        $this->belongsTo('VinhmsRooms', [
//            'foreignKey' => 'vinhms_room_id',
//            'joinType' => 'INNER'
//        ]);
//        $this->belongsTo('Rooms', [
//            'foreignKey' => 'room_id',
//            'joinType' => 'INNER'
//        ]);
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
//            ->integer('num_child')
//            ->requirePresence('num_child', 'create')
//            ->notEmpty('num_child');
//
//        $validator
//            ->scalar('customer_note')
//            ->requirePresence('customer_note', 'create')
//            ->notEmpty('customer_note');
//
//        $validator
//            ->scalar('detail_by_day')
//            ->requirePresence('detail_by_day', 'create')
//            ->notEmpty('detail_by_day');

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
//        $rules->add($rules->existsIn(['vinhmsbooking_id'], 'Vinhmsbookings'));
//        $rules->add($rules->existsIn(['vinhms_package_id'], 'VinhmsPackages'));
//        $rules->add($rules->existsIn(['vinhms_room_id'], 'VinhmsRooms'));
//        $rules->add($rules->existsIn(['room_id'], 'Rooms'));

        return $rules;
    }
}
