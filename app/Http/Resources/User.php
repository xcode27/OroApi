<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'module_id' => $this->module_id,
            'create' => $this->create,
            'read' => $this->read,
            'update' => $this->update,
            'delete' => $this->delete,
        ];
    }
}
