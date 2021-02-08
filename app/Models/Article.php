<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Model;

class Article extends Model
{
    use HasBlocks, HasSlug, HasMedias;

    protected $fillable = [
        'published',
        'title',
        'description',
    ];

    public $slugAttributes = [
        'title',
    ];

    public $mediasParams = [
        'cover' => [
            'desktop' => [
                [
                    'name' => 'desktop',
                    'ratio' => 16 / 9,
                ],
            ],
            'mobile' => [
                [
                    'name' => 'mobile',
                    'ratio' => 1,
                ],
            ],
            'flexible' => [
                [
                    'name' => 'free',
                    'ratio' => 0,
                ],
                [
                    'name' => 'landscape',
                    'ratio' => 16 / 9,
                ],
                [
                    'name' => 'portrait',
                    'ratio' => 3 / 5,
                ],
            ],
        ],
    ];

/*
POSSIBLE CHOICES
footer
header
hero
image
wysiwyg
aside
*/
    public $blockEditorRules = [
        'must have 1 of header',
        'must have 2 of wysiwyg or hero',
        'may have 2 of wysiwyg or image ',
        'must have 1 of   footer',
    ];
}
