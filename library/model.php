<?php

abstract class Model {

    protected function getStatusByStatusIdx($staticIdx) {
        return @$this->statuses[(int) $staticIdx];
    }


    protected function getStatusIdxByStatus($status) {
        $statusIdx = array_search(strtolower(trim($status)), $this->statuses);
        return $statusIdx === false ? null : $statusIdx;
    }


    protected function packInserted(
        $rawResult, $className = '', $raw = false, $person_id = 0
    ) {
        if ($rawResult) {
            $object = $this->getById($rawResult['insert_id'], $raw, $person_id);
            if ($object) {
                return $className ? [$className => $object] : $object;
            }
        }
        return $className ? ['error' => 'server_error'] : null;
    }


    protected function rawGetById($table, $id, $where, $raw = false) {
        if ($table && ($id = (int) $id)) {
            $where = $where ? " AND {$where}" : '';
            $rawObject = Dbio::queryRow(
                "SELECT * FROM `{$table}` WHERE `id` = {$id}{$where};"
            );
            return $raw ? $rawObject : $this->pack($rawObject);
        }
        return null;
    }


    public function multiPack($rawObjects, $person_id = 0) {
        if (is_array($rawObjects)) {
            $objects = [];
            foreach ($rawObjects as $rawObject) {
                if (($object = $this->pack($rawObject, $person_id))) {
                    $objects[] = $object;
                }
            }
            return $objects;
        }
        return null;
    }

}
