<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VinhmsbookingTransportations Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Vinhmsbookings
 *
 * @method \App\Model\Entity\VinhmsbookingTransportation get($primaryKey, $options = [])
 * @method \App\Model\Entity\VinhmsbookingTransportation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingTransportation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingTransportation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VinhmsbookingTransportation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VinhmsbookingTransportation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingTransportation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingTransportation findOrCreate($search, callable $callback = null, $options = [])
 */
class VinhmsbookingTransportationsTable extends Table
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

        $this->setTable('vinhmsbooking_transportations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

//        $this->belongsTo('Vinhmsbookings', [
//            'foreignKey' => 'vinhmsbooking_id'
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
//            ->integer('type')
//            ->requirePresence('type', 'create')
//            ->notEmpty('type');
//
//        $validator
//            ->scalar('transportation')
//            ->maxLength('transportation', 255)
//            ->requirePresence('transportation', 'create')
//            ->notEmpty('transportation');
//
//        $validator
//            ->scalar('station_code')
//            ->maxLength('station_code', 255)
//            ->requirePresence('station_code', 'create')
//            ->notEmpty('station_code');
//
//        $validator
//            ->scalar('flight_code')
//            ->maxLength('flight_code', 255)
//            ->requirePresence('flight_code', 'create')
//            ->notEmpty('flight_code');
//
//        $validator
//            ->integer('num_people')
//            ->requirePresence('num_people', 'create')
//            ->notEmpty('num_people');
//
//        $validator
//            ->dateTime('date')
//            ->requirePresence('date', 'create')
//            ->notEmpty('date');
//
//        $validator
//            ->scalar('time')
//            ->maxLength('time', 50)
//            ->requirePresence('time', 'create')
//            ->notEmpty('time');
//
//        $validator
//            ->scalar('note')
//            ->maxLength('note', 50)
//            ->requirePresence('note', 'create')
//            ->notEmpty('note');

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

        return $rules;
    }
}
