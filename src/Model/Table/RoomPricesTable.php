<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RoomPrices Model
 *
 * @property \App\Model\Table\RoomsTable|\Cake\ORM\Association\BelongsTo $Rooms
 *
 * @method \App\Model\Entity\RoomPrice get($primaryKey, $options = [])
 * @method \App\Model\Entity\RoomPrice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RoomPrice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RoomPrice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RoomPrice|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RoomPrice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RoomPrice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RoomPrice findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RoomPricesTable extends Table
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

        $this->setTable('room_prices');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Rooms', [
            'foreignKey' => 'room_id'
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
//            ->date('room_day')
//            ->allowEmpty('room_day');
//
//        $validator
//            ->integer('price')
//            ->allowEmpty('price');
//
//        $validator
//            ->allowEmpty('available');
//
//        $validator
//            ->allowEmpty('type');

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
        $rules->add($rules->existsIn(['room_id'], 'Rooms'));

        return $rules;
    }
}
