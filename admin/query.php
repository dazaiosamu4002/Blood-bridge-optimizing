<?php
include 'conn.php';
include 'session.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
          body {
        background-color: #f8f9fa; /* Light background for better contrast */
        color: #212529; /* Default text color */
    }

    table {
        background-color: #fff; /* Ensure table background is white */
        color: #212529; /* Ensure table text is dark */
    }

    table th, table td {
        text-align: center;
        vertical-align: middle;
    }

    .table-responsive {
        margin-top: 20px;
    }

    /* Optional: Improve hover and border visibility */
    table tr:hover {
        background-color: #f2f2f2;
    }

    table th {
        background-color: #343a40;
        color: #fff;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .alert {
        margin-top: 20px;
    }

    .pagination li a {
        color: #007bff;
        text-decoration: none;
    }

    .pagination li.active a {
        background-color: #007bff;
        color: white;
    }
        #sidebar {
            position: relative;
            margin-top: -20px;
        }

        #content {
            position: relative;
            margin-left: 210px;
        }

        @media screen and (max-width: 600px) {
            #content {
                position: relative;
                margin-left: auto;
                margin-right: auto;
            }
        }

        #he {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 3px 7px;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            text-align: center;
        }
    </style>
    <script>
        function updateStatus(id) {
            if (confirm("Do you really want to mark this as Read?")) {
                $.post("update_status.php", { id: id }, function(response) {
                    if (response.success) {
                        document.getElementById("status_" + id).innerHTML = "Read";
                    } else {
                        alert("Failed to update status.");
                    }
                }, "json");
            }
        }
    </script>
</head>

<body>
    <div id="header">
        <?php include 'header.php'; ?>
    </div>
    <div id="sidebar">
        <?php $active = "query";
        include 'sidebar.php'; ?>
    </div>
    <div id="content">
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 lg-12 sm-12">
                        <h1 class="page-title" style ="color:#212529">User Requests</h1>
                    </div>
                </div>
                <hr>

                <?php
                $limit = 10;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                $sql = "SELECT * FROM contact_query LIMIT {$offset}, {$limit}";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="text-align:center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Email Id</th>
                                    <th>Mobile Number</th>
                                    <th>Message</th>
                                    <th>Posting Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = $offset + 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $row['query_name']; ?></td>
                                        <td><?php echo $row['query_mail']; ?></td>
                                        <td><?php echo $row['query_number']; ?></td>
                                        <td><?php echo $row['query_message']; ?></td>
                                        <td><?php echo $row['query_date']; ?></td>
                                        <td id="status_<?php echo $row['query_id']; ?>">
                                            <?php echo $row['query_status'] == 1 ? 'Read' : '<a href="javascript:void(0);" onclick="updateStatus(' . $row['query_id'] . ')">Pending</a>'; ?>
                                        </td>
                                        <td>
                                            <a style="background-color:aqua; padding:5px 10px;" href='delete_query.php?id=<?php echo $row['query_id']; ?>'>Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive" style="text-align:center;">
                        <?php
                        $sql1 = "SELECT COUNT(*) AS total FROM contact_query";
                        $result1 = mysqli_query($conn, $sql1);
                        $row = mysqli_fetch_assoc($result1);
                        $total_records = $row['total'];
                        $total_pages = ceil($total_records / $limit);

                        echo '<ul class="pagination">';
                        if ($page > 1) {
                            echo '<li><a href="query.php?page=' . ($page - 1) . '">Prev</a></li>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active = $i == $page ? 'class="active"' : '';
                            echo '<li ' . $active . '><a href="query.php?page=' . $i . '">' . $i . '</a></li>';
                        }
                        if ($page < $total_pages) {
                            echo '<li><a href="query.php?page=' . ($page + 1) . '">Next</a></li>';
                        }
                        echo '</ul>';
                        ?>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-info">No queries found.</div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php
} else {
    echo '<div class="alert alert-danger"><b>Please Login First To Access Admin Portal.</b></div>';
    echo '<a href="login.php" class="btn btn-primary">Go to Login Page</a>';
}
?>
