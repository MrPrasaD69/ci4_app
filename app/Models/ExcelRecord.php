<?php 
namespace App\Models;
use CodeIgniter\Model;
class ExcelRecord extends Model
{
    protected $table = 'excel_record';
    protected $primaryKey = 'id';
    
    protected $allowedFields = ['id','name', 'email_id','status'];
}