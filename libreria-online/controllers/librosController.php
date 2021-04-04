<?php
require_once 'models/Libro.php';

class librosController{

    public function gestion_libros(){
        require_once 'views/libros/book-dashboard.php';
    }

    public function guardar_libro(){
        require_once 'views/libros/save-book.php';
    }

    public function save_book(){
        if(isset($_POST['save-book']) && $_POST['save-book'] == 'Guardar'){

            if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK){

                $fileTmpPath = $_FILES['imagen']['tmp_name'];
                $fileName = $_FILES['imagen']['name'];
                $fileSize = $_FILES['imagen']['size'];

                $dest_path = 'assets/img/books/'.$fileName;

                if(move_uploaded_file($fileTmpPath, $dest_path)){
                    $saved_img = $dest_path;

                    $titulo = $_POST['titulo'];
                    $autor = $_POST['autor'];
                    $editorial = $_POST['editorial'];
                    $sinopsis = $_POST['sinopsis'];
                    $genero = $_POST['genero'];
                    $precio = $_POST['precio'];
                    $stock = $_POST['stock'];
                    $imagen = $saved_img;

                    $book = new Libro();
                    $book->setTitulo($titulo);
                    $book->setAutor($autor);
                    $book->setEditorial($editorial);
                    $book->setSinopsis($sinopsis);
                    $book->setGenero($genero);
                    $book->setPrecio($precio);
                    $book->setStock($stock);
                    $book->setImagen($imagen);

                    $saved_book = $book->save_book();

                    if($saved_book){
                        $_SESSION['save-book'] = 'completed';
                    }else{
                        $_SESSION['save-book'] = 'failed';
                    }
                }else{
                    $_SESSION['save-book'] = 'failed';
                }
            }else{
                $_SESSION['save-book'] = 'failed';
            }
        }
        header('Location:'.base_url.'libros/gestion_libros');
    }

    public function lista_libros(){
        $books = new Libro();
        $book_list = $books->read();
        if(isset($_SESSION['identity'])){
            if($_SESSION['identity']->rol == 'admin' || $_SESSION['identity']->rol == 'vendor'){
                require_once 'views/libros/books-list-admin.php';
            }elseif($_SESSION['identity']->rol == 'client'){
                require_once 'views/libros/books-list-client.php';
            }
        }else{
            require_once 'views/libros/books-list-client.php';
        }
    }

    public function eliminar_libro(){
        if(isset($_SESSION['identity']) && $_SESSION['identity']->rol == 'admin' || $_SESSION['identity']->rol == 'vendor'){
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $book = new Libro();
                $delete_book = $book->delete_book($id);
                if($delete_book){
                    $_SESSION['delete_book'] = 'completed';
                }else{
                    $_SESSION['delete_book'] = 'failed';
                }
                header('Location:'.base_url.'libros/lista_libros');
            }

        }else{
            header('Location:'.base_url.'error/not_found');
        }
    }

    public function ver_libro(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $get_book = new Libro();
            $book = $get_book->get_one_book($id);
            require_once 'views/libros/single-book.php';
        }else{
            header('Location:'.base_url.'error/not_found');
        }
    }

}