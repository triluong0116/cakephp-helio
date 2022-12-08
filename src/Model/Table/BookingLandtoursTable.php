<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BookingLandtours Model
 *
 * @property \App\Model\Table\BookingsTable|\Cake\ORM\Association\BelongsTo $Bookings
 * @property \App\Model\Table\LandToursTable|\Cake\ORM\Association\BelongsTo $Landtours
 *
 * @method \App\Model\Entity\BookingLandtour get($primaryKey, $options = [])
 * @method \App\Model\Entity\BookingLandtour newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BookingLandtour[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BookingLandtour|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingLandtour|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingLandtour patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BookingLandtour[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BookingLandtour findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BookingLandtoursTable extends Table
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

        $this->setTable('booking_landtours');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Bookings', [
            'foreignKey' => 'booking_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Landtours', [
            'foreignKey' => 'landtour_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PickUp', [
            'className' => 'LandTourDrivesurchages',
            'foreignKey' => 'pickup_id',
            'propertyName' => 'pick_up'
        ]);
        $this->belongsTo('DropDown', [
            'className' => 'LandTourDrivesurchages',
            'foreignKey' => 'drop_id',
            'propertyName' => 'drop_down'
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
            ->requirePresence('num_adult', 'create')
            ->notEmpty('num_adult');

        $validator
            ->requirePresence('num_children', 'create')
            ->notEmpty('num_children');

//        $validator
//            ->scalar('child_ages')
//            ->requirePresence('child_ages', 'create')
//            ->notEmpty('child_ages');

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
//        $rules->add($rules->existsIn(['landtour_id'], 'Landtours'));

        return $rules;
    }
}
