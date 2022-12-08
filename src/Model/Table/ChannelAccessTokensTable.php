<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChannelAccessTokens Model
 *
 * @method \App\Model\Entity\ChannelAccessTokens get($primaryKey, $options = [])
 * @method \App\Model\Entity\ChannelAccessTokens newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ChannelAccessTokens[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ChannelAccessTokens|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChannelAccessTokens|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChannelAccessTokens patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ChannelAccessTokens[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ChannelAccessTokens findOrCreate($search, callable $callback = null, $options = [])
 */
class ChannelAccessTokensTable extends Table
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

        $this->setTable('channel_access_tokens');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
//            ->scalar('access_token')
//            ->maxLength('access_token', 255)
//            ->requirePresence('access_token', 'create')
//            ->notEmpty('access_token');

        $validator
            ->dateTime('start_time')
            ->allowEmpty('start_time');

        $validator
            ->dateTime('expire_time')
            ->allowEmpty('expire_time');

        return $validator;
    }
}
