<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VinhmsAccessToken Entity
 *
 * @property int $id
 * @property string $access_token
 * @property \Cake\I18n\FrozenTime|null $start_time
 * @property \Cake\I18n\FrozenTime|null $expire_time
 */
class ChannelAccessTokens extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'access_token' => true,
        'start_time' => true,
        'expire_time' => true
    ];
}
