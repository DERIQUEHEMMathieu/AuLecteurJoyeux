<?php
require_once "model/dataBase.php";
require "model/entity/book.php";
class BookManager extends DataBase {
  // Récupère tous les livres
  public function getBooks():Array {
    $query = $this->getDB()->prepare (
      "SELECT *
      FROM book"
    );
    $query->execute();
    $books = $query -> fetchAll(PDO::FETCH_ASSOC);
    foreach ($books as $key => $book) {
      $books[$key] = new Book($book);
    }
    return $books;
  }

  // Récupère un livre
  public function getBook($book_id):Book {
    $query = $this->getDB()->prepare (
      "SELECT *
      FROM book
      WHERE id = :book_id"
    );
    $query->execute([
      "book_id" => $book_id
    ]);
    $book = $query -> fetchAll(PDO::FETCH_ASSOC);
    $book = new Book($book[0]);
    return $book;
  }

  // Ajoute un nouveau livre
  public function addBook(Book $book):Bool {
    $query = $this->getDB()->prepare(
      "INSERT INTO book(title, author, resume, date, category)
      VALUES (:title, :author, :resume, :date, :category)"
    );
    $result = $query->execute([
      "title"=>$book->getTitle(),
      "author"=>$book->getAuthor(),
      "resume"=>$book->getResume(),
      "date"=>$book->getDate(),
      "category"=>$book->getCategory()
    ]);
    return $result;
  }

  // Pour mettre à jour le statut d'un livre emprunté
  public function updateBookStatus(Book $book, ?int $userID):bool {
    if (empty($userID)){
      $userID = NULL;
    }
    $query = $this->getDB()->prepare(
      "UPDATE book
      SET user_id =:user_id
      WHERE id = :book_id
      ");
    $result=$query->execute([
      "user_id" => $userID,
      "book_id" => $book->getId()
    ]);
    header("Location:index.php");
    exit();
    return $result;
  }

  // Supprimer un livre
  public function bookDelete(Book $book):Bool {
    $query = $this->getDB()->prepare(
      "DELETE FROM book
      WHERE id = :id"
    );
    $result = $query->execute([
      "id"=>$book->getId()
    ]);
    header("Location:index.php");
    exit();
    return $result;
  }
}