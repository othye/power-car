<?php

namespace Controllers;

use Core\Controllers\Controller;
use Model\Stations;
use Model\Technicals;

class StationsController extends Controller {
    public function index() {
        echo $this->twig->render('stations/index.html.twig');
    }

    public function insert(){
        
        ini_set('auto_detect_line_endings',TRUE);
        $handle = fopen('./files/stations.csv','r');
        while ( ($data = fgetcsv($handle) ) !== FALSE ) {
        //process
            //print_r($data);
            $station = Stations::findOne([
                'station_ref' => $data[0]
            ]);

            if(!$station){
                $station = new Stations();
            }

            

            $address = $data[2];
            preg_match("/^(.*)(\d{5})(.*)$/", $address, $elems);
            
            $station->station_ref = $data[0];
            $station->name_station = $data[1];
            $station->address = ((isset($elems[1])) ? $elems[1] : null);
            $station->zip = ((isset($elems[2])) ? $elems[2] : null);
            $station->city = ((isset($elems[3])) ? $elems[3] : null);
            $station->latitude = $data[3];
            $station->longitude = $data[4];



            $station->save();


            $technicals = Technicals::findOne([
                'id_station' => $station->id
            ]);

            if(!$technicals){
                $technicals = new Technicals();
            }
            
            

            $technicals->company = $data[5];
            $technicals->charge_type = $data[6];
            $technicals->nbr_pdc = $data[7];
            $technicals->connectors_type = $data[8];
            $technicals->more_infos = $data[9];
            $technicals->sources = $data[10];
            $technicals->id_station = $station->id;

            $technicals->save();

            //var_dump($data);die;
        }echo 'finished';
        
        ini_set('auto_detect_line_endings',FALSE);
    }

    /*public function address($zip) 
    {
        $station = Stations::find([
            //'id' => $id,
            'zip' => $zip
        ]); 

        var_dump($station);

    }*/

    
    public function search()
    {

        if(isset($_GET['envoi']) && !empty($_GET['search']) ) 
        {   
            $stations = Stations::find();
            $queryBuilder = $stations->getQueryHelper();
            $queryBuilder->orWhere("zip", '=', '"'.$_GET['search'].'"');
            $queryBuilder->orWhere("city", 'LIKE', '"%'.$_GET['search'].'%"');
            //$queryBuilder->orWhere("address", 'LIKE', '"%'.$_GET['search'].'%"');
            $stations->setQueryHelper($queryBuilder);

            $array = [];

            foreach($stations as $key => $station) {
                $array[$key] = $station;
                $array[$key]->technicals = [];

                foreach($station->getTechnicals() as $technical) {
                    $array[$key]->technicals[] = $technical;
                }
            }

            //echo '<pre>'; var_dump($array); die();
            
            echo $this->twig->render('stations/index.html.twig',[  
                'stations' => $array
            ]);
        }


    $this->url->redirect('adress');
    }
}
