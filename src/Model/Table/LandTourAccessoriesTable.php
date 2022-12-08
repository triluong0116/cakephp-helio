<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LandTourAccessories Model
 *
 * @property \App\Model\Table\LandToursTable|\Cake\ORM\Association\BelongsTo $LandTours
 * @property \App\Model\Table\BookingLandtourAccessoriesTable|\Cake\ORM\Association\HasMany $BookingLandtourAccessories
 *
 * @method \App\Model\Entity\LandTourAccessory get($primaryKey, $options = [])
 * @method \App\Model\Entity\LandTourAccessory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LandTourAccessory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LandTourAccessory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandTourAccessory|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandTourAccessory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LandTourAccessory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LandTourAccessory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LandTourAccessoriesTable extends Table
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

        $this->setTable('land_tour_accessories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('LandTours', [
            'foreignKey' => 'land_tour_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('BookingLandtourAccessories', [
            'foreignKey' => 'land_tour_accessory_id'
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
//            ->scalar('name')
//            ->maxLength('name', 255)
//            ->requirePresence('name', 'create')
//            ->notEmpty('name');

//        $validator
//            ->integer('child_price')
//            ->requirePresence('child_price', 'create')
//            ->notEmpty('child_price');
//
//        $validator
//            ->integer('adult_price')
//            ->requirePresence('adult_price', 'create')
//            ->notEmpty('adult_price');

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
//        $rules->add($rules->existsIn(['land_tour_id'], 'LandTours'));

        return $rules;
    }
}
