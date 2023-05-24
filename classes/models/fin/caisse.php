<?php

namespace Models\FIN;

use \Models\Model;
use Session;

class Caisse extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'fnc_caisses';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(
        'User' => array(
            'fk' => 'User',
        ),
        'CaissePassation' => array(
            'type' => 'text',
        ),
        'Label' => array(
            'type' => 'varchar',
        ),
        'Icon' => array(
            'type' => 'varchar',
        ),
        'Banque' => array(
            'type' => 'varchar',
        ),
        'Agence' => array(
            'type' => 'varchar',
        ),
        'Rib' => array(
            'type' => 'varchar',
        ),
        'CanPayDepenses' => array(
            'type' => 'varchar',
        ),
        'Solde' => array(
            'type' => 'double',
        ),
        'ModeSoldes' => array(
            'type' => 'text',
        ),
        'SoldeInstance' => array(
            'type' => 'double',
        ),
        'ForceHistory' => array(
            'type' => 'text',
        ),
        'EditHistory' => array(
            'type' => 'text',
        ),
        'CreatedAt' => array(
            'type' => 'date',
        ),
        'CreatedBy' => array(
            'fk' => 'User',
        ),
    );

    public function beforeSave()
    {
        $history = $this->getArray('EditHistory') ?: array();
        $user = \Session::getInstance()->getCurUser();
        if ($user) {
            $history[] = array(
                'user' => \Session::getInstance()->getCurUser()->ID,
                'action' => $this->saved ? 'update' : 'add',
                'date' => date('Y-m-d H:i:s'),
            );
        }
        $this->setJson('EditHistory', $history);
    }

    public static function getCaisseOfEncaissement($encaissement)
    {

        if ($encaissement->PaiementMode == 'espece') {

            return \Models\FIN\Caisse::where(['User' => $encaissement->UserBy->getKey()])->first();
        }

        return  \Models\FIN\Caisse::where(['Banque IS NOT NULL'])->first();
    }

    public static function banqueCaisses()
    {
        return \Models\FIN\Caisse::where('Banque IS NOT NULL')->get();
    }

    public static function userCaisses()
    {
        return \Models\FIN\Caisse::where('Banque IS  NULL')->get();
    }

    public static function caisseOf($user)
    {
        return \Models\FIN\Caisse::where(['User' => $user->getKey()])->first();
    }

    public function isOf($user)
    {
        return $this->User && $this->User->getKey() == $user->getKey();
    }

    public function canDoMovementBy($user)
    {
        return $this->Solde > 0 && (($this->isOf($user)) || ($this->Banque && in_array($user->Role->getKey(), ['admin', 'resp_financier'])));
    }

    public function addEncaissements($encaissements, $add_to_solde = true)
    {
        $total_montant = 0;
        foreach ($encaissements as $fnc) {
            if ($fnc->Canceled) {
                continue;
            }
            $fnc->set('Caisse', $this)->saveCaisseHistory($this)->save();
            if ($add_to_solde) {
                $this->addModeSolde($fnc->PaiementMode, $fnc->Montant)->save();
            }
            $total_montant += $fnc->Montant;
        }

        if ($add_to_solde) {
            $this->addition($total_montant);
        }

        return $total_montant;
    }

    public function removeEncaissements($encaissements, $sous_from_solde  = true)
    {
        $total_montant = 0;
        foreach ($encaissements as $fnc) {

            if (!$fnc->Caisse || $fnc->Caisse->getKey() != $this->getKey()) {
                continue;
            }

            $fnc->set('Caisse', null)->save();
            if ($sous_from_solde) {
                $this->soustractModeSolde($fnc->PaiementMode, $fnc->Montant)->save();
            }
            $total_montant += $fnc->Montant;
        }
        if ($sous_from_solde) {
            $this->soustraction($total_montant);
        }

        return  $total_montant;
    }

    public function addition($mantant)
    {
        $this->set('Solde', $this->Solde + $mantant)->save();

        return $this->Solde;
    }

    public function additionSoldeInstance($mantant)
    {
        $this->set('SoldeInstance', $this->SoldeInstance + $mantant)->save();

        return $this->SoldeInstance;
    }

    public function soustraction($mantant)
    {
        $this->set('Solde', $this->Solde - $mantant)->save();
        return  $this->Solde;
    }

    public function soustractionSoldeInstance($mantant)
    {
        $this->set('SoldeInstance', $this->SoldeInstance - $mantant)->save();
        return  $this->SoldeInstance;
    }

    public function canDoMovement()
    {
        return \Models\FIN\Encaissement::where(['Caisse' => $this->getKey()])->count() == 0;
    }

    public function getModeSolde($mode)
    {
        $soldesMode =  $this->getArray('ModeSoldes', false, true);

        $solde = 0;
        if (isset($soldesMode[$mode])) {
            $solde = $soldesMode[$mode];
        }

        return $solde;
    }

    public function addModeSolde($mode, $amount)
    {
        $soldesMode =  $this->getArray('ModeSoldes', false, true);

        $solde = 0;
        if (isset($soldesMode[$mode])) {
            $solde = $soldesMode[$mode];
        }
        $solde += $amount;
        $soldesMode[$mode] = $solde;

        $this->setJson('ModeSoldes', $soldesMode);
        $this->save();
        return $this;
    }

    public function soustractModeSolde($mode, $amount)
    {
        $soldesMode =  $this->getArray('ModeSoldes', false, true);

        $solde = 0;
        if (isset($soldesMode[$mode])) {
            $solde = $soldesMode[$mode];
        }
        $solde -= $amount;
        $soldesMode[$mode] = $solde;
        $this->setJson('ModeSoldes', $soldesMode);
        $this->save();
        return $this;
    }
}
