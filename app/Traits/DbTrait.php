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

    private function queryFilters(eloBuilder &$model){
        $requests = request()->all(); 
        foreach($requests as $key => $value){
            $_filter = explode("_",$key);
            if (count($_filter)<=1) continue;
            $_filter_name = $_filter[count($_filter)-1];
            unset($_filter[count($_filter)-1]);
            $column = implode("_",$_filter);
            switch ($_filter_name) {
                case 'contain':
                    $model->where($column,"LIKE","%$value%");
                    break;
                case 'gte':
                    $model->where($column,">=",$value);
                    break;
                case 'lte':
                    $model->where($column,"<=",$value);
                    break;
                case 'gt':
                    $model->where($column,">",$value);
                    break;
                case 'lt':
                    $model->where($column,"<",$value);
                    break;
                case 'eq':
                    $model->where($column,"=",$value);
                    break;
                case 'ne':
                    $model->where($column,"<>",$value);
                    break;
                case 'between':
                    $_between = explode('-',$value);
                    $_between = array_map('intval',$_between);
                    $model->whereBetween($column,$_between);
                    break;
                case 'null':
                    $model->whereNull($column);
                    break;
                case 'notnull':
                    $model->whereNotNull($column);
                    break;
                case 'in':
                    $_in = explode(',',$value);
                    $_in = array_map('intval',$_in);
                    $model->whereIn($column,$_in);
                    break;
                case 'notin':
                    $_notin = explode(',',$value);
                    $_notin = array_map('intval',$_notin);
                    $model->whereNotIn($column,$_notin);
                    break;
                default:
                    # code...
                    break;
            }
        }
       // dd($model->toSql());
       return $model;

    }
}
