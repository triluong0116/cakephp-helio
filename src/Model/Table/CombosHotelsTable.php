<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CombosHotels Model
 *
 * @property \App\Model\Table\CombosTable|\Cake\ORM\Association\BelongsTo $Combos
 * @property \App\Model\Table\HotelsTable|\Cake\ORM\Association\BelongsTo $Hotels
 *
 * @method \App\Model\Entity\CombosHotel get($primaryKey, $options = [])
 * @method \App\Model\Entity\CombosHotel newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CombosHotel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CombosHotel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CombosHotel|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CombosHotel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CombosHotel[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CombosHotel findOrCreate($search, callable $callback = null, $options = [])
 */
class CombosHotelsTable extends Table
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

        $this->setTable('combos_hotels');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Combos', [
            'foreignKey' => 'combo_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Hotels', [
            'foreignKey' => 'hotel_id',
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

        $validator
            ->integer('days_attended')
            ->requirePresence('days_attended', 'create')
            ->notEmpty('days_attended');

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
        $rules->add($rules->existsIn(['combo_id'], 'Combos'));
        $rules->add($rules->existsIn(['hotel_id'], 'Hotels'));

        return $rules;
    }
}
