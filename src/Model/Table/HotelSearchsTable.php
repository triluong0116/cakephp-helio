<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HotelSearchs Model
 *
 * @property \App\Model\Table\RoomsTable|\Cake\ORM\Association\BelongsTo $Rooms
 * @property \App\Model\Table\PricesTable|\Cake\ORM\Association\BelongsTo $Prices
 *
 * @method \App\Model\Entity\HotelSearch get($primaryKey, $options = [])
 * @method \App\Model\Entity\HotelSearch newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HotelSearch[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HotelSearch|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HotelSearch|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HotelSearch patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HotelSearch[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HotelSearch findOrCreate($search, callable $callback = null, $options = [])
 */
class HotelSearchsTable extends Table
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

        $this->setTable('hotel_searchs');

        $this->belongsTo('Rooms', [
            'foreignKey' => 'room_id'
        ]);
        $this->belongsTo('Prices', [
            'foreignKey' => 'price_id'
        ]);
        $this->hasMany('Favourites', [
            'className' => 'Favourites',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => HOTEL],
            'propertyName' => 'favourites',
            'joinType' => 'LEFT'
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
            ->requirePresence('id', 'create')
            ->notEmpty('id');

//        $validator
//            ->scalar('NAME')
//            ->maxLength('NAME', 255)
//            ->requirePresence('NAME', 'create')
//            ->notEmpty('NAME');
//
//        $validator
//            ->scalar('location_name')
//            ->maxLength('location_name', 255)
//            ->allowEmpty('location_name');
//
//        $validator
//            ->scalar('room_name')
//            ->maxLength('room_name', 255)
//            ->allowEmpty('room_name');
//
//        $validator
//            ->date('single_day')
//            ->allowEmpty('single_day');
//
//        $validator
//            ->scalar('WEEKDAY')
//            ->maxLength('WEEKDAY', 255)
//            ->requirePresence('WEEKDAY', 'create')
//            ->notEmpty('WEEKDAY');
//
//        $validator
//            ->scalar('weekend')
//            ->maxLength('weekend', 255)
//            ->requirePresence('weekend', 'create')
//            ->notEmpty('weekend');
//
//        $validator
//            ->scalar('day_name')
//            ->maxLength('day_name', 9)
//            ->allowEmpty('day_name');
//
//        $validator
//            ->allowEmpty('price_day');
//
//        $validator
//            ->allowEmpty('price_type');

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
//        $rules->add($rules->existsIn(['room_id'], 'Rooms'));
//        $rules->add($rules->existsIn(['price_id'], 'Prices'));

        return $rules;
    }
}
