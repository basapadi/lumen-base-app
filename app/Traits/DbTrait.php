<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder as eloBuilder;

/**
 * Description of DbTrait
 * @author bachtiarpanjaitan <bachtiarpanjaitan0@gmail.com>
 * @since 
 */
trait DbTrait {
    
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
     * Query Filters
     * @param Illuminate\Database\Eloquent\Builder $model
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function queryFilters( &$model){
        $requests = request()->all(); 
        foreach($requests as $key => $value){
            $relation = explode('__', $key);
            $table = '';
            if(count($relation) >= 2) {
                $key = $relation[1];
                $table = $relation[0];
            }

            $_filter = explode("_",$key);
            if (count($_filter)<=1) continue;
            $_filter_name = $_filter[count($_filter)-1];
            unset($_filter[count($_filter)-1]);
            $column = implode("_",$_filter);
            
            switch ($_filter_name) {
                case 'is':
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column,$value) {
                            $query->where($column, $value);
                        });
                    } else $model->where($column,$value);
                    break;
                case 'contain':
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column,$value) {
                            $query->where($column,"LIKE","%$value%");
                        });
                    } else $model->where($column,"LIKE","%$value%");
                    break;
                case 'gte':
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column,$value) {
                            $query->where($column,">=",$value);
                        });
                    } else $model->where($column,">=",$value);
                    break;
                case 'lte':
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column,$value) {
                            $query->where($column,"<=",$value);
                        });
                    } else $model->where($column,"<=",$value);
                    break;
                case 'gt':
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column,$value) {
                            $query->where($column,">",$value);
                        });
                    } else $model->where($column,">",$value);
                    break;
                case 'lt':
                    $model->where($column,"<",$value);
                    break;
                case 'eq':
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column,$value) {
                            $query->where($column,"=",$value);
                        });
                    } else $model->where($column,"=",$value);
                    break;
                case 'ne':
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column,$value) {
                            $query->where($column,"<>",$value);
                        });
                    } else $model->where($column,"<>",$value);
                    break;
                case 'between':
                    $_between = explode('-',$value);
                    sort($_between);
                    $_between = array_map('intval',$_between);
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column,$_between) {
                            $query->whereBetween($column,$_between);
                        });
                    } else $model->whereBetween($column,$_between);
                    break;
                case 'null':
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column) {
                            $query->whereNull($column);
                        });
                    } else $model->whereNull($column);
                    break;
                case 'notnull':
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column) {
                            $query->whereNotNull($column);
                        });
                    } else $model->whereNotNull($column);
                    break;
                case 'in':
                    $_in = explode(',',$value);
                    $_in = array_map('intval',$_in);
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column, $_in) {
                            $query->whereIn($column,$_in);
                        });
                    } else $model->whereIn($column,$_in);
                    break;
                case 'notin':
                    $_notin = explode(',',$value);
                    $_notin = array_map('intval',$_notin);
                    if(!empty($table)) {
                        $model->whereHas($table, function($query) use ($column, $_notin) {
                            $query->whereNotIn($column,$_notin);
                        });
                    } else $model->whereNotIn($column,$_notin);
                    break;
                default:
                    # code...
                    break;
            }
        }
       return $model;
    }
}
