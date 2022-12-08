<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BookingsUsers Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\BookingsTable|\Cake\ORM\Association\BelongsTo $Bookings
 *
 * @method \App\Model\Entity\BookingsUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\BookingsUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BookingsUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BookingsUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingsUser|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingsUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BookingsUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BookingsUser findOrCreate($search, callable $callback = null, $options = [])
 */
class BookingsUsersTable extends Table
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

        $this->setTable('bookings_users');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Bookings', [
            'foreignKey' => 'booking_id',
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
            ->requirePresence('id', 'create')
            ->notEmpty('id');

        $validator
            ->integer('revenue')
            ->requirePresence('revenue', 'create')
            ->notEmpty('revenue');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['booking_id'], 'Bookings'));

        return $rules;
    }
}
