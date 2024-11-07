<?php

return [
    'coverages' => [
        [
            'name' => 'Individual',
            'slug' => 'IC',
        ],
        [
            'name' => 'Mancomunado',
            'slug' => 'MC',
        ],
        [
            'name' => 'Banca Comunal',
            'slug' => 'BC',
        ],
        [
            'name' => 'Codeudor',
            'slug' => 'CO',
        ],
    ],

    'user_types' => [
        'ADT' => 'Administrador',
        'UST' => 'Usuario',
        'OPT' => 'Operador',
    ],

    'user_positions' => [
        'CSP' => 'Cajero',      // Cashier
    ],

    'client_types' => [
        'N' => 'Natural',
        'L' => 'Jurídico',
    ],

    'client_document_types' => [
        'CI'  => 'Carnet de Identidad',
        'PE'  => 'Persona Extranjera',
        'PA'  => 'Pasaporte',
        'RUN' => 'Registro Único Nacional',
    ],

    'client_civil_status' => [
        'S' => 'Soltero(a)',
        'C' => 'Casado(a)',
        'D' => 'Divorciado(a)',
        'V' => 'Viudo(a)',
        'F' => 'Unión Libre',
    ],

    'civil_status_fortaleza_company' => [
        'S' => 'S',
        'C' => 'C',
        'D' => 'D',
        'V' => 'V',
        'F' => 'O',
    ],

    'code_extension_fortaleza_company' => [
        'BE' => 11,
        'CB' => 12,
        'CH' => 13,
        'LP' => 14,
        'OR' => 15,
        'PA' => 16,
        'PO' => 17,
        'SC' => 18,
        'TJ' => 19,
        'SE' => 20,
    ],

    'civil_status_code_fortaleza' => [
        1 => 'S',
        2 => 'C',
        3 => 'V',
        4 => 'D',
        5 => 'F',
    ],

    'client_genders' => [
        'M' => 'Masculino',
        'F' => 'Femenino',
    ],

    'client_hands' => [
        'R' => 'Derecha',
        'L' => 'Izquierda',
    ],

    'product_types' => [
        'PH' => 'Ramo Humano',
        'PG' => 'Ramo General',
    ],

    'retailer_product_types' => [
        'MP' => 'Producto Principal',
        'SP' => 'Sub Producto',
        'RP' => 'Producto Alternativo',
    ],

    'currencies' => [
        'BS'  => 'Bolivianos',
        'USD' => 'Dolares',
    ],

    'currencies_ws' => [
        1  => 'BS',
        2 => 'USD',
        3 => 'UFV',
    ],

    'product_parameters' => [
        'GE' => 'General',
        'FC' => 'Free Cover',
        'AE' => 'Afiliación Automática',
        'FA' => 'Facultativo',
    ],

    'header_types' => [
        'Q' => 'Cotización',
        'I' => 'Emisión',
    ],

    'term_types' => [
        'Y' => 'Años',
        'M' => 'Meses',
        'W' => 'Semanas',
        'D' => 'Días',
    ],

    'headlines' => [
        'D' => 'Deudor',
        'C' => 'Codeudor',
    ],

    'beneficiary_coverages' => [
        'AP' => 'Accidentes Personales',
        'VI' => 'Vida',
        'SP' => 'Sepelio',
        'CO' => 'Contingente',
    ],

    'facultative_states' => [
        'PR' => 'Procesado',
        'PE' => 'Pendiente',
    ],

    'avenue_street' => [
        'A' => 'Avenida',
        'S' => 'Calle',
    ],

    'payment_methods' => [
        'CO'  => 'Al Contado',
        'DA'  => 'Débito Automático',
        'PT'  => 'Prima Total',
        'AN'  => 'Anualizado',
        'elp' => [
            'CO' => 'Anual',
            'DA' => 'Mensual',
        ],
    ],

    'account_types' => [
        'CA' => 'Cuenta Corriente',
        'SA' => 'Caja de Ahorro',
        'AC' => 'Mancomuna Alterna',
        'TO' => 'Mancomuna Conjunta',
        'IC' => 'Individual',
    ],

    'account_number_types' => [
        'DC' => 'Tarjeta de Débito',
        'CC' => 'Tarjeta de Crédito',
        //'AC' => 'Cuenta',
    ],

    'periods' => [
        'Y' => 'Anual',
        'M' => 'Mensual',
    ],

    'profiles' => [
        'SUP' => 'Supervisor',   // Reportes - Emision
        'REP' => 'Reportes',     // Nacional - Sucursal - Agencia
        'SEP' => 'Vendedor',     // Cotizacion - Emision - Reportes
        'COP' => 'Compañia',     // Procesar casos - Rep. Producto
        'TEP' => 'Pruebas',      // Supervisor
    ],

    'states' => [
        'cl' => 'Aclaraciones',
        'me' => 'Exámenes Médicos y/o Requisitos',
        'de' => 'Error en Datos',
        're' => 'Reaseguro',
        'in' => 'Inspección',
        'sm' => 'Medidas de Seguridad',
        'lp' => [
            'pe' => 'Pendiente',    // pending
            'np' => 'Impago',       // non-payment
            'va' => 'Vigente',      // valid
            'ca' => 'Cancelado',    // canceled
        ],
    ],

    'company_state' => [
        'A' => 'Aprobado',
        'R' => 'Rechazado',
        'P' => 'Pendiente',
        'O' => 'Observado',
        'C' => 'Subsanado/Pendiente',
    ],

    'company_state_icon' => [
        'A' => 'glyphicon-ok',
        'R' => 'glyphicon-remove',
        'P' => 'glyphicon-time',
        'O' => 'glyphicon-repeat',
        'C' => 'glyphicon-refresh',
    ],

    'medical_certificate_types' => [
        'E' => 'Editor',
        'C' => 'Cuestionario',
    ],

    'mc_question_types' => [
        'CB' => 'Checkbox',
        'RB' => 'Radio Button',
        'TX' => 'Texto',
        'TA' => 'Area Texto',
    ],

    'module_class' => [
        0 => 'col-md-4',
        1 => 'col-md-12',
        2 => 'col-md-6',
    ],

    'permissions' => [
        'RN'   => 'Reporte Nacional',
        'RR'   => 'Reporte Sucursal/Regional',
        'RA'   => 'Reporte Agencia',
        'RU'   => 'Reporte Usuario',
        'APL'  => 'Administración de Polizas',
        'ACV'  => 'Administración de Coberturas',
        'ART'  => 'Administración de Tasas',
        'AOC'  => 'Administración de Ocupaciones',
        'AQS'  => 'Administración de Preguntas',
        'AMC'  => 'Administración de Certificado Médico',
        'AER'  => 'Administración de Tipo de Cambio',
        'AST'  => 'Administración de Estados',
        'AFR'  => 'Administración de Formularios',
        'AEM'  => 'Administración de Correos Electronicos',
        'ACA'  => 'Administración de Departamento/Agencia',
        'AUS'  => 'Administración de Usuarios',
        'ADE'  => 'Administración de Desgravamen',
        'AVI'  => 'Administración de Vida',
        'AAU'  => 'Administración de Automotores',
        'EDPO' => 'Edición de Pólizas',
        'CA'   => 'Anulaciones',
        'CADE' => 'Anulaciones Desgravamen',
        'FAU'  => 'Facultativo AU',
        'FTD'  => 'Facultativo TD',
        'TRU'  => 'Fideicomisos',
        'RCA'  => 'Reporte Anulaciones',
        'RCR'  => 'Reporte Credinform',
        'RSS'  => 'Reporte Sudamericana',
        'RPR'  => 'Reporte Provida',
    ],

    'inbox' => [ 'all', 'approved', 'observed', 'rejected', ],

    'vehicle_category' => [
        'CA' => 'A',
        'CB' => 'B',
        'CC' => 'C',
        'RC' => 'Rent A Car',
        'OT' => 'Otros',
    ],

    'vehicle_use' => [
        'PU' => 'Público',
        'PR' => 'Privado',
    ],

    'property_types' => [
        'PR' => 'Inmueble',
        'EE' => 'Equipo Electrónico',
        'MC' => 'Maquinaria',
        'ME' => 'Equipo Móvil',
    ],

    'property_uses' => [
        'ID' => 'Inmueble Domiciliario',
        'IP' => 'Inmueble Industrial',
        'IC' => 'Inmueble Comercial',
        'OT' => 'Otro',
    ],

    'property_states' => [
        'F' => 'Terminado',
        'C' => 'En construcción',
        'P' => 'En proceso de remodelación, ampliación o refacción',
    ],

    'sp_modalities' => [
        'MS' => 'Modalidad Valor Estático',
        'MV' => 'Modalidad Valor Asegurado',
    ],

    'credit_products' => [
        'PCS' => 'Consumo',
        'PCM' => 'Comercial',
        'PMO' => 'Hipotecario',
        'PCA' => 'Tarjetas',
        'POT' => 'Otros',
        '7x5' => '7x5',
        'PLC' => 'Línea de Crédito',
        'PAD' => 'Adicional',
    ],

    'movement_types' => [
        'FS' => 'Primera/Única',
        'AD' => 'Adicional',
        'LC' => 'Línea de Crédito',
    ],

    'question_types' => [
        'PMO' => 'Hipotecario',
    ],

    'policy_types' => [
        'DE' => 'Desgravamen',
        'VI' => 'Vida Grupo',
        'EL' => 'Ecovidda',
        'EP' => 'Ecoprotección',
    ],

    'certificate_types' => [
        'slip',
        'certificate',
    ],

    'commission_types' => [
        'daily'    => 'Diario',
        'monthly'  => 'Mensual',
        'campaign' => 'Campaña',
    ],

    'client_coverages' => [
        'CD'   => 'Muerte',
        'CTPD' => 'Invalidez Total Permanente',
        'CF'   => 'Sepelio',
    ],

    'steps' => [
        'de' => [
            1 => 'Datos del Prestamo',
            2 => 'Datos del Cliente',
            3 => 'Resultado Cotización',
            4 => 'Emisión de la Póliza de Desgravamen',
            5 => 'Impresión de la Póliza',
        ],
        'am' => [
            1 => 'Datos del Cliente',
            2 => 'Resultado Cotización',
            3 => 'Emisión de la Póliza',
            4 => 'Impresión de la Póliza',
        ],
        'ce' => [
            1 => 'Datos del Cliente',
            2 => 'Resultado Cotización',
            3 => 'Emisión de la Póliza',
            4 => 'Impresión de la Póliza',
        ],
        'tj' => [
            //1 => 'Datos del Prestamo',
            1 => 'Datos del Cliente',
            2 => 'Resultado Cotización',
            3 => 'Emisión de la Póliza',
            4 => 'Impresión de la Póliza',
        ],
    ],

    'credit_line' => [
        'coverages' => [
            'IC' => 'Individual',
            'MC' => 'Mancomunado',
            'UC' => 'Sin Seguro',
        ],
        'policy_type' => [
            'TE' => 'Licitado',
            'TN' => 'No Licitado',
            'CA' => 'Tarjetas',
        ]
    ],

	'fideicomisos' => [
        0  => 'No',
        1  => 'Si',
    ],

	'payment_type' => [
        'VC'  => 'Valor Comercial',
        'SI'  => 'Saldo Insoluto',
    ],
    
    'capital' => [
        'C' => 'Constante',
        'D' => 'Derececiente',
    ],
    
    'tomador' => [
        'SC' => [
            'tomador'=>'BANCO FORTALEZA SA - SANTA CRUZ',
            'address'=>'CALLE RENE MORENO NO140 ZONA CENTRAL',
        ],
        'LP' => [
            'tomador'=>'BANCO FORTALEZA SA - LA PAZ',
            'address'=>'AV 16 DE JULIO NO1440 PLAZA VENEZUELA ZONA CENTRAL',
        ],
        'EAT' => [
            'tomador'=>'BANCO FORTALEZA SA - EL ALTO',
            'address'=>'C/ JORGE CARRASCO NO79 ENTRE C 4 Y 5 ZONA 12 DE OCT NO. 140 ',
        ],
        'CB' => [
            'tomador'=>'BANCO FORTALEZA SA - COCHABAMBA',
            'address'=>'PLAZA 14 DE SEPTIEMBRE ESQUINA CALLE BAPTISTA',
        ],
        'TJ' => [
            'tomador'=>'BANCO FORTALEZA SA - TARIJA',
            'address'=>'C/LA MADRID NO 330 FRENTE A LA PLAZA LUIS DE FUENTES NO. 330',
        ],
        'CH' => [
            'tomador'=>'BANCO FORTALEZA SA - SUCRE',
            'address'=>'CALLE SAN ALBERTO NRO. 108, ZONA CENTRAL',
        ],
        'OR' => [
            'tomador'=>'BANCO FORTALEZA SA - ORURO',
            'address'=>'CALLE LA PLATA NRO. 5955. ZONA CENTRAL',
        ],
    ],
    
    'relationship' => [
        'padre'=>'Padre',
        'madre'=>'Madre',
        'tio'=>'Tio(a)',
        'otros'=>'Otros',
        'hijo'=>'Hijo(a)',
        'hermano'=>'Hermano(a)',
        'conyuge'=>'Cónyuge',
        'sobrino'=>'Sobrino(a)',
        'nieto'=>'Nieto(a)',
        'abuelo'=>'Abuelo(a)',
        'amigo'=>'Amigo(a)',
        'primo'=>'Primo(a)',
        'concubino'=>'Concubino(a)',
        'heredero'=>'Heredero legal',
    ],
    
    'bin_cards_credit' => [
        1=>'531920-XXXXXX',
    ],
	'cod_city_company_card' => [
		'SC'=> 'SC0101',
		'LP'=> 'LP0201',
		'TJ'=> 'TJ0301',
		'OR'=> 'OR0401',
		'CB'=> 'CB0501',
		'CH'=> 'SU0601',
	    ],

	'types_of_requests' => [
		'N-OPE' => 'Nueva Operación',
		'REF'   => 'Refinanciamiento',
		'REP'   => 'Reprogramación',
		//'R-EXC' => 'Regularización de Exclusión',
	    ],
    
        'type_of_contract' => [
                    'Y'     => 'Anual',
                    'INF'   => 'Indefinido',
                    'PF'   => 'Plazo Fijo',
                ],

        'yes_no' => [
                1   => 'SI',
                0   => 'NO',
            ],

    'observations_message' => [
            'accidental_death' => 'Se rechaza la cobertura de Muerte Prematura del Asegurado e Invalidez Total y Permanente, se otorga únicamente la cobertura de Muerte Accidental.',
        ],

    'setting' => [
        'emailTo' => ['EdwinM@tubroker.com'=>'Edwin Manuel'],
        'emailCc' => [],
        'api' => [
            'file_upload' => ['xlsx','txt']
        ],
    ],
];
