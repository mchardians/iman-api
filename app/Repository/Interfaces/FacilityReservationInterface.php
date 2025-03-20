<?php

namespace App\Repository\Interfaces;

interface FacilityReservationInterface {
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
}