<?php
echo "\n";
$serverName = 'tcp:yourserver.database.windows.net,1433';

$connectionOptions = [
    'Database' => 'yourpassword',
    'Uid' => 'yourusername',
    'PWD' => 'yourpassword',
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

$tsql = 'SELECT [CompanyName] FROM SalesLT.Customer';

$getProducts = sqlsrv_query($conn, $tsql);

if ($getProducts === false) {
    die(formatErrors(sqlsrv_errors()));
}

$productCount = 0;
$ctr = 0;
while ($row = sqlsrv_fetch_array($getProducts, SQLSRV_FETCH_ASSOC)) {
    $ctr++;
    echo($row['CompanyName']);
    echo('<br/>');
    $productCount++;
    if ($ctr > 10) {
        break;
    }
}

sqlsrv_free_stmt($getProducts);

$tsql = "INSERT SalesLT.Product (Name, ProductNumber, StandardCost, ListPrice, SellStartDate)
OUTPUT INSERTED.ProductID
VALUES ('SQL Server 15', 'SQL Server 12', 0, 0, getdate());";

$insertReview = sqlsrv_query($conn, $tsql);
if ($insertReview === false) {
    die(formatErrors(sqlsrv_errors()));
}

while ($row = sqlsrv_fetch_array($insertReview, SQLSRV_FETCH_ASSOC)) {
    echo($row['ProductID']);
}
sqlsrv_free_stmt($insertReview);

$tsql = 'DELETE FROM [SalesLT].[Product] WHERE Name=?';
$params = ['SQL Server 15'];

$deleteReview = sqlsrv_prepare($conn, $tsql, $params);
if ($deleteReview === false) {
    die(formatErrors(sqlsrv_errors()));
}

if (sqlsrv_execute($deleteReview) === false) {
    die(formatErrors(sqlsrv_errors()));
}

while ($row = sqlsrv_fetch_array($deleteReview, SQLSRV_FETCH_ASSOC)) {
    echo($row['ProductID']);
}
sqlsrv_free_stmt($deleteReview);
