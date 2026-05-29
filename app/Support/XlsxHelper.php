<?php

namespace App\Support;

/**
 * Minimal XLSX generator & parser — no external package needed.
 * Uses ZipArchive (built into PHP) + SimpleXML.
 *
 * Generate: build proper .xlsx binary from headers + data rows.
 * Parse   : read first sheet of any .xlsx, return 2-D array of strings.
 */
class XlsxHelper
{
    // ─────────────────────────────────────────────────────────────────
    // PUBLIC API
    // ─────────────────────────────────────────────────────────────────

    /**
     * Generate .xlsx binary content.
     *
     * @param  string[]   $headers  e.g. ['Nama', 'Nomor HP', 'Jumlah Kursi', 'Catatan']
     * @param  array[]    $rows     2-D array of cell values (string|int|float)
     * @return string               raw binary — pipe to response()->streamDownload()
     */
    public static function generate(array $headers, array $rows = []): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx_');

        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::OVERWRITE);

        $zip->addFromString('[Content_Types].xml', self::contentTypes());
        $zip->addFromString('_rels/.rels',          self::rootRels());
        $zip->addFromString('xl/workbook.xml',       self::workbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', self::workbookRels());
        $zip->addFromString('xl/styles.xml',         self::styles());
        $zip->addFromString('xl/worksheets/sheet1.xml', self::sheet($headers, $rows));

        $zip->close();

        $bytes = file_get_contents($tmp);
        unlink($tmp);

        return $bytes;
    }

    /**
     * Parse first sheet of an .xlsx file.
     * Returns 2-D array; row 0 = header row.
     *
     * @param  string  $filePath  absolute path to the uploaded .xlsx
     * @return array[]
     */
    public static function parse(string $filePath): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [];
        }

        // ── Shared strings ─────────────────────────────────────
        $shared = [];
        $ssXml  = $zip->getFromName('xl/sharedStrings.xml');
        if ($ssXml !== false) {
            $ssXml = self::stripNamespaces($ssXml);
            $ss    = simplexml_load_string($ssXml, 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOWARNING);
            foreach ($ss->si as $si) {
                // <t> direct or <r><t> (rich text)
                if (isset($si->t)) {
                    $shared[] = (string) $si->t;
                } else {
                    $parts = '';
                    foreach ($si->r as $r) {
                        $parts .= (string) $r->t;
                    }
                    $shared[] = $parts;
                }
            }
        }

        // ── Sheet XML ──────────────────────────────────────────
        // Find the first sheet path via workbook relationships
        $sheetPath = 'xl/worksheets/sheet1.xml';
        $wbRels = $zip->getFromName('xl/_rels/workbook.xml.rels');
        if ($wbRels !== false) {
            $rels = simplexml_load_string($wbRels);
            foreach ($rels->Relationship as $rel) {
                $type = (string) $rel['Type'];
                if (str_ends_with($type, '/worksheet')) {
                    $target = ltrim((string) $rel['Target'], '/');
                    $sheetPath = (str_starts_with($target, 'xl/') ? '' : 'xl/') . $target;
                    break;
                }
            }
        }

        $sheetXml = $zip->getFromName($sheetPath);
        $zip->close();

        if ($sheetXml === false) {
            return [];
        }

        $sheetXml = self::stripNamespaces($sheetXml);

        $sheet = simplexml_load_string($sheetXml, 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOWARNING);
        if ($sheet === false) {
            return [];
        }

        // ── Build 2-D array indexed by [rowNum][colNum] ────────
        $grid = [];
        foreach ($sheet->xpath('//row') as $row) {
            $rowNum = (int) $row['r'];
            foreach ($row->xpath('c') as $cell) {
                $ref  = (string) $cell['r'];           // e.g. "B3"
                $type = (string) $cell['t'];            // s=sharedStr, inlineStr, etc.
                $col  = self::colToIndex($ref);         // 1-based

                $value = '';
                if ($type === 's') {
                    $idx   = (int)(string) $cell->v;
                    $value = $shared[$idx] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = (string) ($cell->is->t ?? '');
                } elseif ($type === 'str') {
                    $value = (string) ($cell->v ?? '');
                } else {
                    $value = isset($cell->v) ? (string) $cell->v : '';
                }

                $grid[$rowNum][$col] = trim($value);
            }
        }

        // ── Normalise to indexed 2-D array ─────────────────────
        if (empty($grid)) return [];

        ksort($grid);
        $maxCol = 0;
        foreach ($grid as $cols) {
            $maxCol = max($maxCol, max(array_keys($cols)));
        }

        $result = [];
        foreach ($grid as $cols) {
            $row = [];
            for ($c = 1; $c <= $maxCol; $c++) {
                $row[] = $cols[$c] ?? '';
            }
            $result[] = $row;
        }

        return $result;
    }

    // ─────────────────────────────────────────────────────────────────
    // GENERATION HELPERS
    // ─────────────────────────────────────────────────────────────────

    private static function contentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }

    private static function rootRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private static function workbook(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            . ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Tamu" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private static function workbookRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private static function styles(): string
    {
        // Style index 0 = normal, 1 = bold white on blue (header), 2 = normal centre
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2">'
            .   '<font><sz val="11"/><color theme="1"/><name val="Calibri"/></font>'
            .   '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            . '</fonts>'
            . '<fills count="3">'
            .   '<fill><patternFill patternType="none"/></fill>'
            .   '<fill><patternFill patternType="gray125"/></fill>'
            .   '<fill><patternFill patternType="solid"><fgColor rgb="FF4472C4"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="2">'
            .   '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .   '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/>'
            . '</cellXfs>'
            . '</styleSheet>';
    }

    private static function sheet(array $headers, array $rows): string
    {
        $cols  = count($headers);
        $colW  = '<cols>';
        for ($i = 1; $i <= $cols; $i++) {
            $w     = [16, 18, 14, 22][$i - 1] ?? 16;
            $colW .= "<col min=\"{$i}\" max=\"{$i}\" width=\"{$w}\" customWidth=\"1\"/>";
        }
        $colW .= '</cols>';

        $sheetData = '<sheetData>';

        // Header row (style 1 = bold blue)
        $sheetData .= '<row r="1">';
        foreach ($headers as $ci => $h) {
            $ref       = self::cellRef($ci + 1, 1);
            $escaped   = htmlspecialchars((string) $h, ENT_XML1);
            $sheetData .= "<c r=\"{$ref}\" t=\"inlineStr\" s=\"1\"><is><t>{$escaped}</t></is></c>";
        }
        $sheetData .= '</row>';

        // Data rows (style 0 = normal)
        foreach ($rows as $ri => $row) {
            $rowNum     = $ri + 2;
            $sheetData .= "<row r=\"{$rowNum}\">";
            foreach ($row as $ci => $val) {
                $ref = self::cellRef($ci + 1, $rowNum);
                if (is_numeric($val) && ! is_string($val)) {
                    $sheetData .= "<c r=\"{$ref}\"><v>{$val}</v></c>";
                } else {
                    $escaped    = htmlspecialchars((string) $val, ENT_XML1);
                    $sheetData .= "<c r=\"{$ref}\" t=\"inlineStr\"><is><t>{$escaped}</t></is></c>";
                }
            }
            $sheetData .= '</row>';
        }

        $sheetData .= '</sheetData>';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . $colW
            . $sheetData
            . '</worksheet>';
    }

    // ─────────────────────────────────────────────────────────────────
    // UTILITIES
    // ─────────────────────────────────────────────────────────────────

    /**
     * Strip all XML namespaces so SimpleXML can parse without prefix errors.
     * Handles: xmlns declarations, namespace-prefixed attributes (mc:Ignorable, xr:uid),
     * and namespace-prefixed element names (<mc:AlternateContent>).
     */
    private static function stripNamespaces(string $xml): string
    {
        // 1. Remove all xmlns declarations (e.g. xmlns="..." xmlns:mc="...")
        $xml = preg_replace('/\s+xmlns(?::[a-zA-Z0-9_]+)?="[^"]*"/', '', $xml);

        // 2. Remove namespace-prefixed attributes (e.g. mc:Ignorable="x14ac" xr:uid="{...}")
        $xml = preg_replace('/\s+[a-zA-Z][a-zA-Z0-9_]*:[a-zA-Z][a-zA-Z0-9_.\-]*="[^"]*"/', '', $xml);

        // 3. Strip prefix from opening/self-closing namespace-prefixed elements
        //    <mc:AlternateContent ...> → <AlternateContent ...>
        $xml = preg_replace('/<([a-zA-Z][a-zA-Z0-9_]*):([a-zA-Z][a-zA-Z0-9_.\-]*)/', '<$2', $xml);

        // 4. Strip prefix from closing tags </mc:AlternateContent> → </AlternateContent>
        $xml = preg_replace('/<\/([a-zA-Z][a-zA-Z0-9_]*):([a-zA-Z][a-zA-Z0-9_.\-]*)/', '</$2', $xml);

        return $xml;
    }

    /** Convert 1-based column number to letter(s): 1→A, 26→Z, 27→AA */
    private static function numToCol(int $n): string
    {
        $s = '';
        while ($n > 0) {
            $n--;
            $s  = chr(65 + ($n % 26)) . $s;
            $n  = intdiv($n, 26);
        }
        return $s;
    }

    /** Build cell reference: col=1, row=1 → "A1" */
    private static function cellRef(int $col, int $row): string
    {
        return self::numToCol($col) . $row;
    }

    /** Parse cell reference "AB12" → column index (1-based) */
    private static function colToIndex(string $ref): int
    {
        preg_match('/^([A-Z]+)/i', $ref, $m);
        $letters = strtoupper($m[1] ?? 'A');
        $n       = 0;
        for ($i = 0; $i < strlen($letters); $i++) {
            $n = $n * 26 + (ord($letters[$i]) - 64);
        }
        return $n;
    }
}
