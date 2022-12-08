<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BookingRooms Model
 *
 * @property \App\Model\Table\BookingsTable|\Cake\ORM\Association\BelongsTo $Bookings
 * @property \App\Model\Table\RoomsTable|\Cake\ORM\Association\BelongsTo $Rooms
 *
 * @method \App\Model\Entity\BookingRoom get($primaryKey, $options = [])
 * @method \App\Model\Entity\BookingRoom newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BookingRoom[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BookingRoom|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingRoom|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingRoom patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BookingRoom[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BookingRoom findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BookingRoomsTable extends Table
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

        $this->setTable('booking_rooms');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Bookings', [
            'foreignKey' => 'booking_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Rooms', [
            'foreignKey' => 'room_id',
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

        $validator
            ->scalar('room_id')
            ->requirePresence('room_id', 'create')
            ->notEmpty('room_id');

        $validator
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');

        $validator
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        $validator
            ->requirePresence('num_adult', 'create')
            ->notEmpty('num_adult');

        $validator
            ->requirePresence('num_children', 'create')
            ->notEmpty('num_children');

        $validator
            ->requirePresence('num_room', 'create')
            ->notEmpty('num_room');

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
        $rules->add($rules->existsIn(['room_id'], 'Rooms'));

        return $rules;
    }
}
