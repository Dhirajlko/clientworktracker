<?php

require_once "../api/db.php";
include "../includes/header.php";

$stmt = $pdo->query("
SELECT *
FROM clients
ORDER BY client_name ASC
");

$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

<i class="fa fa-users"></i>

Client Master

</h2>

<small class="text-secondary">

Total Clients :
<strong><?=count($clients)?></strong>

</small>

</div>

<div>

<button
class="btn btn-success"
data-bs-toggle="modal"
data-bs-target="#clientModal">

<i class="fa fa-user-plus"></i>

Add Client

</button>

</div>

</div>

<div class="card">

<div class="card-body">

<table
id="clientsTable"
class="table table-hover table-bordered align-middle">

<thead>

<tr>

<th width="70">Code</th>

<th>Name</th>

<th>Company</th>

<th>Mobile</th>

<th>PAN</th>

<th>GSTIN</th>

<th>Category</th>

<th>Status</th>

<th width="170">Action</th>

</tr>

</thead>

<tbody>

<?php foreach($clients as $c){ ?>

<tr>

<td><?=htmlspecialchars($c['client_code'])?></td>

<td><?=htmlspecialchars($c['client_name'])?></td>

<td><?=htmlspecialchars($c['company'])?></td>

<td><?=htmlspecialchars($c['mobile'])?></td>

<td><?=htmlspecialchars($c['pan'])?></td>

<td><?=htmlspecialchars($c['gstin'])?></td>

<td><?=htmlspecialchars($c['category'])?></td>

<td>

<?php if($c['status']=="Active"){ ?>

<span class="badge bg-success">

Active

</span>

<?php }else{ ?>

<span class="badge bg-danger">

Inactive

</span>

<?php } ?>

</td>

<td>

<button
class="btn btn-sm btn-primary editClient"
data-id="<?=$c['id']?>">

<i class="fa fa-edit"></i>

</button>

<button
class="btn btn-sm btn-info viewClient"
data-id="<?=$c['id']?>">

<i class="fa fa-eye"></i>

</button>

<button
class="btn btn-sm btn-warning docsClient"
data-id="<?=$c['id']?>">

<i class="fa fa-folder-open"></i>

</button>

<button
class="btn btn-sm btn-danger deleteClient"
data-id="<?=$c['id']?>">

<i class="fa fa-trash"></i>

</button>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<?php include "client_form.php"; ?>

<?php include "../includes/footer.php"; ?>