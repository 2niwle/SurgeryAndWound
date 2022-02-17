<?php
include('../config/autoload.php');
include('includes/path.inc.php');
?>
<!DOCTYPE html>
<html>

<head>
    <?php include CSS_PATH; ?>
</head>

<body>
    <?php include "../script/loginTemplate.php" ?>
</body>

</html>
<?php
if (isset($_POST['loginbtn'])) {
    $inputEmail = $conn->real_escape_string($_POST['email']);

    $check = $conn->prepare("SELECT * FROM user_master WHERE email = ? and m_type='d' ");
    $check->bind_param("s", $inputEmail);
    $check->execute();
    $q = $check->get_result();
    $r = $q->fetch_assoc();
    if (mysqli_num_rows($q) != 1) {
        echo "<script>Swal.fire({title: 'Error!', text: 'Email Does Not exists', type: 'error'}).then(function() { $('#inputEmail').focus(); });</script>";
        exit();
    }else{
        $dbHashpassword = $r["password"];
    }


    // $inputPassword = $conn->real_escape_string(base64_encode($_POST['password']));
    $inputPassword = $conn->real_escape_string(($_POST['password']));

    if(passwordValidator($inputPassword, $dbHashpassword)){

        $_SESSION['d_sess_id'] = $r['m_id'];
        $_SESSION['d_sess_email'] = $r['email'];
        $_SESSION['loggedin'] = 1;
        // echo "<script>window.location.href = 'index.php'</script>";
        echo "<script>window.location.href = 'index.php'</script>";
    }else{
        echo "<script>Swal.fire({title: 'Error!', text: 'Email & Password Not Exist', type: 'error', confirmButtonText: 'Try Again'})</script>";
        exit();
    }


    if ($inputEmail == "" && empty($inputEmail)) {
        echo "<script>Swal.fire({title: 'Error!', text: 'Please Enter a Email', type: 'error'}).then(function() { $('#inputEmail').focus(); });</script>";
        exit();
    }

    if ($inputPassword == "" && empty($inputPassword)) {
        echo "<script>Swal.fire({title: 'Error!', text: 'Please Enter a Password', type: 'error'}).then(function() { $('#inputPassword').focus(); });</script>";
        exit();
    }

    // if ($result->num_rows != 1) {
    //     echo "<script>Swal.fire({title: 'Error!', text: 'Email & Password Not Exist', type: 'error', confirmButtonText: 'Try Again'})</script>";
    //     exit();
    // } else {
    //     $_SESSION['d_sess_id'] = $row['m_id'];
    //     $_SESSION['d_sess_email'] = $row['email'];
    //     $_SESSION['loggedin'] = 1;
    //     // echo "<script>window.location.href = 'index.php'</script>";
    //     echo "<script>window.location.href = 'index.php'</script>";
    // }
    $stmt->close();
}
?>