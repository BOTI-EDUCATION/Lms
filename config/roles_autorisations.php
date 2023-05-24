<?php
// 'admin','direction','resp_pedago','surveillant','assistant','caissier','resp_financier','controlleur_financier','assistant_rh','resp_rh','assistant_communication','resp_communication','resp_transport','assistant_transport','assistant_sortie','chauffeur','assistant_chauffeur','encdrant'
return [
    'receive_inscriptions_email_notifications' => [
        'alias' => 'receive_inscriptions_email_notifications',
        'label' => 'recevoir des notifications d\'inscription par e-mail',
        'order' => 1,
        'description' => 1,
        'roles' => ['admin', 'direction', 'resp_pedago', 'surveillant', 'assistant']
    ],
    'receive_reinscriptions_email_notifications' => [
        'alias' => 'receive_reinscriptions_email_notifications',
        'label' => 'Recevoir des notifications de réinscription par e-mail',
        'order' => 1,
        'description' => 1,
        'roles' => ['admin', 'direction', 'resp_pedago', 'surveillant', 'assistant']
    ],
    'apply_discount_price' => [
        'alias' => 'apply_discount_price',
        'label' => 'Appliquer des remises de tarifs',
        'order' => 1,
        'description' => 1,
        'roles' => ['admin', 'direction', 'resp_pedago', 'surveillant', 'assistant']
    ],

];
