<?php
if (!defined('_INCODE')) die('Access Deined...');

function query($sql, $data = [], $statementStatus = false)
{
    global $conn;
    $query = false;
    try {
        $statement = $conn->prepare($sql);
        if (empty($data)) {
            $query = $statement->execute();
        } else {
            $query = $statement->execute($data);
        }
    } catch (Exception $exception) {
        // echo $exception->getMessage();
        // echo 'File:' . $exception->getFile() . ' - Line:' . $exception->getLine();

        echo $exception->getMessage() . '<br />';
        die();
    }

    if ($statementStatus && $query) {
        return $statement;
    }

    return $query;
}


function insert($table, $dataInsert)
{
    $keyArray = array_keys($dataInsert);
    $fieldStr = implode(',', $keyArray);
    $valueStr = ':' . implode(',:', $keyArray);
    $sql = 'insert into ' . $table . '(' . $fieldStr . ') values(' . $valueStr . ')';

    return query($sql, $dataInsert);
}

function update($table, $dataUpdate, $condition = '')
{
    $updateStr = '';
    foreach ($dataUpdate as $key => $value) {
        $updateStr .= $key . '=:' . $key . ', ';
    }
    $updateStr = rtrim($updateStr, ', ');
    if (!empty($condition)) {
        $sql = 'update ' . $table . ' set ' . $updateStr . ' where ' . $condition;
    } else {
        $sql = 'update ' . $table . ' set ' . $updateStr;
    }

    return query($sql, $dataUpdate);
}

function delete($table, $condition)
{
    if (!empty($condition)) {
        $sql = 'delete from ' . $table . ' where ' . $condition;
    } else {
        $sql = 'delete from ' . $table;
    }
    echo '<br />';
    echo 'query:' . $sql;
    echo '<br />';

    return query($sql);
}

function getRaw($sql)
{
    $statement = query($sql, [], true);
    if (is_object($statement)) {
        $dataFetch = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $dataFetch;
    }
    return false;
}

function firstRaw($sql)
{
    $statement = query($sql, [], true);
    if (is_object($statement)) {
        $dataFetch = $statement->fetch(PDO::FETCH_ASSOC);
        return $dataFetch;
    }
    return false;
}

function get($table, $field = '*', $condition = '')
{
    if (!empty($condition)) {
        $sql = 'select ' . $field . ' from ' . $table . ' where' . $condition;
    } else {
        $sql = 'select ' . $field . ' from ' . $table;
    }

    return query($sql);
}

function first($table, $field = '*', $condition)
{
    if (!empty($condition)) {
        $sql = 'select ' . $field . ' from ' . $table . ' where' . $condition;
    } else {
        $sql = 'select ' . $field . ' from ' . $table;
    }

    return firstRaw($sql);
}

function getRows($sql)
{
    $statement = query($sql, [], true);
    if (!empty($statement)) {
        return $statement->rowCount();
    }
}

function insertId()
{
}
