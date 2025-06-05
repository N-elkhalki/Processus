<?php
/**
 * Retourne le libellé d'un état d'avancement
 */
function getStatusLabel($etat) {
    $labels = [
        'entrevue' => 'Entrevue',
        'confirmation_embauche' => 'Confirmation d\'embauche',
        'type_contrat' => 'Type de contrat',
        'demande_acces' => 'Demande d\'accès',
        'reception_acces' => 'Réception des accès',
        'preparation_materiel' => 'Préparation du matériel',
        'remise_materiel' => 'Remise du matériel',
        'debut_travail' => 'Début du travail',
        'complete' => 'Processus complété'
    ];
    
    return isset($labels[$etat]) ? $labels[$etat] : 'Inconnu';
}

/**
 * Vérifie si une étape est antérieure à une autre
 */
function checkPreviousStep($currentStep, $checkStep) {
    $order = [
        'entrevue' => 1,
        'confirmation_embauche' => 2,
        'type_contrat' => 3,
        'demande_acces' => 4,
        'reception_acces' => 5,
        'preparation_materiel' => 6,
        'remise_materiel' => 7,
        'debut_travail' => 8,
        'complete' => 9
    ];
    
    if (!isset($order[$currentStep]) || !isset($order[$checkStep])) {
        return false;
    }
    
    return $order[$currentStep] >= $order[$checkStep];
}

/**
 * Retourne les prochaines étapes possibles selon l'étape actuelle
 */
function getNextStates($currentStep) {
    $steps = [
        'entrevue' => [
            'confirmation_embauche' => 'Confirmation d\'embauche'
        ],
        'confirmation_embauche' => [
            'type_contrat' => 'Type de contrat'
        ],
        'type_contrat' => [
            'demande_acces' => 'Demande d\'accès'
        ],
        'demande_acces' => [
            'reception_acces' => 'Réception des accès'
        ],
        'reception_acces' => [
            'preparation_materiel' => 'Préparation du matériel'
        ],
        'preparation_materiel' => [
            'remise_materiel' => 'Remise du matériel'
        ],
        'remise_materiel' => [
            'debut_travail' => 'Début du travail'
        ],
        'debut_travail' => [
            'complete' => 'Processus complété'
        ],
        'complete' => []
    ];
    
    return isset($steps[$currentStep]) ? $steps[$currentStep] : [];
}