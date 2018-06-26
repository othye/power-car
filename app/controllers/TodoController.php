<?php

namespace Controllers;

use Core\Controllers\Controller;
use Model\Todo;
use Model\Category;

class TodoController extends Controller {
    
    /**
     * Index method
     *
     * @param string $page
     * @return void
     */
    public function index($page = "1") 
    {
        // $page isn't used actually. This is just for argument demonstration
        
        $todos = Todo::find();
        $categories = Category::find();

        echo $this->twig->render('todos/index.html.twig', [
            'todos' => $todos,
            'categories' => $categories,
        ]);
    }
    
    /**
     * Deleting todo
     *
     * @param int $id
     * @return void
     */
    public function delete($id) 
    {
        $todo = Todo::findOne([
            'id' => $id
        ]);

        $todo->delete();

        $this->flashbag->set('alert', [
            'type' => 'success',
            'msg' => 'Todo deleted !'
        ]);

        $this->url->redirect('');
    }
    
    /**
     * Add todo
     *
     * @return void
     */
    public function add()
    {
        $todo = new Todo();

        $todo->title = $_POST['title'];
        $todo->category_id = $_POST['category_id'];
        $todo->save();

        $this->flashbag->set('alert', [
            'type' => 'success',
            'msg' => 'Todo added !'
        ]);

        $this->url->redirect('');

    }
}