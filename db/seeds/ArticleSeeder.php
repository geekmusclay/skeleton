<?php


use Phinx\Seed\AbstractSeed;

class ArticleSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            [
                'title'   => 'Article 1',
                'subtitle' => 'subtitle 1',
                'body'    => 'Body 1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],[
                'title'   => 'Article 2',
                'subtitle' => 'subtitle 2',
                'body'    => 'Body 2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ]
        ];

        $posts = $this->table('articles');
        $posts->insert($data)
              ->saveData();
    }
}
