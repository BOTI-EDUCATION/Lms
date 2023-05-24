<?php
return [
    [
        'alias' => 'autorisation',
        'label' => 'Autorisation',
        'params' => [
            'can_role_resp_pedago_view_ins_payement' => [
                'alias' => 'can_role_resp_pedago_view_ins_payement',
                'label'         => "Afficher l'état de paiement d'un élève au responsable pédagogique",
                'type'        => 'checkbox',
                'category'      => 'autorisation',
                'presentation'  => "Afficher l'état de paiement d'un élève au responsable pédagogique",
                'icon'          => null,
                'image'         => null,
                'default_value' => false,
            ],
            'can_manually_edit_amount_encaissment_money' => [
                'alias' => 'can_manually_edit_amount_encaissment_money',
                'label'         => "Afficher l'état de paiement d'un élève au responsable pédagogique",
                'type'        => 'checkbox',
                'category'      => 'autorisation',
                'presentation'  => "L'agent financier peut saisir le montant du service à payer. Si désactivé",
                'icon'          => null,
                'image'         => null,
                'default_value' => false,
            ],
            'can_manually_edit_amount_encaissment_money_222' => [
                'alias' => 'can_manually_edit_amount_encaissment_money_222',
                'label'         => "Afficher l'état de paiement d'un élève au responsable pédagogique",
                'type'        => 'text',
                'category'      => 'autorisation',
                'presentation'  => "L'agent financier peut saisir le montant du service à payer. Si désactivé",
                'icon'          => null,
                'image'         => null,
                'default_value' => false,
            ],
        ]
    ],

];
