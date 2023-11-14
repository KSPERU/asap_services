<?php

namespace App\Funciones;

use Symfony\Component\HttpFoundation\StreamedResponse;

class FuncionesCSV
{
    public function generateProgramasCsv(array $programas): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($programas) {
            // $temporada->getTemFechaTermino() ? $temporada->getTemFechaTermino()->format('Y-m-d') : '',
            $handle = fopen('php://output', 'w+');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, [
                'Programa:ID',
                'Programa:Nombre',
                'Programa:Organizacion',
                'Programa:Fecha_Creacion',
                'Programa:Fecha_Finalizacion',
                'Programa:Estado',
                'Prog_Temporada:Cantidad',
                'Temp_Taller:Cantidad',
                'Temp_Colectiva:Cantidad'
            ], ','); // Encabezados del CSV
            foreach ($programas as $programa) {
                $temporadaCount = count($programa->getTemporadas());
                foreach ($programa->getTemporadas() as $temporada){
                    $tallerCount = count($temporada->getTallers());
                    $colectivaCount = count($temporada->getColectivas());
                        $data = [
                            $programa->getId() ? $programa->getId() : '',
                            $programa->getPgNombre() ? $programa->getPgNombre(): '',
                            $programa->getPgOrganizacion() ? $programa->getPgOrganizacion() : '',
                            $programa->getPgFechaCreacion() ? $programa->getPgFechaCreacion()->format('Y-m-d') : '',
                            $programa->getPgFechaFinalizacion() ? $programa->getPgFechaFinalizacion()->format('Y-m-d') : '',
                            $programa->getPgEstado() ? $programa->getPgEstado() : '',
                            $temporadaCount ? $temporadaCount : '0',
                            $tallerCount ? $tallerCount : '0',
                            $colectivaCount ? $colectivaCount : '0',
                        ];

                        fputcsv($handle, $data, ','); 
                    }     
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="programas.csv"');

        return $response;
    }
}