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
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
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
        // Prepare an insert statement
        $sql = "INSERT INTO medications (name, description, price, quantity, manufacturer, expiry_date) VALUES (:name, :description, :price, :quantity, :manufacturer, :expiry_date)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":description", $param_description);
            $stmt->bindParam(":price", $param_price);
            $stmt->bindParam(":quantity", $param_quantity);
            $stmt->bindParam(":manufacturer", $param_manufacturer);
            $stmt->bindParam(":expiry_date", $param_expiry_date);
            
            // Set parameters
            $param_name = $name;
            $param_description = $description;
            $param_price = $price;
            $param_quantity = $quantity;
            $param_manufacturer = $manufacturer;
            $param_expiry_date = $expiry_date;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: medications.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
}

// Delete record
if(isset($_GET["delete"]) && !empty($_GET["delete"])){
    // Prepare a delete statement
    $sql = "DELETE FROM medications WHERE id = :id";
    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["delete"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            header("location: medications.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    unset($stmt);
}

// Close connection
unset($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medications - Pharmacy Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Pharmacy Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="medications.php">Medications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2>Medications List</h2>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMedicationModal">
                    <i class="fas fa-plus"></i> Add New Medication
                </button>
            </div>
        </div>
        
        <?php
        require "config.php";
        
        $sql = "SELECT * FROM medications";
        if(isset($_GET["filter"]) && $_GET["filter"] == "low") {
            $sql = "SELECT * FROM medications WHERE quantity < 10";
        } else if(isset($_GET["filter"]) && $_GET["filter"] == "expiring") {
            $date = date('Y-m-d', strtotime('+30 days'));
            $sql = "SELECT * FROM medications WHERE expiry_date <= '$date'";
        }
        
        if($result = $pdo->query($sql)){
            if($result->rowCount() > 0){
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered table-striped">';
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Name</th>";
                            echo "<th>Description</th>";
                            echo "<th>Price</th>";
                            echo "<th>Quantity</th>";
                            echo "<th>Manufacturer</th>";
                            echo "<th>Expiry Date</th>";
                            echo "<th>Action</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while($row = $result->fetch()){
                        echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['description'] . "</td>";
                            echo "<td>$" . number_format($row['price'], 2) . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                            echo "<td>" . $row['manufacturer'] . "</td>";
                            echo "<td>" . $row['expiry_date'] . "</td>";
                            echo "<td>";
                                echo '<a href="edit_medication.php?id='. $row['id'] .'" class="btn btn-sm btn-primary me-1"><i class="fas fa-pencil-alt"></i></a>';
                                echo '<a href="medications.php?delete='. $row['id'] .'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this medication?\')"><i class="fas fa-trash"></i></a>';
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";
                echo "</div>";
                // Free result set
                unset($result);
            } else{
                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
        
        unset($pdo);
        ?>
    </div>

    <!-- Add Medication Modal -->
    <div class="modal fade" id="addMedicationModal" tabindex="-1" aria-labelledby="addMedicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMedicationModalLabel">Add New Medication</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                                    <label>Price (₱)</label>
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
