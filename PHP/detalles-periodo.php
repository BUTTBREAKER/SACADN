<?php

use SACADN\Repositories\PeriodRepository;

require_once __DIR__ . '/../app/bootstrap.php';

$id = $_GET['id'] ?? null;

if (!$id) {
  exit(http_response_code(404));
}

/** @var PeriodRepository */
$periodRepository = container()->get(PeriodRepository::class);
$periodDetails = $periodRepository->getById($id);

dd($periodDetails);
