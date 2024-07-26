<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Product List</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>
</head>
<body>
    <div class="container">
        <table id="product_table"></table>
    </div>
</body>
<script>
    $(document).ready(function(){
        var table = $("#product_table").DataTable({
           "processing": true,
            lengthMenu:[5,10,25,50],
            paging:true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url(); ?>home/productList",
                "type": "POST",
                "data": function (d) {
                }
            },
            columns:[
                { "data": "product_name", title:'Product Name' },
                { "data": "product_color", title:'Product Color' },
            ]
        });

        
        
    });
</script>
</html>