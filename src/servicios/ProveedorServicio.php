<?php

namespace App\servicios;

use App\Entity\Ganancia;
use App\Entity\Histservproveedor;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class ProveedorServicio
{
    /**
     * Calcula el total de ganancias.
     *
     * @param Collection|Ganancia[] $ganancias
     * @return float
     */
    
    public function calcularTotalGanancias(Collection $ganancias): float
    {
        $totalGanancias = 0;

        foreach ($ganancias as $ganancia) {
            if ($ganancia->isgpestadotransferencia() == 0) {
                $totalGanancias += $ganancia->getGpTotal();
            }
        }

        return round($totalGanancias,2);
    }

    /**
     * Filtra los registros de ganancias sin cobrar.
     *
     * @param Collection|Histservproveedor[] $gansincobrar
     * @return ArrayCollection|Histservproveedor[]
     */
    public function filtrarGansincobrar(Collection $gansincobrar): ArrayCollection
    {
        $filteredGansincobrar = [];

        foreach ($gansincobrar as $gansincobro) {
            if ($gansincobro->isHsEstado() == 1 && $gansincobro->isHsEstadopago() == 1 && $gansincobro->isHsEstadocobro() == 0) {
                $filteredGansincobrar[] = $gansincobro;
            }
        }

        return new ArrayCollection($filteredGansincobrar);
    }

    /**
     * Calcula la ganancia real según una fórmula específica.
     *
     * @param Collection|Histservproveedor[] $gansincobrar
     * @return array ['fecha_min' => DateTime,'fecha_max' => DateTime, 'cantidad' =>int, 'gananciaTotal' => float, 'gananciaReal' => float, 'comision' => float]
     */

    public function calcularGananciaReal(Collection $gansincobrar): array
    {
        //OBTENER FECHAS LIMITES
        $cantidad = count($gansincobrar);
        $fechaMinima = null;
        $fechaMaxima = null;
        if($cantidad>0){
            $sortedGansincobrar = $gansincobrar->getValues();
            usort($sortedGansincobrar, function ($a, $b) {
                return $a->getHsFecha() <=> $b->getHsFecha();
            });
            $fechaMinima = $sortedGansincobrar[0]->getHsFecha();
            $fechaMaxima = end($sortedGansincobrar)->getHsFecha();
        }
        

        $sumaImportes = 0;
        
        foreach ($gansincobrar as $gansincobro) {
            $sumaImportes += $gansincobro->getHsImporte();
             // Ajusta el nombre del método según tu entidad
        }

        // Aplicar la formula y redondear a 2 decimales
        $comision = round(1.18 * (0.0344 * $sumaImportes) + 1.18 * 0.69, 2);
        $gananciaReal = round($sumaImportes - $comision,2);
        $sumaImportes = round($sumaImportes,2);

        return ['fecha_min' => $fechaMinima,'fecha_max' => $fechaMaxima, 'cantidad' =>$cantidad, 'gananciaTotal'=> $sumaImportes, 'gananciaReal' => $gananciaReal, 'comision' => $comision];
    }
}