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

    /** @var DATA_ASSIGN_UPDATE */
    const DATA_ASSIGN_UPDATE = [];

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [];

    /** @var VALIDATOR */
    const VALIDATOR = EmptyValidator::class;

    /** @var MODEL */
    const MODEL = null;

    /** @var ROW_NAME */
    const ROW_NAME = 'row';

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

        /**
         * Dynamic action
         * Data Validation
         */
        $validatorName = $this->getClass()::VALIDATOR;

        $validator = new $validatorName();
        $postData = $this->request->get();

        $errors = $validator->validate($postData);

        if (0 !== count($errors)) {
            throw new PublicException($errors[0]->getMessage(), StatusCodes::HTTP_BAD_REQUEST);
        }

        /**
         * Data Insert
         */
        $modelName = $this->getClass()::MODEL;

        $row = new $modelName();

        // client input
        $row->assign($postData, $this->getClass()::DATA_ASSIGN);

        // controller inputs
        $row->assign($this->foreignkeys(), $this->getClass()::FOREIGN_KEYS);

        // enable row
        $row->setActive(true);

        if (false === $row->create()) {
            throw new \Exception(implode(',', $row->getMessages()));
        }

        return [
            'message' => $this->getClass()::ROW_NAME  . ' created successfully',
            $this->getClass()::ROW_NAME => $row
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
        $row = [];

        return [
            'row'=> $row
        ];
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

        /**
         * Dynamic action
         * Data Validation
         */
        $validatorName = $this->getClass()::VALIDATOR;

        $validator = new $validatorName();
        $postData = $this->request->get();

        $errors = $validator->validate($postData);

        if (0 !== count($errors)) {
            throw new PublicException($errors[0]->getMessage(), StatusCodes::HTTP_BAD_REQUEST);
        }

        /**
         * Data Insert
         */
        $modelName = $this->getClass()::MODEL;
        $findParams = $this->mixModelFindParams([
            'id = :id:',
            'bind'=> [
                'id'=> $rowId
            ]
        ]);

        $row = $modelName::findFirst($findParams);

        // client input
        $row->assign($postData, $this->getClass()::DATA_ASSIGN_UPDATE);

        // enable row
        $row->setActive(true);

        if (false === $row->update()) {
            throw new \Exception(implode(',', $row->getMessages()));
        }

        return [
            'message' => $this->getClass()::ROW_NAME  . ' updated successfully',
            $this->getClass()::ROW_NAME => $row
        ];


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
    /**
     * Return Mix Model Find Params
     *
     * @return array|null
     */
    protected function mixModelFindParams(array $params = []) :? array
    {
        $modelParams = $this->modelFindParams();
        if (true === is_null($modelParams)) {
            return $params;
        }

        $newModelParams = [
            $modelParams[0] . ' and ' . $params[0],
            'bind'=> $modelParams['bind'] + $params['bind']
        ];
        
        // remove first condition && bind
        array_shift($params);
        array_shift($modelParams);
        unset($params['bind']);
        unset($modelParams['bind']);

        
        return array_merge($newModelParams, array_merge($params, $modelParams));
    }
}
