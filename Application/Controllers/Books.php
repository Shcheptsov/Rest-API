<?php 

use MVC\Controller;

class ControllersBooks  extends Controller {

    public function index() {

        // Connect to database
        $model = $this->model('books');

        // Read All Books And Authors Data
        $data_list = $model->getAllData();

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function createBook() {
        $method = $_SERVER['REQUEST_METHOD'];
        if ('POST' === $method) {
            $data = json_decode(file_get_contents("php://input"));
            $model = $this->model('books');
            $book_create = $model->createBook($data);
            if ($book_create) {
                $this->response->sendStatus(200);
                $this->response->setContent('Книга успешно добавлена');
            } else {
                $this->response->sendStatus(503);
                $this->response->setContent("Не удалось добвить книгу.");
            }
        }
    }
    public function updateBook() {
        $method = $_SERVER['REQUEST_METHOD'];
        if ('PUT' === $method) {
            $data = json_decode(file_get_contents("php://input"));
            $model = $this->model('books');
            $book_create = $model->updateBook($data);
            if ($book_create) {
                $this->response->sendStatus(200);
                $this->response->setContent('Книга успешно обоавлена');
            } else {
                $this->response->sendStatus(503);
                $this->response->setContent("Не удалось обновить книгу.");
            }
        }
    }


    public function deleteBook() {
        $method = $_SERVER['REQUEST_METHOD'];
        if ('DELETE' === $method) {
            $data = json_decode(file_get_contents("php://input"));
            $model = $this->model('books');
            $book_delete = $model->deleteBook($data->id);

            if ($book_delete) {
                $this->response->sendStatus(200);
                $this->response->setContent('Книга была успешно удалена');
            } else {
                $this->response->sendStatus(503);
                $this->response->setContent("Не удалось удалить книгу.");
            }
        }
    }


    public function books($param) {

        $model = $this->model('books');
        $book_list = $model->getAllBooks($param);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($book_list);
    }

    public function authors($param) {

        $model = $this->model('books');
        $author_list = $model->getAllAuthors($param);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($author_list);
    }

    public function searchBooksByAuthors($param) {

        // check valid param
        if (isset($param['author']) && $this->validSearchBooks($param['author'])) {

            $model = $this->model('books');
            $result = $model->searchBooksByAuthors($param);

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($result);
        } else {
            $this->response->sendStatus(200);
            $this->response->setContent([
                'message'   => 'Invalid author name OR Your author name is too short'
            ]);
        }
    }

    public function searchBooksByTitle($param) {

        // check valid param
        if (isset($param['title']) && $this->validSearchBooks($param['title'])) {

            $model = $this->model('books');
            $result = $model->searchBooksByTitle($param);

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($result);
        } else {
            $this->response->sendStatus(200);
            $this->response->setContent([
                'message'   => 'Invalid title OR Your title is too short'
            ]);
        }
    }

    public function searchBooksByISBN($param) {

        // check valid param
        if ($this->validISBN($param)) {

            $model = $this->model('books');
            $result = $model->searchBooksByISBN(clean($param['isbn']));

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($result);
        } else {
            $this->response->sendStatus(200);
            $this->response->setContent([
                'message'   => 'Invalid ISBN OR Your ISBN is too short'
            ]);
        }
    }

    private function validISBN($param) {

        // check param
        if (!empty($param) && isset($param['isbn']) && is_numeric($param['isbn']) && strlen((string) $param['isbn']) > 0 && $param['isbn'] != 0)
            return true;
        
        return false;
    }

    private function validSearchBooks($param) {

        // check param
        if (!empty($param) && !is_numeric($param) && strlen((string) $param) > 0)
            return true;
        
        return false;
    }
}
