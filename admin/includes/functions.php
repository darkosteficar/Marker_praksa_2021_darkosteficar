<?php

include("includes/db.php");



// Brands
function createBrand()
{
    global $conn;
    $name = htmlentities($_POST['name']);
    $description = htmlentities($_POST['description']);
    $stmt = $conn->prepare("INSERT INTO brands (name,description) VALUES (?,?)");
    $stmt->bind_param("ss", $name, $description);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// End Brands



// Statuses

function createStatus()
{
    global $conn;
    $name = htmlentities($_POST['name']);
    $description = htmlentities($_POST['description']);
    $stmt = $conn->prepare("INSERT INTO order_statuses (name,description) VALUES (?,?)");
    $stmt->bind_param("ss", $name, $description);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// End Statuses


// Attributes

function createAttribute()
{
    global $conn;
    $name = htmlentities($_POST['name']);
    $value = htmlentities($_POST['value']);
    $stmt = $conn->prepare("INSERT INTO attributes (name,value) VALUES (?,?)");
    $stmt->bind_param("ss", $name, $value);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// End attributes




// Categories

function createCategory()
{
    global $conn;
    $name = htmlentities($_POST['name']);
    $description = htmlentities($_POST['description']);
    $active = htmlentities($_POST['active']);
    $parent = htmlentities($_POST['parent']);
    if ($_POST['parent'] != 0) {
        $stmt = $conn->prepare("INSERT INTO categories (name,description,active,parent_id) VALUES (?,?,?,?)");
        $stmt->bind_param("ssii", $name, $description, $active, $parent);
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name,description,active) VALUES (?,?,?)");
        $stmt->bind_param("ssi", $name, $description, $active);
    }
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// End categories
