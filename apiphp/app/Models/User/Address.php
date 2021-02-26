<?php

namespace Meetingg\Models\User;

use Meetingg\Models\BaseModel;

class Address extends BaseModel
{

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $city_id;

    /**
     *
     * @var string
     */
    public $country_id;

    /**
     *
     * @var string
     */
    public $street;

    /**
     *
     * @var string
     */
    public $postal_code;

    /**
     *
     * @var string
     */
    public $statename;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $created_ip;

    /**
     *
     * @var string
     */
    public $updated_ip;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setDefaultSchema();
        $this->setSource("user_address");

        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
        $this->belongsTo('city_id', 'Meetingg\Models\City', 'id', ['alias' => 'City']);
        $this->belongsTo('country_id', 'Meetingg\Models\Country', 'id', ['alias' => 'Country']);
    }
}
