<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BookingLandtourAccessories Model
 *
 * @property \App\Model\Table\BookingsTable|\Cake\ORM\Association\BelongsTo $Bookings
 * @property \App\Model\Table\LandTourAccessoriesTable|\Cake\ORM\Association\BelongsTo $LandTourAccessories
 *
 * @method \App\Model\Entity\BookingLandtourAccessory get($primaryKey, $options = [])
 * @method \App\Model\Entity\BookingLandtourAccessory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BookingLandtourAccessory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BookingLandtourAccessory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingLandtourAccessory|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingLandtourAccessory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BookingLandtourAccessory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BookingLandtourAccessory findOrCreate($search, callable $callback = null, $options = [])
 */
class BookingLandtourAccessoriesTable extends Table
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

        $this->setTable('booking_landtour_accessories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Bookings', [
            'foreignKey' => 'booking_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('LandTourAccessories', [
            'foreignKey' => 'land_tour_accessory_id',
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
//        $rules->add($rules->existsIn(['booking_id'], 'Bookings'));
//        $rules->add($rules->existsIn(['land_tour_accessory_id'], 'LandTourAccessories'));

        return $rules;
    }
}
