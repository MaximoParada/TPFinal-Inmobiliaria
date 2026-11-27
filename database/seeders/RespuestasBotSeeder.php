<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RespuestasBotSeeder extends Seeder
{
    public function run(): void
    {
        $respuestas = [
            [
                'clave' => 'alquiler_pasos',
                'titulo' => 'Pasos para alquilar',
                'contenido' => "1) Elegí la propiedad y coordiná una visita con su código (ej: 102).\n2) Presentá la documentación y garantía.\n3) Se firma el contrato y se abonan los gastos de ingreso.\n4) ¡Recibís las llaves!",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'alquiler_requisitos',
                'titulo' => 'Requisitos para alquilar',
                'contenido' => "• DNI vigente de inquilino y garantes.\n• Recibos de sueldo o certificación de ingresos.\n• Garantía propietaria o seguro de caución.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'compra_pasos',
                'titulo' => 'Pasos para comprar',
                'contenido' => "1) Reserva de la propiedad.\n2) Firma de boleto de compraventa.\n3) Escrituración ante escribano público.\n4) Entrega de posesión y llaves.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'compra_requisitos',
                'titulo' => 'Requisitos para comprar',
                'contenido' => "• DNI o pasaporte vigente.\n• Justificación / origen de fondos.\n• Documentación personal al día para escribanía.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'compra_pagos',
                'titulo' => 'Formas de pago en compra',
                'contenido' => "• Operaciones de contado (transferencia / efectivo según acuerdo).\n• Consultas sobre financiación o crédito hipotecario con un asesor.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'contacto_info',
                'titulo' => 'Contacto y horarios',
                'contenido' => "• Ubicación: Neuquén Capital, Argentina.\n• Teléfono / WhatsApp: +54 299 555-0148.\n• Email: contacto@loncoprop.com.ar\n• Horario de atención: Lunes a viernes de 8:00 a 16:00 hs.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Si la 'clave' ya existe, actualiza título y contenido; si no existe, la inserta
        DB::table('respuestas_bot')->upsert(
            $respuestas,
            ['clave'],
            ['titulo', 'contenido', 'updated_at']
        );
    }
}