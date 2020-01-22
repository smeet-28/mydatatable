<?php
include 'config.php';

$request = $_POST['request'];

// Datatable data
if($request == 1){
    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    ## Search 
    $searchQuery = " ";
    if($searchValue != ''){
        $searchQuery .= " and (stud_fname like '%".$searchValue."%' or 
        stud_lname like '%".$searchValue."%' or 
        roll_no like '%".$searchValue."%' or
        email like '%".$searchValue."%' or 
        dept like'%".$searchValue."%' ) ";
    }

    ## Total number of records without filtering
    $sel = mysqli_query($con,"select count(*) as allcount from employe");
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
    $sel = mysqli_query($con,"select count(*) as allcount from employe WHERE 1 ".$searchQuery);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];

    ## Fetch records
    $empQuery = "select * from employe WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
    $empRecords = mysqli_query($con, $empQuery);
    $data = array();

    while ($row = mysqli_fetch_assoc($empRecords)) {
        $data[] = array(
                "stud_fname"=>$row['stud_fname'],
                "email"=>$row['email'],
                "stud_lname"=>$row['stud_lname'],
                "roll_no"=>$row['roll_no'],
                "dept"=>$row['dept'],
                "action"=>"<input type='checkbox' class='delete_check' id='delcheck_".$row['id']."' onclick='checkcheckbox();' value='".$row['id']."'>"
            );
    }

    ## Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    echo json_encode($response);
    exit;
}

// Delete record
if($request == 2){
    $deleteids_arr = $_POST['deleteids_arr'];

    foreach($deleteids_arr as $deleteid){
        mysqli_query($con,"DELETE FROM employe WHERE id=".$deleteid);
    }

    echo 1;
    exit;
}

