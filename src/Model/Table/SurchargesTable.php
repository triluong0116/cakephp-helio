<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Surcharges Model
 *
 * @property |\Cake\ORM\Association\BelongsToMany $Hotels
 *
 * @method \App\Model\Entity\Surcharge get($primaryKey, $options = [])
 * @method \App\Model\Entity\Surcharge newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Surcharge[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Surcharge|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Surcharge|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Surcharge patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Surcharge[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Surcharge findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurchargesTable extends Table
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

        $this->setTable('surcharges');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Hotels', [
            'through' => 'HotelsSurchargesRelations',
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
//            ->allowEmpty('name');
//
//        $validator
//            ->allowEmpty('type');

        return $validator;
    }
}
