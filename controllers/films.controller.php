<?php

class FilmsController extends Controller {

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Film();
    }

    public function add() {
        if ($_POST) {
            if ($this->model->addFilm($_POST)) {
                Session::setFlash('Film was added successfully');
            }
        }

        $this->data = $this->model->getFormats();
    }

    public function index() {
        if ($_POST) {
            $this->data = $this->model->getListOrderedByTitle($_POST);
        } else {
            $this->data = $this->model->getListOrderedByTitle();
        }

    }

    public function find() {

    }

    public function findByTitle() {

    }

    public function findByActorsName() {

    }

    public function show() {
        $id = $this->params[0];
        $this->data = $this->model->getById($id);
        $this->data[] = $this->model->getActors($id);
    }

    public function delete() {
        $id = $this->params[0];
        if ($this->model->delete($id)) {
            Session::setFlash('Film was deleted successfully');
            Router::redirect('/');
        }
    }

    public function import() {
        if($_FILES) {
            if($_FILES['import']['type'] !== "text/plain") {
                throw new Exception('Unsupported type of file');
            }

            $uploaddir = '../webroot/uploads/';
            $uploadfile = $uploaddir . basename($_FILES['import']['name']);

            if (move_uploaded_file($_FILES['import']['tmp_name'], $uploadfile)) {
                Session::setFlash('File was uploaded succesfully');
            } else {
                throw new Exception('Something went wrong during upload');
            }

            if ( $this->model->import($uploadfile)) {
                Session::setFlash('Data was imported successfully');
            }
        }
    }
}
