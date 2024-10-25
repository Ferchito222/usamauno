<?php

function menuApp () {
    return [
        'adm' => [
            'home'  => 'adm::',
            'items' => [
                [
                    'title'    => 'Usuarios',
                    'subMenus' => collect([
                        [
                            'title'      => 'Administración de Usuarios',
                            'route'      => 'adm::user.list',
                            'permission' => ['AUS'],
                        ],
                        [
                            'title'      => 'Correos Electrónicos',
                            'route'      => 'adm::email.list',
                            'permission' => ['AEM'],
                        ],
                    ]),
                ],
                [
                    'title'    => 'Operaciones',
                    'subMenus' => collect([
                        [
                            'title'      => 'Anulación de Certificados',
                            'route'      => 'adm::operation.cancel',
                            'permission' => ['ACADE'],
                        ],
                        [
                            'title'      => 'Certificados Adjuntos (Desgravamen)',
                            'route'      => 'adm::operation.attachedCertificate',
                            'permission' => ['DEATR'],
                        ],
                        [
                            'title'      => 'Edición de Certificados',
                            'route'      => 'adm::operation.policyEdit',
                            'permission' => ['EDPO'],
                        ],
                        [
                            'title'      => 'Asignación de cartera',
                            'route'      => 'adm::operation.assignment.wallet.create',
                            'permission' => ['AWA'],
                        ],
                    ]),
                ],
                [
                    'title'    => 'Configuración',
                    'subMenus' => collect([
                        [
                            'title'      => 'Certificado Médico',
                            'route'      => 'adm::mc.list',
                            'permission' => ['AMC'],
                        ],
                        [
                            'title'      => 'Ajustes del Sistema',
                            'route'      => 'adm::setting.list',
                            'permission' => ['ASET'],
                        ],
                    ]),
                ],
            ],
        ],
        'user' => [

        ],
        'currentRoute' => request()->route()->getName(),
        'userPermissions' => auth()->user()->permissions->pluck('slug')->toArray(),
    ];
}