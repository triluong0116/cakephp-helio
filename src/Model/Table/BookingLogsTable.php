<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BookingLogs Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\BookingsTable|\Cake\ORM\Association\BelongsTo $Bookings
 *
 * @method \App\Model\Entity\BookingLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\BookingLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BookingLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BookingLog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingLog|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BookingLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BookingLog findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BookingLogsTable extends Table
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

        $this->setTable('booking_logs');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
//        $this->belongsTo('Bookings', [
//            'foreignKey' => 'booking_id',
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

        $validator
            ->scalar('code')
            ->maxLength('code', 50)
            ->requirePresence('code', 'create')
            ->notEmpty('code');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmpty('title');

//        $validator
//            ->scalar('comment')
//            ->maxLength('comment', 255)
//            ->requirePresence('comment', 'create')
//            ->notEmpty('comment');

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
//        $rules->add($rules->existsIn(['booking_id'], 'Bookings'));

        return $rules;
    }
}
