<?php
require_once 'models/Libro.php';
require_once 'models/Pedido.php';

class carritoController{

    public function ver_carrito(){
        require_once 'views/carrito/carrito-list.php';
    }

    public function agregar_libro(){
        if(isset($_GET['id'])){
			$book_id = $_GET['id'];
		}else{
			header('Location:'.base_url);
		}
		
		if(isset($_SESSION['carrito'])){
			$counter = 0;
			foreach($_SESSION['carrito'] as $index => $element){
				if($element['id_book'] == $book_id){
					$_SESSION['carrito'][$index]['quantity']++;
					$counter++;
				}
			}	
		}
		
		if(!isset($counter) || $counter == 0){
			// Conseguir producto
			$book = new Libro();
			$book = $book->get_one_book($book_id);

			// Añadir al carrito
			if(is_object($book)){
				$_SESSION['carrito'][] = array(
					"id_book" => $book->id,
					"price" => $book->precio,
					"quantity" => 1,
					"product" => $book
				);
			}
		}
		
		header("Location:".base_url);
    }

    public function modificar_unidades(){

    }

    public function eliminar_libro(){

    }

    public function vaciar_carrito(){

    }




}