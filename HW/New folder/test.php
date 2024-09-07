<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <title>Category Manager</title>
</head>
<body>
<?php include 'nav.php'; 
        session_start();
        if(isset($_SESSION['msg'])){
            ?>
            <script>
                var msg = "<?=$_SESSION['msg']?>"
                toastr.success(msg);
            </script>
            <?php
            unset($_SESSION['msg']);
        }
        elseif(isset($_SESSION['error'])){
            ?>
            <script>
                var msg = "<?=$_SESSION['error']?>"
                toastr.error(msg);
            </script>
            <?php
            unset($_SESSION['error']);
       }
     ?>
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-lg-3 col-md-4 col-sm-12">
            <form action="add_product.php" method="post" class="border border-warning bg-light p-3 rounded rounded-lg" enctype="multipart/form-data">
            <div class="form-group">
                <select name="cat" id="cat" class="form-control"onchange="checkcat(this)">
                <option value="">Please choose Category</option>
                <?= SelectCat()?>
                </select>
            </div>
            <div class="form-group mt-4">
                <select name="subcat" id="sub_cat" class="form-control">
                <option value="">Please choose Sub-Category</option>
                <?= SelectSubCat()?>
                </select>
            </div>
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" name="product_name">
            </div>
            <div class="form-group">
                <label for="">Quantity</label>
                <input type="number" class="form-control" name="product_qty">
            </div>
            <div class="form-group">
                <label for="">Price</label>
                <input type="number" class="form-control" name="price">
            </div>
            <div class="form-group">
                <label for="">Image</label>
                <input type="file" class="form-control" name="file">
            </div>
                <button class="btn btn-success form-control mt-2" type="submit" name="add">Add Product</button>
            </form>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Sub-Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'connection.php';
                        $var = 1;
                        $sql = "select * from product where status != 'disabled'";
                        $query = mysqli_query($con, $sql);
                        while ($data = mysqli_fetch_assoc($query)) {
                            $sql1 = "select * from catrgory where id = '".$data['category']."'";
                            $query1 = mysqli_query($con, $sql1);
                            $cat = mysqli_fetch_assoc($query1);

                            $sql2 = "select * from catrgory where id = '".$data['sub_category']."'";
                            $query2 = mysqli_query($con, $sql2);
                            $subcat = mysqli_fetch_assoc($query2);
                        ?>
                            <tr>
                                <td><?= $var ?></td>
                                <td class="name"><?= $data['name'] ?></td>
                                <td class="cat" id="<?= $cat['id'] ?>"><?= $cat['name'] ?></td>
                                <td class="subcat" id="<?= $subcat['id'] ?>"><?= $subcat['name'] ?></td>
                                <td class="qty"><?= $data['quantity'] ?></td>
                                <td class="price"><?= $data['price'] ?></td>
                                <td><a href="product_images/<?= $data['image'] ?>" target="_blank" class="btn btn-primary">view</a></td>
                                <td>
                                        <button id="product_delete_<?= $data['product_id'] ?>" class="btn btn-info" onclick="ProductUpdate('<?= $data['product_id'] ?>')">edit</button>
                                        <a class="btn btn-danger"name="delete" href="delete_product.php?delete=<?= base64_encode($data['product_id']) ?>">delete</a>
                                </td>
                            </tr>
                        <?php
                        $var++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="productUpdateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="update_product.php" method="post" class="" enctype="multipart/form-data">
                        <div class="form-group">
                            <select id="cat_updt" class="form-control" name = "cat"onchange="checkcat(this)">
                                <option value="">Please choose Category</option>
                            <?= SelectCat()?>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <select id="subcat_updt" class="form-control" name = "subcat">
                                <option value="">Please choose Sub-Category</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="product_id">
                            <label for="">Name</label>
                            <input type="text" class="form-control" id="product_name_updt" name="name">
                        </div>
                        <div class="form-group">
                            <label for="">Quantity</label>
                            <input type="number" class="form-control" id="product_qty_updt" name="qty">
                        </div>
                        <div class="form-group">
                            <label for="">Price</label>
                            <input type="number" class="form-control" id="price_updt" name="price">
                        </div>
                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" class="form-control" name="file">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script>
        function checkcat(cat,vall) {
            var cate = cat.value;
            if(cate==null){
                cate=cat;
            }
            if (cate != ""){
                $.ajax({
                    type:'post',
                    url:'subcatfetch.php',
                    data:{cate:cate},
                    success:function(data){
                        if(cat.id=='cat')
                        {
                            $('#sub_cat').html(data);
                        }
                        else
                        {
                            $('#subcat_updt').html(data);
                            $('#subcat_updt').val(vall);
                        }
                    }
                });
            }
            else{
                if(cat.id=='cat')
                {
                    $('#sub_cat').html('<option value="">Please choose Sub-Category</option>');
                }
                else
                {
                    $('#subcat_updt').html('<option value="">Please choose Sub-Category</option>');
                }
            }   
        }
    </script>
<script>
        function ProductUpdate(p_id) {
            var name = $('#product_delete_' + p_id).closest('tr').find('.name').text();
            var cat = $('#product_delete_' + p_id).closest('tr').find('.cat').attr('id');
            var qlty = $('#product_delete_' + p_id).closest('tr').find('.qty').text();
            var price = $('#product_delete_' + p_id).closest('tr').find('.price').text();
            var subcat = $('#product_delete_' + p_id).closest('tr').find('.subcat').attr('id');
            $('#product_name_updt').val(name);
            $('#cat_updt').val(cat);
            checkcat(cat,subcat);
            $('#product_qty_updt').val(qlty);
            $('#price_updt').val(price);
            $('input[name="product_id"]').val(p_id);
            $('#productUpdateModal').modal('show');
        }
    </script>
    <?php
    function SelectCat()
    {
        include 'connection.php';
        $sql = "select * from catrgory where parent_id = 0";
        $query = mysqli_query($con, $sql);
        $sub_cate = '';
        while ($data = mysqli_fetch_assoc($query)) {
            $sub_cate .= "<option value='" . $data['id'] . "'>" . $data['name'] . "</option>";
        }
        echo $sub_cate;
    }
    function SelectSubCat()
    {
        include 'connection.php';
        $sql = "select * from catrgory where parent_id != 0";
        $query = mysqli_query($con, $sql);
        $sub_cate = '';
        while ($data = mysqli_fetch_assoc($query)) {
            $sub_cate .= "<option value='" . $data['id'] . "'>" . $data['name'] . "</option>";
        }
        echo $sub_cate;   
    }
    ?>
</body>
</html>