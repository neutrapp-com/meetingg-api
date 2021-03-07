<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Auth;

use Meetingg\Exception\PublicException;
use Meetingg\Http\StatusCodes;
use Phalcon\Validation as EmptyValidator;

/**
 *  Landing Index Controller
 */
class ApiModelController extends AuthentifiedController
{
    /** @var DATA_ASSIGN */
    const DATA_ASSIGN = [];

    /** @var FORGING_KEYS */
    const FORGING_KEYS = [];

    /** @var VALIDATOR */
    const VALIDATOR = EmptyValidator::class;

    /** @var MODEL */
    const MODEL = null;

    /**
     * Get My Row
     *
     * @return array|null
     */
    public function getMy() :? array
    {
        if (null === $this->getClass()::MODEL) {
            return ['action'=> __FUNCTION__,];
        }

        // dynamic action
        $model = $this->getClass()::MODEL;

        $rows = $model::find($this->modelFindParams());

        return [
            'rows' => $rows
        ];
    }
    /**
     * New One Row
     *
     * @return array|null
     */
    public function newOne() :? array
    {
        if (null === $this->getClass()::MODEL) {
            return ['action'=> __FUNCTION__,];
        }

        // dynamic action
        // validator name
        $validatorName = $this->getClass()::VALIDATOR;

        $validator = new $validatorName();
        $postData = $this->request->get();

        $errors = $validator->validate($postData);

        if (0 !== count($errors)) {
            throw new PublicException($errors[0]->getMessage(), StatusCodes::HTTP_BAD_REQUEST);
        }

        // model name
        $model = $this->getClass()::MODEL;

        $rows = $model::find($this->modelFindParams());

        return [
            'rows' => $rows
        ];
    }

    /**
     * Get One Row
     *
     * @return array|null
     */
    public function getOne(string $rowId) :? array
    {
        self::validUUIDOrThrowException($rowId);
        if (null === $this->getClass()::MODEL) {
            return ['action'=> __FUNCTION__,'id'=>$rowId];
        }

        // dynamic action


        return [];
    }

    /**
     * Update One Row
     *
     * @return array|null
     */
    public function updateOne(string $rowId) :? array
    {
        self::validUUIDOrThrowException($rowId);
        if (null === $this->getClass()::MODEL) {
            return ['action'=> __FUNCTION__,'id'=>$rowId];
        }

        // dynamic action


        return [];
    }

    /**
     * Delete One Row
     *
     * @return array|null
     */
    public function deleteOne(string $rowId) :? array
    {
        self::validUUIDOrThrowException($rowId);
        if (null === $this->getClass()::MODEL) {
            return ['action'=> __FUNCTION__,'id'=>$rowId];
        }

        // dynamic action


        return [];
    }

    /**
     * Get Called Class
     *
     * @return string
     */
    protected function getClass() : string
    {
        return get_called_class();
    }

    /**
     * Foreign Keys
     *
     * @return array
     */
    protected function foreignkeys() : array
    {
        return [];
    }

    /**
     * Return Model Find Params
     *
     * @return array|null
     */
    protected function modelFindParams() :? array
    {
        return null;
    }
}
