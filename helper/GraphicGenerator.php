<?php
namespace helper;

require_once 'vendor/autoload.php';

use mitoteam\jpgraph\MtJpGraph;
use Graph;
use BarPlot;

class GraphicGenerator {



    public function renderChartView($labels, $values, $filePath)
    {
        // Cargar la librería de JpGraph necesaria para el tipo de gráfico (en este caso, barra)
        MtJpGraph::load(['bar']);

        // Crear el objeto de gráfico con dimensiones específicas
        $graph = new Graph(750, 600);

        // Establecer la escala del gráfico
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

        // Verificar si el arreglo de valores está vacío y ajustarlo si es necesario
        if (empty($values)) {
            $values = [0];
        }

        // Calcular los valores mínimo y máximo para ajustar la escala si son iguales
        $minValue = min($values);
        $maxValue = max($values);

        if ($minValue == $maxValue) {
            $minValue -= 1;
            $maxValue += 1;
        }

        // Configurar la escala automática para el eje y
        $graph->yaxis->scale->SetAutoMin(0.5);
        $graph->yaxis->scale->SetAutoMax($maxValue);

        // Crear el gráfico de barras y agregarlo al gráfico principal
        $barplot = new BarPlot($values);
        $barplot->value->Show();
        $barplot->value->SetFont(FF_ARIAL, FS_BOLD, 12); // Configurar la fuente para los valores
        $graph->Add($barplot);

        // Generar la imagen del gráfico y guardarla en el servidor
        $graph->Stroke($filePath);

        // Devolver la ruta al archivo de imagen generado
        return $filePath;
    }




}
