<?php
include "koneksi.php";

$aksi = $_GET['aksi'];

if ($aksi == "tambah") {
    $task = $_POST['task'];
    $query = "INSERT INTO todos (task) VALUES ('$task')";
    mysqli_query($koneksi, $query);
} elseif ($aksi == "hapus") {
    $id = $_GET['id'];
    $query = "DELETE FROM todos WHERE id = $id";
    mysqli_query($koneksi, $query);
} elseif ($aksi == "ubah") {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $query = "UPDATE todos SET completed = $status WHERE id = $id";
    mysqli_query($koneksi, $query);
}

header("Location: index.php");
?>