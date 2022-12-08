<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LandTourUserPrices Model
 *
 * @property \App\Model\Table\LandToursTable|\Cake\ORM\Association\BelongsTo $LandTours
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\LandTourUserPrice get($primaryKey, $options = [])
 * @method \App\Model\Entity\LandTourUserPrice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LandTourUserPrice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LandTourUserPrice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandTourUserPrice|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandTourUserPrice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LandTourUserPrice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LandTourUserPrice findOrCreate($search, callable $callback = null, $options = [])
 */
class LandTourUserPricesTable extends Table
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

        $this->setTable('land_tour_user_prices');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('LandTours', [
            'foreignKey' => 'land_tour_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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

//        $validator
//            ->integer('price')
//            ->requirePresence('price', 'create')
//            ->notEmpty('price');

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
//        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
