<?php
header('Access-Control-Allow-Origin: *');

use Models\Classe;
use Models\Cycle;
use Models\Evaluation;
use Models\Matiere;
use Models\Niveau;
use Models\NiveauUnite;
use Models\Site;
use Models\Unite;
use Models\User;
use PhpParser\JsonDecoder;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

/**
 * Controller Class 
 */

class ContentController
{
    public function index()
    {

        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $promotion_label  =  \Models\Promotion::promotion_overte_pour_inscriptions()->get('Label');
        $last_promotion  =  $promotion->lastPromo();
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';

        $new_inscriptions = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                'Promotion' => $promotion->ID,
                $where_promotion
            ]
        ]);
        $isCrmAllowed =  isAllowed('activate_crm_module');
        return sendResponse([
            'insc' => count($new_inscriptions),
            'label' => $promotion_label,
            'isCrmAllowed' => $isCrmAllowed,
        ]);
    }
    public function init()
    {
        sendResponse([
            'school' => [
                'logo' => (Config::get('logo') ? URL::absolute(URL::base('assets/images/schools/' . Config::get('logo'))) : URL::absolute(URL::base('assets/images/logo.92874874.png'))),
                'school_name' => Config::get('nom_ecole'),
                'isCrmAllowed' =>    isAllowed('activate_crm_module'),
            ]
        ]);
    }
    public function inscriptions_()
    {
        $getInsReinByWeek =  $this->getInsReinByWeek();
        $getReinscriptionsInscriptions =  $this->getReinscriptionsInscriptions();
        $getReinscriptions =  $this->getReinscriptions();
        $getProspects =  $this->getProspects();
        $getInsReinsPros =  $this->getInsReinsPros();
        $getInscriptionsByCycles =  $this->getInscriptionsByCycles();
        $getInscriptionsByLevel =  $this->getInscriptionsByLevel();
       
        // echo '<pre>';
        // print_r($getReinscriptionsInscriptions);
        // echo '</pre>';
        extract($getInsReinByWeek);
        extract($getReinscriptionsInscriptions);
        extract($getReinscriptions);
        $data = [];
        $data['ins'] = $ins;
        $data['rein'] = $rein;
        $data['days'] = $days;
        $data['last_promotion'] = $last_promotion;
        $data['promotion'] = $promotion;
        $data['total'] = $total;
        $data['pourcentage'] = $pourcentage;
        $data['count_reinscriptions'] = $count_reinscriptions;
        
        extract($getProspects);
        $data['leads'] = $leads;
        $data['label'] = $label;
        $data['pourcentage_prospet'] = $pourcentage_prospet;

        extract($getInsReinsPros);
        $data['insCount'] = $insCount;
        $data['reinsCount'] = $reinsCount;
        $data['insLastYearCount'] = $insLastYearCount;

        extract($getInscriptionsByCycles);
        $data['cycles'] =  $cycles;

        extract($getInscriptionsByLevel);
        $data['ins_level'] =  $ins_level;
        $data['reins_level'] =  $reins_level;
        $data['all_level'] =  $all_level;


        sendResponse($data);
    }
    public function getInscCount()
    {
        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';

        $new_inscriptions = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                'Promotion' => $promotion->ID,
                $where_promotion
            ]
        ]);
        return sendResponse([
            'count' => count($new_inscriptions),
        ]);
    }

    public function getProspects()
    {
        $promotion_label  =  \Models\Promotion::promotion_overte_pour_inscriptions()->get('Label');

        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $last_promo_id = $last_promotion ? $last_promotion->get('ID') : null;
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_3 = $last_promotion ? '`Promotion` = ' . $last_promotion->ID  : '`Validated` IS NOT NULL';

        $new_inscriptions_year = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                $where_promotion_3,
                $where_promotion_
            ]
        ]);
        $count_non_resinsrit = $last_promo_id  ? \Models\Inscription::where(['Promotion' => $last_promo_id])
            ->where("(`Eleve` NOT IN (SELECT `Eleve` FROM `ins_inscriptions` where `Promotion` =" . $promotion->get('ID')  . "))")
            ->where("(`Eleve` IN (SELECT `ID` FROM `gen_eleves`))")
            ->where('(`Depart` IS NULL)')->where('`Validated` IS NOT NULL')->count() : 0;
        $pourcentage = number_format($count_non_resinsrit * 100 /  count($new_inscriptions_year));
        return ['leads' => $count_non_resinsrit, 'label' => $promotion_label, 'pourcentage_prospet' => $pourcentage,];
        return sendResponse([
            'leads' => $count_non_resinsrit,
            'label' => $promotion_label,
            'pourcentage_prospet' => $pourcentage,
        ]);
    }
    public function getInsReinsPros()
    {
        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_3 = $last_promotion ? '`Promotion` = ' . $last_promotion->ID   : '`Validated` IS NOT NULL';

        $new_inscriptions = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                'Promotion' => $promotion->ID,
                $where_promotion
            ]
        ]);
        $reinscriptions = \Models\Inscription::getList([
            'where' => [
                'Promotion' => $promotion->ID,
                "`Validated` IS NOT NULL",
                $where_promotion_
            ]
        ]);
        $new_inscriptions_year = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                $where_promotion_3,
                $where_promotion_
            ]
        ]);
        $query = [
            "insCount" => ($new_inscriptions),
            "reinsCount" => ($reinscriptions),
            "insLastYearCount" => ($new_inscriptions_year),
        ];
        return ['insCount' => count($query["insCount"]), 'reinsCount' => count($query["reinsCount"]), 'insLastYearCount' => count($query["insLastYearCount"]),];
        return sendResponse([
            'insCount' => count($query["insCount"]),
            'reinsCount' => count($query["reinsCount"]),
            'insLastYearCount' => count($query["insLastYearCount"]),
        ]);
    }
    public function getReinscriptions()
    {
        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $reinscriptions = \Models\Inscription::getList([
            'where' => [
                'Promotion' => $promotion->ID,
                "`Validated` IS NOT NULL",
                $where_promotion_
            ]
        ]);
        $count_reinscriptions = count($reinscriptions);
        $data['count_reinscriptions'] = $count_reinscriptions;
        $data['promotion'] = $promotion->get('Label');
        return $data;
        print_r(json_encode($data));
    }
    public function getReinscriptionsInscriptions()
    {


        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_3 = $last_promotion ? '`Promotion` = ' . $last_promotion->ID   : '`Validated` IS NOT NULL';

        $new_inscriptions = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                'Promotion' => $promotion->ID,
                $where_promotion
            ]
        ]);
        $new_inscriptions_year = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                $where_promotion_3,
                $where_promotion_
            ]
        ]);
        $reinscriptions = \Models\Inscription::getList([
            'where' => [
                'Promotion' => $promotion->ID,
                "`Validated` IS NOT NULL",
                $where_promotion_
            ]
        ]);
        $total = count($reinscriptions) + count($new_inscriptions);
        $data['last_promotion'] = $last_promotion ? $last_promotion->get('Label') : '';
        $data['promotion'] = $promotion->get('Label');
        $data['total'] = $total;
        $data['pourcentage'] = $new_inscriptions_year  ? number_format((($total - count($new_inscriptions_year)) * 100) / count($new_inscriptions_year)) : 100;
        return $data;
        print_r(json_encode($data));
    }
    public function getInscriptionsByLevel()
    {
        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';

        $query = [
            "ins" => "select gen_niveaux.Label AS 'niveau_label' , COUNT(DISTINCT ins_inscriptions.ID) AS 'count_ins'   FROM ins_inscriptions INNER JOIN gen_niveaux ON ins_inscriptions.Niveau = gen_niveaux.ID  where Depart IS NULL
            and Validated IS NOT NULL and Promotion = " . $promotion->ID . " and " . $where_promotion . " GROUP BY gen_niveaux.Label",
            "reins" => "select gen_niveaux.Label AS 'niveau_label' , COUNT(DISTINCT ins_inscriptions.ID) AS 'count_ins'   FROM ins_inscriptions INNER JOIN gen_niveaux ON ins_inscriptions.Niveau = gen_niveaux.ID  where
            Validated IS NOT NULL and Promotion = " . $promotion->ID . " and " . $where_promotion_ . " GROUP BY gen_niveaux.Label",
            "all" => "select gen_niveaux.Label AS 'niveau_label' , COUNT(DISTINCT ins_inscriptions.ID) AS 'count_ins'   FROM ins_inscriptions INNER JOIN gen_niveaux ON ins_inscriptions.Niveau = gen_niveaux.ID  where Depart IS NULL
            and Validated IS NOT NULL and Promotion = " . $promotion->ID . " and " . $where_promotion . " or " . $where_promotion_ . " GROUP BY gen_niveaux.Label",
        ];
        $ins_level =  DB::reader($query["ins"]);
        $reins_level =  DB::reader($query["reins"]);
        $all_level =  DB::reader($query["all"]);
        $result = [
            'ins_level' => $ins_level,
            'reins_level' => $reins_level,
            'all_level' => $all_level,
        ];
        return $result;
        print_r(json_encode($result));
    }
    public function getInscriptionsByCycles()
    {
        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';

        $new_inscriptions = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                'Promotion' => $promotion->ID,
                $where_promotion
            ]
        ]);
        $reinscriptions = \Models\Inscription::getList([
            'where' => [
                'Promotion' => $promotion->ID,
                "`Validated` IS NOT NULL",
                $where_promotion_
            ]
        ]);
        $total = count($reinscriptions) + count($new_inscriptions);
        $data['total'] = $total;
        $cycles = array();

        foreach (\Models\Cycle::getList() as $cycle) {
            $cycleInscriptions = array_filter($new_inscriptions, function ($inscri) use ($cycle) {
                return $inscri->Niveau->Cycle->ID == $cycle->ID;
            });
            $cycleReinscriptions = array_filter($reinscriptions, function ($inscri) use ($cycle) {
                return $inscri->Niveau->Cycle->ID == $cycle->ID;
            });

            $cycleNiveaux = [];

            foreach (\Models\Niveau::getList(['where' => ['Cycle' => $cycle->ID]]) as $niveau) {
                $niveauInscriptions = array_filter($cycleInscriptions, function ($inscri) use ($niveau) {
                    return $inscri->Niveau->ID == $niveau->ID;
                });
                $niveauReinscriptions = array_filter($cycleReinscriptions, function ($inscri) use ($niveau) {
                    return $inscri->Niveau->ID == $niveau->ID;
                });
                $cycleNiveaux[] = [
                    'label' => $niveau->Label,
                    'inscriptions' => count($niveauInscriptions),
                    'reinscriptions' => count($niveauReinscriptions),
                ];
            }

            $cycles[] = [
                'id' => $cycle->ID,
                'label' => $cycle->Label,
                'alias' => \Models\Cycle::cyclesAlias($cycle->ID),
                'inscriptions' => count($cycleInscriptions),
                'reinscriptions' => count($cycleReinscriptions),
                'niveaux' => $cycleNiveaux,
            ];
        }
        return ['cycles' => $cycles];
        sendResponse(['cycles' => $cycles]);
    }

    public function getInsReinByDay()
    {
        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $today     = new DateTime(); // today
        $begin     = $today->sub(new DateInterval('P30D')); //created 30 days interval back
        $end       = new DateTime();
        $end       = $end->modify('+1 day'); // interval generates upto last day
        $interval  = new DateInterval('P1D'); // 1d interval range
        $daterange = new DatePeriod($begin, $interval, $end); // it always runs forwards in date
        $new_inscriptions = [];
        $reinscriptions = [];
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';

        foreach ($daterange as $date) { // date object
            $d[] = $date->format("d/m"); // your date
            $new_inscriptions_ = \Models\Inscription::getList([
                'where' => [
                    "DATE(DateInscripiton) = '" . $date->format("Y-m-d") . "'",
                    "Depart IS NULL",
                    "`Validated` IS NOT NULL",
                    'Promotion' => $promotion->ID,
                    $where_promotion
                ]
            ]);
            $new_inscriptions[] = count($new_inscriptions_);
            $reinscriptions_ = \Models\Inscription::getList([
                'where' => [
                    "DATE(DateInscripiton) = '" . $date->format("Y-m-d") . "'",
                    'Promotion' => $promotion->ID,
                    "`Validated` IS NOT NULL",
                    $where_promotion_
                ]
            ]);
            $reinscriptions[] = count($reinscriptions_);
        }

        sendResponse(['ins' => $new_inscriptions, 'rein' => $reinscriptions, 'days' => $d]);
    }

    public function getInsReinByWeek()
    {
        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        for ($i = 1; $i <= 50; $i++) {
            $last_week_dates[] = [
                'start' => date('Y-m-d', strtotime('last monday - ' . $i * 6 . ' day')),
                'end' => date('Y-m-d', strtotime('last monday - ' . ($i * 6 - 6)  . ' day')),
            ];
        }
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $new_inscriptions = [];
        $reinscriptions = [];
        foreach (array_reverse($last_week_dates) as  $date) { // date object

            $new_inscriptions_ = \Models\Inscription::getList([
                'where' => [
                    "DATE(DateInscripiton) BETWEEN '" . $date['start'] . "' AND '" . $date['end'] . "'",
                    "Depart IS NULL",
                    "`Validated` IS NOT NULL",
                    'Promotion' => $promotion->ID,
                    $where_promotion
                ]
            ]);
            // print_r($new_inscriptions_);
            $reinscriptions_ = \Models\Inscription::getList([
                'where' => [
                    "DATE(DateInscripiton) BETWEEN '" . $date['start'] . "' AND '" . $date['end'] . "'",
                    'Promotion' => $promotion->ID,
                    "`Validated` IS NOT NULL",
                    $where_promotion_
                ]
            ]);
            if (!$new_inscriptions_  && !$reinscriptions_) {
                continue;
            }
            $days[] =  date('d/m', strtotime($date['start'])); // your date
            $new_inscriptions[] = $new_inscriptions_ ? count($new_inscriptions_) : 0;
            $reinscriptions[] = $reinscriptions_ ? count($reinscriptions_) : 0;
        }
        return ['ins' => $new_inscriptions, 'rein' => $reinscriptions, 'days' => $days];
        sendResponse(['ins' => $new_inscriptions, 'rein' => $reinscriptions, 'days' => $days]);
    }
    public function getCrmData()
    {
        $data = array();

        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();

        $data['promotion'] = $promotion->Label;
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';

        $new_inscriptions = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                'Promotion' => $promotion->ID,
                $where_promotion
            ]
        ]);

        $count_new_inscriptions = count($new_inscriptions);

        $reinscriptions = \Models\Inscription::getList([
            'where' => [
                'Promotion' => $promotion->ID,
                "`Validated` IS NOT NULL",
                $where_promotion_
            ]
        ]);

        $count_reinscriptions = count($reinscriptions);
        $count_non_resinsrit = $last_promotion  ? \Models\Inscription::where(['Promotion' => $last_promotion->get('ID')])
            ->where("(`Eleve` NOT IN (SELECT `Eleve` FROM `ins_inscriptions` where `Promotion` =" . $promotion->get('ID')  . "))")
            ->where("(`Eleve` IN (SELECT `ID` FROM `gen_eleves`))")
            ->where('(`Depart` IS NULL)')->where('`Validated` IS NOT NULL')->count() : 0;
        $count_prospects = \Models\Parentt::where("(`ID` IN (SELECT `Parent` from `parrainages` WHERE `Eleve` NOT IN (SELECT `Eleve` FROM `ins_inscriptions`)) OR `ID` NOT IN (SELECT `Parent` from `parrainages`))")
            ->order(array('ID' => false))
            ->count();
        $count_testes_admission = \Models\CRM\Action::getCount([
            'where' => [
                'Action' => 'test'
            ]
        ]);

        $count_entretiens = \Models\CRM\Action::getCount([
            'where' => [
                'Action' => 'interview'
            ]
        ]);

        $count_visites = \Models\CRM\Action::getCount([
            'where' => [
                'Action' => 'visite'
            ]
        ]);
        $count_appeles = \Models\CRM\Action::getCount([
            'where' => [
                'Action' => 'call'
            ]
        ]);
        // $test_mission_by_ressponsable = "SELECT COUNT(crm_actions.ID) as 'count_test_admission' , CONCAT(users.Nom , ' ' , users.Prenom ) as 'responsable' FROM crm_actions INNER JOIN users on crm_actions.User = users.ID GROUP BY crm_actions.User;";
        $test_mission_by_ressponsable = " SELECT CONCAT(users.Nom ,' ' ,users.Prenom) as 'responsable' , ActionDetails, count(*) 'count_test_admission' FROM crm_actions INNER JOIN users on users.ID = TRIM(BOTH '\"' FROM JSON_EXTRACT(ActionDetails, '$.affected_to')) GROUP BY JSON_EXTRACT(ActionDetails, '$.affected_to');";
        $cycles = array();

        foreach (\Models\Cycle::getList() as $cycle) {
            $cycleInscriptions = array_filter($new_inscriptions, function ($inscri) use ($cycle) {
                return $inscri->Niveau->Cycle->ID == $cycle->ID;
            });
            $cycleReinscriptions = array_filter($reinscriptions, function ($inscri) use ($cycle) {
                return $inscri->Niveau->Cycle->ID == $cycle->ID;
            });

            $cycleNiveaux = [];

            foreach (\Models\Niveau::getList(['where' => ['Cycle' => $cycle->ID]]) as $niveau) {
                $niveauInscriptions = array_filter($cycleInscriptions, function ($inscri) use ($niveau) {
                    return $inscri->Niveau->ID == $niveau->ID;
                });
                $niveauReinscriptions = array_filter($cycleReinscriptions, function ($inscri) use ($niveau) {
                    return $inscri->Niveau->ID == $niveau->ID;
                });
                $cycleNiveaux[] = [
                    'label' => $niveau->Label,
                    'inscriptions' => count($niveauInscriptions),
                    'reinscriptions' => count($niveauReinscriptions),
                ];
            }

            $cycles[] = [
                'id' => $cycle->ID,
                'label' => $cycle->Label,
                'alias' => \Models\Cycle::cyclesAlias($cycle->ID),
                'inscriptions' => count($cycleInscriptions),
                'reinscriptions' => count($cycleReinscriptions),
                'niveaux' => $cycleNiveaux,
            ];
        }
        $test_admission_by_result = array();
        $test_admission_positif = 0;
        $test_admission_negative = 0;
        $test_admission_enattente = 0;
        foreach (\Models\CRM\Action::getList() as $action) {
            $feedback = json_decode($action->ActionDetails)  ? (isset(json_decode($action->ActionDetails)->feedback) && json_decode($action->ActionDetails)->feedback   ?  json_decode($action->ActionDetails)->feedback : '') : '';
            if (isset($feedback->result) && $feedback->result) {
                if ($feedback->result == 'en_attente') {
                    $test_admission_enattente++;
                } elseif ($feedback->result == 'positif') {
                    $test_admission_positif++;
                } elseif ($feedback->result == 'negatif') {
                    $test_admission_negative++;
                }
            }
        }
        $test_admission_by_result['positif'] = $test_admission_positif;
        $test_admission_by_result['negative'] = $test_admission_negative;
        $test_admission_by_result['enattente'] = $test_admission_enattente;

        $today     = new DateTime(); // today
        $begin     = $today->sub(new DateInterval('P30D')); //created 30 days interval back
        $end       = new DateTime();
        $end       = $end->modify('+1 day'); // interval generates upto last day
        $interval  = new DateInterval('P1D'); // 1d interval range
        $daterange = new DatePeriod($begin, $interval, $end); // it always runs forwards in date
        $new_inscriptions = [];
        $test_admissions_perday = [];
        foreach ($daterange as $date) { // date object
            $d[] = $date->format("d/m"); // your date
            $new_admissions = \Models\CRM\Action::getList([
                'where' => [
                    "DATE(ActionDate) = '" . $date->format("Y-m-d") . "'",
                ]
            ]);
            $test_admissions_perday[] = count($new_admissions);
        }

        // to add condition on promotion later
        $data['count_inscriptions_request'] = \Models\RequestInscription::getCount(['where' => [
            '`ValidateBy` IS NULL',
            '`ValidateAt` IS NULL'
        ]]);
        $data['promotion'] = $promotion->get('Label');
        $data['count_new_inscriptions'] = $count_new_inscriptions;
        $data['count_reinscriptions'] = $count_reinscriptions;
        $data['count_non_resinsrit'] = $count_non_resinsrit;
        $data['count_prospects'] = $count_prospects;
        $data['count_testes_admission'] = $count_testes_admission;
        $data['count_entretiens'] = $count_entretiens;
        $data['count_visites'] = $count_visites;
        $data['count_appeles'] = $count_appeles;
        $data['cycles'] = $cycles;
        $data['test_admission_by_result'] = $test_admission_by_result;
        $data['test_admissions_perday'] = $test_admissions_perday;
        $data['test_mission_by_ressponsable'] = DB::reader($test_mission_by_ressponsable);
        $data['days'] = $d;

        print_r(json_encode($data));
    }
    public function communication()
    {
        $data = array();
        $where = array();
        $date =  isset($_GET["date"]) ? $_GET["date"] : "";
        $where[] = "PostCategorie  IN(SELECT `ID` FROM  `com_postcategories` WHERE `Alias`IN ('actualites','note','blog'))";
        $where[] = "Date LIKE CONCAT('%','" . $date . "','%')";
        $posts = Models\Post::getList(array('where' => $where));
        $messages = Models\COM\Message::getList(array('where' => ["Date LIKE CONCAT('%','" . $date . "','%')"]));
        $reclamation = Models\HDDemande::getList(array('where' => ["Date LIKE CONCAT('%','" . $date . "','%')"]));
        $posts_orders = "SELECT DISTINCT (com_posts.Label) , COUNT(com_postviews.ID) as 'post_count'  from com_postviews INNER JOIN com_posts on com_posts.ID =com_postviews.Post  GROUP BY  com_postviews.Post ORDER BY post_count DESC LIMIT 10;";
        $where = array(
            'PostCategorie' => Models\PostCategorie::getByAlias('album')->ID,
            "Date LIKE CONCAT('%','" . $date . "','%')",
        );
        $albums = Models\Post::getList(array('where' => $where));
        $date_query = $date ? "and `com_posts`.`Date` LIKE CONCAT('%','" . $date . "','%')" : '';
        $devoir_query = [
            "devoires" => "SELECT
            com_posts.Date,
            com_postcategories.Label as 'Categorie',
            CONCAT(users.Nom,' ',users.Prenom) as 'User'
            FROM
            com_posts,
            com_postcategories,
            users
            WHERE
            com_posts.User=users.ID
            AND com_posts.PostCategorie=com_postcategories.ID
            AND com_posts.PostCategorie in(3,4)
            AND com_posts.Date " . $date_query,
            "ressources" => "SELECT DISTINCT `com_posts`.`ID` as 'ID',
            CONCAT(`users`.`Nom`,' ',`users`.`Prenom`) as 'User',
            `roles`.`Label` as 'Role',
            `com_postcategories`.`Label` as 'PostCategorie',
            `com_posts`.`Date` as 'Date'
            FROM
            `com_posts`,
            `users`,
            `roles`,
            `com_postcategories`
            WHERE
            `com_postcategories`.`ID`=`com_posts`.`PostCategorie` 
            AND `com_posts`.`User`=`users`.`ID`
            AND `users`.`Role`=`roles`.`ID`
            AND `com_posts`.`PostCategorie`=6
            " . $date_query,
            "comments" => "select * from com_postcommentaires WHERE com_postcommentaires.Post in (SELECT id from com_posts ) and Date = '" . $date . "'"
        ];

        $data['posts'] = count($posts);
        $data['albums'] = count($albums);
        $data['messages'] = count($messages);
        $data['reclamation'] = count($reclamation);
        $data['devoirs'] = count(DB::reader($devoir_query['devoires']));
        $data['ressources'] = count(DB::reader($devoir_query['ressources']));
        $data['comments'] = count(DB::reader($devoir_query['comments']));
        $data['posts_orders'] = DB::reader($posts_orders);
        $data['reponses'] = count(Models\PostQuestionnaire::getList(array('where' => ["Date LIKE CONCAT('%','" . $date . "','%')"])));
        return sendResponse($data);
    }
    public function structurePersonnel()
    {
        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $where_promotion = $last_promotion ? 'Eleve NOT IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';
        $where_promotion_ = $last_promotion ? 'Eleve IN ( SELECT `Eleve` FROM `ins_inscriptions` WHERE `Promotion` = ' . $last_promotion->ID . ' )'  : '`Validated` IS NOT NULL';

        $new_inscriptions = \Models\Inscription::getList([
            'where' => [
                "Depart IS NULL",
                "`Validated` IS NOT NULL",
                'Promotion' => $promotion->ID,
                $where_promotion
            ]
        ]);
        $reinscriptions = \Models\Inscription::getList([
            'where' => [
                'Promotion' => $promotion->ID,
                "`Validated` IS NOT NULL",
                $where_promotion_
            ]
        ]);
        // $ins_by_gender = "select COUNT(users.ID) 'count_eleves' ,  users.Homme 'gender' from users where users.id in (select gen_eleves.User from gen_eleves where id in (select ins_inscriptions.Eleve from ins_inscriptions where Depart IS NULL and Validated IS NOT NULL AND  ins_inscriptions.Promotion = " . $promotion->get('ID') . " )) GROUP BY gender;";
        $ins_by_site = "SELECT COUNT(DISTINCT ins_inscriptions.ID) 'count_eleves' , ins_inscriptions.Site 'site' FROM ins_inscriptions WHERE Promotion = " . $promotion->ID . " and Validated IS NOT NULL and Depart IS NULL and " . $where_promotion_ . " or " . $where_promotion . " GROUP BY site";
        $ins_by_gender = "SELECT COUNT( gen_eleves.ID) 'count_eleves' , users.Homme 'gender'  FROM gen_eleves INNER JOIN users ON gen_eleves.User = users.ID WHERE gen_eleves.ID in (select ins_inscriptions.Eleve from ins_inscriptions WHERE Depart IS NULL and Validated IS NOT NULL and ins_inscriptions.Promotion = " . $promotion->ID . ") GROUP BY gender;";
        $ens_by_gender = "SELECT COUNT(users.ID) 'count_ens' , users.Homme 'gender' from users INNER JOIN gen_enseignants on gen_enseignants.User = users.ID GROUP BY gender";
        $ens_by_unites = "SELECT COUNT(sco_enseignantunite.Enseignant) 'count_ens' , sco_unites.Label 'label' FROM sco_unites INNER JOIN sco_enseignantunite on sco_unites.ID = sco_enseignantunite.Unite GROUP BY label";
        $ens_by_cycles = "SELECT COUNT(sco_enseignantniveaux.ID) 'ens_count' , cycles.Label 'cycle' FROM sco_enseignantniveaux INNER JOIN gen_niveaux on gen_niveaux.ID = sco_enseignantniveaux.Niveau INNER JOIN cycles on gen_niveaux.Cycle = cycles.ID GROUP by cycle";
        $team_by_function = "SELECT COUNT(rh_collaborateur.ID) 'ens_count' ,rh_collaborateur.Fonction 'function' FROM rh_collaborateur GROUP BY function;";
        $team_by_age = "SELECT avg( DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), users.DateNaissance)), '%Y')+0) AS 'age'  from users WHERE users.Role = 4";
        $data = array();
        $data['inscriptions'] = count($new_inscriptions) + count($reinscriptions);
        $data['classes'] = count(\Models\Classe::getList());
        $data['enseignants'] = count(\Models\Enseignant::getList());
        $data['team'] = count(\Models\RH\Collaborateur::getList());
        $data['ins_by_gender'] = DB::reader($ins_by_gender);
        $data['ins_by_site'] = DB::reader($ins_by_site);
        $data['ens_by_gender'] = DB::reader($ens_by_gender);
        $data['ens_by_unites'] = DB::reader($ens_by_unites);
        $data['ens_by_cycles'] = DB::reader($ens_by_cycles);
        $data['team_by_function'] = DB::reader($team_by_function);
        $data['team_by_age'] = DB::reader($team_by_age);
        $data['promotion'] = $promotion->get('Label');
        print_r(json_encode($data));
    }
    public function filterNiveauByCycle()
    {
        if (isset($_GET['cycle']) && $_GET['cycle'] && $_GET['cycle'] != 'Tous') {

            $cycle = Cycle::where(['Label' => $_GET['cycle']])->first();
            $niveaux = Niveau::getList(['where' => [
                'Cycle' => $cycle->ID
            ]]);
        } else {
            $niveaux = Niveau::getList();
        }
        $niveaux = array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $niveaux);
        print_r(json_encode(['niveaux' => $niveaux]));
    }
    public function filterClasseByNiveau()
    {
        if (isset($_GET['niveau']) && $_GET['niveau'] && $_GET['niveau'] != 'Tous' && $_GET['niveau'] != 'undefined') {
            $niveau = Niveau::where(['Label' => $_GET['niveau']])->first();
            $classes = Classe::getList(['where' => [
                'Niveau' => $niveau->ID
            ]]);
        } else {
            $classes = Classe::getList();
        }
        $filter_by_classe = $classes ? $classes[0]->ID : null;
        $inscriptions_per_classe = \Models\Classe::getList(['where' => ['ID' => $filter_by_classe]])[0]->getCountEleves();
        $classes = array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $classes);
        print_r(json_encode([
            'classes' => $classes,
            'inscriptions_per_classe' => $inscriptions_per_classe
        ]));
    }
    public function lastDays()
    {
        $data = [];
        $today     = new DateTime(); // today
        $begin     = $today->sub(new DateInterval('P30D')); //created 30 days interval back
        $end       = new DateTime();
        $end       = $end->modify('+1 day'); // interval generates upto last day
        $interval  = new DateInterval('P1D'); // 1d interval range
        $daterange = new DatePeriod($begin, $interval, $end); // it always runs forwards in date
        foreach ($daterange as $date) { // date object
            $d[] = $date->format("d/m"); // your date

        }
        $data['days'] = $d;
        print_r(json_encode($data));
    }
    public function getMatieres()
    {
        if (isset($_GET['unite']) && $_GET['unite'] && $_GET['unite'] != 'Tous') {

            $unite = Unite::where(['Label' => $_GET['unite']])->first();
            $matieres = Matiere::getList(['where' => [
                'Unite' => $unite->ID
            ]]);
        } else {
            $matieres = Matiere::getList();
        }
        $matieres = array_map(fn ($item) => ['value' => $item->ID, 'text' => $item->Label], $matieres);
        print_r(json_encode(['matieres' => $matieres]));
    }
    public function notesCLasse_()
    {
        $cont_passes_unites = DB::reader("SELECT count(sco_evaluations.ID) 'count_ev' , sco_unites.Label FROM sco_evaluations INNER JOIN sco_unites on sco_evaluations.Unite = sco_unites.ID WHERE Niveau IS NOT NULL AND Classe IS NOT NULL AND `Semestre` = 1 AND NotesValidees is not null AND NotesPubliees is not null AND sco_evaluations.ID in (SELECT notes.Evaluation from notes) GROUP by sco_unites.Label;");
    }
    public function notesCLasse()
    {
        $data = [];

        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();

        $classes = array_map(fn ($item) => $item->Label, Models\Classe::getList());
        $cycles = array_map(fn ($item) => $item->Label, Models\Cycle::getList());
        $niveaux = array_map(fn ($item) => $item->Label, Models\Niveau::getList());
        $semestres = ['Semestre 1', 'Semestre 2'];

        $filterNiveau = isset($_GET['niveau']) && $_GET['niveau'] && $_GET['niveau'] != 'undefined'  ? Models\Niveau::getList(['where' => ['Label' => $_GET['niveau']]])[0]->ID :  Models\Niveau::getList(['where' => ['Label' => $niveaux[0]]])[0]->ID;
        $filterClasse =  isset($_GET['classe']) && $_GET['classe'] && $_GET['classe'] != 'undefined' && Models\Classe::getList(['where' => ['Label' => $_GET['classe']]]) ? Models\Classe::getList(['where' => ['Label' => $_GET['classe']]])[0]->ID : Models\Classe::getList(['where' => ['Label' => $classes[0]]])[0]->ID;
        $filterSemestre =  isset($_GET['semestre']) && $_GET['semestre'] == "Semestre 1" ? 1 : 2;

        $where = [];
        $where[] =  $filterNiveau ? 'Niveau = ' . $filterNiveau : 'Niveau IS NOT NULL';
        $where[] =   'Classe = ' . $filterClasse;
        if ($last_promotion) {

            $where['Promotion'] =  $last_promotion->ID;
        }
        $where[] =  '`Validated` IS NOT NULL';

        $inscriptions_per_classe = \Models\Classe::getList(['where' => ['ID' => $filterClasse]])[0]->getCountEleves();

        $where = [];
        $where[] =  'Niveau = ' . $filterNiveau;
        $niveaux_unites = NiveauUnite::getList(['where' => $where]);
        $controles_programmes = 0;
        foreach ($niveaux_unites as $niveau_unites) {
            $niveaux_classes = Classe::getList(['where' => ['Niveau' => $niveau_unites->Niveau->ID]]);
            $controles_continue_by_semestre = $filterSemestre == 1 ? explode(',', $niveau_unites->Evaluations) : explode(',', $niveau_unites->EvaluationsS2);
            $count_ev = 0;
            $count_evs = 0;
            $count_ev = (isset($controles_continue_by_semestre) && $controles_continue_by_semestre[0] ? $controles_continue_by_semestre[1] : 0);
            try {
                // code...
                $count_evs = ($count_ev *   count($niveau_unites->Unite->getProgramtionMatieres($niveau_unites->Niveau->get('ID')))) * count($niveaux_classes);
            } catch (\Throwable $th) {
                continue;
            }
            $controles_programmes +=  $count_evs;
        }

        $where[] =  'Classe = ' . $filterClasse;

        // $where[] =  'NotesValidees is null';
        $where[] =   'Semestre = ' . $filterSemestre;
        $where[] =  'date < curdate()';
        $controles_planifier = count(Evaluation::getList(['where' => $where]));

        unset($where[3]);
        // $where[] =  'NotesPubliees is not null';
        $where[] =  'date > curdate()';
        $controles_passes = Evaluation::getList(['where' => $where]);

        unset($where[3]);
        $where[] =  'ID in (SELECT notes.Evaluation from notes)';
        $controles_notes = Evaluation::getList(['where' => $where]);


        $data['classes'] = $classes;
        $data['cycles'] = $cycles;
        $data['niveaux'] = $niveaux;
        $data['semestres'] = $semestres;
        $data['inscriptions_per_classe'] = $inscriptions_per_classe;
        $data['controles_programmes'] = $controles_programmes;
        $data['controles_planifier'] = $controles_planifier;
        $data['controles_passes'] = count($controles_passes);
        $data['controles_notes'] = count($controles_notes);
        print_r(json_encode($data));
    }
    public function getUniteEvaluations()
    {
        $promotion  =  \Models\Promotion::promotion_overte_pour_inscriptions();
        $last_promotion  =  $promotion->lastPromo();
        $data = [];
        $where = [];
        $niveau = isset($_GET["niveau"])  && $_GET["niveau"] && $_GET["niveau"] != 'Tous' ?  Models\Niveau::where(['Label' => $_GET["niveau"]])->first()->get('ID')  : null;
        $filterPeriod = isset($_GET["period"])  && $_GET["period"] == "Semestre 1" ? 1 : 2;
        $filterNiveau = $niveau ? 'Niveau = ' . $niveau : 'Niveau IS NOT NULL';
        $where[] =  'NotesValidees is not null';
        $where[] =  'NotesPubliees is not null';
        $where['Semestre'] =  $filterPeriod;
        $unite = null;
        if (isset($_GET['unite']) && $_GET['unite']) {
            $unite = Unite::where(['Label' => $_GET['unite']])->first();
        }
        $unites = Unite::getList();
        $unites_evaluations = [];
        foreach ($unites as $unite) {
            $total = 0;
            $where['Unite'] =  $unite->ID;
            # code...
            $evaluations_programmes =  0;
            $niveaux_unites = NiveauUnite::getList(['where' => ['Unite' => $unite->ID,  $filterNiveau]]);
            foreach ($niveaux_unites as $niveau_unites) {
                $semestre_count_evaluations = 0;
                $semestre_evaluations = $filterPeriod == 1 ?  explode(',', $niveau_unites->Evaluations) : explode(',', $niveau_unites->EvaluationsS2);
                $semestre_count_evaluations += (isset($semestre_evaluations) && $semestre_evaluations[0]   ? $semestre_evaluations[1] : 0);
                try {
                    // code...
                    $evaluations_programmes += ($semestre_count_evaluations) *  ($niveau_unites->getMatieres() ? count($niveau_unites->getMatieres()) : 0);
                } catch (\Throwable $th) {
                    continue;
                }
            }

            $evaluations_passed =  Evaluation::getList(['where' => $where]);
            $unites_evaluations[] = [
                'evaluations_passed' => count($evaluations_passed),
                'evaluations_programmes' => $evaluations_programmes,
                'unite_label' => $unite->Label,
            ];
        }
        echo  '<pre>';
        print_r($unites_evaluations);
        echo  '</pre>';
        exit;
        $matieres =   Matiere::getList(['where' => ['unite' => ($unite ? $unite->ID :  $unites[0]->ID)]]);
        // $cont_passes_unites = DB::reader("SELECT count(sco_evaluations.ID) 'count_ev' , sco_unites.Label FROM sco_evaluations INNER JOIN sco_unites on sco_evaluations.Unite = sco_unites.ID WHERE Niveau IS NOT NULL AND Classe IS NOT NULL AND `Semestre` = 1 AND NotesValidees is not null AND NotesPubliees is not null AND sco_evaluations.ID in (SELECT notes.Evaluation from notes) GROUP by sco_unites.Label;");

        $data['unites_evaluations'] = $unites_evaluations;
        print_r(json_encode($data));
    }
    public function getLinks()
    {
        $data = array();
        $links = [
            [
                'title' => 'Inscriptions',
                'icon' =>  ['icon' => 'tabler-user'],
                'to' => 'analytics-inscriptions',
            ],
            [
                'title' => 'Crm',
                'icon' =>  ['icon' => 'tabler-users'],
                'to' => 'analytics-prospects',
            ],
            [
                'title' => 'Communication',
                'icon' =>  ['icon' => 'tabler-file'],
                'to' => 'analytics-comunication',
            ],
            [
                'title' => 'Structure & personnel',
                'icon' =>  ['icon' => 'tabler-school'],
                'to' => 'analytics-personnel',
            ],
            [
                'title' => 'Notes et évaluations',
                'icon' =>  ['icon' => 'tabler-notes'],
                'to' => 'analytics-noteclasse',
            ],
            // [
            //     'title' => 'Select',
            //     'to' => 'forms-select',
            //     'icon' => ['icon' => 'tabler-file']
            // ],
        ];
        $data['links'] = $links;
        print_r(json_encode($data));
    }
}

/* Router options */
$action = Request::getArgs(0) ? Request::getArgs(0) : 'getInscCount';
$id = Request::getArgs(1);
$controller = new ContentController;


if (!method_exists('ContentController', $action))
    $controller->index();


$controller->{$action}($id);
