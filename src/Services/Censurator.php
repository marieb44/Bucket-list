<?php

namespace App\Services;

class Censurator
{

    public function purify(string $str): string
    {
        //idéalement, lire la liste des gros mots dans un fichier
//      const MOTS_INTERDITS = ["putain", "merde", "connerie"];

        //l'application est appelée depuis "public"
        $json = file_get_contents("../data/listeGrosMots.json");

        //transformation du fichier en tableau indexé
        $grosMots = json_decode($json, false);

        //piste: construire le tableau associatif "grosMot" => "motMasque" cf AKIM

        $cleanStr = $str;
//       foreach (self::MOTS_INTERDITS as $mot) {
        foreach ($grosMots as $mot) {
            $motMasque = str_repeat("*", strlen($mot));
            $cleanStr = str_ireplace($mot, $motMasque, $cleanStr);
        }
        return $cleanStr;
    }

}