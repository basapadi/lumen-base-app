<?php

namespace App\Transformers\Quasar;

use League\Fractal\TransformerAbstract;

class ColumnTransformer extends TransformerAbstract {

    /**
    *   parameter format columns:
    *   $resp = [
    *       ['value' => 'id', 'label' => 'id','align' => 'left'],
    *       ...
    *   ];
    */
    public function transform($resp) {
        $align = 'left';
        if(isset($resp->align)) $align = $resp->align;
        return [
            'name' => $resp['value'],
            'required' => true,
            'label' => ucwords(implode(' ',explode('_',ucfirst($resp['label'])))),
            'align' =>  $align,
            'field' => $resp['value'],
            'sortable' => true
        ];
    }
}