<?php

namespace Sibas\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\CertificateRepository;

class CertificateController extends Controller
{

    protected static $code39 = [
        '0' => 'bwbwwwbbbwbbbwbw',
        '1' => 'bbbwbwwwbwbwbbbw',
        '2' => 'bwbbbwwwbwbwbbbw',
        '3' => 'bbbwbbbwwwbwbwbw',
        '4' => 'bwbwwwbbbwbwbbbw',
        '5' => 'bbbwbwwwbbbwbwbw',
        '6' => 'bwbbbwwwbbbwbwbw',
        '7' => 'bwbwwwbwbbbwbbbw',
        '8' => 'bbbwbwwwbwbbbwbw',
        '9' => 'bwbbbwwwbwbbbwbw',
        'A' => 'bbbwbwbwwwbwbbbw',
        'B' => 'bwbbbwbwwwbwbbbw',
        'C' => 'bbbwbbbwbwwwbwbw',
        'D' => 'bwbwbbbwwwbwbbbw',
        'E' => 'bbbwbwbbbwwwbwbw',
        'F' => 'bwbbbwbbbwwwbwbw',
        'G' => 'bwbwbwwwbbbwbbbw',
        'H' => 'bbbwbwbwwwbbbwbw',
        'I' => 'bwbbbwbwwwbbbwbw',
        'J' => 'bwbwbbbwwwbbbwbw',
        'K' => 'bbbwbwbwbwwwbbbw',
        'L' => 'bwbbbwbwbwwwbbbw',
        'M' => 'bbbwbbbwbwbwwwbw',
        'N' => 'bwbwbbbwbwwwbbbw',
        'O' => 'bbbwbwbbbwbwwwbw',
        'P' => 'bwbbbwbbbwbwwwbw',
        'Q' => 'bwbwbwbbbwwwbbbw',
        'R' => 'bbbwbwbwbbbwwwbw',
        'S' => 'bwbbbwbwbbbwwwbw',
        'T' => 'bwbwbbbwbbbwwwbw',
        'U' => 'bbbwwwbwbwbwbbbw',
        'V' => 'bwwwbbbwbwbwbbbw',
        'W' => 'bbbwwwbbbwbwbwbw',
        'X' => 'bwwwbwbbbwbwbbbw',
        'Y' => 'bbbwwwbwbbbwbwbw',
        'Z' => 'bwwwbbbwbbbwbwbw',
        '-' => 'bwwwbwbwbbbwbbbw',
        '.' => 'bbbwwwbwbwbbbwbw',
        ' ' => 'bwwwbbbwbwbbbwbw',
        '*' => 'bwwwbwbbbwbbbwbw',
        '$' => 'bwwwbwwwbwwwbwbw',
        '/' => 'bwwwbwwwbwbwwwbw',
        '+' => 'bwwwbwbwwwbwwwbw',
        '%' => 'bwbwwwbwwwbwwwbw',
    ];

    /**
     * @var CertificateRepository
     */
    private $repository;

    /**
     * @var null|\Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected $payload = null;

    /**
     * @var int
     */
    protected $status = 200;

    /**
     * @var PdfController
     */
    private $pdf;

    /**
     * @var string
     */
    protected $route = '';


    public function __construct(CertificateRepository $repository, PdfController $pdf)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

        $this->repository = $repository;
        $this->pdf        = $pdf;
    }


    /**
     * Display the specified resource.
     *
     * @param string $code
     * @param string $rp_id
     * @param string $header_id
     * @param string $type
     * @param string $format
     * @param bool   $read
     *
     * @return \Illuminate\Http\Response
     * @throws Exception
     * @throws \Throwable
     */
    public function show($code, $rp_id, $header_id, $type, $format, $read = true)
    {
        $header      = $this->repository->getDataCertificate($code, decode($header_id));
        $slip        = null;
        $certificate = null;

        if ($header instanceof Model) {
            switch ($code) {
                case 'de':
                    $slip        = 'slip';
                    $certificate = $this->repository->getTemplateCertificate($header);
                    break;
                case 'au':
                case 'td':
                case 'ema':
                case 'rma':
		        case 'tj':
                case 'vd':
                    $slip        = 'certificate_slip';
                    $certificate = $this->repository->getTemplateCertificate($header);
                    break;
                case 'vi':
                    break;
                case 'elp':
                    $client_eco = $header->detail->client_eco;
                    $slip       = $client_eco ? 'slip-ecoproteccion' : 'slip-ecovida';
                    $template   = $this->repository->getTemplateCertificate($header);
                    $template   = json_decode($template, true);

                    if (is_array($template)) {
                        if ($client_eco && key_exists('ep', $template)) {
                            $certificate = $template['ep'];
                        } elseif ( ! $client_eco && key_exists('el', $template)) {
                            $certificate = $template['el'];
                        }
                    }

                    break;
                case 'eb':
                    $slip        = 'slip-ecoaguinaldo';
                    $certificate = $this->repository->getTemplateCertificate($header);
                    break;
                default:
                    $this->status = 400;
                    break;
            }
            
            if ($this->status === 200) {
                try {
                    switch ($type) {
                        case 'slip':
                            $this->payload = view('cert.' . $code . '.' . $slip, [
                                'header' => $header,
                                'format' => $format,
                            ]);
                            break;
                        case 'certificate':
                            if ($code === 'de' && $read) {
                                $header = $this->repository->setCopyNumber();
                            }

                            $barcode = $this->code39('SB2015', 37.34, 2);

                            $this->payload = view('cert.' . $code . '.' . $certificate, [
                                'header'  => $header,
                                'format'  => $format,
                                'barcode' => $barcode,
                            ]);
                            break;
                        case 'all':
                            $this->payload = view('cert.' . $code . '.' . $slip, [
                                'header' => $header,
                                'format' => $format,
                            ])->nest('sub_module', 'cert.' . $code . '.' . $certificate, [
                                'header' => $header,
                                'format' => $format,
                            ]);
                            break;
                    }

                    if ($format === 'print') {
                        $this->route = route('certificate.show', [
                            'code'      => $code,
                            'rp_id'     => $rp_id,
                            'type'      => $type,
                            'header_id' => $header_id,
                            'format'    => 'pdf',
                        ]);
                    }
                } catch (\InvalidArgumentException $e) {
                    $this->status = 500;
                }
            }
        } else {
            $this->status = 500;
        }

        return $this->render($format, $code);
    }

    public function templatePdfs($code, $rp_id, $id, $type, $format)
    {
        
        $model       = null;
        $slip        = null;
        $certificate = null;

            switch ($type) {
                case 'rejected_customer':
                    
                    $this->rejected_repository = new \Sibas\Repositories\De\RejectedCustomerRepository();
                    if($this->rejected_repository->getRejectedCustomerById(decode($id)))
                        $model = $this->rejected_repository->getModel();            
                    
                    $certificate = $type;
                    break;
                default:
                    $this->status = 400;
                    break;
            }
            
            if ($this->status === 200) {
                try {
                    switch ($type) {
                        
                        case 'rejected_customer':

                            $barcode = $this->code39('SB2015', 37.34, 2);

                            $this->payload = view('cert.' . $code . '.' . $certificate, [
                                'model'  => $model,
                                'format'  => $format,
                                'barcode' => $barcode,
                            ]);
                            break;
                    }

                } catch (\InvalidArgumentException $e) {
                    $this->status = 500;
                }
            }
        
        return $this->render($format, $code);
    }


    /**
     * @param string $format
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws Exception
     * @throws \Throwable
     */
    protected function render($format)
    {
            $response = $this->payload->render();
        
        if ($this->status === 200) {
            switch ($format) {
                case 'print':
                    $response = view('cert.certificate', [
                        'format' => $format,
                        'view'   => $response,
                        'route'  => $this->route,
                    ])->render();

                    break;
            }
        } else {
            $response = view('errors.503', [
                'message' => 'El Certificado no puede ser procesado.',
            ])->render();
        }

        if ($format === 'print') {
            $response = response()->json([
                'payload' => $response,
            ]);
        } elseif ($format === 'pdf') {
            return $this->pdf->create($response, 'Certificado');
        }

        return $response;
    }


    /**
     * @param string $text
     * @param int    $height
     * @param int    $widthScale
     *
     * @return string
     * @throws Exception
     */
    protected function code39($text, $height = 50, $widthScale = 1)
    {
        if (! preg_match('/^[A-Z0-9-. $+\/%]+$/i', $text)) {
            throw new Exception('Invalid text input.');
        }

        $text   = strtoupper($text);
        $text   = '*' . strtoupper($text) . '*'; // *UPPERCASE TEXT*
        $length = strlen($text);

        $barcode = imageCreate($length * 16 * $widthScale, $height);

        $bg = imagecolorallocate($barcode, 255, 255, 0); //sets background to yellow
        imagecolortransparent($barcode, $bg); //makes that yellow transparent
        $black = imagecolorallocate($barcode, 0, 0, 0); //defines a color for black

        $chars = str_split($text);

        $colors = '';

        foreach ($chars as $char) {
            $colors .= self::$code39[ $char ];
        }

        foreach (str_split($colors) as $i => $color) {
            if ($color == 'b') {
                imageFilledRectangle($barcode, $widthScale * $i, 0, $widthScale * ( $i + 1 ) - 1, $height - 13, $black);
            }
        }

        ob_start();
        imagepng($barcode);
        imageDestroy($barcode);
        $image = ob_get_contents();
        ob_end_clean();

        return $image;
    }
}
