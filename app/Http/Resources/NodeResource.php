<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class NodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'height' => $this->height ?? null,
            'departmentName' => $this->department_name ?? null,
            'programmingLanguage' => $this->programming_language ?? null,
            'children' => self::collection($this->children),
        ];
    }
}
