<?php

namespace SACADN\Repositories;

use mysqli;

final readonly class PeriodRepository
{
  function __construct(private mysqli $db) {}

  /**
   * @return array<int, array{
   *   id: int,
   *   anio_inicio: int,
   *   estado: 'activo' | 'inactivo'
   * }>
   */
  function all(): array
  {
    return $this->db->query('
      SELECT id, anio_inicio, estado
      FROM periodos
      ORDER BY anio_inicio DESC
    ')->fetch_all(MYSQLI_ASSOC);
  }

  /**
   * @return ?array{
   *   id: int,
   *   anio_inicio: int,
   *   estado: 'activo' | 'inactivo'
   * }
   */
  function getById(int $id): ?array {
    $stmt = $this->db->prepare('
      SELECT id, anio_inicio, estado
      FROM periodos
      WHERE id = ?
    ');

    $stmt->execute([$id]);
    $period = $stmt->get_result()->fetch_assoc();

    return $period ?: null;
  }
}
