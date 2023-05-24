<?php
class Checkup
{
    public static function _absences_successives($max)
    {

        $query = array();
        for ($i = 0; $i < $max; $i++) {
            $date = date('Y-m-d', strtotime("-" . $i . " days"));
            $day_n = (int)date('N',   strtotime($date)) + 1;

            if (in_array($day_n, array(7, 6))) {
                $max++;

                continue;
            }

            $query[] = "ID IN (select Inscription from sco_absences where Retards IS NULL AND DeletedAt IS NULL AND `Date` LIKE '$date%' AND `Cours` = ALL (SELECT `ID` FROM `sco_seance_tracking` WHERE `Seance` = ALL  (SELECT ID from sco_seance WHERE Day =  $day_n AND Classe = ins_inscriptions.Classe)))";
        }
        return  Models\Inscription::getList(array('where' => $query));

        return [];
    }

    public static function absences_successives($max_cumulative)
    {
        $date = date('Y-m-d', strtotime("-1 days"));
        $where = ["ID IN (select `Inscription` from `sco_absences` WHERE `CumulativeAbsence` = $max_cumulative AND `Retards` IS NULL AND `DeletedAt` IS NULL AND `Date` LIKE '$date%')"];
        return  Models\Inscription::getList(array('where' => $where));
    }


    public static function ___absences_successives($max)
    {

        $query = array();
        for ($i = 0; $i < $max; $i++) {
            $date = date('Y-m-d', strtotime("-" . $i . " days"));
            $day_n = (int)date('N',   strtotime($date));

            if (in_array($day_n, array(7, 6))) {
                $max++;

                continue;
            }

            $query = Models\Inscription::sqlQuery(true);
            $query .= "JOIN (SELECT COUNT(`ID`) AS `J1Count`, `Inscription` AS `J1Inscription` ,`Cours` AS `J1Cours` FROM `sco_absences` WHERE `Retards` IS NULL AND `DeletedAt` IS NULL AND `Date` LIKE '$date%') AS `J1` ON `J1`.`J1Inscription` = `ins_inscriptions`.`ID`";
            $query .= "JOIN (SELECT COUNT(`ID`) AS `J2Count`, `ID` AS `J2ID`,`Sceance` AS `J2Sceance` FROM `sco_seance_tracking`) AS `J2` ON `J2`.`J2ID` = `J1`.`J1Cours`";
            $query .= "JOIN (SELECT `ID` AS `J3ID` , `Classe` AS `J3Classe` FROM `sco_seance` WHERE Day = $day_n) AS `J3` ON `J3`.`J3ID` = `J2`.`J2Sceance`";

            $inscriptipons =   Models\Inscription::getList(
                array('where' => [
                    '`J3Classe` = `Classe`',
                    '`J1Count` = `J2Count`'

                ]),
                $query
            );

            // $query[] = "ID,clas IN (select Inscription from sco_absences where Retards IS NULL AND DeletedAt IS NULL AND `Date` LIKE '$date%' AND ";

            // $query = "SELECT `ID` FROM `sco_seance_tracking` WHERE `Seance` IN (SELECT ID from sco_seance WHERE Day = 1  AND Classe = 1 )";

            // " )";
        }
    }

    public static function last_five_successives()
    {
        $number_successives =  ParamsValue('absence_seuil_alerte') ?: 3;
        $ins_successives = array();
        $result = array();

        for ($i = $number_successives; $i <= 5; $i++) {
            $ins_successives[] = array(
                'nombre_jour' => $i,
                'inscriptions' => self::absences_successives($i),
            );
        }

        foreach ($ins_successives as $abs_succ) {
            if (!count($abs_succ['inscriptions'])) {
                continue;
            }
            foreach ($abs_succ['inscriptions'] as $key => $inscription_absence) {
                if (!$inscription_absence->get('Eleve')) {
                    continue;
                }
                $result[$inscription_absence->ID] = array(
                    'nombre_jour' => $abs_succ['nombre_jour'],
                    'inscription' => $inscription_absence
                );
            }
        }



        return $result;
    }
}
