<?php

require_once "config/Conexion.php";
require_once "Modelo/Colaborador.php";
require_once "Utilidades/Sanitizacion.php";
require_once "Utilidades/Validaciones.php";
require_once "Modelo/CatalogoModel.php";

class ColaboradorController
{
    private $model;
    private $cat;

    public function __construct()
    {
        $db = (new Conexion())->getConexion();
        $this->model = new Colaborador($db);
        $this->cat = new CatalogoModel($db);
    }

    public function getCatalogos()
    {
        return [
            "sexo" => $this->cat->getSexo() ?? [],
            "sangre" => $this->cat->getSangre() ?? [],
            "rutas" => $this->cat->getRutas() ?? [],
            "estadoCivil" => $this->cat->getEstadoCivil() ?? [],
            "ocupaciones" => $this->cat->getOcupaciones() ?? []
        ];
    }

    public function listar()
    {
        return $this->model->obtenerTodos();
    }

    public function exportarExcel($salida = true)
    {
        $colaboradores = $this->model->obtenerTodos();

        $rows = [];
        $rows[] = [
            'id',
            'identidad',
            'nombre',
            'apellido',
            'edad',
            'sexo_id',
            'tipo_sangre_id',
            'ruta_id',
            'ocupacion_id',
            'estado_civil_id',
            'nacionalidad',
            'email',
            'celular'
        ];

        foreach ($colaboradores as $colab) {
            $rows[] = [
                $colab['id'] ?? '',
                $colab['identidad'] ?? '',
                $colab['nombre'] ?? '',
                $colab['apellido'] ?? '',
                $colab['edad'] ?? '',
                $colab['sexo_id'] ?? '',
                $colab['tipo_sangre_id'] ?? '',
                $colab['ruta_id'] ?? '',
                $colab['ocupacion_id'] ?? '',
                $colab['estado_civil_id'] ?? '',
                $colab['nacionalidad'] ?? '',
                $colab['email'] ?? '',
                $colab['celular'] ?? ''
            ];
        }

        $sheetXml = $this->buildSheetXml($rows);
        $tmpFile = tempnam(sys_get_temp_dir(), 'excel');

        if ($tmpFile === false) {
            throw new Exception('No se pudo crear el archivo temporal para exportar');
        }

        $zip = new ZipArchive();
        if ($zip->open($tmpFile, ZipArchive::OVERWRITE | ZipArchive::CREATE) !== true) {
            throw new Exception('No se pudo crear el archivo .xlsx');
        }

        $zip->addFromString('[Content_Types].xml', $this->buildContentTypesXml());
        $zip->addFromString('_rels/.rels', $this->buildRelsXml());
        $zip->addFromString('docProps/app.xml', $this->buildAppXml());
        $zip->addFromString('docProps/core.xml', $this->buildCoreXml());
        $zip->addFromString('xl/workbook.xml', $this->buildWorkbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->buildWorkbookRelsXml());
        $zip->addFromString('xl/styles.xml', $this->buildStylesXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();

        $contenido = file_get_contents($tmpFile);
        unlink($tmpFile);

        if ($salida) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="colaboradores.xlsx"');
            echo $contenido;
            exit;
        }

        return $contenido;
    }

    private function buildSheetXml(array $rows)
    {
        $sheetData = [];
        $sheetData[] = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $sheetData[] = '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
        $sheetData[] = '  <sheetData>';

        foreach ($rows as $index => $row) {
            $sheetData[] = '    <row r="' . ($index + 1) . '">';
            foreach ($row as $value) {
                $escaped = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
                $sheetData[] = '      <c t="inlineStr"><is><t>' . $escaped . '</t></is></c>';
            }
            $sheetData[] = '    </row>';
        }

        $sheetData[] = '  </sheetData>';
        $sheetData[] = '</worksheet>';

        return implode("\n", $sheetData);
    }

    private function buildContentTypesXml()
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
</Types>
XML;
    }

    private function buildRelsXml()
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>
XML;
    }

    private function buildWorkbookXml()
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Colaboradores" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>
XML;
    }

    private function buildWorkbookRelsXml()
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>
XML;
    }

    private function buildStylesXml()
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="1"><font><sz val="11"/><name val="Calibri"/></font></fonts>
  <fills count="1"><fill><patternFill patternType="none"/></fill></fills>
  <borders count="1"><border/></borders>
  <cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>
  <cellXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/></cellXfs>
  <cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>
</styleSheet>
XML;
    }

    private function buildAppXml()
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"
 xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>PHP</Application>
</Properties>
XML;
    }

    private function buildCoreXml()
    {
        $date = gmdate('Y-m-d\TH:i:s\Z');
        return <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"
 xmlns:dc="http://purl.org/dc/elements/1.1/"
 xmlns:dcterms="http://purl.org/dc/terms/"
 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>Colaboradores</dc:title>
  <dc:creator>PHP</dc:creator>
  <cp:lastModifiedBy>PHP</cp:lastModifiedBy>
  <dcterms:created xsi:type="dcterms:W3CDTF">$date</dcterms:created>
  <dcterms:modified xsi:type="dcterms:W3CDTF">$date</dcterms:modified>
</cp:coreProperties>
XML;
    }

    public function guardar()
    {
        // VALIDACIONES DE CAMPOS REQUERIDOS
        if (empty($_POST["identidad"]) || !Validaciones::requerido($_POST["identidad"])) {
            die("Error: Identidad es requerida");
        }
        if (empty($_POST["nombre"]) || !Validaciones::requerido($_POST["nombre"])) {
            die("Error: Nombre es requerido");
        }
        if (empty($_POST["apellido"]) || !Validaciones::requerido($_POST["apellido"])) {
            die("Error: Apellido es requerido");
        }
        if (empty($_POST["edad"]) || !Validaciones::edadValida($_POST["edad"])) {
            die("Error: Edad debe estar entre 18 y 70 años");
        }
        if (empty($_POST["email"]) || !Validaciones::emailValido($_POST["email"])) {
            die("Error: Email inválido");
        }
        if (empty($_POST["celular"]) || !Validaciones::celularValido($_POST["celular"])) {
            die("Error: Celular inválido (debe tener 7-8 dígitos)");
        }

        // SANITIZACIÓN
        $data = [
            "identidad" => Sanitizacion::limpiarTexto($_POST["identidad"]),
            "nombre" => Sanitizacion::tipoTitulo($_POST["nombre"]),
            "apellido" => Sanitizacion::tipoTitulo($_POST["apellido"]),
            "edad" => (int)$_POST["edad"],

            "sexo_id" => !empty($_POST["sexo_id"]) ? (int)$_POST["sexo_id"] : null,
            "tipo_sangre_id" => !empty($_POST["tipo_sangre_id"]) ? (int)$_POST["tipo_sangre_id"] : null,
            "ruta_id" => !empty($_POST["ruta_id"]) ? (int)$_POST["ruta_id"] : null,
            "ocupacion_id" => !empty($_POST["ocupacion_id"]) ? (int)$_POST["ocupacion_id"] : null,
            "estado_civil_id" => !empty($_POST["estado_civil_id"]) ? (int)$_POST["estado_civil_id"] : null,

            "nacionalidad" => !empty($_POST["nacionalidad"]) ? Sanitizacion::tipoTitulo($_POST["nacionalidad"]) : null,
            "email" => Sanitizacion::limpiarEmail($_POST["email"]),
            "celular" => Sanitizacion::limpiarNumero($_POST["celular"])
        ];

        if ($this->model->insertar($data)) {
            header("Location: Index.php?accion=listar&msg=ok");
            exit;
        } else {
            die("Error al guardar colaborador");
        }
    }
}