<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer as Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelHandler {



  private $filename = "";
  private $spreadsheet = null;

  public function __construct($fname){
    $this->spreadsheet = new Spreadsheet;
    $this->filename = $fname;
  }

  public function addSheet($sheetname, $content, $header = []){
    $cursheet = new Worksheet($this->spreadsheet, $sheetname);
    $this->spreadsheet->addSheet($cursheet);

    // first, populatae the header
    $colcount = 1;
    $rowcount = 1;
    // dd($header);

    foreach($header as $head){
      $cursheet->setCellValueByColumnAndRow($colcount, $rowcount, $head);
      $colcount++;
    }
    $rowcount++;

    // then populate the data
    // dd($content);
    foreach ($content as $value) {

      $colcount = 1;
      foreach ($value as $oncel) {
        $cursheet->setCellValueByColumnAndRow($colcount, $rowcount, $oncel);
        $colcount++;
      }
      $rowcount++;
    }
    // $colcount = 1;
    //
    // // foreach ($content as $value) {
    //
    //   foreach ($content as $oncel) {
    //     $cursheet->setCellValueByColumnAndRow($colcount, $rowcount, $oncel);
    //     $colcount++;
    //   }
    //   $rowcount++;
    // // }
  }

  public function getBinary(){
    return base64_encode(serialize($this->spreadsheet));
  }

  public function saveToPerStorage(){
    // dd('sini',$this->filename);
    $writer = new Writer\Xlsx($this->spreadsheet);
    $writer->save('var/www/storage/app/reports/' . $this->filename);
  }

  public function download(){
    $writer = new Writer\Xlsx($this->spreadsheet);

    $response =  new StreamedResponse(
        function () use ($writer) {
            $writer->save('php://output');
        }
    );
    // $response->headers->set('Content-Type', 'application/vnd.ms-excel');

    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Content-Disposition', 'attachment;filename="'.$this->filename.'"');
    $response->headers->set('Cache-Control','max-age=0');
    return $response;
  }

  public static function DownloadFromBin($datafromdb, $fname){
    $unserialize_sp = unserialize(base64_decode($datafromdb));
    $writer = new Writer\Xlsx($unserialize_sp);

    $response =  new StreamedResponse(
        function () use ($writer) {
            $writer->save('php://output');
        }
    );
    // $response->headers->set('Content-Type', 'application/vnd.ms-excel');

    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Content-Disposition', 'attachment;filename="'.$fname.'"');
    $response->headers->set('Cache-Control','max-age=0');
    return $response;
  }

  public static function DownloadFromPerStorage($fname){
    // dd($fname);
    if(\Storage::exists('reports/'.$fname)){
      // dd('ada');
      return response()->download(storage_path("app/reports/".$fname));
      // \Storage::download('reports/'.$fname, $fname, [
      //   'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      //   'Content-Disposition' => 'attachment;filename="'.$fname.'"',
      //   'Cache-Control' => 'max-age=0'
      // ]);
    } else {
      return "report 404";
    }

  }
}
