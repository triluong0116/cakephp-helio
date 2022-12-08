<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vinhmsbookings Model
 *
 * @property \App\Model\Table\VinhmsbookingGroupInforsTable|\Cake\ORM\Association\HasMany $VinhmsbookingGroupInfors
 * @property \App\Model\Table\VinhmsbookingRoomsTable|\Cake\ORM\Association\HasMany $VinhmsbookingRooms
 * @property |\Cake\ORM\Association\HasMany $VinhmsbookingTransportations
 *
 * @method \App\Model\Entity\Vinhmsbooking get($primaryKey, $options = [])
 * @method \App\Model\Entity\Vinhmsbooking newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Vinhmsbooking[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Vinhmsbooking|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vinhmsbooking|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vinhmsbooking patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Vinhmsbooking[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Vinhmsbooking findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VinhmsbookingsTable extends Table
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

        $this->setTable('vinhmsbookings');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('VinhmsbookingGroupInfors', [
            'foreignKey' => 'vinhmsbooking_id'
        ]);
        $this->hasMany('VinhmsbookingRooms', [
            'foreignKey' => 'vinhmsbooking_id'
        ]);
        $this->hasMany('VinhmsbookingTransportations', [
            'foreignKey' => 'vinhmsbooking_id'
        ]);
        $this->belongsTo('Hotels', [
            'foreignKey' => 'hotel_id'
        ]);
        $this->hasOne('Vinpayments', [
            'foreignKey' => 'booking_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
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
//            ->scalar('code')
//            ->maxLength('code', 50)
//            ->requirePresence('code', 'create')
//            ->notEmpty('code');
//
//        $validator
//            ->scalar('name')
//            ->maxLength('name', 255)
//            ->requirePresence('name', 'create')
//            ->notEmpty('name');
//
//        $validator
//            ->scalar('surname')
//            ->maxLength('surname', 255)
//            ->requirePresence('surname', 'create')
//            ->notEmpty('surname');
//
//        $validator
//            ->scalar('phone')
//            ->maxLength('phone', 50)
//            ->requirePresence('phone', 'create')
//            ->notEmpty('phone');
//
//        $validator
//            ->email('email')
//            ->requirePresence('email', 'create')
//            ->notEmpty('email');
//
//        $validator
//            ->scalar('nation')
//            ->maxLength('nation', 255)
//            ->requirePresence('nation', 'create')
//            ->notEmpty('nation');
//
//        $validator
//            ->scalar('country')
//            ->maxLength('country', 255)
//            ->requirePresence('country', 'create')
//            ->notEmpty('country');
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
//        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
