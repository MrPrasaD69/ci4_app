<?php

namespace App\Controllers;
use App\Models\ExcelRecord;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function readExcelView(){
        return view('readExcelView');
    }

    // public function readExcel(){
    //     $model = new ExcelRecord;
    //     $data = array();

    //     $csvFile = (!empty($_FILES['excel_file']) ? $_FILES['excel_file']['tmp_name'] : '');
    //     $csvData = array();
        
    //     //vertical table
    //     // if (($handle = fopen($csvFile, "r")) !== FALSE) {
    //     //     $header = fgetcsv($handle, 1000, ",");
    //     //     print_r($header);
    //     //     $i=0;
    //     //     while (($excel_data = fgetcsv($handle, 1000, ",")) !== FALSE) {                
    //     //         $data['name'] = (!empty($excel_data[1]) ? $excel_data[1] : '');
    //     //         $data['email_id'] = (!empty($excel_data[2]) ? $excel_data[2] : '');
    //     //         $model->save($data);                
    //     //         $i++;
    //     //     }
            
    //     //     fclose($handle);
    //     // } else {
    //     //     echo "Error: Could not open the file.";
    //     // }

    //     //horizontal table
    //     if (($handle = fopen($csvFile, "r")) !== FALSE) {
            
    //         while(($row = fgetcsv($handle, 1000, ',')) !== FALSE){
    //             $csvData[] = $row;
                
    //         }
    //         fclose($handle);
    //         $columns = count($csvData[0]);

    //         for($i=1; $i< $columns; $i++){
    //             // $data['id'] = $csvData[0][$i];
    //             $data['name'] = $csvData[1][$i];
    //             $data['email_id'] = $csvData[2][$i];
    //             $check = $model->where('id="'.$csvData[0][$i].'" ')->first();
    //             if(!empty($check)){
    //                 // echo"Not Empty \n";
    //                 $check['name'] = $csvData[1][$i];
    //                 $check['email_id'] = $csvData[2][$i];
    //                 $model->update($check['id'],$check);
    //             }
    //             else{
    //                 // echo "Empty \n";
    //                 // print_r($data);
    //                 $model->save($data);
    //             }
    //         }
    //     }
    // }

    // public function writeExcel(){
    //     $model = new ExcelRecord;
    //     $user_data = $model->where('status="1"')->findAll();

    //     if(!empty($user_data)){
    //         $path = FCPATH.'/upload/excel_users/';

    //         if(!is_dir($path)){
    //             mkdir($path,0777,true);
    //         }

    //         $filePath = $path.'users.csv';
            
    //         if (($handle = fopen($filePath, 'w')) !== FALSE) {
    //             $header = ['ID', 'Name', 'Email'];
    //             fputcsv($handle, $header);

    //             foreach($user_data as $val){
    //                 $row = [$val['id'],$val['name'],$val['email_id']];
    //                 fputcsv($handle,$row);
    //             }
    //             fclose($handle);

    //         }

            
    //         header('Content-Description: File Transfer');
    //         header('Content-Type: application/octet-stream');
    //         header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    //         header('Expires: 0');
    //         header('Cache-Control: must-revalidate');
    //         header('Pragma: public');
    //         header('Content-Length: ' . filesize($filePath));

    //         // Clear output buffer
    //         flush();

    //         // Read the file and output its contents
    //         readfile($filePath);

    //     }
    // }

    public function writeExcel(){
        $model = new ExcelRecord;
            
        $user_data = $model->where('status="1"')->findAll();
        if(!empty($user_data)){
            $path = FCPATH.'/upload/excel_users/';

            $filePath = $path.'users.csv';

            if(($handle = fopen($filePath,'w')) !== FALSE){
                $header = ['ID','Name','Email'];
                fputcsv($handle, $header);

                foreach($user_data as $val){
                    $row = [$val['id'], $val['name'], $val['email_id']];
                    fputcsv($handle, $row);
                }
                fclose($handle);
            }
            
            $this->download($filePath);
        }
    }
}
