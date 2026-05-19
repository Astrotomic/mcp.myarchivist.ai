<?php

namespace App\Data;

class QuestDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'order_index' => ['required', 'integer'],
            'quest_name' => ['required', 'string'],
            'quest_category' => ['nullable', 'string', 'in:main,side,faction,personal,n/a'],
            'status' => ['nullable', 'string', 'in:planned,in-progress,blocked,failed,done,n/a'],
            'image' => ['nullable', 'string'],
            'approved' => ['required', 'boolean'],
            'discovered' => ['required', 'boolean'],
        ];
    }
}
