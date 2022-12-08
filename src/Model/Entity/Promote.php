<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Promote Entity
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $type
 * @property int $num_booking_share
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property int $revenue
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Promote extends Entity
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
        '*' => true,
        'id' => false
    ];
}
