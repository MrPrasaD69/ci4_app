<?php 
namespace App\Models;
use CodeIgniter\Model;
class ProductModel extends Model
{
    protected $table = 'tbl_product';
    protected $primaryKey = 'product_id';
    
    protected $allowedFields = ['parent_id','product_name', 'product_color','added_on','status'];
}