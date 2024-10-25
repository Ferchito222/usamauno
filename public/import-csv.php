<?php

require __DIR__ . '/../vendor/autoload.php';

ini_set('auto_detect_line_endings', true);

$dsn      = 'mysql:dbname=bnb_03;host=localhost';
$user     = 'miguel';
$password = 'secret';

try {
    $dbh = new PDO($dsn, $user, $password);

    $csv_file = 'lc_import.csv'; // Name of your CSV file
    $csv = collect([]);

    $lines = file($csv_file, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $key => $value)
    {
        $csv->push(str_getcsv($value, ';'));
    }

    $csv = $csv->groupBy(function ($item, $key) {
        return $item[3];
    });

    $id      = date('U');
    $idc     = $id;
    $date    = date('Y-m-d H:i:s');
    $query   =
        'INSERT INTO op_de_credit_lines(id, ad_user_id, op_de_header_id, number_line, coverage, policy_type, free_cover, confirmed, deleted, data_records, created_at, updated_at) VALUES ';
    $clients =
        'INSERT INTO op_de_credit_line_clients (id, op_de_credit_line_id, dni, extension, complement, number, numbers, percentage_credit, rate, percentage, terms, insured, headline, created_at, updated_at) VALUES ';

    foreach ($csv as $nl => $items) {
        $query .= '('
                  . $id
                  . ', null, null, "'
                  . $nl
                  . '", "'
                  . $items[0][ 4 ]
                  . '", "'
                  . ( $items[0][ 9 ] === 'SI' ? 'TE' : 'TN' )
                  . '", '
                  . ( $items[0][ 10 ] === 'SI' ? 1 : 0 )
                  . ', true, false, "[]", "'
                  . $date
                  . '", "'
                  . $date
                  . '"),';

        foreach ($items as $index => $item) {
            $clients .= '('
                        . $idc
                        . ', '
                        . $id
                        . ', "'
                        . $item[ 0 ]
                        . '", "'
                        . $item[ 1 ]
                        . '", "'
                        . $item[ 2 ]
                        . '", '
                        . mt_rand()
                        . ', "[]", '
                        . $item[ 5 ]
                        . ', '
                        . str_replace(',', '.', $item[ 6 ])
                        . ', '
                        . $item[ 7 ]
                        . ', "'
                        . $item[ 8 ]
                        . '", '
                        . ($data[ 4 ] === 'UC' ? 0 : 1)
                        . ', '
                        . ($index + 1)
                        . ', "'
                        . $date
                        . '", "'
                        . $date
                        . '"),';

            $idc += 1;
        }

        $id += 1;
    }

    $query   = trim($query, ',') . ';';
    $clients = trim($clients, ',') . ';';

    var_dump($query);
    var_dump($clients);

    $count  = $dbh->exec($query);
    var_dump($dbh->errorInfo());
    $count2 = $dbh->exec($clients);
    var_dump($dbh->errorInfo());

    var_dump($count, $count2);

    dd("File data successfully imported to database!!");

} catch (PDOException $e) {
    dd($e->getMessage());
}
