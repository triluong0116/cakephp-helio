<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HomeStaysCategories Model
 *
 * @property \App\Model\Table\HomeStaysTable|\Cake\ORM\Association\BelongsTo $HomeStays
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsTo $Categories
 *
 * @method \App\Model\Entity\HomeStaysCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\HomeStaysCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HomeStaysCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HomeStaysCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HomeStaysCategory|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HomeStaysCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HomeStaysCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HomeStaysCategory findOrCreate($search, callable $callback = null, $options = [])
 */
class HomeStaysCategoriesTable extends Table
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

        $this->setTable('home_stays_categories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('HomeStays', [
            'foreignKey' => 'home_stay_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
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
        $rules->add($rules->existsIn(['home_stay_id'], 'HomeStays'));
        $rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }
}
