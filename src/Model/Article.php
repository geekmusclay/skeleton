<?php 

declare(strict_types=1);

namespace App\Model;

use Geekmusclay\ORM\Entity\Model;

class Article extends Model
{
    /** @var string $title The title of the article */
    private string $title = 'Default title';

    /** @var string $subtitle The subtitle of the article */
    private string $subtitle = 'Default subtitle';

    /** @var string $body The body of the article */
    private string $body = 'Default body';
}