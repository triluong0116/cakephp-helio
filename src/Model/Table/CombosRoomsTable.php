<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CombosRooms Model
 *
 * @property \App\Model\Table\CombosTable|\Cake\ORM\Association\BelongsTo $Combos
 * @property \App\Model\Table\RoomsTable|\Cake\ORM\Association\BelongsTo $Rooms
 *
 * @method \App\Model\Entity\CombosRoom get($primaryKey, $options = [])
 * @method \App\Model\Entity\CombosRoom newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CombosRoom[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CombosRoom|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CombosRoom|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CombosRoom patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CombosRoom[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CombosRoom findOrCreate($search, callable $callback = null, $options = [])
 */
class CombosRoomsTable extends Table
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

        $this->setTable('combos_rooms');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Combos', [
            'foreignKey' => 'combo_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Rooms', [
            'foreignKey' => 'room_id',
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
        $rules->add($rules->existsIn(['combo_id'], 'Combos'));
        $rules->add($rules->existsIn(['room_id'], 'Rooms'));

        return $rules;
    }
}
