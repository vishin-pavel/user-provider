<?php
/**
 * 
 * @author Vishin Pavel
 * @date 30.04.15
 * @time 16:29
 */

namespace common\models\basic\db;

use yii\base\Exception;

class ActiveRecord extends \yii\db\ActiveRecord{
    public function getAttributesByScenario($scenario){
        $scenarios = $this->scenarios();
        if(!isset($scenarios[$scenario])) throw new Exception('Wrong scenario name for getting attributes');
        return $this->getAttributes($scenarios[$scenario]);
    }

}