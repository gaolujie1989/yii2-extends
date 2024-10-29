<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\command;

/**
 * Class PdfCommander
 * @package lujie\extend\command
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PdfCommander extends BaseCommander
{
    public $binPaths = [
        'pdfattach' => 'pdfattach',
        'pdfdetach' => 'pdfdetach',
        'pdffonts' => 'pdffonts',
        'pdfimages' => 'pdfimages',
        'pdfinfo' => 'pdfinfo',
        'pdfseparate' => 'pdfseparate',
        'pdfsig' => 'pdfsig',
        'pdftocairo' => 'pdftocairo',
        'pdftohtml' => 'pdftohtml',
        'pdftoppm' => 'pdftoppm',
        'pdftops' => 'pdftops',
        'pdftotext' => 'pdftotext',
        'pdfunite' => 'pdfunite',
        'pdftk' => 'pdftk',
        'gs' => 'gs',
        'tesseract' => 'tesseract',
    ];

    /**
     * @param string $pdf
     * @param array $options
     * @return string
     * @inheritdoc
     */
    public function pdfToText(string $pdf, array $options): string
    {
        $options = $this->parseOptions($options);
        $options[] = $pdf;
        return $this->run($this->binPaths['pdftotext'], $options);
    }

    /**
     * @param string $pdf
     * @param string $outputImageFilePrefix
     * @param array $options
     * @return string
     * @inheritdoc
     */
    public function pdfToImages(string $pdf, string $outputImageFilePrefix, array $options = ['-png', '-r 300']): string
    {
        $options = $this->parseOptions($options);
        $options[] = $pdf;
        $options[] = $outputImageFilePrefix;
        return $this->run($this->binPaths['pdftoppm'], $options);
    }

    /**
     * @param string $pdf
     * @param string $outputTxtFile
     * @param array $options
     * @return string
     * @inheritdoc
     */
    public function imageToText(string $pdf, string $outputTxtFile, array $options = []): string
    {
        $options = $this->parseOptions($options);
        $options[] = $pdf;
        $options[] = $outputTxtFile;
        return $this->run($this->binPaths['tesseract'], $options);
    }

    /**
     * @param string $pdf
     * @param array $options
     * @return string
     * @inheritdoc
     */
    public function separatePdfByPopplerUtils(string $pdf, string $outputFile, array $options): string
    {
        $options = $this->parseOptions($options);
        $options[] = $pdf;
        $options[] = $outputFile;
        return $this->run($this->binPaths['pdfseparate'], $options);
    }

    /**
     * @param array $pdfs
     * @param string $outputFile
     * @param array $options
     * @return string
     * @inheritdoc
     */
    public function mergePdfByPopplerUtils(array $pdfs, string $outputFile, array $options = []): string
    {
        $options = $this->parseOptions($options);
        $options = array_merge($options, $pdfs, [$outputFile]);
        return $this->run($this->binPaths['pdfunite'], $options);
    }

    /**
     * @param string $inputPdf
     * @param string $outputPdf
     * @param array $options
     * @return string
     * @inheritdoc
     */
    public function separatePdf(string $inputPdf, string $outputPdf, array $options = []): string
    {
        if (isset($options['firstPage'])) {
            $options[] = '-dFirstPage=' . $options['firstPage'];
            unset($options['firstPage']);
        }
        if (isset($options['lastPage'])) {
            $options[] = '-dLastPage=' . $options['lastPage'];
            unset($options['lastPage']);
        }
        $options = array_merge($options, [
            '-dBATCH', '-dNOPAUSE', '-dSAFER', '-dQUIET', '-sDEVICE=pdfwrite',
            '-sOutputFile=' . $outputPdf
        ]);
        $options = $this->parseOptions($options);
        $options[] = $inputPdf;
        return $this->run($this->binPaths['gs'], $options);
    }

    /**
     * @param array $inputPdfs
     * @param string $outputPdf
     * @param array $options
     * @return string
     * @inheritdoc
     */
    public function mergePdf(array $inputPdfs, string $outputPdf, array $options = []): string
    {
        $options = array_merge($options, [
            '-dBATCH', '-dNOPAUSE', '-dQUIET', '-sDEVICE=pdfwrite',
            '-sOutputFile=' . $outputPdf
        ]);
        $options = $this->parseOptions($options);
        $options = array_merge($options, $inputPdfs);
        return $this->run($this->binPaths['gs'], $options);
    }

    /**
     * @param string $inputPdf
     * @param string $outputPdf
     * @param array $options
     * @return string
     * @inheritdoc
     */
    public function compressPdf(string $inputPdf, string $outputPdf, array $options = []): string
    {
        $options = array_merge($options, [
            '-dBATCH', '-dNOPAUSE', '-dQUIET', '-sDEVICE=pdfwrite',
            '-dCompatibilityLevel=1.4',
            '-dPDFSETTINGS=prepress',
            '-sOutputFile=' . $outputPdf
        ]);
        $options = $this->parseOptions($options);
        $options[] = $inputPdf;
        return $this->run($this->binPaths['gs'], $options);
    }
}
