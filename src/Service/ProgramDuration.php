<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{

    public function calculate(Program $program): int
    {
       $seasons = $program->getSeasons();
       $totalDuration = 0;

       foreach ($seasons as $season) {
           $episodes = $season->getEpisodes();

           foreach ($episodes as $episode) {
               $totalDuration += $episode->getDuration();
           }
       }
        return $totalDuration;
    }

    public function convertisseurTime($totalDuration) : string
    {
        if ($totalDuration < 1440) {
            $jours = 0;
            $heures = floor($totalDuration/60);
            $minutes = $totalDuration%60;
        } else {
            $jours = floor($totalDuration/1440);
            $heures = floor(($totalDuration%1440)/60);
            $minutes = (($totalDuration%1440)%60);
        }
        return "$jours jours, $heures heures et $minutes minutes.";
    }

}