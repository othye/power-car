<?php

namespace Controllers;

use Core\Controllers\Controller;
use Model\Category;

class CategoryController extends Controller {
    
    /**
     * Index method
     *
     * @param string $page
     * @return void
     */
    public function index($page = "1") 
    {
        $categories = Category::find();

        echo $this->twig->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

        
    /**
     * Deleting category
     *
     * @param int $id
     * @return void
     */
    public function delete($id) 
    {
        $category = Category::findOne([
            'id' => $id
        ]);

        $category->delete();

        $this->flashbag->set('alert', [
            'type' => 'success',
            'msg' => 'Category deleted !'
        ]);

        $this->url->redirect('categories');
    }

    /**
     * Add category
     *
     * @return void
     */
    public function add()
    {
        $category = new Category();

        $category->name = $_POST['name'];
        $category->save();

        $this->flashbag->set('alert', [
            'type' => 'success',
            'msg' => 'category added !'
        ]);

        $this->url->redirect('categories');

    }
}