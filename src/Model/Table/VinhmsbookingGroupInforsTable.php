<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VinhmsbookingGroupInfors Model
 *
 * @property \App\Model\Table\VinhmsbookingsTable|\Cake\ORM\Association\BelongsTo $Vinhmsbookings
 *
 * @method \App\Model\Entity\VinhmsbookingGroupInfor get($primaryKey, $options = [])
 * @method \App\Model\Entity\VinhmsbookingGroupInfor newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingGroupInfor[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingGroupInfor|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VinhmsbookingGroupInfor|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VinhmsbookingGroupInfor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingGroupInfor[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VinhmsbookingGroupInfor findOrCreate($search, callable $callback = null, $options = [])
 */
class VinhmsbookingGroupInforsTable extends Table
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

        $this->setTable('vinhmsbooking_group_infors');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Vinhmsbookings', [
            'foreignKey' => 'vinhmsbooking_id',
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
//            ->scalar('name')
//            ->maxLength('name', 255)
//            ->requirePresence('name', 'create')
//            ->notEmpty('name');
//
//        $validator
//            ->dateTime('birthday')
//            ->requirePresence('birthday', 'create')
//            ->notEmpty('birthday');

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
        $rules->add($rules->existsIn(['vinhmsbooking_id'], 'Vinhmsbookings'));

        return $rules;
    }
}
