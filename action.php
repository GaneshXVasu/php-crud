<?php
session_start();
include 'config.php';

$update = false;
/*initializing Form variable*/
$id = "";
$name = "";
$email = "";
$phone = "";
$photo = "";


if(isset($_POST['add'])){
  
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $photo = $_FILES['image']['name'];
    $upload = 'uploads/'.$photo;

    $query= "INSERT INTO crud (name,email,phone,photo)VALUES(?,?,?,?)";// qurry for inserting data

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss",$name,$email,$phone,$upload);//binding all value as integer ie.ssss
    $stmt->execute();

    move_uploaded_file($_FILES['image']['tmp_name'], $upload);

    header('location:index.php');
    $_SESSION['response'] = 'Successfully Data Added to Database..!';
    $_SESSION['res_type'] = 'success';
}

if(isset($_GET['delete'])){
    $id=$_GET['delete'];

    $sql = "SELECT photo FROM crud WHERE id=?"; //deleting image from directive
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param('i',$id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row = $result2->fetch_assoc();

$imagepath = $row['photo'];
unlink($imagepath);

    $query = "DELETE FROM crud WHERE id=?";//delete query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i",$id);
    $stmt->execute();

    header('location:index.php');//for redirecting to index
    $_SESSION['response'] = 'Successfully Data Deleted from Database..!';
    $_SESSION['res_type'] = 'danger';
}

if(isset($_GET['edit'])){
    $id = $_GET['edit'];

    $query = "SELECT * FROM crud WHERE id=?";//Edit query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $id = $row['id'];//update query
    $name = $row['name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $photo = $row['photo'];

    $update = true;

}

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $oldimage = $_POST['oldimage'];

   if(isset($_FILES['image']['name'])&&($_FILES['image']['name']!="")){
    $newimage = "uploads/".$_FILES['image']['name'];
    unlink($oldimage);
    move_uploaded_file( $_FILES['image']['name'], $newimage);
   }else{
       $newimage= $oldimage;
   }
   $query = "UPDATE crud SET name=?,email=?,phone=?,photo=? WHERE id=?";
   $stmt = $conn->prepare($query);
   $stmt->bind_param('ssssi',$name,$email,$phone, $newimage,$id);
   $stmt->execute();

   $_SESSION['response'] = "Updated Successfully";
   $_SESSION['res_type'] = "primary";
   header('location:index.php');

}

if(isset($_GET['details'])){
    $id= $_GET['details'];

    $query = "SELECT * FROM crud WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result = $stmt->get_result();
$row =$result->fetch_assoc();

$vid = $row['id'];
$vname = $row['name'];
$vemail = $row['email'];
$vphone = $row['phone'];
$vphoto = $row['photo'];

}

?>