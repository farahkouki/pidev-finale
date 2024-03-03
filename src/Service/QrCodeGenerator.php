<?php
 
 namespace App\Service;

use App\Entity\Association;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\SvgWriter;
use Symfony\Component\HttpFoundation\Request;


class QrCodeGenerator 
{
 
public function createQrCode(Request $request,  Association $association): ResultInterface
{
    // Récupérez les informations du patient
    $id = $association->getId();
    $nom = $association->getNom();
    $email = $association->getEmail();
    $description = $association->getDescription();
    $numeroTelephone = $association->getNumeroTelephone();
    $dateCreation = $association->getDateCreation()->format('Y-m-d');   
    $adresse = $association->getAdresse();

    $info = "
    $id
    $nom
    $email
    $description
    $numeroTelephone
    $dateCreation
    $adresse
    ";

    // Générez le code QR avec les informations du patient
    $result = Builder::create()
        ->writer(new SvgWriter())
        ->writerOptions([])
        ->data($info)
        ->encoding(new Encoding('UTF-8'))
        ->size(200)
        ->margin(10)
        ->labelText('Vous trouvez vos informations ici')
        ->labelFont(new NotoSans(20))
        ->validateResult(false)
        ->build();

    return $result;
}}