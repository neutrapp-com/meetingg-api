<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Auth;

use Phalcon\Validation as EmptyValidator;

use Meetingg\Http\StatusCodes;
use Meetingg\Exception\PublicException;

/**
 *  Landing Index Controller
 */
class ApiModelController extends AuthentifiedController
{
    /** @var GET_ONE */
    const GET_ONE = true;

    /** @var GET_MY */
    const GET_MY = true;

    /** @var INSERT_ONE */
    const INSERT_ONE = true;

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

    /** @var FOREIGN_KEYS */
    const PRIMARY_KEYS = ['id'];

    /** @var INSERT_ROW_ACTIVE */
    const INSERT_ROW_ACTIVE = false;

    /** @var UPDATE_ROW_ACTIVE */
    const UPDATE_ROW_ACTIVE = false;

    /** @var VALIDATOR */
    const VALIDATOR = EmptyValidator::class;

    /** @var MODEL */
    const MODEL = null;

    /**
     * Get My Row
     *
     * @return array|null
     */
    public function getMy() : object
    {
        $selfClass = $this->getClass();

        if (false === $selfClass::GET_MY) {
            throw new PublicException("Action forbidden", StatusCodes::HTTP_UNAUTHORIZED);
        }
        if (null === $selfClass::MODEL) {
            return ['action'=> __FUNCTION__,];
        }

        // dynamic action
        $model = $selfClass::MODEL;

        return $model::find($this->modelFindParams());
    }

    /**
     * New One Row
     *
     * @return self|null
     */
    public function newOne() : object
    {
        $selfClass = $this->getClass();

        if (false === $selfClass::INSERT_ONE) {
            throw new PublicException("Action forbidden", StatusCodes::HTTP_UNAUTHORIZED);
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
        $row->setActive($selfClass::INSERT_ROW_ACTIVE);

        if (false === $row->create()) {
            throw new PublicException(implode(',', $row->getMessages()), StatusCodes::HTTP_BAD_REQUEST);
        }

        return $row;
    }

    /**
     * Get One Row
     *
     * @param array $data
     * @return object|null
     */
    public function getOne(array $data = []) : object
    {
        $selfClass = $this->getClass();

        if (false === $selfClass::GET_ONE) {
            throw new PublicException("Action forbidden", StatusCodes::HTTP_UNAUTHORIZED);
        }

        if (null === $selfClass::MODEL) {
            return ['action'=> __FUNCTION__,'data'=> $data];
        }

        /**
         * Dynamic Action
         * Find One Row
         */
        $modelName = $selfClass::MODEL;
        $findParams = $this->generateFindParams($data);

        /**
         * Model Query Result
         */
        return self::findFirstOrThrowException($modelName, $findParams);
    }

    /**
     * Update One Row
     *
     * @param array $data
     * @return array
     */
    public function updateOne(array $data = []) : object
    {
        $selfClass = $this->getClass();

        if (false === $selfClass::UPDATE_ONE) {
            throw new PublicException("Action forbidden", StatusCodes::HTTP_UNAUTHORIZED);
        }
        
        if (null === $selfClass::MODEL) {
            return ['action'=> __FUNCTION__,'data'=>$data];
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
         * Dynamic Action
         * Find One Row
         */
        $modelName = $selfClass::MODEL;
        $findParams = $this->generateFindParams($data);
    
        $row = self::findFirstOrThrowException($modelName, $findParams);

        $updateColumns = $selfClass::DATA_ASSIGN_UPDATE;
        $updateColumns = (false === $updateColumns) ? [] : $updateColumns;
        $updateColumns = (true  ===  $updateColumns) ? $selfClass::DATA_ASSIGN : $updateColumns;

        // client input
        $row->assign($postData, $updateColumns);

        // row status
        $row->setActive($selfClass::UPDATE_ROW_ACTIVE);

        if (false === $row->update()) {
            throw new PublicException(implode(',', $row->getMessages()), StatusCodes::HTTP_BAD_REQUEST);
        }

        return $row;
    }

    /**
     * Delete One Row
     *
     * @param array $data
     * @return array|null
     */
    public function deleteOne(array $data = []) : object
    {
        $selfClass = $this->getClass();

        if (false === $selfClass::DELETE_ONE) {
            throw new PublicException("Action forbidden", StatusCodes::HTTP_UNAUTHORIZED);
        }

        if (null === $selfClass::MODEL) {
            return ['action'=> __FUNCTION__, 'data'=>$data];
        }

        /**
         * Dynamic Action
         * Find One Row
         */
        $modelName = $selfClass::MODEL;
        $findParams = $this->generateFindParams($data);

        $row = self::findFirstOrThrowException($modelName, $findParams);
        
        if (false === $row->delete()) {
            throw new PublicException(implode(',', $row->getMessages()), StatusCodes::HTTP_BAD_REQUEST);
        }

        return $row;
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
        if (true === is_null($modelParams) || 0 === count($modelParams)) {
            return $params;
        }
        if (true === is_null($params) || 0 === count($params)) {
            return $modelParams;
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
     * Generate Find Params By Primary Keys
     *
     * @param [type] $data
     * @return array
     */
    protected function generateFindParams($data) : array
    {
        /**
         * Dynamic Query Condition Generate With Primary Keys
         */
        $queryCondition = implode(" and ", array_map(function ($key) {
            return "$key = :$key:";
        }, $this->getClass()::PRIMARY_KEYS));

        $bindData = [];
        foreach ($this->getClass()::PRIMARY_KEYS as $key) {
            $bindData[$key] = $data[$key] ?? null;
        }
 
        /**
         * Final Model Query Params
         */
        $findParams = $this->mixModelFindParams([
            $queryCondition,
            'bind'=> $bindData
        ]);

        return $findParams;
    }

    /**
     * Find Or Throw Exception
     *
     * @param string $modelName
     * @param array|null $findParams
     * @return object|null
     */
    public static function findFirstOrThrowException(string $modelName, ?array $findParams) : object
    {
        $row = $modelName::findFirst($findParams);
        if (true === is_null($row)) {
            throw new PublicException("Row does not exist !", StatusCodes::HTTP_NOT_FOUND);
        }

        return $row;
    }
}
