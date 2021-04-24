<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => __('Cet :attribute doit etre accepté.'),
    'active_url' => __('Cet :attribute n\'est pas un URL valide.'),
    'after' => __('Cet :attribute doit etre une date après :date.'),
    'after_or_equal' => __('Cet :attribute doit etre une date après ou egal à :date.'),
    'alpha' => __('Cet :attribute ne peut contenir que des lettres.'),
    'alpha_dash' => __('Cet :attribute ne peut contenir que des lettres, chiffres, - et _.'),
    'alpha_num' => __('Cet :attribute ne peut contenir que des lettres et des chiffres.'),
    'array' => __('Cet :attribute doit etre un tableau.'),
    'before' => __('Cet :attribute doit etre une date avant :date.'),
    'before_or_equal' => __('The :attribute must be a date before or equal to :date.'),
    'between' => [
        'numeric' => __('Cet :attribute doit etre entre :min and :max.'),
        'file' => __('Cet :attribute doit etre entre :min and :max kilooctets.'),
        'string' => __('Cet :attribute doit etre entre :min and :max caractères.'),
        'array' => __('Cet :attribute doit etre entre :min et :max elements.'),
    ],
    'boolean' => __('Cet :attribute doit etre vrai ou faux.'),
    'confirmed' => __('Cet :attribute de confirmation ne correspond pas.'),
    'date' => __('Cet :attribute n\'est pas une date valide.'),
    'date_equals' => __('Cet :attribute doit etre une date égale à :date.'),
    'date_format' => __('Cet :attribute doit etre au format :format.'),
    'different' => __('Cet :attribute et :other doivent etre different.'),
    'digits' => __('Cet :attribute doit etre :digits chiffres.'),
    'digits_between' => __('Cet :attribute doit etre entre :min and :max chiffres.'),
    'dimensions' => __('Cet :attribute has invalid image dimensions.'),
    'distinct' => __('Ce champ :attribute a une valeur dupliquée.'),
    'email' => __('Cet :attribute doit etre une adresse email valide.'),
    'exists' => __('Ce selected :attribute est invalide.'),
    'file' => __('Cet :attribute doit etre un fichier.'),
    'filled' => __('Cet :attribute doit avoir une valeur.'),
    'gt' => [
        'numeric' => __('Cet :attribute doit etre supérieur à :value.'),
        'file' => __('Cet :attribute doit etre supérieur à :value kilooctets.'),
        'string' => __('Cet :attribute doit etre supérieur à :value caractères.'),
        'array' => __('Cet :attribute doit avoir plus de :value elements.'),
    ],
    'gte' => [
        'numeric' => __('Cet :attribute doit etre superieur ou égal  à :value.'),
        'file' => __('Cet :attribute doit etre superieur ou égal  à :value kilobytes.'),
        'string' => __('Cet :attribute doit etre superieur ou égal  à :value characters.'),
        'array' => __('Cet :attribute doit voir :value elements ou plus.'),
    ],
    'image' => __('Cet :attribute must be an image.'),
    'in' => __('Cet :attribute selectionné est invalide.'),
    'in_array' => __('Ce champ :attribute n\'existe pas dans :other.'),
    'integer' => __('Cet :attribute doit etre un entier.'),
    'ip' => __('Cet :attribute doit etre une adresse IP valide.'),
    'ipv4' => __('Cet :attribute doit etre une adresse IPv4 valide.'),
    'ipv6' => __('Cet :attribute doit etre une adresse IPv6 valide.'),
    'json' => __('Cet :attribute doit etre un JSON valide.'),
    'lt' => [
        'numeric' => __('Cet :attribute doit etre inferieur à :value.'),
        'file' => __('Cet :attribute doit etre inferieur à :value kilooctets.'),
        'string' => __('Cet :attribute doit etre inferieur à :value caractères.'),
        'array' => __('Cet :attribute doit avoir moins de :value elements.'),
    ],
    'lte' => [
        'numeric' => __('Cet :attribute doit etre inferieur ou égal à :value.'),
        'file' => __('Cet :attribute doit etre inferieur ou égal à :value kilooctets.'),
        'string' => __('Cet :attribute doit etre inferieur ou égal à :value caractères.'),
        'array' => __('Cet :attribute ne doit pas avoir plus de :value elements.'),
    ],
    'max' => [
        'numeric' => __('Cet :attribute peut ne pas etre supérieur  à :max.'),
        'file' => __('Cet :attribute peut ne pas etre supérieur  à :max kilooctets.'),
        'string' => __('Cet :attribute peut ne pas etre supérieur  à :max caractères.'),
        'array' => __('Cet :attribute peut ne pas avoir :max elements.'),
    ],
    'mimes' => __('Cet :attribute doit etre un fichier de type: :values.'),
    'mimetypes' => __('Cet :attribute doit etre des fichiers de type: :values.'),
    'min' => [
        'numeric' => __('Cet :attribute doit etre au minimum :min.'),
        'file' => __('Cet :attribute doit etre au minimum :min kilooctets.'),
        'string' => __('Cet :attribute doit avoir au minimum :min caractères.'),
        'array' => __('Cet :attribute doit avoir au minimum :min. elements.'),
    ],
    'not_in' => __('Cet :attribute selectionné est invalide.'),
    'not_regex' => __('Ce format d\':attribute est invalide.'),
    'numeric' => __('Cet :attribute doit etre un nombre.'),
    'present' => __('Ce champ d\':attribute doit etre present.'),
    'regex' => __('Ce format d\':attribute est invalide.'),
    'required' => __('Ce champ d\':attribute est requis.'),
    'required_if' => __('Ce champ d\':attribute est requis quand :other est :value.'),
    'required_unless' => __('Ce champ d\':attribute est requis sauf si :other est dans :values.'),
    'required_with' => __('Ce champ d\':attribute est requis quand :values est present.'),
    'required_with_all' => __('Ce champ d\':attribute field is required when :values are present.'),
    'required_without' => __('Ce champ d\':attribute est requis quand :values n\'est pas present.'),
    'required_without_all' => __('Ce champ d\':attribute est requis quand nul de :values est present.'),
    'same' => __('Cet :attribute et :other doivent correspondre.'),
    'size' => [
        'numeric' => __('Cet :attribute doit avoir :size.'),
        'file' => __('Cet :attribute doit avoir :size kilooctets.'),
        'string' => __('Cet :attribute doit avoir :size caractères.'),
        'array' => __('Cet :attribute doit contenir :size elements.'),
    ],
    'starts_with' => __('Cet :attribute doit commencer par une des valeurs suivants: :values'),
    'string' => __('Cet :attribute doit etre une chaine de caractères.'),
    'timezone' => __('Cet :attribute doit etre une zone.'),
    'unique' => __('Cet :attribute a deja été pris.'),
    'uploaded' => __('Cet :attribute n\'a pas pu etre télécharger.'),
    'url' => __('le format de :attribute est invalide.'),
    'uuid' => __('Cet :attribute doit etre un UUID valide.'),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => __('custom-message'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
