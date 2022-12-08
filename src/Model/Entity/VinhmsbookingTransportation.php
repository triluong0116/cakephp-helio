<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VinhmsbookingTransportation Entity
 *
 * @property int $id
 * @property int $type
 * @property string $transportation
 * @property string $station_code
 * @property string $flight_code
 * @property int $num_people
 * @property \Cake\I18n\FrozenTime $date
 * @property string $time
 * @property string $note
 * @property int|null $vinhmsbooking_id
 */
class VinhmsbookingTransportation extends Entity
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
