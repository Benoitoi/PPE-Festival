<?php
namespace modele\dao;

use modele\metier\Attribution;
use PDO;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AttributionDAO
 *
 * @author btssio
 */
class AttributionDAO {
    protected static function enregVersMetier($enreg) {
        $idEtab = $enreg[strtoupper('idEtab')];
        $idTypeChambre = $enreg[strtoupper('idTypeChambre')];
        $idGroupe = $enreg[strtoupper('idGroupe')];
        $nombreChambres = $enreg[strtoupper('nombreChambres')];

        $uneAttribution = new Attribution($idEtab, $idTypeChambre, $idGroupe, $nombreChambres);

        return $uneAttribution;
    }

    /**
     * Valorise les paramètre d'une requête préparée avec l'état d'un objet Attribution
     * @param type $objetMetier un Attribution
     * @param type $stmt requête préparée
     */
    protected static function metierVersEnreg($objetMetier, $stmt) {
        // On utilise bindValue plutôt que bindParam pour éviter des variables intermédiaires
        $stmt->bindValue(':idEtab', $objetMetier->getIdEtab());
        $stmt->bindValue(':idTypeChambre', $objetMetier->getIdTypeChambre());
        $stmt->bindValue(':idGroupe', $objetMetier->getIdGroupe());
        $stmt->bindValue(':nombreChambres', $objetMetier->getNbChambres());
    }
     /**
     * Insérer un nouvel enregistrement dans la table à partir de l'état d'un objet métier
     * @param Etablissement $objet objet métier à insérer
     * @return boolean =FALSE si l'opérationn échoue
     */
    public static function insert($objet) {
        $req = "INSERT INTO Attribution VALUES(:idEtab, :idTypeChambre, :idGroupe, :nombreChambres)";
        $stmt = Bdd::getPdo()->prepare($req);
        self::metierVersEnreg($objet, $stmt);
        $ok = $stmt->execute();
        return $ok;
    }

    /**
     * Mettre à jour enregistrement dans la table à partir de l'état d'un objet métier
     * @param string identifiant de l'enregistrement à mettre à jour
     * @param Etablissement $objet objet métier à mettre à jour
     * @return boolean =FALSE si l'opérationn échoue
     */
    public static function update($id, $objet) {
        $idEtab = $id['idEtab'];
        $idTypeChambre = $id['idTypeChambre'];
        $idGroupe = $id['idGroupe'];
        $req = "UPDATE Attribution SET nombreChambres=:nombreChambres WHERE idEtab=:idEtab AND idTypeChambre=:idTypeChambre AND idGroupe=:idGroupe";
        $stmt = Bdd::getPdo()->prepare($req);
        self::metierVersEnreg($objet, $stmt);
        $stmt->bindParam(':idEtab', $idEtab);
        $stmt->bindParam(':idTypeChambre', $idTypeChambre);
        $stmt->bindParam(':idGroupe', $idGroupe);
        $ok = $stmt->execute();
        return $ok;
    }

    public static function delete($id) {
        $idEtab = $id['idEtab'];
        $idTypeChambre = $id['idTypeChambre'];
        $idGroupe = $id['idGroupe'];
        $req = "DELETE FROM Attribution WHERE idEtab=:idEtab AND idTypeChambre=:idTypeChambre AND idGroupe=:idGroupe";
        $stmt = Bdd::getPdo()->prepare($req);
        $stmt->bindParam(':idEtab', $idEtab);
        $stmt->bindParam(':idTypeChambre', $idTypeChambre);
        $stmt->bindParam(':idGroupe', $idGroupe);
        $ok = $stmt->execute();
        return $ok;
    }

    public static function getAll() {
        $lesObjets = array();
        $requete = "SELECT * FROM Attribution";
        $stmt = Bdd::getPdo()->prepare($requete);
        $ok = $stmt->execute();
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesObjets[] = self::enregVersMetier($enreg);
            }
        }
        return $lesObjets;
    }

    public static function getOneById($id) {
        $idEtab = $id['idEtab'];
        $idTypeChambre = $id['idTypeChambre'];
        $idGroupe = $id['idGroupe'];
        $objetConstruit = null;
        $requete = "SELECT * FROM Attribution WHERE idEtab=:idEtab AND idGroupe=:idGroupe AND idTypeChambre=:idTypeChambre";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':idEtab', $idEtab);
        $stmt->bindParam(':idGroupe', $idGroupe);
        $stmt->bindParam(':idTypeChambre', $idTypeChambre);
        $ok = $stmt->execute();
        // attention, $ok = true pour un select ne retournant aucune ligne
        if ($ok && $stmt->rowCount() > 0) {
            $objetConstruit = self::enregVersMetier($stmt->fetch(PDO::FETCH_ASSOC));
        }
        return $objetConstruit;
    }
    
    public static function obtenirNbDispo($idEtab, $idTypeChambre) {
        $nbOffre = OffreDAO::obtenirNbOffre($idEtab, $idTypeChambre);
        if ($nbOffre != 0) {
            // Recherche du nombre de chambres occupées pour l'établissement et le
            // type de chambre en question
            $nbOccup = self::obtenirNbOccup($idEtab, $idTypeChambre);
            // Calcul du nombre de chambres libres
            $nbChLib = $nbOffre - $nbOccup;
            return $nbChLib;
        } else {
            return 0;
        }
    }
    
    public static function obtenirNbOccupGroupe($idEtab, $idTypeChambre, $idGroupe) {
        $req = "SELECT nombreChambres FROM Attribution WHERE idEtab=:idEtab AND idTypeChambre=:idTypeChambre AND idGroupe=:idGroupe";
        $stmt = Bdd::getPdo()->prepare($req);
        $stmt->bindParam(':idEtab', $idEtab);
        $stmt->bindParam(':idTypeChambre', $idTypeChambre);
        $stmt->bindParam(':idGroupe', $idGroupe);
        $stmt->execute();
        $ok = $stmt->fetchColumn();
        if ($ok) {
            return $ok;
        } else {
            return 0;
        }
    }
    public static function existeAttributionsEtab($idEtab) {
        $req = "SELECT COUNT(*) FROM Attribution WHERE idEtab=:idEtab";
        $stmt = Bdd::getPdo()->prepare($req);
        $stmt->bindParam(':idEtab', $idEtab);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public static function existeAttributionsTypeChambre($idTypeChambre) {
        $req = "SELECT COUNT(*) FROM Attribution WHERE idTypeChambre=:idTypeChambre";
        $stmt = Bdd::getPdo()->prepare($req);
        $stmt->bindParam(':idTypeChambre', $idTypeChambre);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public static function existeAttributionsGroupe($idGroupe) {
        $req = "SELECT COUNT(*) FROM Attribution WHERE idGroupe=:idGroupe";
        $stmt = Bdd::getPdo()->prepare($req);
        $stmt->bindParam(':idGroupe', $idGroupe);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public static function obtenirNbAttribGrp($idEtab, $idTypeChambre, $idGroupe) {
        $req = "SELECT COUNT(*) AS nombreAttribGroupe FROM Attribution WHERE idEtab= :idEtab AND idTypeChambre=:idTypeChambre AND idGroupe=:idGroupe";
        $stmt = Bdd::getPdo()->prepare($req);
        $stmt->bindParam(':idEtab', $idEtab);
        $stmt->bindParam(':idTypeChambre', $idTypeChambre);
        $stmt->bindParam(':idGroupe', $idGroupe);
        $stmt->execute();
        $lgAttrib = $stmt->fetchColumn();
        return $lgAttrib;
    }
    public static function obtenirNbOccup($idEtab, $idTypeChambre) {
        $req = "SELECT IFNULL(SUM(nombreChambres), 0) AS totalChambresOccup FROM Attribution WHERE idEtab=:idEtab AND idTypeChambre=:idTypeChambre";
        $stmt = Bdd::getPdo()->prepare($req);
        $stmt->bindParam(':idEtab', $idEtab);
        $stmt->bindParam(':idTypeChambre', $idTypeChambre);
        $stmt->execute();
        $nb = $stmt->fetchColumn();
        return $nb;
    }
}
