<?php 

declare(strict_types=1);

namespace App\Model;

use Geekmusclay\ORM\Entity\Model;

class Article extends Model
{
    protected array $data = [
        'title' => 'Default title',
        'subtitle' => 'Default subtitle',
        'body' => 'Default body'
    ];
}