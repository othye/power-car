<?php

namespace Controllers;

use Core\Controllers\Controller;
use Model\Stations;
use Model\Technical;

class StationsController extends Controller {
    public function index() {
        echo $this->twig->render('todos/index.html.twig');
    }
    public function insert(){
        
        ini_set('auto_detect_line_endings',TRUE);
        $handle = fopen('./stations.csv','r');
        while ( ($data = fgetcsv($handle) ) !== FALSE ) {
        //process
            //print_r($data);
            $station = Stations::findOne([
                'station_ref' => $data[0]
            ]);

            if(!$station){
                $station = new Stations();
            }

            

            $adress = $data[2];
            preg_match("/^(.*)(\d{5})(.*)$/", $adress, $elems);
            
            $station->station_ref = $data[0];
            $station->name_station = $data[1];
            $station->adress = ((isset($elems[1])) ? $elems[1] : null);
            $station->zip = ((isset($elems[2])) ? $elems[2] : null);
            $station->city = ((isset($elems[3])) ? $elems[3] : null);
            $station->latitude = $data[3];
            $station->longitude = $data[4];



            $station->save();


            $technical = Technical::findOne([
                'id_station' => $station->id
            ]);

            if(!$technical){
                $technical = new Technical();
            }
            
            

            $technical->company = $data[5];
            $technical->charge_type = $data[6];
            $technical->nbr_pdc = $data[7];
            $technical->connectors_type = $data[8];
            $technical->more_infos = $data[9];
            $technical->sources = $data[10];
            $technical->id_station = $station->id;

            $technical->save();

            //var_dump($data);die;
        }echo 'finished';
        
        ini_set('auto_detect_line_endings',FALSE);
    }
}
// class stationsController extends Controller {
//         /**
//      * Add category
//      *
//      * @return void
//      */
//     public function add()
//     {
//         $category = new Category();

//         $category->name = $_POST['name'];
//         $category->save();

//         $this->flashbag->set('alert', [
//             'type' => 'success',
//             'msg' => 'category added !'
//         ]);

//         $this->url->redirect('categories');

//     }

//     /**
//      * Index method
//      *
//      * @param string $page
//      * @return void
//      */
//     public function index($page = "1") 
//     {
//         $categories = Category::find();

//         echo $this->twig->render('categories/index.html.twig', [
//             'categories' => $categories
//         ]);
//     }

        
//     /**
//      * Deleting category
//      *
//      * @param int $id
//      * @return void
//      */
//     public function delete($id) 
//     {
//         $category = Category::findOne([
//             'id' => $id
//         ]);

//         $category->delete();

//         $this->flashbag->set('alert', [
//             'type' => 'success',
//             'msg' => 'Category deleted !'
//         ]);

//         $this->url->redirect('categories');
//     }


// }