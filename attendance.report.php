<?php
require('fpdf186/fpdf.php');

// Get parameters from the URL
$id = $_GET['id'];
$dateFrom = $_GET['date_from'];
$dateTo = $_GET['date_to'];

// Initialize the name variable
$name = "";

// Establish database connection
$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "attendance_monitoring_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the name from the tblinfo table
$sql = "SELECT Firstname, Middlename, Lastname FROM tblinfo WHERE Idnumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['Firstname'] . " " . $row['Middlename'] . " " . $row['Lastname'];
} else {
    $name = "Name not found";
}
$stmt->close();

function gettimeout($date, $id, $conn) {
    $sql = "SELECT SUBSTR(log_date,11,19) as time_out FROM tbllogs WHERE user_id = ? ORDER BY log_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['time_out'];
    } else {
        return '-';
    }
}

// Fetch attendance logs from tbllogs table
$sql = "SELECT SUBSTR(log_date,1,10) as log_date, SUBSTR(log_date,11,19) as time_in FROM tbllogs WHERE user_id = ? AND log_date BETWEEN ? AND ? GROUP BY SUBSTR(log_date,1,10)";
$stmt = $conn->prepare($sql);
$dateTo;
$date_to = new DateTime($dateTo);
$date_to->modify('+1 day');
$date_final = date_format($date_to, "Y-m-d");
$stmt->bind_param("sss", $id, $dateFrom, $date_final);
$stmt->execute();
$result = $stmt->get_result();

// Create a new PDF document
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Set title and add header image
$pdf->Image('aaa.jpeg', 10, 5, 30); // Example image
$pdf->SetFont('Times', 'B', 18); // Change font to Times New Roman, Bold, size 18
$pdf->Cell(180, 10, 'Attendance Monitoring', 0, 1, 'C');
$pdf->Cell(180, 25, '', 0, 1, 'C');


// Name section
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 5, 'Name:', 0, 0, 'L');
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(50, 5, $name, 'B', 1, 'L');

// Attendance date section
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 5, 'Attendance From:', 0, 0, 'L');
$pdf->Cell(40, 5, $dateFrom, 'B', 1, 'L');
$pdf->Cell(40, 5, 'Attendance To:', 0, 0, 'L');
$pdf->Cell(40, 5, $dateTo, 'B', 1, 'L');

// Table headers with improved design
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 220, 255); // Light blue fill color
$pdf->Cell(10, 5, '#', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'Log Date', 1, 0, 'L', 1);
$pdf->Cell(40, 5, 'Time In', 1, 0, 'L', 1);
$pdf->Cell(40, 5, 'Time Out', 1, 0, 'L', 1);
$pdf->Cell(20, 5, 'Late', 1, 0, 'L', 1);
$pdf->Cell(30, 5, 'Under Time', 1, 1, 'L', 1);

// Output the attendance logs
$c = 0;
$total_late = 0;
$total_undertime = 0;
while ($row = $result->fetch_assoc()) {
    $c++;
    $t = strtotime($row['time_in']);
    $d = strtotime(gettimeout($row['log_date'], $id, $conn));
    $in = strtotime("8:00:00 AM");
    $out = strtotime("5:00:00 PM");
    $late = ($t - $in) / 60;
    $undertime = ($out - $d) / 60 / 60;
    
    // Avoid negative values for late and undertime
    if ($late < 0) {
        $late = 0;
    }
    if ($undertime < 0) {
        $undertime = 0;
    }
    
    $total_late += $late;
    $total_undertime += $undertime;
    
    // Use alternate row colors for better readability
    if ($c % 2 == 0) {
        $pdf->SetFillColor(255, 255, 255); // White background for even rows
    } else {
        $pdf->SetFillColor(240, 240, 240); // Light gray for odd rows
    }
    
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(10, 5, $c, 1, 0, 'C', 1);
    $pdf->Cell(40, 5, $row['log_date'], 1, 0, 'L', 1);
    $pdf->Cell(40, 5, date("h:i:s A", $t), 1, 0, 'L', 1);
    $pdf->Cell(40, 5, date("h:i:s A", $d), 1, 0, 'L', 1);
    $pdf->Cell(20, 5, number_format($late, 1), 1, 0, 'L', 1);
    $pdf->Cell(30, 5, number_format($undertime, 1), 1, 1, 'L', 1);
}

// Summary section
$pdf->Cell(30, 10, '', 0, 1, 'L');
$pdf->Cell(30, 5, "LATE", 0, 0, 'L');
$pdf->Cell(20, 5, number_format($total_late, 1), 'B', 1, 'L');
$pdf->Cell(30, 5, "UNDERTIME", 0, 0, 'L');
$pdf->Cell(20, 5, number_format($total_undertime, 1), 'B', 1, 'L');

$stmt->close();

// Output the PDF
$pdf->Output();

// Close the database connection
$conn->close();
?>
