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
    /** @var GET_ONE */
    const GET_ONE = true;

    /** @var GET_MY */
    const GET_MY = true;

    /** @var NEW_ONE */
    const NEW_ONE = true;

    /** @var UPDATE_ONE */
    const UPDATE_ONE = true;

    /** @var DELETE_ONE */
    const DELETE_ONE = true;


    /** @var DATA_ASSIGN */
    const DATA_ASSIGN = [];

    /** @var DATA_ASSIGN_UPDATE */
    const DATA_ASSIGN_UPDATE = true;

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [];

    /** @var NEW_ROW_ACTIVE */
    const NEW_ROW_ACTIVE = false;

    /** @var UPDATE_ROW_ACTIVE */
    const UPDATE_ROW_ACTIVE = false;

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
        $selfClass = $this->getClass();

        if (false === $selfClass::GET_MY) {
            throw new PublicException("Action forbidden");
        }
        if (null === $selfClass::MODEL) {
            return ['action'=> __FUNCTION__,];
        }

        // dynamic action
        $model = $selfClass::MODEL;

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
        $selfClass = $this->getClass();

        if (false === $selfClass::NEW_ONE) {
            throw new PublicException("Action forbidden");
        }
        
        if (null === $selfClass::MODEL) {
            return ['action'=> __FUNCTION__,];
        }

        /**
         * Dynamic action
         * Data Validation
         */
        $validatorName = $selfClass::VALIDATOR;

        $validator = new $validatorName();
        $postData = $this->request->get();

        $errors = $validator->validate($postData);

        if (0 !== count($errors)) {
            throw new PublicException($errors[0]->getMessage(), StatusCodes::HTTP_BAD_REQUEST);
        }

        /**
         * Data Insert
         */
        $modelName = $selfClass::MODEL;

        $row = new $modelName();

        // client input
        $row->assign($postData, $selfClass::DATA_ASSIGN);

        // controller inputs
        $row->assign($this->foreignkeys(), $selfClass::FOREIGN_KEYS);

        // row status
        $row->setActive($selfClass::NEW_ROW_ACTIVE);

        if (false === $row->create()) {
            throw new \Exception(implode(',', $row->getMessages()));
        }

        return [
            'message' => $selfClass::ROW_NAME  . ' created successfully',
            $selfClass::ROW_NAME => $row
        ];
    }

    /**
     * Get One Row
     *
     * @return array|null
     */
    public function getOne(string $rowId) :? array
    {
        $selfClass = $this->getClass();

        if (false === $selfClass::GET_ONE) {
            throw new PublicException("Action forbidden");
        }

        self::validUUIDOrThrowException($rowId);
        if (null === $selfClass::MODEL) {
            return ['action'=> __FUNCTION__,'id'=>$rowId];
        }

        /**
         * Dynamic Action
         * Find One Row
         */
        $modelName = $selfClass::MODEL;
        $findParams = $this->mixModelFindParams([
            'id = :id:',
            'bind'=> [
                'id'=> $rowId
            ]
        ]);

        $row = self::findFirstOrThrowException($modelName, $findParams);

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
        $selfClass = $this->getClass();

        if (false === $selfClass::UPDATE_ONE) {
            throw new PublicException("Action forbidden");
        }
        
        self::validUUIDOrThrowException($rowId);
        if (null === $selfClass::MODEL) {
            return ['action'=> __FUNCTION__,'id'=>$rowId];
        }

        /**
         * Dynamic action
         * Data Validation
         */
        $validatorName = $selfClass::VALIDATOR;

        $validator = new $validatorName();
        $postData = $this->request->get();

        $errors = $validator->validate($postData);

        if (0 !== count($errors)) {
            throw new PublicException($errors[0]->getMessage(), StatusCodes::HTTP_BAD_REQUEST);
        }

        /**
         * Data Update
         */
        $modelName = $selfClass::MODEL;
        $findParams = $this->mixModelFindParams([
            'id = :id:',
            'bind'=> [
                'id'=> $rowId
            ]
        ]);

        $row = self::findFirstOrThrowException($modelName, $findParams);

        $updateColumns = $selfClass::DATA_ASSIGN_UPDATE;
        $updateColumns = (false === $updateColumns) ? [] : $updateColumns;
        $updateColumns = (true  ===  $updateColumns) ? $selfClass::DATA_ASSIGN : $updateColumns;

        // client input
        $row->assign($postData, $updateColumns);

        // row status
        $row->setActive($selfClass::UPDATE_ROW_ACTIVE);

        if (false === $row->update()) {
            throw new \Exception(implode(',', $row->getMessages()));
        }

        return [
            'message' => $selfClass::ROW_NAME  . ' updated successfully',
            $selfClass::ROW_NAME => $row
        ];
    }

    /**
     * Delete One Row
     *
     * @return array|null
     */
    public function deleteOne(string $rowId) :? array
    {
        $selfClass = $this->getClass();

        if (false === $selfClass::DELETE_ONE) {
            throw new PublicException("Action forbidden");
        }

        self::validUUIDOrThrowException($rowId);
        if (null === $selfClass::MODEL) {
            return ['action'=> __FUNCTION__,'id'=>$rowId];
        }

        /**
         * Data Delete
         */
        $modelName = $selfClass::MODEL;
        $findParams = $this->mixModelFindParams([
            'id = :id:',
            'bind'=> [
                'id'=> $rowId
            ]
        ]);

        $row = self::findFirstOrThrowException($modelName, $findParams);
        
        if (false === $row->delete()) {
            throw new \Exception(implode(',', $row->getMessages()));
        }

        return [
            'message' => $selfClass::ROW_NAME  . ' deleted successfully',
            $selfClass::ROW_NAME => [
                'id'=> $rowId
            ]
        ];
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


    /**
     * Find Or Throw Exception
     *
     * @param string $modelName
     * @param array|null $findParams
     * @return void
     */
    public static function findFirstOrThrowException(string $modelName, ?array $findParams)
    {
        $row = $modelName::findFirst($findParams);
        if (true === is_null($row)) {
            throw new PublicException("Row does not exist !", StatusCodes::HTTP_NOT_FOUND);
        }


        return $row;
    }
}
