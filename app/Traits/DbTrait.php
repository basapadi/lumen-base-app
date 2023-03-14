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
}
