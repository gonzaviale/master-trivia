<?php
namespace helper;

require_once 'vendor/autoload.php';

use mitoteam\jpgraph\MtJpGraph;
use Graph;
use BarPlot;
use PieGraph;
use PiePlot;

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
        $graph->yaxis->scale->SetAutoMin(0);
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

    public function renderPieChartView($labels, $values, $filePath)
    {
        // Cargar la librería de JpGraph necesaria para el tipo de gráfico (en este caso, pie)
        MtJpGraph::load(['pie']);

        // Crear el objeto de gráfico con dimensiones específicas
        $graph = new PieGraph(750, 600);

        // Configuraciones del título
        $graph->title->Set('Datos Generales');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 16);

        // Verificar si el arreglo de valores está vacío y ajustarlo si es necesario
        if (empty($values)) {
            $values = [0];
            $labels = ['Sin datos'];
        }

        // Crear el gráfico de torta y agregarlo al gráfico principal
        $piePlot = new PiePlot($values);
        $piePlot->SetLegends($labels);
        //$piePlot->SetLabelType(PIE_VALUE_ABS); // Muestra los valores absolutos en las etiquetas
        $piePlot->value->SetFont(FF_ARIAL, FS_BOLD, 12); // Configurar la fuente para los valores
        $piePlot->value->SetColor('black'); // Color del texto de los valores
        $piePlot->SetGuideLines(true, false); // Mostrar las guías hacia las etiquetas
        $piePlot->SetGuideLinesAdjust(1.5); // Ajustar la longitud de las guías
        $graph->Add($piePlot);

        // Generar la imagen del gráfico y guardarla en el servidor
        $graph->Stroke($filePath);

        // Devolver la ruta al archivo de imagen generado
        return $filePath;
    }



}
