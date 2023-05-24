<?php

namespace Models\CRM;

use \Models\Model;

class Action extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'crm_actions';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );

    protected static $fields = array(
        'Parent' => array(
            'fk' => 'Parentt',
        ),
        'Eleve' => array(
            'fk' => 'Eleve',
        ),
        'Promotion' => array(
            'fk' => 'promotion',
        ),
        'Action' => array(
            'type' => 'varchar',
        ),
        'Promotion' => array(
            'fk' => 'promotion',
        ),
        'PersonsContacted' => array(
            'type' => 'text',
        ),
        'ActionDate' => array(
            'type' => 'datetime',
        ),
        'ActionDetails' => array(
            'type' => 'text',
        ),
        'Commentaire' => array(
            'type' => 'varhcar',
        ),
        'Feedback' => array(
            'type' => 'varchar',
        ),
        'Files' => array(
            'type' => 'text',
        ),
        'User' => array(
            'fk' => 'User',
        ),
        'DateCreation' => array(
            'type' => 'date',
        ),
        'EditHistory' => array(
            'type' => 'text',
        ),
        'Deleted' => array(
            'type' => 'text',
        ),
    );

    function lead()
    {
        return Lead::where(array('LeadReference' => $this->LeadReference))->first();
    }

    static function  getActionLabel($action = null)
    {


        switch ($action) {
            case 'call':
                return "Appel";
                break;

            case 'email':
                return "E-mail";
                break;
            case 'send_email':
                return "Envoyer Mail";
                break;
            case 'test':
                return "Test d'admission";
                break;

            case 'interview':
                return "Entretien";
                break;


            case 'visite':
                return "Visite";
                break;

            case 'form':
                return "Admission Form";
                break;

            case 'letter':
                return "Admission Letter";
                break;

            case 'contract':
                return "Contracts & Autorisations";
                break;

            case 'fees':
                return "Fees payments";
                break;

            default:
                return "Visite";
                break;
        }
    }

    static function labels($key_status = null)
    {
        $status =  [
            'call' => 'Appel',
            'email' => 'E-mail',
            'send_email' => 'Envoyer un e-mail',
            'test' => 'Test d\'admission',
            'form' => 'Form d\'admission',
            'interview' => 'Entretien',
            'contract' => 'Contrats & Autorisations',
            'fees' => 'Paiements des frais',
            'visite' => 'Visite',
            'sms' => 'SMS',
            'letter' => 'Lettre d\'admission'
        ];

        if ($key_status) {
            return $status[trim($key_status)];
        }

        return  $status;
    }


    static function  visiteStatus($key_status = null)
    {
        $status =  [
            'a_programmer' => 'A programmer',
            'non_applicable' => 'Non applicable',
            'faite' => 'Faite'
        ];

        if ($key_status) {
            return $status[trim($key_status)];
        }

        return  $status;
    }

    static function  visiteResultFeedback($key_result = null)
    {
        $result =  [
            'positif' => 'Positif',
            'negatif' => 'Négatif',
            'en_attente' => 'En attente'
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }

    static function  visitColors($key_result = null)
    {
        $result =  [
            'a_programmer' => 'l-neutre',
            'non_applicable' => 'l-success',
            'faite' => 'l-danger',
            'done' => 'l-success',
            'awaiting_feedback' => 'l-neutre',
            'positif' => 'l-success',
            'negatif' => 'l-danger',
            'en_attente' => 'l-danger',
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }

    static function  callStatus($key_result = null)
    {
        $result =  [
            'busy' => 'occupée',
            'connected' => 'Lié',
            'left_voice_message' => 'Message vocal',
            'left_whatsapp_message' => 'Message WhatsApp',
            'wrong_number' => 'Numéro Erroné',
            'no_answer' => 'Pas de réponse',
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }

    static function  callColors($key_result = null)
    {
        $result =  [
            'busy' => 'l-neutre',
            'connected' => 'l-success',
            'left_voice_message' => 'l-neutre',
            'left_whatsapp_message' => 'l-neutre',
            'wrong_number' => 'l-danger',
            'no_answer' => 'l-danger',
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }


    static function  interviewStatus($key_status = null)
    {
        $status =  [
            'to_be_scheduled'   => 'À planifier',
            'scheduled'         => 'Programmé',
            'done_with_one'     => 'Fait avec un parent',
            'done_with_both'    => 'Fait avec les deux parents',
        ];

        if ($key_status) {
            return $status[trim($key_status)];
        }

        return  $status;
    }


    static function  interviewResultFeedback($key_result = null)
    {
        $result =  [
            'awaiting_feedback' => 'En attente d\'un retour',
            'positive' => 'Positive',
            'undecided' => 'Indécise',
            'negative' => 'Négative'
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }

    static function  interviewColors($key_result = null)
    {
        $result =  [
            'awaiting_feedback' => 'l-neutre',
            'positive' => 'l-success',
            'undecided' => 'l-neutre',
            'negative' => 'l-danger'
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }


    static function  formStatus($key_status = null)
    {

        $status =  [
            'to_be_sent'   => 'A envoyer',
            'sent'         => 'Envoyé',
            'filled_manually'     => 'Rempli manuellement',
        ];

        if ($key_status) {
            return isset($result[$key_status]) ? $result[$key_status] : 'A programmer';
        }

        return  $status;
    }

    static function  formColors($key_result = null)
    {
        $result =  [
            'to_be_sent'   => 'l-neutre',
            'sent'         => 'l-success',
            'filled_manually'     => 'l-dangery',
            'awaiting_feedback' => 'l-dangery',
            'filled_awaiting ' => 'l-dangery',
            'review' => 'l-success',
            'filled_reviewed_ok' => 'l-success',
            'filled_reviewed_ko' => 'l-neutre'
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }

    static function  formResultFeedback($key_result = null)
    {
        $result =  [
            'awaiting_feedback' => 'En attente d\'un retour',
            'filled_awaiting ' => 'Rempli en attente',
            'review' => 'Review',
            'filled_reviewed_ok' => 'Rempli et examiné',
            'filled_reviewed_ko' => 'Rempli et examiné'

        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }


    static function  testStatus($key_status = null)
    {
        $status =  [
            'a_programmer' => 'A programmer',
            'non_applicable' => 'Non applicable',
            'faite' => 'Faite'
        ];

        if ($key_status) {
            return isset($result[trim($key_status)]) ? $result[trim($key_status)] : 'A programmer';
        }

        return  $status;
    }

    static function  testColors($key_result = null)
    {
        $result =  [
            'to_be_scheduled' => 'l-neutre',
            'scheduled' => 'l-success',
            'canceled' => 'l-danger',
            'done' => 'l-success',
            'awaiting_feedback' => 'l-neutre',
            'positif' => 'l-success',
            'undecided' => 'l-danger',
            'negative' => 'l-danger',
        ];

        if ($key_result) {
            return isset($result[trim($key_result)]) ? $result[trim($key_result)] : 'l-danger';
        }

        return  $result;
    }

    static function  testResultFeedback($key_result = null)
    {
        $result =  [
            'positif' => 'Positif',
            'negatif' => 'Négatif',
            'en_attente' => 'En attente'
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }

    static function  letterStatus($key_status = null)
    {
        $status =  [
            'awaiting_process' => 'Processus en attente',
            'to_be_sent' => 'Être envoyé',
            'sent' => 'Expédié',
        ];

        if ($key_status) {
            return $status[trim($key_status)];
        }

        return  $status;
    }

    static function  letterColors($key_result = null)
    {
        $result =  [
            'awaiting_process' => 'l-neutre',
            'to_be_sent' => 'l-success',
            'sent' => 'l-danger',
            'accepted' => 'l-success',
            'refused' => 'l-danger',

        ];

        if ($key_result) {
            return isset($result[trim($key_result)]) ? $result[trim($key_result)] : 'l-neutre';
        }

        return  $result;
    }

    static function letterResultFeedback($key_result = null)
    {
        $result =  [
            'refused' => 'Refusé',
            'accepted' => 'Accepté',
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }

    static function testPaimentModes($key_result = null)
    {
        $result =  [
            'espece' => 'Espèce',
            'cheque' => 'Chèque',
            'virement' => 'Virement',
            'tpe' => 'TPE',
        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }

    static function lastAction($action, $lead, $student)
    {
        return self::where(['Parent' => $lead->ID, 'action' => $action, 'Eleve' => $student->ID])->order(array('ID' => false))->first();
    }

    static function  contractStatus($key_status = null)
    {
        $status =  [
            'awaiting_process' => 'En attente du process',
            'contracts_to_be_sent' => 'Contracts à envoyer',
            'contracts_sent' => 'Contracts envoyés',
        ];

        if ($key_status) {
            return $status[trim($key_status)];
        }

        return  $status;
    }

    static function  contractColors($key_result = null)
    {
        $result =  [
            'awaiting_process' => 'l-neutre',
            'contracts_to_be_sent' => 'l-danger',
            'contracts_sent' => 'l-success',
            'signed_online_only' => 'l-success',
            'signed_paper_only' => 'l-success',
            'signed_online_offline' => 'l-success',
        ];

        if ($key_result) {
            return isset($result[trim($key_result)]) ? $result[trim($key_result)] : 'l-neutre';
        }

        return  $result;
    }

    static function  contractResultFeedback($key_result = null)
    {
        $result =  [
            'awaiting_signature' => 'En attente de signature',
            'signed_online_only' => 'Signé en ligne uniquement',
            'signed_paper_only' => 'Signé sur papier uniquement',
            'signed_online_offline' => 'Signé en ligne et hors ligne',

        ];

        if ($key_result) {
            return $result[trim($key_result)];
        }

        return  $result;
    }
}
