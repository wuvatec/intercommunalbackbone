<?php

require '../../../config/app.php';

// User
if(isset($_SESSION['USER'])) {

    $user = R::load('users', $_SESSION['USER']);

} else {
    // Redirect user to login
    header('location: layout.php');
}

// Page was visited directed.
// Kill any activity and redirect user to account summary is logged in
if(!isset($_SERVER['HTTP_REFERER'])) {
    header('location: summary.php');
    exit();
}

$pages_list = ['beneficiary', 'ticket'];
$page_exist = isset($_GET['page']) && in_array($_GET['page'], $pages_list);

if($page_exist) {
    // Delete beneficiary
    if(isset($_GET['beneficiary'])) {
        delete_item('beneficiaries', $_GET['beneficiary'], 'summary.php');
    }

    if(isset($_GET['ticket'])) {
        delete_item('messages', $_GET['ticket'], 'messages.php');
    }

    // Delete another item

}

function delete_item($table, $item_id, $redirect_to) {
    $item = R::load($table, $item_id);

    if(R::trash($item)) {
        $_SESSION['DELETED'] = ucfirst($_GET['page']) . ' successfully deleted';
        header('location: ' . $redirect_to);
        exit();
    }
}
