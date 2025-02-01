<?php

use models\Author;
use models\Book;

require_once 'Repository.php';
require_once __DIR__.'/../models/Book.php';
require_once __DIR__.'/../models/Author.php';

class BookRepository extends \Repository
{
    public function getJsonData($id) {
        $stmt = $this->database->prepare('
            SELECT
                ksiazki.ksiazka_id as id,
                tytul as title,
                podtytul as subtitle,
                tytul_oryg as title_original,
                podtytul_oryg as subtitle_original,
                isbn as isbn,
                liczba_str as pages,
                oprawki.typ as binding,
                json_agg(autorzy.imie) as authors,
                json_agg(DISTINCT kategorie.kategoria) as genres,
                img_path as imageUrl,
                opis as description
            FROM ksiazki
                     LEFT JOIN oprawki
                               ON ksiazki.oprawka_id = oprawki.oprawka_id
            
                     LEFT JOIN ksiazki_kategorie
                                ON ksiazki.ksiazka_id = ksiazki_kategorie.ksiazka_id
                     LEFT JOIN kategorie
                                ON ksiazki_kategorie.kategoria_id = kategorie.kategoria_id
            
                     INNER JOIN ksiazki_autorzy
                                ON ksiazki.ksiazka_id = ksiazki_autorzy.ksiazka_id
                     INNER JOIN autorzy
                                ON ksiazki_autorzy.autor_id = autorzy.autor_id
            
            WHERE ksiazki.ksiazka_id = :id
            GROUP BY ksiazki.ksiazka_id, oprawki.typ, ksiazki.tytul, ksiazki.podtytul, ksiazki.tytul_oryg, ksiazki.podtytul_oryg, ksiazki.isbn, ksiazki.liczba_str, ksiazki.img_path, ksiazki.opis
        ');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllJsonData() {
        $stmt = $this->database->prepare('
            SELECT
                ksiazki.ksiazka_id as id,
                tytul as title,
                podtytul as subtitle,
                tytul_oryg as title_original,
                podtytul_oryg as subtitle_original,
                isbn as isbn,
                liczba_str as pages,
                oprawki.typ as binding,
                json_agg(CONCAT(autorzy.imie, \' \', autorzy.nazwisko)) as authors,
                json_agg(DISTINCT kategorie.kategoria) as genres,
                img_path as imageUrl,
                opis as description
            FROM ksiazki
                     LEFT JOIN oprawki
                               ON ksiazki.oprawka_id = oprawki.oprawka_id
            
                     LEFT JOIN ksiazki_kategorie
                                ON ksiazki.ksiazka_id = ksiazki_kategorie.ksiazka_id
                     LEFT JOIN kategorie
                                ON ksiazki_kategorie.kategoria_id = kategorie.kategoria_id
            
                     INNER JOIN ksiazki_autorzy
                                ON ksiazki.ksiazka_id = ksiazki_autorzy.ksiazka_id
                     INNER JOIN autorzy
                                ON ksiazki_autorzy.autor_id = autorzy.autor_id
            
            GROUP BY ksiazki.ksiazka_id, oprawki.typ, ksiazki.tytul, ksiazki.podtytul, ksiazki.tytul_oryg, ksiazki.podtytul_oryg, ksiazki.isbn, ksiazki.liczba_str, ksiazki.img_path, ksiazki.opis
        ');
        $stmt->execute();

        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$books) {
            return null;
        }

        for($i = 0; $i < sizeof($books); $i++) {
            $books[$i]['authors'] = json_decode($books[$i]['authors']);
            $books[$i]['genres'] = json_decode($books[$i]['genres']);
        }

        return json_encode($books);
    }

    function borrowBook($bookId, $readerId): void
    {
        $stmt = Database::get()->prepare('
        SELECT wypozycz_ksiazke(:bookId, :readerId);
        ');
        $stmt->bindParam(':bookId', $bookId);
        $stmt->bindParam(':readerId', $readerId);
        $stmt->execute();
    }

    function getById($bookId){
        $stmt = $this->database->prepare('
        SELECT ksiazka_id AS id, tytul AS title
        FROM ksiazki
        WHERE ksiazka_id = :id
        ');
        $stmt->bindParam(':id', $bookId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($result)){
            return null;
        }

        $stmt = $this->database->prepare('
        SELECT autorzy.autor_id AS id, imie AS name, nazwisko AS surname
        FROM ksiazki_autorzy
        INNER JOIN autorzy ON ksiazki_autorzy.autor_id = autorzy.autor_id
        WHERE ksiazki_autorzy.ksiazka_id = :id;
        ');
        $stmt->bindParam(':id', $bookId);
        $stmt->execute();
        $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $authors = array();
        foreach ($result2 as $row){
            $authors[] = new Author($row['id'], $row['name'], $row['surname']);
        }

        return new Book($result['id'], $result['title'], $authors);
    }

}