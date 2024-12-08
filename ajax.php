<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php
 // Auto suggestion
 if(isset($_POST['product_name']) && strlen($_POST['product_name']))
 {
   $products = find_product_by_title($_POST['product_name']);
   $html = '';
   if($products){
      foreach ($products as $product):
         $html .= "<li class=\"list-group-item\" onClick=\"fill('".addslashes($product['name'])."')\">";
         $html .= $product['name'];
         $html .= "</li>";
       endforeach;
    } else {
      $html .= '<li class="list-group-item">Not found</li>';
    }

    echo json_encode($html);
 }
?>

<?php
// find all product
if(isset($_POST['p_name']) && strlen($_POST['p_name']))
{
  $product_title = remove_junk($db->escape($_POST['p_name']));
  $results = find_all_product_info_by_title($product_title);
  $html = '';
  if($results){
      foreach ($results as $result) {
        $html .= "<tr>";
        $html .= "<td id=\"s_name\">".$result['name']."</td>";
        $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">";
        $html .= "<td><input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$result['sale_price']}\"></td>";
        $html .= "<td id=\"s_qty\"><input type=\"text\" class=\"form-control\" name=\"quantity\" value=\"1\"></td>";
        $html .= "<td><input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$result['sale_price']}\"></td>";
        $html .= "<td><input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\"></td>";
        $html .= "<td><button type=\"submit\" name=\"add_sale\" class=\"btn btn-primary\">Add sale</button></td>";
        $html .= "</tr>";
      }
  } else {
      $html ='<tr><td>Product name not registered</td></tr>';
  }

  echo json_encode($html);
}
?>

<?php
require_once('includes/load.php');

// Fetch products based on the search term
if (isset($_POST['title'])) {
    $title = $db->escape($_POST['title']);
    $query = "SELECT * FROM products WHERE product_name LIKE '%{$title}%'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<a href="#" class="list-group-item list-group-item-action" data-id="'.$row['id'].'" data-name="'.$row['product_name'].'" data-price="'.$row['price'].'">'.$row['product_name'].' - $'.$row['price'].'</a>';
        }
    } else {
        echo '<a href="#" class="list-group-item">No products found</a>';
    }
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#sug_input').on('input', function() {
        var query = $(this).val();
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: { title: query },
            success: function(data) {
                $('#result').html(data);
            }
        });
    });

    $(document).on('click', '.list-group-item', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var price = $(this).data('price');

        $('#product_info').append(
            '<tr>' +
            '<td>' + name + '</td>' +
            '<td>' + price + '</td>' +
            '<td><input type="number" name="quantity[]" class="form-control" min="1" value="1"></td>' +
            '<td><input type="text" name="total[]" class="form-control" readonly></td>' +
            '<td><input type="date" name="date[]" class="form-control"></td>' +
            '<td><button type="button" class="btn btn-danger remove-row">Remove</button></td>' +
            '</tr>'
        );
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });

    $('#sug-form').on('submit', function(e) {
        e.preventDefault();
        // Add code to handle form submission if needed
    });
});
</script>
