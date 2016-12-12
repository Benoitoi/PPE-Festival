<?php
namespace modele\metier;

/**
 * Description of Attribution
 *
 * @author btssio
 */
class Attribution {
    /**
     * identifiant de l'établissement
     * @var string
     */
    private $idEtab;
    /**
     * identifiant du type de chambre
     * @var string
     */
    private $idTypeChambre;
    /**
     * identifiant du groupe 
     * @var string
     */
    private $idGroupe;
    /**
     * nombre de chambres attribuées
     * @var int
     */
    private $nbChambres;
    
    
    function __construct($idEtab, $idTypeChambre,$idGroupe, $nbChambres) {
        $this->idEtab = $idEtab;
        $this->idTypeChambre = $idTypeChambre;
        $this->idGroupe = $idGroupe;
        $this->nbChambres = $nbChambres;
    }
    
    function getIdEtab() {
        return $this->idEtab;
    }

    function getIdTypeChambre() {
        return $this->idTypeChambre;
    }

    function getIdGroupe() {
        return $this->idGroupe;
    }

    function getNbChambres() {
        return $this->nbChambres;
    }

    function setIdEtab($idEtab) {
        $this->idEtab = $idEtab;
    }

    function setIdTypeChambre($idTypeChambre) {
        $this->idTypeChambre = $idTypeChambre;
    }

    function setIdGroupe($idGroupe) {
        $this->idGroupe = $idGroupe;
    }

    function setNbChambres($nbChambres) {
        $this->nbChambres = $nbChambres;
    }
}
