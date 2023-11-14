<?php

namespace App\servicios;

use Symfony\Component\HttpFoundation\StreamedResponse;

class GenerarCSV
{
    public function generateHistorialServiciosCsv(array $historialServicios): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($historialServicios) {
            $handle = fopen('php://output', 'w+');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, [
                'ID',
                'Fecha',
                'Importe',
                'Servicio',
                'Cliente',
                'Estado_Servicio',
                'Estado_pago',
                'Estado_cobro',
                // Agrega más columnas según sea necesario
            ], ','); // Encabezados del CSV

            foreach ($historialServicios as $historialServicio) {
                $estadoServicio = $historialServicio->isHsEstado() ? 'Completado' : 'Pendiente';
                $estadoPago = $historialServicio->isHsEstadopago() ? 'Pagado' : 'Pendiente';
                $estadoCobro = $historialServicio->isHsEstadocobro() ? 'Cobrado' : 'Pendiente';
                $data = [
                    $historialServicio->getId() ? $historialServicio->getId() : '',
                    $historialServicio->getHsFecha() ? $historialServicio->getHsFecha()->format('Y-m-d') : '',
                    $historialServicio->getHSImporte() ? 'S/.' .$historialServicio->getHsImporte() : '',
                    $historialServicio->getIdservicio()->getSvNombre() ? $historialServicio->getIdservicio()->getSvNombre() : '',
                    $historialServicio->getIdcliente()->getPnombre().' '.$historialServicio->getIdcliente()->getPapellido()? $historialServicio->getIdcliente()->getPnombre().' '.$historialServicio->getIdcliente()->getPapellido() : '',
                    $estadoServicio,
                    $estadoPago,
                    $estadoCobro,
                    // Agrega más datos según sea necesario
                ];

                fputcsv($handle, $data, ','); 
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="historial_servicios.csv"');

        return $response;
    }
}