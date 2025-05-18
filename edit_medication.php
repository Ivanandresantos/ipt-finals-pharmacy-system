<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
 
// Define variables and initialize with empty values
$name = $description = $price = $quantity = $manufacturer = $expiry_date = "";
$name_err = $description_err = $price_err = $quantity_err = $manufacturer_err = $expiry_date_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a medication name.";
    } else{
        $name = $input_name;
    }
    
    // Validate description
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter a description.";     
    } else{
        $description = $input_description;
    }
    
    // Validate price
    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the price.";     
    } elseif(!is_numeric($input_price) || $input_price <= 0){
        $price_err = "Please enter a positive number.";
    } else{
        $price = $input_price;
    }
    
    // Validate quantity
    $input_quantity = trim($_POST["quantity"]);
    if(empty($input_quantity)){
        $quantity_err = "Please enter the quantity.";     
    } elseif(!ctype_digit($input_quantity) || $input_quantity < 0){
        $quantity_err = "Please enter a non-negative integer.";
    } else{
        $quantity = $input_quantity;
    }
    
    // Validate manufacturer
    $input_manufacturer = trim($_POST["manufacturer"]);
    if(empty($input_manufacturer)){
        $manufacturer_err = "Please enter the manufacturer.";     
    } else{
        $manufacturer = $input_manufacturer;
    }
    
    // Validate expiry date
    $input_expiry_date = trim($_POST["expiry_date"]);
    if(empty($input_expiry_date)){
        $expiry_date_err = "Please enter an expiry date.";     
    } else{
        $expiry_date = $input_expiry_date;
    }
    
    // Check input errors before inserting into database
    if(empty($name_err) && empty($description_err) && empty($price_err) && empty($quantity_err) && empty($manufacturer_err) && empty($expiry_date_err)){
        // Prepare an update statement
        $sql = "UPDATE medications SET name=:name, description=:description, price=:price, quantity=:quantity, manufacturer=:manufacturer, expiry_date=:expiry_date WHERE id=:id";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":description", $param_description);
            $stmt->bindParam(":price", $param_price);
            $stmt->bindParam(":quantity", $param_quantity);
            $stmt->bindParam(":manufacturer", $param_manufacturer);
            $stmt->bindParam(":expiry_date", $param_expiry_date);
            $stmt->bindParam(":id", $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_description = $description;
            $param_price = $price;
            $param_quantity = $quantity;
            $param_manufacturer = $manufacturer;
            $param_expiry_date = $expiry_date;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: medications.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM medications WHERE id = :id";
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $description = $row["description"];
                    $price = $row["price"];
                    $quantity = $row["quantity"];
                    $manufacturer = $row["manufacturer"];
                    $expiry_date = $row["expiry_date"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
        
    } else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Medication - Pharmacy Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
        }
        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }
        .bg-primary {
            background: linear-gradient(135deg, #3a8ffe 0%, #1d66c7 100%) !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3a8ffe 0%, #1d66c7 100%) !important;
            border: none !important;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d66c7 0%, #0d4dab 100%) !important;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-pills me-2"></i>
                Pharmacy Management System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="medications.php">
                            <i class="fas fa-capsules me-1"></i> Medications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="medications.php">Medications</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Medication</li>
            </ol>
        </nav>
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-edit me-2"></i>Update Medication</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                                        <span class="invalid-feedback"><?php echo $name_err;?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Manufacturer</label>
                                        <input type="text" name="manufacturer" class="form-control <?php echo (!empty($manufacturer_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $manufacturer; ?>">
                                        <span class="invalid-feedback"><?php echo $manufacturer_err;?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                                <span class="invalid-feedback"><?php echo $description_err;?></span>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Price ($)</label>
                                        <input type="text" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                                        <span class="invalid-feedback"><?php echo $price_err;?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="text" name="quantity" class="form-control <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantity; ?>">
                                        <span class="invalid-feedback"><?php echo $quantity_err;?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Expiry Date</label>
                                        <input type="date" name="expiry_date" class="form-control <?php echo (!empty($expiry_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $expiry_date; ?>">
                                        <span class="invalid-feedback"><?php echo $expiry_date_err;?></span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                            <div class="d-flex justify-content-end mt-3">
                                <a href="medications.php" class="btn btn-secondary me-2">Cancel</a>
                                <input type="submit" class="btn btn-primary" value="Update">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
