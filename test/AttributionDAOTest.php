<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>AttributionDAO : test</title>
    </head>

    <body>

        <?php

        use modele\dao\AttributionDAO;
        use modele\dao\Bdd;
        use modele\metier\Attribution;

        require_once __DIR__ . '/../includes/autoload.php';

        $idEtab = '0350773A';
        $idTypeChambre = 'C2';
        $idGroupe = 'g001';
        $nombreChambres = 7;
        $id = array('idEtab' => $idEtab, 'idTypeChambre' => $idTypeChambre, 'idGroupe' => $idGroupe);
        Bdd::connecter();

        echo "<h2>Test AttributionDAO</h2>";
        // Test n°1
        echo "<h3>1- getOneById</h3>";
        try {
            $id2 = array('idEtab' => '0350773A', 'idTypeChambre' => 'C2', 'idGroupe' => 'g004');
            $objet = AttributionDAO::getOneById($id2);
            var_dump($objet);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°2
        echo "<h3>2- getAll</h3>";
        try {
            $lesObjets = AttributionDAO::getAll();
            var_dump($lesObjets);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }
        
        // Test n°3
        echo "<h3>3- insert</h3>";
        try {
            //$objet = array('idEtab' => $idEtab, 'idTypeChambre' => $idTypeChambre, 'idGroupe' => $idGroupe,'nombreChambres' => $nombreChambres);
            $objet = new Attribution($idEtab, $idTypeChambre, $idGroupe, $nombreChambres);
            $ok = AttributionDAO::insert($objet);
            if ($ok) {
                echo "<h4>ooo réussite de l'insertion : ajout de 6 chambres de type C1 pour l'établissement d'id 0350773A ooo</h4>";
                $objetLu = AttributionDAO::getOneById($id);
                var_dump($objetLu);
            } else {
                echo "<h4>>ooo réussite du test : la requête d'insertion a échoué ooo</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>>ooo réussite du test : la requête d'insertion a échoué ooo</h4>" . $ex->getMessage();
        }


        // Test n°4
        echo "<h3>4- update</h3>";
        try {
            //$objet = 5;
            $objet->setNbChambres(5);
            $ok = AttributionDAO::update($id, $objet);
            if ($ok) {
                echo "<h4>ooo réussite de la mise à jour de l'etablissement d'id 0350773A il a désormais 7 pour les chambres de type C1 ooo</h4>";
                $objetLu = AttributionDAO::getOneById($id);
                var_dump($objetLu);
            } else {
                echo "<h4>*** échec de la mise à jour ***</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°5
        echo "<h3>5- delete</h3>";
        try {
            $ok = AttributionDAO::delete($id);
            if ($ok) {
                echo "<h4>ooo réussite de la suppression ooo</h4>";
            } else {
                echo "<h4>*** échec de la suppression ***</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°6
        echo "<h3>6-obtenirNbDispo - obtenir la disponibilité des chambres </h3>";
        try {
            $objet = AttributionDAO::obtenirNbDispo($idEtab, $idTypeChambre);
            echo "Il y a ".$objet." chambre de type ".$idTypeChambre." pour l'établissement d'id ".$idEtab;
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }
        
        // Test n°7
        echo "<h3>7- obtenirNbOccupGroupe - obtenir le nombre de chambre occupé par groupe </h3>";
        try {
            $objet = AttributionDAO::obtenirNbOccupGroupe('0350773A' , 'C2', 'g004');
            echo "Le groupe d'id g004 occupe ".$objet." chambres de type C2 dans l'établissement d'id 0350773A";
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }
        
        // Test n°8
        echo "<h3>8- existeAttributionsEtab - verifier si une attribution existe pour un établissement | CAS EXISTANT</h3>";
        try {
            $objet = AttributionDAO::existeAttributionsEtab($idEtab);
            if($objet){
                echo "<h4>Une attribution existe pour l'établissement d'id ".$idEtab." : test conforme</h4>";
            }else{
                echo "<h4>Une attribution existe  pas pour l'établissement d'id ".$idEtab." : test non conforme</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        } 
        
        // Test n°8-2
        echo "<h3>8-2 existeAttributionsEtab - verifier si l'attribution existe pour un établissement | CAS NON EXISTANT</h3>";
        try {
            $objet = AttributionDAO::existeAttributionsEtab('nonexistant');
            if($objet){
                echo "<h4>Une attribution existe pour l'établissement d'id ".$idEtab." : test non conforme</h4>";
            }else{
                echo "<h4>Une attribution existe pas pour l'établissement d'id ".$idEtab." : test conforme</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }
        
        // Test n°9
        echo "<h3>9- existeAttributionsTypeChambre - verifier si l'attribution existe pour un type chambre | CAS EXISTANT</h3>";
        try {
            $objet = AttributionDAO::existeAttributionsTypeChambre($idTypeChambre);
            if($objet){
                echo "<h4>Une attribution existe pour le type chambre ".$idTypeChambre." : test conforme</h4>";
            }else{
                echo "<h4>Une attribution existe pas pour le type chambre ".$idTypeChambre." : test non conforme</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        } 
        
        // Test n°9-2
        echo "<h3>9-2 existeAttributionsTypeChambre - verifier si l'attribution existe pour un type chambre | CAS NON EXISTANT</h3>";
        try {
            $objet = AttributionDAO::existeAttributionsTypeChambre('nonexistant');
            if($objet){
                echo "<h4>Une attribution existe pour le type chambre ".$idTypeChambre." : test non conforme</h4>";
            }else{
                echo "<h4>Une attribution existe pas pour le type chambre ".$idTypeChambre." : test conforme</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        } 
        
        // Test n°10
        echo "<h3>10- obtenirNbAttribGrp - obtenir le nombre d'attributions par groupe </h3>";
        try {
            $objet = AttributionDAO::obtenirNbAttribGrp('0350773A' , 'C2', 'g004');
            echo "<h4>Il y a ".$objet." chambres de type C2 dans l'établissement d'id 0350773A pour le groupe d'id g004</h4>";
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        } 
        
        // Test n°11
        echo "<h3>11- obtenirNbOccup </h3>";
        try {
            $objet = AttributionDAO::obtenirNbOccup('0350773A' , 'C2');
            echo "<h4>Il y a ".$objet." chambres de type C2 dans l'établissement d'id 0350773A qui sont occupées</h4>";
        } catch (Exception $ex) {
            echo "<h4>>ooo réussite du test : la requête d'insertion a logiquement échoué ooo</h4>" . $ex->getMessage();
        }
        
        Bdd::deconnecter();
        ?>

    </body>
</html>
