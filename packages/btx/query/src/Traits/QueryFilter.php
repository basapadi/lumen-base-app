<?php
namespace Btx\QueryFilter\Traits;

use Illuminate\Database\Eloquent\Builder as eloBuilder;
use Btx\QueryFilter\Statics\Operator;

/**
 * Description of Query Filter Trait
 * @author bachtiarpanjaitan <bachtiarpanjaitan0@gmail.com>
 * @since 
 */
trait QueryFilter {
    
    private $_defaultLimit;
    public function setDefaultLimit(int $limit) {
        $this->_defaultLimit = $limit;
    }
    
    /**
     * limiter record for model
     * @param Illuminate\Database\Eloquent\Builder $model
     * @param int $defaultLimit
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function limiter(eloBuilder &$model, int $defaultLimit = 10) {
        $page = (int) request('page');
        $limit = (int) request('limit');
        
        if ($defaultLimit == -1) {
            return $model;
        }
        
        if (!empty($this->_defaultLimit)) {
            $limit = $this->_defaultLimit;
        } elseif (empty($limit)) {
            $limit = $defaultLimit;
        }
        
        if ($page > 1) {
            $skip = ($page - 1) * $limit;
        } else {
            $skip = 0;
        }
        
        return $model->skip($skip)->take($limit);
        
    }

    /**
     * filter record of model using parameter request
     * @param Illuminate\Database\Eloquent\Builder $model
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function filter( &$model){
        $requests = request()->all(); 
        $page = 1;
        $limit = 10;
        foreach($requests as $key => $value){
            $relation = explode('__', $key);
            $table = [];
            if(count($relation) >= 2) {
                $key = $relation[count($relation) -1];
                unset($relation[count($relation)-1]);
                $table = $relation;
            }

            $_filter = explode("_",$key);
            if (count($_filter)<=1) continue;
            $_filter_name = $_filter[count($_filter)-1];
            unset($_filter[count($_filter)-1]);
            $column = implode("_",$_filter);
            // dd($_filter_name);
            $operators= Operator::$OPERATOR;
            if(isset($operators[$_filter_name])){
                $params = [
                    'table' => $table,
                    'column' => $column,
                    'value' => $value,
                    'operator' => $operators[$_filter_name],
                    'filter' => $_filter_name
                ];
                $this->_generateQuery($model, $params);
            }

            if($key == '_page') $page = $value;
            if($key == '_limit') $limit = $value;

            if ($page > 1)  $skip = ($page - 1) * $limit;
            else  $skip = 0;

            if($key == '_sort'){
                $sort = explode(':',$value);
                if(count($sort) < 2) continue;
                $model->orderBy(trim($sort[0]),trim($sort[1]));
            }
        }
        $model->skip($skip)->take($limit);
       return $model;
    }

    /**
     * Genereate Query, supports up to 3 deep tree relationships
     * 
     * @param $model Illuminate\Database\Eloquent\Builder $model
     * @param $params array
     * @return Illuminate\Database\Eloquent\Builder
     */
    private function _generateQuery(&$model, $params){
        $tables = $params['table'];
        $column = $params['column'];
        $value = $params['value'];
        $op = $params['operator'];
        $filter = $params['filter'];
        if(count($tables) > 0) {
            $model->whereHas($tables[0], function($query) use ($column,$value,$tables,$op,$filter) {
                if(isset($tables[1])){
                    $query->whereHas($tables[1], function($query) use ($column,$value,$tables,$op,$filter) {
                        if(isset($tables[2])){
                            $query->whereHas($tables[2], function($query) use ($column,$value,$op,$filter) {
                                $this->_generator($query,$column,$value,$op,$filter);
                            });
                        } else $this->_generator($query,$column,$value,$op,$filter);
                    });
                } else $this->_generator($query,$column,$value,$op,$filter);
            });
        } else $this->_generator($model,$column,$value,$op,$filter);
    }

    /**
     * Generate query
     */
    private function _generator(&$query,$column,$value,$op,$filter){
        if(in_array($filter,['in','notin'])){
            $_values = explode(',',$value);
            $_values = array_map('intval',$_values);
            $value = $_values;
        }
        if(empty($value)) {
            $query->{$op['q']}($column);
        } elseif(isset($op['a']) && isset($op['s']) && isset($op['q']))
            $query->{$op['q']}($column,$op['s'],$op['a'].$value.$op['a']);
        elseif (isset($op['s']) && isset($op['q']))
            $query->{$op['q']}($column,$op['s'],$value);
        elseif (isset($op['q'])) $query->{$op['q']}($column,$value);
        
    }
}