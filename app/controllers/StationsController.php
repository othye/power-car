<?php

namespace Controllers;

use Core\Controllers\Controller;
use Model\Stations;
use Model\Technicals;

class StationsController extends Controller {

    public function index() {

        echo $this->twig->render('stations/index.html.twig');
    }

    public function insert() // Inserer les données du fichier *.CSV dans la base de données
    {
        
        ini_set('auto_detect_line_endings',TRUE);
        $handle = fopen('./files/stations.csv','r');
        while ( ($data = fgetcsv($handle) ) !== FALSE ) {
        //process
        
            $station = Stations::findOne([ //  verifier si les données existe pas sur la base de données
                'station_ref' => $data[0] 
            ]);
            if(!$station){
                $station = new Stations(); // insetion des données stations 
            }
            
            $address = $data[2]; // découpé l'adrese complet en adresse , code postale  et ville
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
                $technicals = new Technicals(); // insertion des données sous la table technicals
            }
            
            $technicals->company = $data[5];
            $technicals->charge_type = $data[6];
            $technicals->nbr_pdc = $data[7];
            $technicals->connectors_type = $data[8];
            $technicals->more_infos = $data[9];
            $technicals->sources = $data[10];
            $technicals->id_station = $station->id;
            $technicals->save();
            
        }echo 'finished';
        
        ini_set('auto_detect_line_endings',FALSE);
    } 

    public function nearestStations() {
        // recupérer les position actuelle via ajax
        $locationLatitude = $_POST['lat'];
        $locationLongitude = $_POST['lon'];
      
        // Requet pour calculer la distance entre la position actuelle et les stations dans un rayon  de 20 km
        $sql = "SELECT * , ( 3959 * ACOS( COS( RADIANS( :locationLatitude ) ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS( :locationLongitude ) ) + SIN( RADIANS( :locationLatitude  ) ) * SIN( RADIANS( latitude )))) AS distance
                FROM stations
                HAVING distance < 20
                ORDER BY distance";
       
        $pdo = $this->pdo;
        $query = $pdo->prepare($sql);

        $query->execute([
           'locationLatitude' => $locationLatitude,
           'locationLongitude' => $locationLongitude
        ]);

       $datas = $query->fetchAll();

       echo json_encode($datas); 
    }

    public function allStations() // afficher toutes les bornes sur la carte
    {
        $allStations = Stations::find();
        $stationsArray = [];
        foreach($allStations as $key => $allStation) {
            $stationsArray[$key] = $allStation;

            foreach($allStation->getTechnicals() as $allTechnical) {
                $array[$key]->technicalArray[] = $allTechnical;
            }
        }
        
       echo $this->twig->render('stations/index.html.twig',[  
            'allStations' => $stationsArray 
        ]);
    }


    public function search() // afficher les bornes par recherches (par ville ou code postale)
    {
        if(isset($_GET['envoi']) && !empty($_GET['search']) ) 
        {   
            $stations = Stations::find(); // requete SELECT * FROM Stations
            $queryBuilder = $stations->getQueryHelper();
            $queryBuilder->orWhere("zip", '=', '"'.$_GET['search'].'"'); // par codes postales
            $queryBuilder->orWhere("city", 'LIKE', '"%'.$_GET['search'].'%"'); // Ou par villes
            //$queryBuilder->orWhere("address", 'LIKE', '"%'.$_GET['search'].'%"'); //  Ou par adresse
            $stations->setQueryHelper($queryBuilder);
            $array = [];
            foreach($stations as $key => $station) {
                $array[$key] = $station;
                $array[$key]->technicals = [];
                foreach($station->getTechnicals() as $technical) {
                    $array[$key]->technicals[] = $technical;
                }
            }
            
            echo $this->twig->render('stations/index.html.twig',[  
                'stations' => $array
            ]);
        }
    }



}