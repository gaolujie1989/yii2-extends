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
     * @param string $inputPdf
     * @param string $outputPdf
     * @param int $firstPage
     * @param int $lastPage
     * @param string $engine
     * @return string
     * @inheritdoc
     */
    public function separatePdf(string $inputPdf, string $outputPdf, int $firstPage = 1, int $lastPage = 1, string $engine = 'pdftk'): string
    {
        return match ($engine) {
            'pdftk' => $this->separatePdfByPdfTk($inputPdf, $outputPdf, $firstPage, $lastPage),
            'pdfunite' => $this->separatePdfByPopplerUtils($inputPdf, $outputPdf, $firstPage, $lastPage),
            'gs' => $this->separatePdfByGhostScript($inputPdf, $outputPdf, $firstPage, $lastPage),
        };
    }

    /**
     * @param array $inputPdfs
     * @param string $outputPdf
     * @param string $engine
     * @return string
     * @throws UserException
     * @inheritdoc
     */
    public function mergePdf(array $inputPdfs, string $outputPdf, string $engine = 'pdftk'): string
    {
        return match ($engine) {
            'pdftk' => $this->mergePdfByPdfTk($inputPdfs, $outputPdf),
            'pdfunite' => $this->mergePdfByPopplerUtils($inputPdfs, $outputPdf),
            'gs' => $this->mergePdfByGhostScript($inputPdfs, $outputPdf),
        };
    }

    /**
     * @param string $inputPdf
     * @param string $outputPdf
     * @param int $firstPage
     * @param int $lastPage
     * @return string
     * @inheritdoc
     */
    protected function separatePdfByPopplerUtils(string $inputPdf, string $outputPdf, int $firstPage = 1, int $lastPage = 1): string
    {
        $options = [$inputPdf, $outputPdf];
        return $this->run($this->binPaths['pdfseparate'], $options);
    }

    /**
     * @param array $inputPdfs
     * @param string $outputPdf
     * @return string
     * @inheritdoc
     */
    protected function mergePdfByPopplerUtils(array $inputPdfs, string $outputPdf): string
    {
        $options = array_merge($inputPdfs, [$outputPdf]);
        return $this->run($this->binPaths['pdfunite'], $options);
    }

    /**
     * @param string $inputPdf
     * @param string $outputPdf
     * @param int $firstPage
     * @param int $lastPage
     * @return string
     * @inheritdoc
     */
    protected function separatePdfByPdfTk(string $inputPdf, string $outputPdf, int $firstPage = 1, int $lastPage = 1): string
    {
        $options = [$inputPdf, 'cat', "$firstPage-$lastPage", 'output', $outputPdf];
        return $this->run($this->binPaths['pdftk'], $options);
    }

    /**
     * @param array $inputPdfs
     * @param string $outputPdf
     * @return string
     * @inheritdoc
     */
    protected function mergePdfByPdfTk(array $inputPdfs, string $outputPdf): string
    {
        $options = array_merge($inputPdfs, ['cat', 'output', $outputPdf]);
        return $this->run($this->binPaths['pdftk'], $options);
    }

    /**
     * @param string $inputPdf
     * @param string $outputPdf
     * @param int $firstPage
     * @param int $lastPage
     * @return string
     * @inheritdoc
     */
    protected function separatePdfByGhostScript(string $inputPdf, string $outputPdf, int $firstPage = 1, int $lastPage = 1): string
    {
        $options = [
            '-dBATCH', '-dNOPAUSE', '-dSAFER', '-dQUIET', '-sDEVICE=pdfwrite',
            '-dFirstPage=' . $firstPage, '-dLastPage=' . $lastPage,
            '-sOutputFile=' . $outputPdf
        ];
        $options = $this->parseOptions($options);
        $options[] = $inputPdf;
        return $this->run($this->binPaths['gs'], $options);
    }

    /**
     * @param array $inputPdfs
     * @param string $outputPdf
     * @return string
     * @inheritdoc
     */
    protected function mergePdfByGhostScript(array $inputPdfs, string $outputPdf): string
    {
        $options = [
            '-dBATCH', '-dNOPAUSE', '-dQUIET', '-sDEVICE=pdfwrite',
            '-dCompatibilityLevel=1.7',
            '-dPDFSETTINGS=/prepress',
            '-sOutputFile=' . $outputPdf
        ];
        $options = $this->parseOptions($options);
        $options = array_merge($options, $inputPdfs);
        return $this->run($this->binPaths['gs'], $options);
    }
}
