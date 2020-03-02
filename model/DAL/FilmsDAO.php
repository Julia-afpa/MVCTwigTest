<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Films
 *
 * @author Julia
 */
class FilmsDAO extends Dao
{

//Récupérer tout les films
    public function getAll($recherche)
    {
        //On définit la bdd pour la fonction

        $query = $this->_bdd->prepare("SELECT idFilm, titre, realisateur, affiche, annee FROM films WHERE upper(titre) LIKE :recherche OR upper(realisateur) LIKE :recherche OR annee LIKE :recherche ");
        $query->bindValue(':recherche', '%'.strtoupper($recherche).'%');
        $query->execute();
        $films = array();

        while ($data = $query->fetch()) 
        {
            $personnages = array();

            $queryP = $this->_bdd->prepare("SELECT personnage, nom, prenom 
                                        FROM role 
                                            JOIN acteurs ON acteurs.idActeur = role.idActeur
                                                WHERE idFilm = :idFilm");

            $queryP->bindValue(':idFilm', $data['idFilm']);

            $queryP->execute();

            while ($dataP = $queryP->fetch()) 
            {
                $personnages[] = new Personnages($dataP['personnage'], new Acteurs($dataP['nom'],$dataP['prenom']));
            }
            
            $films[] = new Films($data, $personnages);
            

        }
        // var_dump($films[0]->get_personnages());
        return ($films);
    }

    // Ajouter une offre
    // public function add($data)
    // {

    //     $valeurs = ['title' => $data->get_title(), 'description' => $data->get_description()];
    //     $requete = 'INSERT INTO offers (title, description) VALUES (:title , :description)';
    //     $insert = $this->_bdd->prepare($requete);
    //     if (!$insert->execute($valeurs)) {
    //         //print_r($insert->errorInfo());
    //         return false;
    //     } else {
    //         return true;
    //     }

    // }

    //Récupérer plus d'info sur 1 offre
    public function getOne($id_offer)
    {

        $query = $this->_bdd->prepare('SELECT * FROM offers WHERE offers.id = :id_offer')->fetch(PDO::FETCH_ASSOC);
        $query->execute(array(':id_offer' => $id_offer));
        $data = $query->fetch();
        $offer = new Offres($data);
        return ($offer);
    }

   
}
