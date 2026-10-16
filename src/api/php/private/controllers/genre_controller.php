<?php

class GenreController
{
    public function __construct(private RelationalDatabase $db) {}

    public function getGenres(): void
    {
        $queryString = $_SERVER['QUERY_STRING'];
        $parameters = [];
        parse_str($queryString, $parameters);

        $active = $parameters['active'] ?? false;

        $query = QueryBuilder::table('bookstore.genres', 'g')
            ->select([
                'g.genre_id',
                'g.name',
                'COUNT(bg.book_id) AS book_count'
            ])
            ->groupBy([
                'g.genre_id',
                'g.name'
            ]);

        $active ?
            $query->innerJoin('bookstore.book_genres', 'g.genre_id', 'bg.genre_id', 'bg') :
            $query->leftJoin('bookstore.book_genres', 'g.genre_id', 'bg.genre_id', 'bg');

        $data = $this->db->executeAndReturnAll($query);

        echo json_encode($data);
    }
}
