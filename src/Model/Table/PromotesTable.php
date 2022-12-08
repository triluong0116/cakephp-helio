<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Promotes Model
 *
 * @method \App\Model\Entity\Promote get($primaryKey, $options = [])
 * @method \App\Model\Entity\Promote newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Promote[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Promote|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Promote|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Promote patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Promote[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Promote findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PromotesTable extends Table
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

        $this->setTable('promotes');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->belongsTo('Hotels', [
            'className' => 'Hotels',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['type' => P_BOOK_SHARE_HOTEL],
            'propertyName' => 'hotels'
        ]);
        $this->belongsTo('Locations', [
            'className' => 'Locations',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['type' => P_BOOK_SHARE_LOCATION],
            'propertyName' => 'locations'
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
//            ->scalar('title')
//            ->maxLength('title', 255)
//            ->requirePresence('title', 'create')
//            ->notEmpty('title');
//
//        $validator
//            ->scalar('slug')
//            ->maxLength('slug', 255)
//            ->requirePresence('slug', 'create')
//            ->notEmpty('slug');
//
//        $validator
//            ->integer('type')
//            ->requirePresence('type', 'create')
//            ->notEmpty('type');
//
//        $validator
//            ->integer('num_booking_share')
//            ->requirePresence('num_booking_share', 'create')
//            ->notEmpty('num_booking_share');
//
//        $validator
//            ->date('start_date')
//            ->requirePresence('start_date', 'create')
//            ->notEmpty('start_date');
//
//        $validator
//            ->date('end_date')
//            ->requirePresence('end_date', 'create')
//            ->notEmpty('end_date');
//
//        $validator
//            ->requirePresence('revenue', 'create')
//            ->notEmpty('revenue');

        return $validator;
    }
}
