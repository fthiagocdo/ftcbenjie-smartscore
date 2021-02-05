<?php

namespace App\Helpers;

use RealRashid\SweetAlert\Facades\Alert;
use App\Competition;
use App\Variable;

class Helper
{
    public static function isRandomOrderCompetition()
    {
        $competition = Competition::orderBy('id')->get()->first();
        if(isset($competition) && $competition->random) {
            Alert::error('Erro', 'A competição foi iniciada! Não é mais possível adicionar/deletar juízes, competidores ou editar os dados da competição.')
                ->autoClose(10000);
            return true;
        } else {
            return false;
        }
    }

    public static function getVariable($name) {
        $variable = Variable::where('name', $name)->get()->first();
        if(isset($variable)) {
            return $variable->value;
        } else {
            return null;
        }
    }

    public static function setVariable($name, $value) {
        $variable = Variable::where('name', $name)->get()->first();

        if(isset($variable)) {
            $variable->name = $name;
            $variable->value = $value;
            $variable->update();
        } else {
            $variable = new Variable();
            $variable->name = $name;
            $variable->value = $value;
            $variable->save();
        }
        
    }

    public static function calculateTotal($total_lap, $score_type) {
        if($score_type == 'total_average') {
            $total = 0;
            
            foreach ($total_lap as $lap) {
                $total += $lap->score;
            }

            if($total_lap->count() == 0) {
                return 0;
            } else {
                return round($total / $total_lap->count(), 2);
            }
        } else if($score_type == 'best_lap') {
            $best_lap = 0;

            foreach ($total_lap as $lap) {
                if($lap->score > $best_lap) {
                    $best_lap = $lap->score;
                }
            }

            return $best_lap;
        }
    }
}