<?php

namespace Fabric;

class Type extends \Eloquent
{
    const TYPE_FACE = "face";
    const TYPE_LINING = 'lining';
    const TYPE_INTERLINING = "interlining";
    const TYPE_EMBELLISHMENT = "embellishment";
    const TYPE_FACE_VALANCE = "face-valance";
    const TYPE_LINING_VALANCE = "lining-valance";
    const TYPE_INTERLINING_VALANCE = "interlining-valance";
    const TYPE_EMBELLISHMENT_VALANCE = "embellishment-valance";

    protected $table = "fabric_types";

    public function fabrics()
    {
        return $this->belongsToMany('Fabric', 'selected_fabric_types', 'type_id', 'fabric_id');
    }

    public function isForValance(){

        $isForValance = false;

        if(
            $this->type === Type::TYPE_FACE_VALANCE
            ||
            $this->type === Type::TYPE_LINING_VALANCE
            ||
            $this->type === Type::TYPE_INTERLINING_VALANCE
            ||
            $this->type === Type::TYPE_EMBELLISHMENT_VALANCE
        ){
            $isForValance = true;
        }

        return $isForValance;
    }
}