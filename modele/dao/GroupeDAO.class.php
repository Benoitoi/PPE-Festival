<?php

namespace modele\dao;

use modele\metier\Groupe;
use PDO;

/**
 * Description of GroupeDAO
 * Classe métier :  Groupe
 * @author btssio
 */
class GroupeDAO implements IDAO {


    protected static function enregVersMetier($enreg) {
        $id = $enreg['ID'];
        $nom = $enreg['NOM'];
        $identite = $enreg['IDENTITERESPONSABLE'];
        $adresse = $enreg['ADRESSEPOSTALE'];
        $nbPers = $enreg['NOMBREPERSONNES'];
        $nomPays = $enreg['NOMPAYS'];
        $hebergement = $enreg['HEBERGEMENT'];
        $unGroupe = new Groupe($id, $nom, $identite, $adresse, $nbPers, $nomPays, $hebergement);

        return $unGroupe;
    }
    
    /**
     * Valorise les paramètre d'une requête préparée avec l'état d'un objet Groupe
     * @param type $objetMetier un Groupe
     * @param type $stmt requête préparée
     */
    protected static function metierVersEnreg($objetMetier, $stmt) {
        // On utilise bindValue plutôt que bindParam pour éviter des variables intermédiaires
        $stmt->bindValue(':id', $objetMetier->getId());
        $stmt->bindValue(':nom', $objetMetier->getNom());
        $stmt->bindValue(':identiteResponsable', $objetMetier->getIdentite());
        $stmt->bindValue(':adressePostale', $objetMetier->getAdresse());
        $stmt->bindValue(':nombrePersonnes', $objetMetier->getNbPers());
        $stmt->bindValue(':nomPays', $objetMetier->getNomPays());
        $stmt->bindValue(':hebergement', $objetMetier->getHebergement());
    }

    public static function getAll() {
        $lesObjets = array();
        $requete = "SELECT * FROM Groupe";
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
        $objetConstruit = null;
        $requete = "SELECT * FROM Groupe WHERE ID = :id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        // attention, $ok = true pour un select ne retournant aucune ligne
        if ($ok && $stmt->rowCount() > 0) {
            $objetConstruit = self::enregVersMetier($stmt->fetch(PDO::FETCH_ASSOC));
        }
        return $objetConstruit;
    }

    /**
     * Insérer un nouvel enregistrement dans la table à partir de l'état d'un objet métier
     * @param Groupe $objet objet métier à insérer
     * @return boolean =FALSE si l'opérationn échoue
     */
    public static function insert($objet) {
        $requete = "INSERT INTO Groupe VALUES (:id, :nom, :identiteResponsable, :adressePostale, :nombrePersonnes, :nomPays, :hebergement)";
        $stmt = Bdd::getPdo()->prepare($requete);
        self::metierVersEnreg($objet, $stmt);
        $ok = $stmt->execute();
        return ($ok && $stmt->rowCount() > 0);
    }

    /**
     * Mettre à jour enregistrement dans la table à partir de l'état d'un objet métier
     * @param string identifiant de l'enregistrement à mettre à jour
     * @param Groupe $objet objet métier à mettre à jour
     * @return boolean =FALSE si l'opérationn échoue
     */
    public static function update($id, $objet) {
        $ok = false;
        $requete = "UPDATE Groupe SET NOM=:nom, identiteResponsable=:identiteResponsable, adressePostale=:adressePostale, nombrePersonnes=:nombrePersonnes, nomPays=:nomPays, hebergement=:hebergement WHERE ID=:id";
        $stmt = Bdd::getPdo()->prepare($requete);
        self::metierVersEnreg($objet, $stmt);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        return ($ok && $stmt->rowCount() > 0);
    }

    public static function delete($id) {
        $ok = false;
        $requete = "DELETE FROM Groupe WHERE ID = :id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        $ok = $ok && ($stmt->rowCount() > 0);
        return $ok;
    }

    /**
     * Retourne la liste des groupes d'un groupe donné
     * @param string $idGroupe
     * @return array
     */
    public static function getAllByGroupe($idGroupe) {
        $lesGroupes = array();
        $requete = "SELECT * FROM Groupe
                    WHERE ID IN (
                    SELECT DISTINCT ID FROM Groupe g
                            INNER JOIN Attribution a ON a.IDGROUPE = g.ID 
                            WHERE IDETAB=:id
                    )";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $idGroupe);
        $ok = $stmt->execute();
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesGroupes[] = self::enregVersMetier($enreg);
            }
        } 
        return $lesGroupes;
    }

     /**
     * Permet de vérifier s'il existe ou non un groupe ayant déjà le même identifiant dans la BD
     * @param string $id identifiant du groupe à tester
     * @return boolean =true si l'id existe déjà, =false sinon
     */
    public static function isAnExistingId($id) {
        $requete = "SELECT COUNT(*) FROM Groupe WHERE ID=:id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn(0);
    }

    /**
     * Permet de vérifier s'il existe ou non un groupe portant déjà le même nom dans la BD
     * En mode modification, l'enregistrement en cours de modification est bien entendu exclu du test
     * @param boolean $estModeCreation =true si le test est fait en mode création, =false en mode modification
     * @param string $id identifiant du groupe à tester
     * @param string $nom nom du groupe à tester
     * @return boolean =true si le nom existe déjà, =false sinon
     */
    public static function isAnExistingName($estModeCreation, $id, $nom) {
        $nom = str_replace("'", "''", $nom);
        // S'il s'agit d'une création, on vérifie juste la non existence du nom sinon
        // on vérifie la non existence d'un autre groupe (id!='$id') portant 
        // le même nom
        if ($estModeCreation) {
            $requete = "SELECT COUNT(*) FROM Groupe WHERE NOM=:nom";
            $stmt = Bdd::getPdo()->prepare($requete);
            $stmt->bindParam(':nom', $nom);
            $stmt->execute();
        } else {
            $requete = "SELECT COUNT(*) FROM Groupe WHERE NOM=:nom AND ID<>:id";
            $stmt = Bdd::getPdo()->prepare($requete);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nom', $nom);
            $stmt->execute();
        }
        return $stmt->fetchColumn(0);
    }
    
    /**
     * Retourne la liste des groupes d'un établissement donné
     * @param string $idEtab
     * @return array
     */
    public static function getAllByEtablissement($idEtab) {
        $lesGroupes = array();
        $requete = "SELECT * FROM Groupe
                    WHERE ID IN (
                    SELECT DISTINCT ID FROM Groupe g
                            INNER JOIN Attribution a ON a.IDGROUPE = g.ID 
                            WHERE IDETAB=:id
                    )";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $idEtab);
        $ok = $stmt->execute();
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesGroupes[] = self::enregVersMetier($enreg);
            }
        } 
        return $lesGroupes;
    }
    
    /**
     * Retourne la liste des groupes souhaitant un hébergement
     * @return array liste des groupes souhaitant un hébergement, ordonnée par id
     */
    public static function getAllToHost() {
        $lesGroupes = array();
        $requete = "SELECT * FROM Groupe WHERE HEBERGEMENT='O' ORDER BY ID";
        $stmt = Bdd::getPdo()->prepare($requete);
        $ok = $stmt->execute();
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesGroupes[] = self::enregVersMetier($enreg);
            }
        }
        return $lesGroupes;
    }

}
