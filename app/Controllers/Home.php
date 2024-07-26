<?php

namespace App\Controllers;
use App\Models\ExcelRecord;
use App\Models\ProductModel;
use \Mpdf\Mpdf;

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

    public function productList(){
        $limit = (!empty($_REQUEST['length']) ? (int)$_REQUEST['length'] : null);
        $start = (!empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0);
        $data = array();
        $model = new ProductModel;
        $condition = 'status="1"';
        
        if(!empty($limit)){

            if(!empty($_REQUEST['search']['value'])){
                $condition .= ' AND product_name LIKE "%'.$_REQUEST['search']['value'].'%" OR product_color LIKE "%'.$_REQUEST['search']['value'].'%" ';
            }
                        
            $product_data = $model->where($condition)->orderBy('product_id','desc')->findAll($limit,$start);
            $all_data = $model->where($condition)->countAllResults();
            
            if(!empty($product_data)){
                $data['data'] = $product_data;
                $data['draw'] = $_REQUEST['draw'];
                $data['recordsTotal'] = $all_data;
                $data['recordsFiltered'] = $all_data;
            }else{
                $data['data'] = array();
                $data['draw'] = $_REQUEST['draw'];
                $data['recordsTotal'] = 0;
                $data['recordsFiltered'] = 0;
            }
            echo json_encode($data);
        }
        else{
            return view('productList');
        }
    }

    public function showPDF(){
    
        if (ob_get_length()) {
            ob_end_clean();
        }

        $html = '<html>
            <head>
                <meta charset="utf-8" />
                <title>A simple, clean, and responsive HTML invoice template</title>
        
                <style>
                    .invoice-box {
                        max-width: 800px;
                        margin: auto;
                        padding: 30px;
                        border: 1px solid #eee;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                        font-size: 16px;
                        line-height: 24px;
                        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                        color: #555;
                    }
        
                    .invoice-box table {
                        width: 100%;
                        line-height: inherit;
                        text-align: left;
                    }
        
                    .invoice-box table td {
                        padding: 5px;
                        vertical-align: top;
                    }
        
                    .invoice-box table tr td:nth-child(2) {
                        text-align: right;
                    }
        
                    .invoice-box table tr.top table td {
                        padding-bottom: 20px;
                    }
        
                    .invoice-box table tr.top table td.title {
                        font-size: 45px;
                        line-height: 45px;
                        color: #333;
                    }
        
                    .invoice-box table tr.information table td {
                        padding-bottom: 40px;
                    }
        
                    .invoice-box table tr.heading td {
                        background: #eee;
                        border-bottom: 1px solid #ddd;
                        font-weight: bold;
                    }
        
                    .invoice-box table tr.details td {
                        padding-bottom: 20px;
                    }
        
                    .invoice-box table tr.item td {
                        border-bottom: 1px solid #eee;
                    }
        
                    .invoice-box table tr.item.last td {
                        border-bottom: none;
                    }
        
                    .invoice-box table tr.total td:nth-child(2) {
                        border-top: 2px solid #eee;
                        font-weight: bold;
                    }
        
                    @media only screen and (max-width: 600px) {
                        .invoice-box table tr.top table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }
        
                        .invoice-box table tr.information table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }
                    }
        
                    /** RTL **/
                    .invoice-box.rtl {
                        direction: rtl;
                        font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                    }
        
                    .invoice-box.rtl table {
                        text-align: right;
                    }
        
                    .invoice-box.rtl table tr td:nth-child(2) {
                        text-align: left;
                    }
                </style>
            </head>
        
            <body>
                <div class="invoice-box">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="top">
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td class="title">
                                            <img
                                                src="https://sparksuite.github.io/simple-html-invoice-template/images/logo.png"
                                                style="width: 100%; max-width: 300px"
                                            />
                                        </td>
        
                                        <td>
                                            Invoice #: 123<br />
                                            Created: January 1, 2023<br />
                                            Due: February 1, 2023
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
        
                        <tr class="information">
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            Sparksuite, Inc.<br />
                                            12345 Sunny Road<br />
                                            Sunnyville, CA 12345
                                        </td>
        
                                        <td>
                                            Acme Corp.<br />
                                            John Doe<br />
                                            john@example.com
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
        
                        <tr class="heading">
                            <td>Payment Method</td>
        
                            <td>Check #</td>
                        </tr>
        
                        <tr class="details">
                            <td>Check</td>
        
                            <td>1000</td>
                        </tr>
        
                        <tr class="heading">
                            <td>Item</td>
        
                            <td>Price</td>
                        </tr>
        
                        <tr class="item">
                            <td>Website design</td>
        
                            <td>$300.00</td>
                        </tr>
        
                        <tr class="item">
                            <td>Hosting (3 months)</td>
        
                            <td>$75.00</td>
                        </tr>
        
                        <tr class="item last">
                            <td>Domain name (1 year)</td>
        
                            <td>$10.00</td>
                        </tr>
        
                        <tr class="total">
                            <td></td>
        
                            <td>Total: $385.00</td>
                        </tr>
                    </table>
                </div>
            </body>
        </html>';
        
        $pdf = new Mpdf();
        
        $pdf->WriteHTML($html);
        $pdf->Output('file.pdf', 'I');
        exit;
        
        
    }
}
