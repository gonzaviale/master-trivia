<?php
namespace helper;

require_once 'vendor/autoload.php'; // Asegúrate de cargar el autoload de Composer

use mitoteam\jpgraph\MtJpGraph;
use Graph;
use BarPlot;

class GraphicGenerator {

    public function renderChartView($labels, $values, $filePath)
    {
        MtJpGraph::load(['bar']);

        $graph = new Graph(750, 600);
        $graph->SetScale('textlin');

        // Configuraciones del título y ejes
        $graph->title->Set('Datos Generales');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 16);
        $graph->xaxis->title->Set('Categoría');
        $graph->xaxis->title->SetFont(FF_ARIAL, FS_BOLD, 10);
        $graph->xaxis->SetTickLabels($labels);
        $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 12);
        $graph->yaxis->title->Set('Cantidad');
        $graph->yaxis->title->SetFont(FF_ARIAL, FS_BOLD, 10);
        $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 12);

        if (!defined('IMG_PNG')) {
            define('IMG_PNG', true);
        }

        // Verificar si $values está vacío y manejar el caso
        if (empty($values)) {
            $values = [0]; // Asignar un valor predeterminado, por ejemplo, 0
        }

        // Obtener los valores mínimos y máximos de $values
        $minValue = min($values);
        $maxValue = max($values);

        // Asegurarnos de que el valor mínimo y máximo son válidos
        if ($minValue == $maxValue) {
            $minValue -= 1;
            $maxValue += 1;
        }

        // Configurar la escala del eje Y
        $graph->yaxis->scale->SetAutoMin(0.5);
        $graph->yaxis->scale->SetAutoMax($maxValue);

        // Crear el gráfico de barras
        $barplot = new BarPlot($values);
        $barplot->value->Show();
        $barplot->value->SetFont(FF_ARIAL, FS_BOLD, 12); // Fuente Arial, tamaño 12, negrita
        $graph->Add($barplot);

        // Generar la imagen del gráfico y guardarla en el archivo especificado
        $graph->Stroke($filePath);
    }

}
